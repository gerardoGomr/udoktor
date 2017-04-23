<?php
/* Controlador para crear cuentas
 * Autor: OT
 * Fecha: 05-12-2016
*/
namespace Udoktor\Http\Controllers;

use Illuminate\Http\Request;
use Udoktor\Http\Requests;
use Udoktor\Http\Controllers\Controller;
use Udoktor\User;
use Udoktor\V_person;
use Udoktor\RolUsuario;
use DB;
use Udoktor\V_service;
use Udoktor\V_classifications;
use Udoktor\V_personservice;
use Mail;

use Udoktor\Pais;
use Udoktor\Estado;
use Udoktor\Ciudad;
use Udoktor\Address;
use Udoktor\Shipper;
use Udoktor\Preferencias;
use Udoktor\ShipperAccount;

class CrearCuentaController extends Controller
{
    //funcion que llena los combos del login
    public function crearCuenta(){


        $sWhere1="deleted is null and active=true";
        $servicios = V_service::whereRaw(DB::raw($sWhere1))->orderBy("name")->get();

        $sWhere2="deleted is null and active=true";
        $clasificaciones = V_classifications::whereRaw(DB::raw($sWhere2))->orderBy("name")->get();


        $paises = Pais::where("active",1)->orderBy("name")->get();

        return view('login.crear_cuenta')
                ->with("servicios",$servicios)
                ->with("clasificaciones",$clasificaciones)
                ->with("paises",$paises)
            ;
    }

    /* Muestra el formulario para crear la cuenta del cliente
     * Autor: OT
     * Fecha: 21-07-2016
    */
    public function crearCuentaCliente(){
        return view('login.crearCuentaCliente');
    }

    /* Guarda el servicio
     * Autor: OT
     * Fecha: 06-12-2016
    */
    public function nuevoServicio(Request $request){
        $nuevoServicio=trim($request["nuevoServicio"]);
        $serviciosid=$request["serviciosid"];
        $_fecha=date("Y-m-d H:i:s");
        $cadenaServicios="";

        $existeServicio = V_service::whereRaw(DB::raw("upper(name)='".strtoupper($nuevoServicio)."'"))->count();

        if($existeServicio>0)return response()->json(['error' => 1,'servicios'=>$cadenaServicios]);

        try{
                DB::beginTransaction();
                $servicio=V_service::create([
                    'name'=>$nuevoServicio,
                    'description'=>'',
                    'created'=>$_fecha,
                    'updated'=>$_fecha,
                    'active'=>TRUE,
                ]);

                $idInsertado=$servicio->id;

                DB::commit();

                if($serviciosid==null){
                    $arregloServicios=array();
                }else{
                    $arregloServicios=$serviciosid;
                }

                $arregloServicios[]=$idInsertado;

                $serviciosActivos = V_service::whereRaw(DB::raw("deleted is null and active=true"))->orderBy('name')->get();

                foreach($serviciosActivos as $rowServicio){

                    if (in_array($rowServicio->id, $arregloServicios)) {
                        $cadenaServicios.="<option selected value='$rowServicio->id'>$rowServicio->name</option>";
                    }else{
                        $cadenaServicios.="<option value='$rowServicio->id'>$rowServicio->name</option>";
                    }
                }

                return response()->json(['error' => 0,'servicios'=>$cadenaServicios]);

        }catch (Exception $ex) {
            DB::rollback();
            return response()->json(['error' => 2,'servicios'=>$cadenaServicios]);
        }
    }


    /* Crea la cuenta del cliente
     * Autor: OT
     * Fecha: 06-12-2016
    */
    public function cuentaCliente(Request $request){
        $_fecha=date("Y-m-d H:i:s");

        if($request->ajax()){
            $nombreCompleto=trim($request["nombreCompleto"]);
            $emailCuenta=trim($request["emailCuenta"]);
            $companiaCuenta=trim($request["companiaCuenta"]);
            $telefonoCuenta=trim($request["telefonoCuenta"]);
            $passCuenta=trim($request["passCuenta"]);
            $valorCaptcha=$request["valorCaptcha"];
            $passEncriptado=bcrypt($passCuenta);
            $paisCuenta=$request["paisCuenta"];
            $estadoCuenta=$request["estadoCuenta"];
            $ciudadCuenta=$request["ciudadCuenta"];

            $existeUsuario = User::whereRaw(DB::raw("upper(email)='".strtoupper($emailCuenta)."'"))->count();

            if($existeUsuario>0)return response()->json(['error' => 1]);


            $secret = '6Lc9piUTAAAAAMLydHhGLzKHkvBPMwx41iO47R61';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$valorCaptcha);
            $responseData = json_decode($verifyResponse);
            if($responseData->success){
                try{
                        DB::beginTransaction();
                        $person=V_person::create([
                            'fullname'=>$nombreCompleto,
                            'company'=>$companiaCuenta,
                            'phone'=>$telefonoCuenta,
                            'isclient'=>TRUE,
                            'isserviceprovider'=>FALSE,
                            'suspended'=>FALSE,
                            'newoffer'=>TRUE,
                            'offercanceled'=>TRUE,
                            'newquestion'=>TRUE,
                            'offeraccepted'=>FALSE,
                            'offerrejected'=>FALSE,
                            'newreply'=>FALSE,
                            'shipmentcollected'=>TRUE,
                            'shipmentdelivered'=>TRUE,
                            'feedback'=>TRUE,
                            'assignedvehicle'=>TRUE,
                            'updated'=>$_fecha,
                            'newshipping'=>FALSE,
                            'shippingexpiration'=>FALSE,
                            'shippingcheck'=>TRUE,
                            'competingoffer'=>FALSE,
                            'newdate'=>FALSE,
                            'confirmationdate'=>TRUE,
                            'rejectiondate'=>TRUE,
                            'canceldate'=>FALSE,

                        ]);

                        $idInsertado=$person->id;

                         $dir=Address::create([
                            'streeUdoktor'=>'',
                            'cityid'=>$ciudadCuenta,
                            'street2'=>'',
                            'stateid'=>$estadoCuenta,
                            'personid'=>$idInsertado,
                            'postalcode'=>'',
                            'telephone'=>''
                        ]);


                        $usuario=User::create([
                            'email'=>strtolower($emailCuenta),
                            'password'=>$passEncriptado,
                            'resetpasswordtoken'=>'',
                            'resetpasswordsentat'=>$_fecha,
                            'remembercreatedat'=>$_fecha,
                            'currentsigninat'=>$_fecha,
                            'currentsigninip'=>'',
                            'lastsigninip'=>'',
                            'confirmedat'=>$_fecha,
                            'lastsigninat'=>$_fecha,
                            'confirmationsentat'=>$_fecha,
                            'lockedat'=>$_fecha,
                            'unlocktoken'=>'',
                            'confirmationtoken'=>'1',
                            'personid'=>$idInsertado,
                            'active'=>TRUE,
                            'verifiedaccount'=>TRUE,
                            'isverified'=>TRUE
                        ]);

                        $usuarioInsertado=$usuario->id;

                        $usuarioRol=  RolUsuario::create([
                            'created_at'=>$_fecha,
                            'updated_at'=>$_fecha,
                            'userid'=>$usuarioInsertado,
                            'roleid'=>1,
                        ]);


                        $datosCorreo=array();
                        $datosCorreo["clienteNombre"]=$nombreCompleto;
                        $datosCorreo["correo"]=strtolower($emailCuenta);

                        $emailCuenta=strtolower($emailCuenta);
                        Mail::send('correos.verifica',$datosCorreo,function($msj) use ($emailCuenta){
                            $msj->subject(" - Bienvenido a la comunidad");
                            $msj->to($emailCuenta);
                        });

                        $error="0";
                       DB::commit();
                }catch (Exception $ex) {
                    DB::rollback();
                    return $ex;
                }
            }else{
                $error=2;
            }
                return response()->json(['error' => $error]);
        }
    }


    /* Crea la cuenta del prestador de servicios
     * Autor: OT
     * Fecha: 06-12-2016
    */
    public function prestadorServicio(Request $request){
        $_fecha=date("Y-m-d H:i:s");
        if($request->ajax()){

            $nombreCompleto=trim($request["nombreCompleto"]);
            $emailCuenta=trim($request["emailCuenta"]);
            $companiaCuenta=trim($request["companiaCuenta"]);
            $telefonoCuenta=trim($request["telefonoCuenta"]);
            $passCuenta=trim($request["passCuenta"]);
            $valorCaptcha=$request["valorCaptcha"];
            $passEncriptado=bcrypt($passCuenta);
            $idClasificacion=trim($request["idClasificacion"]);
            $serviciosid=$request["serviciosid"];
            $latitudUbicacion=trim($request["latitudUbicacion"]);
            $longitudUbicacion=trim($request["longitudUbicacion"]);
            //$precioServicio=trim($request["precioServicio"]);
            //$precioGeneral=trim($request["precioGeneral"]);
            $paisCuenta=$request["paisCuenta"];
            $estadoCuenta=$request["estadoCuenta"];
            $ciudadCuenta=$request["ciudadCuenta"];

            $existeUsuario = User::whereRaw(DB::raw("upper(email)='".strtoupper($emailCuenta)."'"))->count();

            if($existeUsuario>0)return response()->json(['error' => 1]);


            $secret = '6Lc9piUTAAAAAMLydHhGLzKHkvBPMwx41iO47R61';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$valorCaptcha);
            $responseData = json_decode($verifyResponse);
            if($responseData->success){
                DB::beginTransaction();
                try{
                    if($existeUsuario==0){

                        $person=V_person::create([
                            'fullname'=>$nombreCompleto,
                            'company'=>$companiaCuenta,
                            'phone'=>$telefonoCuenta,
                            'isclient'=>FALSE,
                            'isserviceprovider'=>TRUE,
                            'suspended'=>FALSE,
                            'newoffer'=>TRUE,
                            'offercanceled'=>TRUE,
                            'newquestion'=>TRUE,
                            'offeraccepted'=>FALSE,
                            'offerrejected'=>FALSE,
                            'newreply'=>FALSE,
                            'shipmentcollected'=>TRUE,
                            'shipmentdelivered'=>TRUE,
                            'feedback'=>TRUE,
                            'assignedvehicle'=>TRUE,
                            'updated'=>$_fecha,
                            'newshipping'=>FALSE,
                            'shippingexpiration'=>FALSE,
                            'shippingcheck'=>TRUE,
                            'competingoffer'=>FALSE,
                            'id_classification'=>$idClasificacion,
                            'latitude'=>$latitudUbicacion,
                            'longitude'=>$longitudUbicacion,
                            'priceservice'=>false,
                            'generalprice'=>false,
                            'newdate'=>TRUE,
                            'confirmationdate'=>FALSE,
                            'rejectiondate'=>FALSE,
                            'canceldate'=>TRUE
                        ]);

                        $idInsertado=$person->id;

                        //guardamos servicios
                        if(count($serviciosid)>0){
                            for($k=0;$k<count($serviciosid);$k++){
                                $vSer= V_personservice::create([
                                    'personid'=>$idInsertado,
                                    'serviceid'=>$serviciosid[$k],
                                    'updated'=>$_fecha,
                                ]);
                            }
                        }

                        $dir=Address::create([
                            'streeUdoktor'=>'',
                            'cityid'=>$ciudadCuenta,
                            'street2'=>'',
                            'stateid'=>$estadoCuenta,
                            'personid'=>$idInsertado,
                            'postalcode'=>'',
                            'telephone'=>''
                        ]);


                        /*$estadosActivos=  Estado::where("countryid",$paisT)->where("active",1)->get();
                        foreach($estadosActivos as $rowEstados){
                                $preferencia=  Preferencias::create([
                                'personid'=>$idInsertado,
                                'administrativeunitid'=>$rowEstados->id,
                            ]);
                        }*/


                        $usuario=User::create([
                            'email'=>strtolower($emailCuenta),
                            'password'=>$passEncriptado,
                            'resetpasswordtoken'=>'',
                            'resetpasswordsentat'=>$_fecha,
                            'remembercreatedat'=>$_fecha,
                            'currentsigninat'=>$_fecha,
                            'currentsigninip'=>'',
                            'lastsigninip'=>'',
                            'confirmedat'=>$_fecha,
                            'lastsigninat'=>$_fecha,
                            'confirmationsentat'=>$_fecha,
                            'lockedat'=>$_fecha,
                            'unlocktoken'=>'',
                            'confirmationtoken'=>'1',
                            'personid'=>$idInsertado,
                            'active'=>TRUE,
                            'verifiedaccount'=>TRUE,
                            'isverified'=>TRUE
                        ]);

                        $usuarioInsertado=$usuario->id;

                            $usuarioRol=  RolUsuario::create([
                                'created_at'=>$_fecha,
                                'updated_at'=>$_fecha,
                                'userid'=>$usuarioInsertado,
                                'roleid'=>2,
                            ]);

                        /*$shipper=Shipper::create([
                              'personid'=>$idInsertado,
                              'paymentbank'=>'Default',
                              'paymentbankaccountownerfullname'=>$nombre1.' '.$apellido1,
                              'paymentbankaccountnumber'=>'123456789',
                              'paymentbankbranchnumber'=>'',
                              'paymentbankroutingnumber'=>''
                        ]);


                         $cuenta =  ShipperAccount::create([
                           'balance'=>0.00,
                           'available'=>0.00,
                           'createdat'=>$_fecha,
                           'currencyid'=>1,
                           'shipperid'=>$shipper->id,
                           'updated'=>$_fecha,
                          ]);*/


                        $datosCorreo=array();
                        $datosCorreo["clienteNombre"]=$nombreCompleto;
                        $datosCorreo["correo"]=strtolower($emailCuenta);

                        $emailCuenta=strtolower($emailCuenta);
                        Mail::send('correos.verifica',$datosCorreo,function($msj) use ($emailCuenta){
                            $msj->subject(" - Bienvenido a la comunidad");
                            $msj->to($emailCuenta);
                        });


                        DB::commit();
                        $error="0";
                    }else{
                        $error="1";
                    }
                }
                catch(Exception $e){
                    $error=$e->getMessage();
                    DB::rollaback();
                }
            }else{
                $error="2";
            }
            return response()->json(['error' => $error]);
        }
    }




    /*
     * Establece el password de la cuenta
     * Autor: OT
     * Fecha: 01-07-2016

    */
    public function confirmarPassword(Request $request){
        $pass1=trim($request["pass1"]);
        $pass2=trim($request["pass2"]);
        $idUsuario=$request["idUsuario"];
        $nombreUsuario=$request["nombreUsuario"];
        $correoUsuario=$request["correoUsuario"];
        $passTemp=$request["passTemp"];

        $fechaActual=date("Y-m-d H:i:s");
           DB::table('users')->where('id', $idUsuario)
                        ->update(['password' =>bcrypt($pass1),
                                  'confirmationtoken'=>"1",
                                  'updated_at'=>$fechaActual,
                                  'tokentemp'=>'',
                                ]);


        return redirect('login/inicio/1');
    }





    /*
     * Muestra la pantalla para verificar la cuenta del cliente
     * Autor: OT
     * Fecha: 02-07-2016

    */
    public function solicitarContrasena($correo=""){
        $correo=trim($correo);
        $idUsuario=0;
        $cliente="";
        if($correo==""){
            return "correonoencontrado" ;
        }

        $datosUsuario=DB::table('users')->select("users.id","users.email","users.confirmationtoken","person.fullname")
                     ->leftJoin('person', 'person.id', '=', 'users.personid')
                     ->where('users.email','=',$correo)
                     ->get();

        if(count($datosUsuario)==0){
            return "correonoencontrado";
        }

        foreach($datosUsuario as $rowUsuario){
            $idUsuario=$rowUsuario->id;
            $cliente=ucwords($rowUsuario->fullname);
        }

        $tokenEncriptado=$cliente.rand(1,100000);
        $tokenEncriptado=bcrypt($tokenEncriptado);
        $tokenEncriptado=  str_replace("/","", $tokenEncriptado);

        $datosCorreo=array();
        $datosCorreo["clienteNombre"]=$cliente;
        $datosCorreo["token"]=$tokenEncriptado;
        $datosCorreo["correo"]=$correo;

        DB::table('users')->where('id', $idUsuario)->update(['tokentemp' =>$tokenEncriptado]);


         Mail::send('correos.cambioContrasena',$datosCorreo,function($msj) use ($correo){
                $msj->subject("Efletex - Reestablecer contraseña");
                $msj->to($correo);
         });

         return "ok";
    }

    /*
     * Muestra la pantalla reestablecer la contraseña del usuario
     * Autor: OT
     * Fecha: 01-07-2016

    */
    public function reestablecerContrasena($correo="",$pass=""){
        $idUsuario="";
        $cliente="";
        $cuentaVerificada="0";

        if($pass=="" || $correo==""){
            return redirect('/login');
        }

        $datosUsuario=DB::table('users')->select("users.id","users.email","users.confirmationtoken","person.fullname")
                     ->leftJoin('person', 'person.id', '=', 'users.personid')
                     ->where('users.tokentemp','=',$pass)
                     ->where('users.email','=',$correo)
                     ->get();

        if(count($datosUsuario)==0){
            return redirect('/login');
        }

        foreach($datosUsuario as $rowUsuario){
            $idUsuario=$rowUsuario->id;
            $cliente=ucwords($rowUsuario->fullname);
            $cuentaVerificada=$rowUsuario->confirmationtoken;
        }


        return view('login.verificarCuenta')
                ->with("error",0)
                ->with("idUsuariol",$idUsuario)
                ->with("verificada",$cuentaVerificada)
                ->with("nombreCliente",$cliente)
                ->with("correoCliente",$correo)
                ->with("passCliente",$pass);

    }
}