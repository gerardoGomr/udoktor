<?php

namespace Udoktor\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

/**
 * Class HomeController
 *
 * @package Udoktor\Http\Controllers
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class HomeController extends Controller
{
    /**
     * checks user in order to the appropiate redirect
     *
     * @return \Illuminate\Http\Redirect
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->isServiceProvider()) {
                return redirect('prestador-servicios');

            } else if (Auth::user()->isClient()) {
                return redirect('cliente');
            }
        } else {
            Auth::logout();
            return redirect('/');
        }
    }
}