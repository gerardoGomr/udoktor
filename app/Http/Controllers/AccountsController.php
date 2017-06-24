<?php
namespace Udoktor\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Application\CustomCollection;
use Udoktor\Application\Factories\ProfileViewsFactory;
use Udoktor\Domain\Persons\FullName;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Regions\Location;
use Udoktor\Domain\Users\Classification;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Domain\Users\ServiceType;
use Udoktor\Http\Requests\ServiceProviderUpdateRequest;
use Udoktor\Mail\PasswordResetWasRequested;

/**
 * Class AccountsController
 *
 * @package Udoktor\Http\Controllers
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class AccountsController extends Controller
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * Class constructor
     *
     * @param UsersRepository $repository
     */
    public function __construct(UsersRepository $repository)
    {
        $this->usersRepository = $repository;
    }

    /**
     * verifies the user's account
     *
     * @param string $userId The id from user
     * @param string $token the token to be verified
     * @return \Illuminate\Support\Facades\Redirect
     * @throws Exception when storage error or token is invalid
     */
    public function verify($userId, $token)
    {
        try {
            $user         = $this->usersRepository->find((int)base64_decode($userId));
            $tempPassword = $user->getTempPassword();
            $user->verify($token);

            $this->usersRepository->persist($user);

            if (Auth::attempt(['email' => $user->getEmail(), 'password' => $tempPassword, 'active' => 1])) {
                return redirect('/')
                    ->with('verified', 'Cuenta verificada');

            } else {
                return redirect('login')
                    ->with('error', 'Error de correo electrónico y/o contraseña');
            }

        } catch (Exception $e) {
            $response['estatus'] = 'fail';
            $response['message'] = 'No se pudo activar la cuenta: ' . $e->getMessage();

            Log::error($e->getMessage());
        }
    }

    /**
     * shows view for password recovery
     *
     * @return \Illuminate\Suppor\Facades\View
     */
    public function showRecoverPassword()
    {
        return view('accounts.password_recovery');
    }

    /**
     * sends instructions for resetting password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendRecoverPassword(Request $request)
    {
        try {
            $user = $this->usersRepository->findOneBy(['email' => $request->get('email')]);

            if (is_null($user)) {
                // account doesnt exist, send mail that this option was requested and ignore the mail
                Mail::to($user->getEmail())->send(new PasswordResetForNonUserWasRequested($user));
            }

            // account exists, send instructions
            $user->requestPasswordReset();

            // save changes
            $this->usersRepository->persist($user);

            Mail::to($user->getEmail())->send(new PasswordResetWasRequested($user));

            $response = ['status' => 'success'];

        } catch (Exception $e) {
            $response['estatus'] = 'fail';
            $response['message'] = $e->getMessage();

            Log::error($e->getMessage());
        } finally {
            return response()->json($response);
        }
    }

    /**
     * shows view for set new passord
     *
     * @param string $userId
     * @param string $token
     * @return \Illuminate\Support\Facades\View
     */
    public function showResetPassword($userId, $token)
    {
        try {
            $user = $this->usersRepository->find((int)base64_decode($userId));

            if (!$user->canResetPassword($token)) {
                return view('errors.403')->with('error', 'Error al procesar el reseteo de contraseña debido a que el token es inválido o el periodo ha expirado.');
            }

            return view('accounts.reset_password')
                ->with('email', $user->getEmail())
                ->with('userId', base64_encode($user->getId()));

        } catch (Exception $e) {
            dd($e->getMessage());
            $response['estatus'] = 'fail';
            $response['message'] = $e->getMessage();

            Log::error($e->getMessage());

            abort('403', 'Error al procesar el reseteo de contraseña');
        }
    }

    /**
     * resets password on user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $user = $this->usersRepository->find((int) base64_decode($request->input('userId')));

            $user->resetPassword($request->input('password'));

            $this->usersRepository->persist($user);

            $response = ['status' => 'success'];

        } catch (Exception $e) {
            $response['estatus'] = 'fail';
            $response['message'] = $e->getMessage();

            Log::error($e->getMessage());
        } finally {
            return response()->json($response);
        }
    }

    /**
     * return view for edit the user's profile
     *
     * @return \Illuminate\Support\Facades\View
     */
    public function index()
    {
        return ProfileViewsFactory::make();
    }

    /**
     * complete profile
     *
     * @param ServiceProviderUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(ServiceProviderUpdateRequest $request)
    {
        $response = ['status' => 'success'];

        try {
            $aUnit = EntityManager::getRepository(AdministrativeUnit::class)->find((int) $request->input('municipality'));
            $user  = Auth::user();
            $user->completeProfile(
                new FullName($request->input('name'), $request->input('middleName'), $request->input('lastName')),
                $request->input('email'),
                $request->input('phoneNumber'),
                $aUnit
            );

            // persisting
            $this->usersRepository->persist($user);
            return response()->json($response);

        } catch (Exception $e) {
            // log exception & construct response
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);

        }
    }

    /**
     * saves profile picture on disk
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeProfileImage(Request $request)
    {
        if (!$request->hasFile('loadPicture')) {
            return response()->json([
                'status' => 'error',
                'error'  => 'The file was not uploaded'
            ]);
        }

        $image = $request->file('loadPicture');

        if (!$image->isValid()) {
            return response()->json([
                'status' => 'error',
                'error'  => 'There is a problem with the uploaded file'
            ]);
        }

        // deleting current file if exists
        if (Auth::user()->hasProfilePicture()) {
            Storage::disk('public')->delete('profile_pictures/' . Auth::user()->getProfilePicture());
        }

        $extension   = $image->extension();
        $pictureName = (string) Auth::user()->getId() . '.' . $extension;
        $path        = $image->storeAs('profile_pictures', $pictureName, 'public');

        Auth::user()->updateProfilePicture($pictureName);

        try {
            $this->usersRepository->persist(Auth::user());

        } catch (Exception $e) {
            // log exception & construct response
            $response = [];
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);
        }

        return response()->json([
            'status' => 'success',
            'imgUrl' => asset('storage/' . $path)
        ]);
    }

    /**
     * sets the notifications to user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setNotifications(Request $request)
    {
        Auth::user()->clearNotifications();
        $notifications = [];

        if ($request->input('newDate') === '1') {
            Auth::user()->addNotification('newDate');
        }

        if ($request->input('dateCancelled') === '1') {
            Auth::user()->addNotification('dateCancelled');
        }

        try {
            $this->usersRepository->persist(Auth::user());

        } catch (Exception $e) {
            // log exception & construct response
            $response = [];
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * updates the services the user offers
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateServices(Request $request)
    {
        $user = Auth::user();
        try {
            if ($user->isServiceProvider()) {
                throw new Exception('The current user isn\'t a service provider');
            }

            $services       = new CustomCollection;
            $classification = EntityManager::getRepository(Classification::class)->find((int) $request->input('classification'));
            $serviceTypeIds = explode(',', $request->input('services'));

            foreach ($serviceTypeIds as $serviceId) {
                $serviceType = EntityManager::getRepository(ServiceType::class)->find((int) $serviceId);
                $services->add($serviceType);
            }

            $user->addComponentsForServiceProvider($classification, $services);
            $this->usersRepository->persist($user);

        } catch (Exception $e) {
            // log exception & construct response
            $response = [];
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function updateLocation(Request $request)
    {
        $user = Auth::user();
        try {
            if (!$user->isServiceProvider()) {
                throw new Exception('The current user isn\'t a service provider');
            }

            $user->updateLocation(new Location($request->input('longitude'), $request->input('latitude'), $request->input('location')));
            $this->usersRepository->persist($user);

        } catch (Exception $e) {
            // log exception & construct response
            $response = [];
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}