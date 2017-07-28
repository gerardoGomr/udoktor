<?php

namespace Udoktor\Http\Controllers\ServiceProviders;

use Illuminate\Http\Request;
use Udoktor\Http\Controllers\Controller;

/**
 * Class AppointmentsController
 *
 * @package Udoktor\Http\Controllers\ServiceProviders
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class AppointmentsController extends Controller
{
    /**
     * returns index view
     *
     * @return \Illuminate\Support\Facades\View
     */
    public function index()
    {
        return view('service_provider.appointments');
    }
}
