<?php
namespace Udoktor\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Domain\Users\User;
use Udoktor\Http\Controllers\Controller;

/**
 * this class process login requests
 *
 * @package Udoktor\Http\Controllers
 * @category Controller
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class LoginController extends Controller
{
    /**
     * shows login view
     *
     * @return Illuminate\Support\Facades\View
     */
    public function index(){
        return view('login');
    }

    /**
     * process login
     *
     * @param  Request $request
     * @return Illuminate\Support\Redirect
     */
    public function login(Request $request)
    {
        $correo     = $request->get('correo');
        $pass       = $request->get('pass');
        $rememberMe = $request->has('rememberMe') ? true : false;

        if (Auth::attempt(['email' => $correo, 'password' => $pass, 'active' => 1, 'verified' => 1], $rememberMe)) {
            return redirect()->intended('/');

        } else {
            $user = EntityManager::getRepository(User::class)->findOneBy(['email' => $correo]);

            if (is_null($user)) {
                $errorMessage = 'Error de correo electrónico y/o contraseña';
            } elseif (!$user->isVerified()) {
                $errorMessage = 'No ha verificado su cuenta';
            } else {
                $errorMessage = 'Error de correo electrónico y/o contraseña';
            }

            return redirect()
                ->back()
                ->with('error', $errorMessage);
        }
    }
}
