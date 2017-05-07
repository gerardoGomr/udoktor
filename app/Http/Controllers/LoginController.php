<?php
namespace Udoktor\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
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
        return view('login.login');
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

        if (Auth::attempt(['email' => $correo, 'password' => $pass, 'active' => 1], $rememberMe)) {
            return redirect()->intended('/');

        } else {
            return redirect()
                ->back()
                ->with('error', 'Error de correo electrónico y/o contraseña');
        }
    }
}
