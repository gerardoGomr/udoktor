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
    return view('login.login');
});

// login
Route::resource('login', 'LoginController', ['only' => ['index', 'store']]);

// logout
Route::get('logout', function () {
    Auth::logout();
    return view('login.login');
});


Route::get('login', function () {
    if (Auth::check()) {
        $idPerson   = Auth::user()->personid;
        $dataPerson = V_person::find($idPerson);
        if (count($dataPerson) > 0) {
            if ($dataPerson->isserviceprovider == true) {
                return redirect('prestadorServicios');
            } else if ($dataPerson->isclient == true) {
                redirect('cliente');
            } else if ($dataPerson->isadmin == true) {
                redirect('admin');
            } else {
                redirect('cliente');
            }
        }
    } else {
        Auth::logout();
        return view('login.login');
    }

});


/* Redirecciona a la pantalla de login
 * Autor: OT
 * Fecha: 17-05-2016
*/
Route::get('login/inicio/{varificado?}', function ($verificado = "") {
    if (Auth::check()) {
        $idPerson   = Auth::user()->personid;
        $dataPerson = V_person::find($idPerson);
        if (count($dataPerson) > 0) {
            if ($dataPerson->isserviceprovider == true) {
                return redirect('prestadorServicios');
            } else if ($dataPerson->isclient == true) {
                redirect('cliente');
            } else if ($dataPerson->isadmin == true) {
                redirect('admin');
            } else {
                redirect('cliente');
            }
        }
    } else {
        if ($verificado != "1") $verificado = "";
        return view('login.login')->with('verificado', $verificado);
    }


});

// ruta para crear una nueva cuenta
Route::get('crear-cuenta', 'CrearCuentaController@crearCuenta');


/* Muestra el mapa para buscar ubicacion del prestador de servicios
 * Autor: OT
 * Fecha: 06-12-2016
*/

Route::get('crear-cuenta/verMapaUbicacion', function () {

        return view('login.mapaUbicacion');
});


/* Agrega el servicio desde la pantalla de crear cuenta
 * Autor: OT
 * Fecha: 07-12-2016
*/
Route::get('crear-cuenta/nuevoServicio', 'CrearCuentaController@nuevoServicio');


/* Muestra el formulario para crear la cuenta del cliente
 * Autor: OT
 * Fecha: 05-12-2016
*/
Route::get('cuentacliente/', 'CrearCuentaController@crearCuentaCliente');


/* Ruta para crear cuenta de cliente - personal
 * Autor: OT
 * Fecha: 05-12-2016
*/
Route::post('crear-cuenta/cliente', 'CrearCuentaController@cuentaCliente');


/* Ruta para crear cuenta del prestador de servicios
 * Autor: OT
 * Fecha: 06-12-2016
*/
Route::post('crear-cuenta/prestadorServicio', 'CrearCuentaController@prestadorServicio');



/* Establece el password de la cuenta
 * Autor: OT
 * Fecha: 01-07-2016
*/
Route::post('crear-cuenta/confirmarPassword', 'CrearCuentaController@confirmarPassword');


/* Muestra el formulario enviar mail de recuperacion de contraseña
 * Autor: OT
 * Fecha: 01-07-2016
*/
Route::get('crear-cuenta/olvidoCuenta', function () {
    return view('login.olvidoCuenta');
});


/* Envia mail para recuperar contraseña
 * Autor: OT
 * Fecha: 02-07-2016
*/
Route::get('/crear-cuenta/solicitarContrasena/{correo?}', [
    'uses' => 'CrearCuentaController@solicitarContrasena'
]);


/* Muestra el formulario para reestablecer la contraseña
 * Autor: OT
 * Fecha: 02-07-2016
*/

Route::get('/crear-cuenta/reestablecerContrasena/{correo?}/{pass?}', [
    'uses' => 'CrearCuentaController@reestablecerContrasena'
]);


/* =============== GENERALES ================ */

/* Ruta para consultar estados
 * Autor: OT
 * Fecha: 20-05-2016
*/
Route::get('general/estados', 'GeneralController@estados');

/* Ruta para consultar ciudades
 * Autor: OT
 * Fecha: 20-05-2016
*/
Route::get('general/ciudades', 'GeneralController@ciudades');

/* Cambio de idioma
 * Autor: OT
 * Fecha: 20-05-2016
*/
Route::get('lang/{lang}', function ($lang) {
    session(['lang' => $lang]);
    return \Redirect::back();
})->where([
    'lang' => 'en|es'
]);






/* **************************** ADMINISTRADOR ***************************************/


/*Dashboard Admin
OT - 25-07-16 */
Route::get('admin',[
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Administrador'],
    'uses'       => 'AdminController@Dashboard'
]);



/* Muestra la pantalla de listado de tipos de vehículo
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::get('/admin/clasificacion', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@clasificacion',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);

/* Genera de datatable de tipo vehiculos
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::get('/admin/listaClasificacion', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@listaClasificacion',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);

/* Muestra el formulario para agregar un vehiculo
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::get('/admin/nuevaClasificacion/', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@nuevaClasificacion',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* Guarda clasificacion
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::post('/admin/guardarClasificacion', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@guardarClasificacion',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* Editar clasificacion
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::get('/admin/editarClasificacion/', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@editarClasificacion',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* Activa o desactiva la clasificacion
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::get('/admin/activarDesactivarClasificacion/', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@activarDesactivarClasificacion',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* Muestra la pantalla de servicios
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::get('/admin/servicios', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@servicios',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);

/* Genera de datatable de servicios
 * Autor: OT
 * Fecha: 14-12-2016
*/
Route::get('/admin/listaServicios', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@listaServicios',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* Muestra la pantalla para agregar servicios
 * Autor: OT
 * Fecha: 30-12-2016
*/
Route::get('/admin/nuevoServicioAdmin', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@nuevoServicioAdmin',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);

/* Guarda el servicio
 * Autor: OT
 * Fecha: 30-12-2016
*/
Route::post('/admin/guardarServicioAdmin', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@guardarServicioAdmin',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* Editar servicio
 * Autor: OT
 * Fecha: 30-12-2016
*/
Route::get('/admin/editarServicioAdmin/', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@editarServicioAdmin',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* Activa o desactiva servicios
 * Autor: OT
 * Fecha: 30-12-2016
*/
Route::get('/admin/activarDesactivarServicio/', [
    'middleware' => 'auth',
    'uses'       => 'AdminController@activarDesactivarServicio',
    'middleware' => 'roles',
    'roles'=>['Administrador'],
]);


/* **************************** PRESTADOR DE SERVICIOS ***************************************/


/* Pagina principal prestador de servicios
 * Autor: OT
 * Fecha: 08-12-2016
*/
Route::get('prestadorServicios',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@Dashboard',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);


/* Muestra los servicios del usuario
 * Autor: OT
 * Fecha: 08-12-2016
*/
Route::get('prestadorServicios/misServicios',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@misServicios',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Guarda los servicio del usuario prestador de servicios
 * Autor: OT
 * Fecha: 10-12-2016
*/
Route::post('prestadorServicios/guardarPrecioServicios',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@guardarPrecioServicios',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Mustra el formulario para agregar un servicio al prestador
 * Autor: OT
 * Fecha: 10-12-2016
*/
Route::get('prestadorServicios/agregarServicioPrestador',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@agregarServicioPrestador',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Asigna los servicios al usuario
 * Autor: OT
 * Fecha: 12-12-2016
*/
Route::get('prestadorServicios/guardarServicioUsuario',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@guardarServicioUsuario',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Mustra el formulario para agregar un servicio nuevo al sistema y al usuario
 * Autor: OT
 * Fecha: 12-12-2016
*/
Route::get('prestadorServicios/agregarNuevoServicioPrestador',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@agregarNuevoServicioPrestador',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Agregar el servicio y lo asigna al usuario
 * Autor: OT
 * Fecha: 12-12-2016
*/
Route::post('prestadorServicios/guardarNuevoServicioUsuario',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@guardarNuevoServicioUsuario',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Muestra la configuracion de la agenda
 * Autor: OT
 * Fecha: 15-12-2016
*/
Route::get('prestadorServicios/agendaConfiguracion',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@agendaConfiguracion',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);


/* Muestra el formulario para agregar horario
 * Autor: OT
 * Fecha: 16-12-2016
*/
Route::get('prestadorServicios/agregarHorarioPrestador',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@agregarHorarioPrestador',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Guardar horario
 * Autor: OT
 * Fecha: 16-12-2016
*/
Route::get('prestadorServicios/guardarHorario',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@guardarHorario',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Cargar horario
 * Autor: OT
 * Fecha: 16-12-2016
*/
Route::get('prestadorServicios/cargarHorario',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@cargarHorario',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);


/* Eliminar horario
 * Autor: OT
 * Fecha: 16-12-2016
*/
Route::get('prestadorServicios/eliminarHorario',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@eliminarHorario',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Elimina todo el horario
 * Autor: OT
 * Fecha: 17-12-2016
*/
Route::get('prestadorServicios/eliminarHorarioTodo',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@eliminarHorarioTodo',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);


/* Muestra el formulario para cambiar el tiempo del servicio
 * Autor: OT
 * Fecha: 19-12-2016
*/
Route::get('prestadorServicios/formularioCambioTiempo',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@formularioCambioTiempo',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/*
* Guarda el tiempo del servicio
* Fecha: 19-12-2016
* Autor: OT
*/
Route::get('prestadorServicios/guardarTiempoServicio',[
    'middleware' => 'auth',
    'uses'       => 'PrestadorServiciosController@guardarTiempoServicio',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);


/**
 * Buscar notificaciones nuevas del prestador
 * @author OT
 * Fecha: 26-12-2016
 */
Route::get('prestadorServicios/buscarNotificacionesNuevas', [
    'uses'       => 'PrestadorServiciosController@buscarNotificacionesNuevas',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);


/**
 * Muestra la notificacion del prestador
 * @author OT
 * Fecha: 26-12-2016
 */
Route::get('/prestadorServicios/mostrarNotificacionPrestador/{idNotificacion?}', [
    'uses'       => 'PrestadorServiciosController@mostrarNotificacionPrestador',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);



/* Muestra la lista de citas del prestador de servicios
* Autor: OT
* Fecha: 26-12-2016
*/
Route::get('prestadorServicios/misCitas', [
    'uses'       => 'PrestadorServiciosController@misCitas',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);

/* Genera la lista de citas del prestador de servicios
* Autor: OT
* Fecha: 26-12-2016
*/
Route::get('prestadorServicios/listaCitas', [
    'uses'       => 'PrestadorServiciosController@listaCitas',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);

/**
 * Guarda la cita del cliente
 * @author OT
 * Fecha: 22-12-2016
 */
Route::get('prestadorServicios/aceptarCita', [
    'uses'       => 'PrestadorServiciosController@aceptarCita',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);


/**
 * Muestra el formulario para indicar el motivo de rechazo de cita
 * @author OT
 * Fecha: 28-12-2016
 */
Route::get('/prestadorServicios/motivoRechazoCita', [
    'uses'       => 'PrestadorServiciosController@motivoRechazoCita',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);

/**
 * Guardar el rechazo de la cita
 * @author OT
 * Fecha: 28-12-2016
 */
Route::get('/prestadorServicios/guardarRechazoCita', [
    'uses'       => 'PrestadorServiciosController@guardarRechazoCita',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);

/*
* Muestra el motivo de rechazo
* Autor: OT
* Fecha: 28-12-2016
*
*/
Route::get('/prestadorServicios/verMotivoRechazoCita', [
    'uses'       => 'PrestadorServiciosController@verMotivoRechazoCita',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);


/*
* Carga la tabla de los servicios
* Autor: OT
* Fecha: 28-12-2016
*
*/
Route::post('/prestadorServicios/cargarTablaServicios', [
    'uses'       => 'PrestadorServiciosController@cargarTablaServicios',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);


/*
* Devuele las citas del prestador de servicios
* Autor: OT
* Fecha: 09-01-2017
*
*/
Route::get('/prestadorServicios/obtenerCitasCalendario', [
    'uses'       => 'PrestadorServiciosController@obtenerCitasCalendario',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);


/*
* Muestra el detalle de la cita
* Autor: OT
* Fecha: 10-01-2017
*
*/
Route::get('/prestadorServicios/detalleCita', [
    'uses'       => 'PrestadorServiciosController@detalleCita',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);

/*
* Guarda el cambio de la cita desde la pantalla pricipal
* Autor: OT
* Fecha: 10-01-2017
*
*/
Route::post('/prestadorServicios/guardaCambioCita', [
    'uses'       => 'PrestadorServiciosController@guardaCambioCita',
    'middleware' => ['roles', 'auth'],
    'roles'=>['Prestador'],
]);


/* =============== CLIENTES ================ */


/* Pagina principal cliente
 * Autor: OT
 * Fecha: 19-12-2016
*/
Route::get('cliente',[
    'middleware' => 'auth',
    'uses'       => 'ClienteController@Dashboard',
    'middleware' => 'roles',
    'roles'=>['Cliente'],
]);

/* Buscar servicios
 * Autor: OT
 * Fecha: 20-12-2016
*/
Route::get('cliente/servicios',[
    'middleware' => 'auth',
    'uses'       => 'ClienteController@servicios',
    'middleware' => 'roles',
    'roles'=>['Cliente'],
]);

/**
 * ruta para buscar las ofertas que cumplan con el parámetro de búsqueda
 * @author : OT
 * Fecha: 20-12-2016
 */
Route::post('cliente/buscarServicios', [
    'as'         => 'buscar-servicios',
    'uses'       => 'ClienteController@buscarServiciosMapa',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);

/**
 * ruta para buscar estados
 * @author OT
 * @version 1.0
 */
Route::post('cliente/buscar-estados', [
    'as'         => 'buscar-estados',
    'uses'       => 'ClienteController@buscarEstados',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);

/**
 * ruta para buscar ciudades
 * @author OT
 * @version 1.0
 */
Route::post('cliente/buscar-ciudades', [
    'as'         => 'buscar-ciudades',
    'uses'       => 'ClienteController@buscarCiudades',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);

/**
 * Muestra la pantalla para agendar cita
 * @author OT
 * Fecha: 21-12-2016
 */
Route::get('cliente/agendarCita/{idPrestador?}', [
    'uses'       => 'ClienteController@agendarCita',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/**
 * Guarda la cita del cliente
 * @author OT
 * Fecha: 22-12-2016
 */
Route::get('cliente/guardarCita', [
    'uses'       => 'ClienteController@guardarCita',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/* Obtener la disponibilidad de la fecha enviada
* Autor: OT
* Fecha: 22-12-2016
*/
Route::get('cliente/obtenerDisponibleCita', [
    'uses'       => 'ClienteController@obtenerDisponibleCita',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/* Muestra la lista de citas
* Autor: OT
* Fecha: 26-12-2016
*/
Route::get('cliente/misCitas', [
    'uses'       => 'ClienteController@misCitas',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);

/* Genera la lista de citas del cliente
* Autor: OT
* Fecha: 26-12-2016
*/
Route::get('cliente/listaCitasCliente', [
    'uses'       => 'ClienteController@listaCitasCliente',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/* Cancela la cita del cliente
* Autor: OT
* Fecha: 26-12-2016
*/
Route::get('cliente/cancelarCita', [
    'uses'       => 'ClienteController@cancelarCita',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/**
 * Muestra la pantalla para cambiar la cita seleccionada
 * @author OT
 * Fecha: 21-12-2016
 */
Route::get('cliente/cambiarCita/{idCita?}', [
    'uses'       => 'ClienteController@cambiarCita',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);



Route::get('/cliente/miCalendario', [
    'middleware' => 'auth',
    'uses'       => 'ClienteController@miCalendario',
    'middleware' => 'roles',
    'roles'=>['Cliente'],
]);


/* Obtener la disponibilidad de la fecha enviada sin tomar en cuenta la fecha a cancelar
* Autor: OT
* Fecha: 26-12-2016
*/
Route::get('cliente/obtenerDisponibleCita2', [
    'uses'       => 'ClienteController@obtenerDisponibleCita2',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/**
 * Guarda el cambio de cita del cliente
 * @author OT
 * Fecha: 26-12-2016
 */
Route::get('cliente/guardarCambioCita', [
    'uses'       => 'ClienteController@guardarCambioCita',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/**
 * Buscar notificaciones nuevas del cliente
 * @author OT
 * Fecha: 26-12-2016
 */
Route::get('cliente/buscarNotificacionesNuevas', [
    'uses'       => 'ClienteController@buscarNotificacionesNuevas',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/**
 * Muestra la notificacion del cliente
 * @author OT
 * Fecha: 26-12-2016
 */
Route::get('/cliente/mostrarNotificacionCliente/{idNotificacion?}', [
    'uses'       => 'ClienteController@mostrarNotificacionCliente',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/*
* Muestra el motivo de rechazo
* Autor: OT
* Fecha: 28-12-2016
*
*/
Route::get('/cliente/verMotivoRechazoCita', [
    'uses'       => 'ClienteController@verMotivoRechazoCita',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);

/**
 * Muestra la pantalla para agendar cita a domicilio
 * @author OT
 * Fecha: 05-01-2017
 */
Route::get('cliente/citaDomicilio/{idPrestador?}', [
    'uses'       => 'ClienteController@citaDomicilio',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);

/**
 * Muestra el formulario para cmbiar la cita a domicilio
 * @author OT
 * Fecha: 06-01-2017
 */
Route::get('cliente/cambiarCitaDomicilio/{idCita?}', [
    'uses'       => 'ClienteController@cambiarCitaDomicilio',
    'middleware' => ['roles', 'auth'],
    'roles'      => ['Cliente']
]);


/**
 * Muestra la pantalla para el mapa
 * @author OT
 * Fecha: 05-01-2017
 */
Route::get('cliente/verMapaUbicacion', function () {

        return view('login.mapaUbicacion');
});

/* *********************   PERFIL   *****************************************/

/* Redirecciona al perfil del usuario logueado
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::get('/miPerfil', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@miPefil',
    'middleware' => 'roles',
    'roles'=>['Cliente','Prestador','Administrador'],
]);

/* Muestra la información del usuario prestador
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::get('/miPerfil/datosPrestador', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@datosPrestador',
    'middleware' => 'roles',
    'roles'=>['Prestador'],

]);

/* Guarda informacion personal del usuario prestador
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::post('/miPerfil/guardarInfoPrestador', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@guardarInfoPrestador',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Muestra el formulario para cambiar contraseña
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::get('/miPerfil/formularioContrasenaPerfil', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@formularioContrasenaPerfil',
    'middleware' => 'roles',
    'roles'=>['Cliente','Prestador','Administrador'],
]);


/* Cambia la contraseña del usuario
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::post('/general/guardarCambioContrasena', [
    'middleware' => 'auth',
    'uses'       => 'GeneralController@guardarCambioContrasena',
    'middleware' => 'roles',
    'roles'=>['Cliente','Prestador','Administrador'],
]);


/* Muestra el formulario de notificaciones del transportistas
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::get('/miPerfil/formularioNotificacionesPrestador', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@formularioNotificacionesPrestador',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);

/* Guarda las notificaciones del prestador de servicio
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::post('/miPerfil/guardarNotificacionesPrestador', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@guardarNotificacionesPrestador',
    'middleware' => 'roles',
    'roles'=>['Prestador'],
]);


/* Muestra la información del usuario cliente
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::get('/miPerfil/datosCliente', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@datosCliente',
    'middleware' => 'roles',
    'roles'=>['Cliente'],
]);

/* Guarda informacion personal del cliente
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::post('/miPerfil/guardarInfoCliente', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@guardarInfoCliente',
    'middleware' => 'roles',
    'roles'=>['Cliente'],
]);

/* Muestra el formulario de notificaciones del cliente
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::get('/miPerfil/formularioNotificacionesCliente', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@formularioNotificacionesCliente',
    'middleware' => 'roles',
    'roles'=>['Cliente'],
]);

/* Guarda las notificaciones del cliente
 * Autor: OT
 * Fecha: 29-12-2016
*/
Route::post('/miPerfil/guardarNotificacionesCliente', [
    'middleware' => 'auth',
    'uses'       => 'PerfilController@guardarNotificacionesCliente',
    'middleware' => 'roles',
    'roles'=>['Cliente'],
]);