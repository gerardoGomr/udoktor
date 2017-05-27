<?php
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
    return view('dashboard');
})->middleware('auth');

// login
Route::get('login', 'LoginController@index');
Route::post('login', 'LoginController@login');
Route::get('logout', function () {
    Auth::logout();
    return view('login');
});


// ruta para crear una nueva cuenta
Route::resource('crear-cuenta', 'SignUpController', ['only' => ['index', 'store']]);

// ruta para obtener la lista de unidades administrativas
Route::post('crear-cuenta/a-units/search', 'SignUpController@searchAUnit');

// ruta para activar la cuenta
Route::get('cuentas/activar/{userId}/{token}', 'AccountsController@verify');

// ruta para mostrar la vista de recuperación de contraseña
Route::get('cuentas/recuperar-contrasenia', 'AccountsController@showRecoverPassword');

// ruta para enviar correo electrónico recuperación de contraseña
Route::post('cuentas/recuperar-contrasenia', 'AccountsController@sendRecoverPassword');

// ruta para mostrar la vista de setear nuevo password
Route::get('cuentas/nueva-contrasenia/{userId}/{token}', 'AccountsController@showResetPassword');

// ruta para resetear nuevo password
Route::post('cuentas/nueva-contrasenia', 'AccountsController@resetPassword');