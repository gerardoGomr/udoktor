<?php
namespace Udoktor\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Mail\PasswordResetForNonUserWasRequested;
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
}