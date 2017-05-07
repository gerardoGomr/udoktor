<?php
use Udoktor\V_person;
use Udoktor\Pais;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    dd(\Auth::viaRemember());
})->middleware('auth');

// login
Route::get('login', 'LoginController@index');
Route::post('login', 'LoginController@login');
Route::get('logout', function () {
    Auth::logout();
    return view('login.login');
});


// ruta para crear una nueva cuenta
Route::resource('crear-cuenta', 'SignUpController', ['only' => ['index', 'store']]);
Route::post('crear-cuenta/a-units/search', 'SignUpController@searchAUnit');