<?php

namespace Udoktor\Http\Controllers\ServiceProviders;

use Illuminate\Http\Request;
use Udoktor\Http\Controllers\Controller;

/**
 * Class DatesController
 *
 * @package Udoktor\Http\Controllers\ServiceProviders
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class DatesController extends Controller
{
    /**
     * show calendar
     *
     * @return Illuminate\Support\Facades\View
     */
    public function index()
    {
        return view('service_provider.index');
    }
}
