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

Route::group(['middleware' => ['auth', 'picture']], function () {
    // main view
    Route::get('/', 'HomeController@index');

    // service provider profile
    Route::group(['prefix' => 'prestador-servicios/perfil'], function () {
        Route::get('/', 'UsersController@index');
        Route::put('ubicacion', 'UsersController@updateLocation');
    });

    // update price type for service provider
    Route::put('prestador-servicios/precios', 'UsersController@updatePriceType');
    Route::patch('prestador-servicios/precios', 'UsersController@updatePrices');

    // group for service providers
    Route::group(['prefix' => 'prestador-servicios', 'namespace' => 'ServiceProviders'], function () {
        // calendar
        Route::get('/', 'DatesController@index');

        // services
        Route::get('servicios', 'ServicesController@index');
        Route::put('servicios', 'ServicesController@addServices');
        Route::delete('servicios', 'ServicesController@removeService');

        // agenda
        Route::get('agenda/configuracion', 'DiariesController@index');
        Route::put('agenda/tipo', 'DiariesController@changeDiaryType');
        Route::put('agenda/duracion-servicios', 'DiariesController@modifyServicesLasting');
        Route::put('agenda/agregar-horario', 'DiariesController@addSchedule');

        // citas
        Route::get('agenda/citas', 'AppointmentsController@index');
    });

    Route::group(['prefix' => 'clientes', 'namespace' => 'Clients'], function () {
        Route::get('/', 'ServicesController@index');
    });

    // service provider profile
    Route::group(['prefix' => 'clientes/perfil'], function () {
        Route::get('/', 'UsersController@index');
    });

    // profile
    Route::put('perfil', 'UsersController@updateProfile');
    Route::put('perfil/picture', 'UsersController@changeProfileImage');
    Route::put('perfil/notificaciones', 'UsersController@setNotifications');
});

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