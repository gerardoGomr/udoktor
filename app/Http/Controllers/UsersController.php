<?php
namespace Udoktor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Application\Factories\ProfileViewsFactory;
use Udoktor\Domain\Persons\FullName;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Regions\Location;
use Udoktor\Domain\Users\OfferedService;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Exceptions\InvalidPriceForOfferedServiceException;
use Udoktor\Http\Requests\ServiceProviderUpdateRequest;
use Udoktor\Http\Requests\UpdatePricesRequest;

/**
 * Class UsersController
 *
 * @package Udoktor\Http\Controllers
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class UsersController extends Controller
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

        if ($request->has('notifications')) {
            foreach ($request->input('notifications') as $notification) {
                Auth::user()->addNotification($notification);
            }
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
     * updates the user's current location
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * update the price type
     *
     * @param Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function updatePriceType(Request $request)
    {
        $user     = Auth::user();
        $response = [];

        try {
            if (!$user->isServiceProvider()) {
                throw new Exception('The current user isn\'t a service provider');
            }

            $this->validate($request, [
                'priceType' => 'required|integer|in:1,2',
            ]);

            $priceType = (int) $request->input('priceType');
            $user->changePriceType($priceType);
            $this->usersRepository->persist($user);

        } catch (InvalidPriceForOfferedServiceException $e) {
            // log exception & construct response
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);
        } catch (Exception $e) {
            // log exception & construct response
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);
        }

        return response()->json([
            'html'   => view('service_provider.services_list')->render(),
            'status' => 'success'
        ]);
    }

    /**
     * updates prices
     *
     * @param UpdatePricesRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function updatePrices(UpdatePricesRequest $request)
    {
        $user     = Auth::user();
        $response = [];

        try {
            if (!$user->isServiceProvider()) {
                throw new Exception('The current user isn\'t a service provider');
            }

            foreach ($request->input('prices') as $index => $price) {
                $offeredServiceId = (int) $request->input('offeredServicesIds')[$index];
                $offeredService   = $user->getOfferedService($offeredServiceId);
                if (!is_null($offeredService)) {
                    $offeredService->changePrice((float) $price);
                }
            }

            $this->usersRepository->persist($user);

        } catch (Exception $e) {
            // log exception & construct response
            $response['status']  = 'error';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());
            return response()->json($response);
        }

        return response()->json([
            'html'   => view('service_provider.services_list')->render(),
            'status' => 'success'
        ]);
    }
}