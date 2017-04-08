<?php
/* Controlador para el inicio de sesion
 * Autor: OT
 * Fecha: 17-06-2016
*/
namespace Udoktor\Http\Controllers;
use Illuminate\Http\Request;
use Udoktor\Http\Requests;
use Udoktor\User;
use Udoktor\V_person;
use Session;
use Redirect;
use Response;
use Auth;
use Udoktor\Pais;
use Udoktor\Estado;
use Udoktor\Ciudad;
use DB;
use Udoktor\Funciones;
use Udoktor\Preferencias;


use Udoktor\Http\Controllers\Controller;

class PerfilController extends Controller
{
    /* Reedireciona al perfil del usuario logueado
     * Autor: OT
     * Fecha: 13-07-2016
    */
    public function miPefil(){
        
        $idPerson = Auth::user()->personid;
                $dataPerson=V_person::find($idPerson);
                if(count($dataPerson)>0){
                    if($dataPerson->isserviceprovider==true){
                        $compania="";
                        $nombre1="";
                        $correo="";
                        $ubicacion="";
                        $idEstado="";
                        $idPais="";
                        $arregloDatos=array();
                        $datosUsuario=DB::table('person')->select("person.fullname","person.company",
                        "address.stateid","state.countryid as idpais","state.name as nomestado","country.name as nompais",
                        "city.name as nombreciudad","users.email","person.img","person.updated")
                        ->leftJoin('users', 'users.personid', '=', 'person.id')
                        ->leftJoin('address', 'address.personid', '=', 'person.id')
                        ->leftJoin('city', 'city.id', '=', 'address.cityid')
                        ->leftJoin('state', 'state.id', '=', 'address.stateid')
                        ->leftJoin('country', 'country.id', '=', 'state.countryid')
                        ->where('person.id','=',$idPerson)
                        ->get();
                        
                        foreach($datosUsuario as $rowUsuario){
                            $arregloDatos["compania"]= ucfirst($rowUsuario->company);
                            $arregloDatos["nombre"]= ucfirst($rowUsuario->fullname);
                            $arregloDatos["correo"]=$rowUsuario->email;
                            $arregloDatos["ciudad"]=ucfirst($rowUsuario->nombreciudad);
                            $arregloDatos["idEstado"]=$rowUsuario->stateid;
                            $arregloDatos["idPais"]=$rowUsuario->idpais;
                            $arregloDatos["nombreEstado"]=ucfirst($rowUsuario->nomestado);
                            $arregloDatos["nombrePais"]=ucfirst($rowUsuario->nompais);
                            $arregloDatos["imagen"]=$rowUsuario->img;
                            $arregloDatos["actualizacion"]=Funciones::fechaF1Hora($rowUsuario->updated);
                        }
                        
                        return view('vvperfilPrestador.index')->with("datosUsuario",$arregloDatos);
                        
                        
                    }else if($dataPerson->isemployee==true){
                
                    }else if($dataPerson->isdriver==true){
                
                    }else{
                        $compania="";
                        $nombre1="";
                        $correo="";
                        $idEstado="";
                        $idPais="";
                        $idCiudad="";
                        $arregloDatos=array();
                        $datosUsuario=DB::table('person')->select("person.fullname","person.company",
                        "address.stateid","state.countryid as idpais","state.name as nomestado","country.name as nompais",
                        "city.name as nombreciudad","users.email","person.img","person.updated")
                        ->leftJoin('users', 'users.personid', '=', 'person.id')
                        ->leftJoin('address', 'address.personid', '=', 'person.id')
                        ->leftJoin('city', 'city.id', '=', 'address.cityid')
                        ->leftJoin('state', 'state.id', '=', 'address.stateid')
                        ->leftJoin('country', 'country.id', '=', 'state.countryid')
                        ->where('person.id','=',$idPerson)
                        ->get();
                        
                        foreach($datosUsuario as $rowUsuario){
                            $arregloDatos["compania"]= ucfirst($rowUsuario->company);
                            $arregloDatos["nombre"]= ucfirst($rowUsuario->fullname);
                            $arregloDatos["correo"]=$rowUsuario->email;
                            $arregloDatos["ciudad"]=ucfirst($rowUsuario->nombreciudad);
                            $arregloDatos["idEstado"]=$rowUsuario->stateid;
                            $arregloDatos["idPais"]=$rowUsuario->idpais;
                            $arregloDatos["nombreEstado"]=ucfirst($rowUsuario->nomestado);
                            $arregloDatos["nombrePais"]=ucfirst($rowUsuario->nompais);
                            $arregloDatos["imagen"]=$rowUsuario->img;
                            $arregloDatos["actualizacion"]=Funciones::fechaF1Hora($rowUsuario->updated);
                        }
                        
                        
                        return view('vvperfilcliente.index')->with("datosUsuario",$arregloDatos);
                    }
                }else{
                    Auth::logout();
                    return redirect('login/inicio');
                }
    }
    
    /* Muestra la información del usuario prestador de servicios
     * Autor: OT
     * Fecha: 29-12-2016
    */
    public function datosPrestador(){
        $idPerson = Auth::user()->personid;
                $dataPerson=  V_person::find($idPerson);
                if(count($dataPerson)==0){
                    Auth::logout();
                    return redirect('login/inicio');
                }else{
                        $compania="";
                        $nombre="";
                        $correo="";
                        $idEstado="";
                        $idPais="";
                        $arregloDatos=array();
                        $datosUsuario=DB::table('person')->select("person.fullname","person.company","person.phone",
                        "address.stateid","state.countryid","state.name as nomestado","country.name as nompais",
                        "city.name as nombreciudad","users.email","person.img","address.cityid")
                        ->leftJoin('users', 'users.personid', '=', 'person.id')
                        ->leftJoin('address', 'address.personid', '=', 'person.id')
                        ->leftJoin('city', 'city.id', '=', 'address.cityid')
                        ->leftJoin('state', 'state.id', '=', 'address.stateid')
                        ->leftJoin('country', 'country.id', '=', 'state.countryid')
                        ->where('person.id','=',$idPerson)
                        ->get();
                        
                        foreach($datosUsuario as $rowUsuario){
                            $arregloDatos["compania"]= ucfirst($rowUsuario->company);
                            $arregloDatos["nombre1"]= ucfirst($rowUsuario->fullname);
                            $arregloDatos["telefono"]=$rowUsuario->phone;
                            $arregloDatos["ciudad"]=ucfirst($rowUsuario->nombreciudad);
                            $arregloDatos["idEstado"]=$rowUsuario->stateid;
                            $arregloDatos["idPais"]=$rowUsuario->countryid;
                            $arregloDatos["idCiudad"]=$rowUsuario->cityid;
                            $arregloDatos["nombreEstado"]=ucfirst($rowUsuario->nomestado);
                            $arregloDatos["nombrePais"]=ucfirst($rowUsuario->nompais);
                            $arregloDatos["imagen"]=$rowUsuario->img;
                        }
                        
                        $pais = Pais::where('id', '>', 0)->where('active', '=', 1)->orderBy('name')->get();
                        $estado = Estado::where('countryid', '=', $arregloDatos["idPais"])->where('active', '=', 1)->orderBy('name')->get();
                        $ciudad = Ciudad::where('stateid', '=', $arregloDatos["idEstado"])->where('active', '=', 1)->orderBy('name')->get();
                        
                        return view('vvperfilPrestador.infoUsuario')
                                ->with("datosUsuario",$arregloDatos)
                                ->with('ciudad',$ciudad)
                                ->with('estado',$estado)
                                ->with('pais',$pais);
                    
                    
                }
    }
    
    /* Guarda informacion personal del prestador de servicio
     * Autor: OT
     * Fecha: 29-12-2016
    */
    public function guardarInfoPrestador(Request $request){
        $idPerson = Auth::user()->personid;
        $fechaActual=date("Y-m-d H:i:s");
        $imagenPerfil=trim($request["imagenPerfil"]);
        $imagenPerfilTem=trim($request["imagenPerfil"]);
        $primerNombre=trim($request["primerNombre"]);
        $telefono=trim($request["telefono"]);
        $paisT=trim($request["paisT"]);
        $estadoT=trim($request["estadoT"]);
        $ciudad=trim($request["ciudad"]);
        $imagenModificada=trim($request["imagenModificada"]);
        $nombrearchivo='';
        $error=0;
        
        
        if($imagenPerfilTem!="" && strlen($imagenPerfilTem)>100){
                 if(substr($imagenPerfilTem,0,10)=="data:image"){
                    list($tipo, $imagenPerfilTem) = explode(';', $imagenPerfilTem);
                    if($tipo!="data:image/jpeg" && $tipo!="data:image/png" && $tipo!="data:image/gif"){
                        $error=1;
                        return "errorimagen";
                    }
                }else{
                    $error=1;
                    return "errorimagen";
                }
             }
        if($error==0){
         $cambiaImagen=0;
         
                            if($imagenPerfil!="" && strlen($imagenPerfil)>100){
                                $cambiaImagen=1;
                                $dirImagen="imagenPerfil/".$idPerson;
                                if(!file_exists($dirImagen)){
                                    mkdir($dirImagen, 0777);
                                }

                                list($tipo, $imagenPerfil) = explode(';', $imagenPerfil);
                                list(, $imagenPerfil) = explode(',', $imagenPerfil);
                                $imagenPerfil = base64_decode($imagenPerfil);
                                if($tipo=="data:image/jpeg"){
                                    $nombrearchivo=$idPerson."img.jpg";
                                    $tipoA=1;
                                }else if($tipo=="data:image/png"){
                                    $nombrearchivo=$idPerson."img.png";
                                    $tipoA=2;
                                }else if($tipo=="data:image/gif"){
                                    $nombrearchivo=$idPerson."img.gif";
                                    $tipoA=3;
                                }
                                

                                file_put_contents($dirImagen."/".$nombrearchivo, $imagenPerfil);
                                $nombrearchivo=$dirImagen."/".$nombrearchivo;
                            }
            
            
            
            try{
                DB::beginTransaction();

                if($cambiaImagen==1){
                    DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'fullname' =>$primerNombre,
                                        'phone' =>$telefono ,
                                        'updated' =>$fechaActual,
                                        'img' => $nombrearchivo,
                                   ]);
                }else if($imagenModificada==1){
                    DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'fullname' =>$primerNombre,
                                        'phone' =>$telefono ,
                                        'updated' =>$fechaActual,
                                        'img' => '',
                                    ]);
                }else{
                    DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'fullname' =>$primerNombre,
                                        'phone' =>$telefono ,
                                        'updated' =>$fechaActual,
                                    ]);
                }
                

                DB::table('address')->where('personid', $idPerson)
                            ->update([
                                        'streeUdoktor' =>'',
                                        'street2' =>'' ,
                                        'cityid' =>$ciudad,
                                        'stateid' =>$estadoT ,
                                        'postalcode' =>'' ,
                                    ]);



                DB::commit();
                return "ok";
            }catch (Exception $ex) {
                DB::rollback();
                return $ex;
            }
        }
    }
    
    /* Muestra la información del usuario cliente
     * Autor: OT
     * Fecha: 29-12-2016
    */
    public function datosCliente(){
        $idPerson = Auth::user()->personid;
         $dataPerson=  V_person::find($idPerson);
                if(count($dataPerson)==0){
                    Auth::logout();
                    return redirect('login/inicio');
                }else{
                        $compania="";
                        $nombre="";
                        $correo="";
                        $idEstado="";
                        $idPais="";
                        $arregloDatos=array();
                        $datosUsuario=DB::table('person')->select("person.fullname","person.company","person.phone",
                        "address.stateid","state.countryid","state.name as nomestado","country.name as nompais",
                        "city.name as nombreciudad","users.email","person.img","address.cityid")
                        ->leftJoin('users', 'users.personid', '=', 'person.id')
                        ->leftJoin('address', 'address.personid', '=', 'person.id')
                        ->leftJoin('city', 'city.id', '=', 'address.cityid')
                        ->leftJoin('state', 'state.id', '=', 'address.stateid')
                        ->leftJoin('country', 'country.id', '=', 'state.countryid')
                        ->where('person.id','=',$idPerson)
                        ->get();
                        
                        foreach($datosUsuario as $rowUsuario){
                            $arregloDatos["compania"]= ucfirst($rowUsuario->company);
                            $arregloDatos["nombre1"]= ucfirst($rowUsuario->fullname);
                            $arregloDatos["telefono"]=$rowUsuario->phone;
                            $arregloDatos["ciudad"]=ucfirst($rowUsuario->nombreciudad);
                            $arregloDatos["idEstado"]=$rowUsuario->stateid;
                            $arregloDatos["idPais"]=$rowUsuario->countryid;
                            $arregloDatos["idCiudad"]=$rowUsuario->cityid;
                            $arregloDatos["nombreEstado"]=ucfirst($rowUsuario->nomestado);
                            $arregloDatos["nombrePais"]=ucfirst($rowUsuario->nompais);
                            $arregloDatos["imagen"]=$rowUsuario->img;
                        }
                        
                        $pais = Pais::where('id', '>', 0)->where('active', '=', 1)->orderBy('name')->get();
                        $estado = Estado::where('countryid', '=', $arregloDatos["idPais"])->where('active', '=', 1)->orderBy('name')->get();
                        $ciudad = Ciudad::where('stateid', '=', $arregloDatos["idEstado"])->where('active', '=', 1)->orderBy('name')->get();
                        
                        return view('vvperfilcliente.infoUsuario')
                                ->with("datosUsuario",$arregloDatos)
                                ->with('ciudad',$ciudad)
                                ->with('estado',$estado)
                                ->with('pais',$pais);
                        
                        return view('vvperfilcliente.infoUsuario')->with("datosUsuario",$arregloDatos);
                    
                }
    }
    
    /* Guarda informacion personal del cliente
     * Autor: OT
     * Fecha: 14-07-2016
    */
    public function guardarInfoCliente(Request $request){
        $idPerson = Auth::user()->personid;
        $fechaActual=date("Y-m-d H:i:s");
        $imagenPerfil=trim($request["imagenPerfil"]);
        $imagenPerfilTem=trim($request["imagenPerfil"]);
        $primerNombre=trim($request["primerNombre"]);
        $paisT=trim($request["paisT"]);
        $telefono=trim($request["telefono"]);
        $estadoT=trim($request["estadoT"]);
        $ciudad=trim($request["ciudad"]);
        $imagenModificada=trim($request["imagenModificada"]);
        $nombrearchivo='';
        $error=0;
        
        if($imagenPerfilTem!="" && strlen($imagenPerfilTem)>30){
                 if(substr($imagenPerfilTem,0,10)=="data:image"){
                    list($tipo, $imagenPerfilTem) = explode(';', $imagenPerfilTem);
                    if($tipo!="data:image/jpeg" && $tipo!="data:image/png" && $tipo!="data:image/gif"){
                        $error=1;
                        return "errorimagen";
                    }
                }else{
                    $error=1;
                    return "errorimagen";
                }
             }
        if($error==0){
         $cambiaImagen=0;
         
                            if($imagenPerfil!="" && strlen($imagenPerfil)>30){
                                $cambiaImagen=1;
                                $dirImagen="imagenPerfil/".$idPerson;
                                if(!file_exists($dirImagen)){
                                    mkdir($dirImagen, 0777);
                                }

                                list($tipo, $imagenPerfil) = explode(';', $imagenPerfil);
                                list(, $imagenPerfil) = explode(',', $imagenPerfil);
                                $imagenPerfil = base64_decode($imagenPerfil);
                                if($tipo=="data:image/jpeg"){
                                    $nombrearchivo=$idPerson."img.jpg";
                                    $tipoA=1;
                                }else if($tipo=="data:image/png"){
                                    $nombrearchivo=$idPerson."img.png";
                                    $tipoA=2;
                                }else if($tipo=="data:image/gif"){
                                    $nombrearchivo=$idPerson."img.gif";
                                    $tipoA=3;
                                }
                                

                                file_put_contents($dirImagen."/".$nombrearchivo, $imagenPerfil);
                                $nombrearchivo=$dirImagen."/".$nombrearchivo;
                            }
            try{
                DB::beginTransaction();

                if($cambiaImagen==1){
                    DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'fullname' =>$primerNombre,
                                        'phone' =>$telefono ,
                                        'updated' =>$fechaActual,
                                        'img' => $nombrearchivo,
                                    ]);
                } else if($imagenModificada==1){
                    DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'fullname' =>$primerNombre,
                                        'phone' =>$telefono ,
                                        'updated' =>$fechaActual,
                                        'img' => '',
                                    ]);
                }else{
                    DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'fullname' =>$primerNombre,
                                        'phone' =>$telefono ,
                                        'updated' =>$fechaActual,
                                    ]);
                }
                
                DB::table('address')->where('personid', $idPerson)
                            ->update([
                                        'streeUdoktor' =>'',
                                        'street2' =>'' ,
                                        'cityid' =>$ciudad,
                                        'stateid' =>$estadoT ,
                                        'postalcode' =>'' ,
                                    ]);

                DB::commit();
                return "ok";
            }catch (Exception $ex) {
                DB::rollback();
                return $ex;
            }
        }
    }
    
    /* Muestra el formulario para cambiar la contraseña
     * Autor: OT
     * Fecha: 15-07-2016
    */
    public function formularioContrasenaPerfil(){
        $idPerson = Auth::user()->personid;
         $dataPerson=  V_person::find($idPerson);
                if(count($dataPerson)==0){
                    Auth::logout();
                    return redirect('login/inicio');
                }else{
                     return view('vvperfilcliente.cambioContrasena');
                }
    }
    
    /* Muestra el formulario de notificaciones del transportistas
     * Autor: OT
     * Fecha: 15-07-2016
    */
    public function formularioNotificacionesPrestador(){
        $idPerson = Auth::user()->personid;
         $dataPerson=  V_person::find($idPerson);
         if(count($dataPerson)==0){
             Auth::logout();
             return redirect('login/inicio');
         }else{
             $arreglocheck=array();
             
             if($dataPerson->newdate==true){
                 $arreglocheck["nuevaCita"]="checked";
             }else{
                 $arreglocheck["nuevaCita"]="";
             }
             
             if($dataPerson->canceldate==true){
                 $arreglocheck["cancelada"]="checked";
             }else{
                 $arreglocheck["cancelada"]="";
             }
             
             return view('vvperfilPrestador.notificaciones')->with("datos",$arreglocheck);
         }
    }
    
    /* Guarda las notificaciones del prestador de servicios
     * Autor: OT
     * Fecha: 29-12-2016
    */
    public function guardarNotificacionesPrestador(Request $request){
        $idPerson = Auth::user()->personid;
        $fechaActual=date("Y-m-d H:i:s");
        
        $nuevaCita=trim($request["nuevaCita"]);
        $citaCancelada=trim($request["citaCancelada"]);
        
            try{
                DB::beginTransaction();
                
                DB::table('person')->where('id', $idPerson)
                            ->update([
                                'newdate' =>$nuevaCita,
                                'canceldate' =>$citaCancelada ,
                                'updated'=>$fechaActual
                            ]);

                DB::commit();
                return "ok";
            }catch (Exception $ex) {
                DB::rollback();
                return $ex;
            }
    }
    
    
    /* Muestra el formulario de notificaciones del cliente
     * Autor: OT
     * Fecha: 29-12-2016
    */
    public function formularioNotificacionesCliente(){
        
        $idPerson = Auth::user()->personid;
         $dataPerson=  V_person::find($idPerson);
         if(count($dataPerson)==0){
             Auth::logout();
             return redirect('login/inicio');
         }else{
             $arreglocheck=array();
             
             if($dataPerson->confirmationdate==true){
                 $arreglocheck["confirmacioncita"]="checked";
             }else{
                 $arreglocheck["confirmacioncita"]="";
             }
             
             if($dataPerson->rejectiondate==true){
                 $arreglocheck["citarechazada"]="checked";
             }else{
                 $arreglocheck["citarechazada"]="";
             }
             
             return view('vvperfilcliente.notificaciones')->with("datos",$arreglocheck);
         }
    }
    
    /* Guarda las notificaciones del cliente
     * Autor: OT
     * Fecha: 29-07-2016
    */
    public function guardarNotificacionesCliente(Request $request){
        $idPerson = Auth::user()->personid;
        $fechaActual=date("Y-m-d H:i:s");
        
        $confirmacioncita=trim($request["confirmacioncita"]);
        $citarechazada=trim($request["citarechazada"]);
        
        
            try{
                DB::beginTransaction();
                
                DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'confirmationdate' =>$confirmacioncita,
                                        'rejectiondate' =>$citarechazada ,
                                        'updated'=>$fechaActual
                                    ]);

                DB::commit();
                return "ok";
            }catch (Exception $ex) {
                DB::rollback();
                return $ex;
            }
    }
    
    
    
    /* Muestra las preferencias del transportista
     * Autor: OT
     * Fecha: 15-07-2016
    */
    public function verPreferenciasTransportista(){
        
        $idPerson = Auth::user()->personid;
         $dataPerson=Person::find($idPerson);
         if(count($dataPerson)==0){
             Auth::logout();
             return redirect('login/inicio');
         }else{
           $paisesUsuario= DB::table('preferences')->select("country.id")
                        ->leftJoin('administrativeunit', 'administrativeunit.id', '=', 'preferences.administrativeunitid')
                        ->leftJoin('country', 'country.id', '=', 'administrativeunit.countryid')
                        ->where("preferences.personid","=",$idPerson)
                        ->distinct()
                        ->get();
           
           $estadosUsuario= DB::table('preferences')->where("personid","=",$idPerson)->get();
           
           $cadenaPaisesUsuario="";
           $arregloPaisesUsuario=array();
           foreach($paisesUsuario as $rowPaisesUsuarios){
               $cadenaPaisesUsuario.=$rowPaisesUsuarios->id . ",";
               $arregloPaisesUsuario[]=$rowPaisesUsuarios->id;
           }
           
           $cadenaEstadosUsuario="";
           $arregloEstadosUsuario=array();
           foreach($estadosUsuario as $rowEstadosUsuarios){
               $cadenaEstadosUsuario.=$rowEstadosUsuarios->administrativeunitid . ",";
               $arregloEstadosUsuario[]=$rowEstadosUsuarios->administrativeunitid;
           }
           
          /*Agregamos estados  */
         $respuesta="";
         $contaEstados=0;
            for ($k=0;$k<count($arregloPaisesUsuario);$k++){
                $estadoPais = Estado::where('id', '>', 0)->where('active', '=', 1)->where('countryid',$arregloPaisesUsuario[$k])->orderBy('name')->get(); 
                $dataPais=Pais::find($arregloPaisesUsuario[$k]);
                $nombrePais="";
                if(count($dataPais)>0){
                    $nombrePais=  ucwords($dataPais->name);
                }
                    
                $respuesta.="<div class='row'><p><b>$nombrePais:</b>&nbsp;&nbsp;";
                if(count($estadoPais)>0){
                    foreach($estadoPais as $rowEstadoPais){
                        $estadoSeleccionado="";
                        if (in_array($rowEstadoPais->id, $arregloEstadosUsuario)) {
                           $respuesta.=$rowEstadoPais->name.", ";
                           $contaEstados++;
                        }
                    }
                }
                $respuesta=  substr($respuesta, 0, -2);
                $respuesta.=".</p></div>";
            }

           
          $divEstados="<div class='col-sm-12'>".$respuesta."</div>";
          
          $datosPreferencias=array();
          $datosPreferencias["estados"]=$divEstados; 
          $datosPreferencias["contaEstados"]=$contaEstados;
          
          return view('vvperfiltransportista.verPreferencias')->with('datosPreferencias',$datosPreferencias);
         }
    }
    
    
    /* Muestra el formulario de las preferencias del transportista
     * Autor: OT
     * Fecha: 15-07-2016
    */
    public function formularioPreferenciasTransportista(){
        
        $idPerson = Auth::user()->personid;
         $dataPerson=Person::find($idPerson);
         if(count($dataPerson)==0){
             Auth::logout();
             return redirect('login/inicio');
         }else{
           $paisesUsuario= DB::table('preferences')->select("country.id")
                        ->leftJoin('administrativeunit', 'administrativeunit.id', '=', 'preferences.administrativeunitid')
                        ->leftJoin('country', 'country.id', '=', 'administrativeunit.countryid')
                        ->where("preferences.personid","=",$idPerson)
                        ->distinct()
                        ->get();
           
           $estadosUsuario= DB::table('preferences')->where("personid","=",$idPerson)->get();
           
           $cadenaPaisesUsuario="";
           $arregloPaisesUsuario=array();
           foreach($paisesUsuario as $rowPaisesUsuarios){
               $cadenaPaisesUsuario.=$rowPaisesUsuarios->id . ",";
               $arregloPaisesUsuario[]=$rowPaisesUsuarios->id;
           }
           
           $cadenaEstadosUsuario="";
           $arregloEstadosUsuario=array();
           foreach($estadosUsuario as $rowEstadosUsuarios){
               $cadenaEstadosUsuario.=$rowEstadosUsuarios->administrativeunitid . ",";
               $arregloEstadosUsuario[]=$rowEstadosUsuarios->administrativeunitid;
           }
           
           $pais = Pais::where('id', '>', 0)->where('active', '=', 1)->orderBy('name')->get(); 
           
           $cadenaPaises="<div class='form-group'>";
           foreach($pais as $rowPais){
              $paisSeleccionado="";
              if (in_array($rowPais->id, $arregloPaisesUsuario)) {
                  $paisSeleccionado="checked";
              }
               
              $cadenaPaises.="<div class='col-sm-4'>
                                <input type='checkbox' $paisSeleccionado name='chPais' id='$rowPais->id'> $rowPais->name
                              </div>";
           }
          $cadenaPaises.="</div>";
          $divPaises="<div class='col-sm-12'>".$cadenaPaises ."</div>";
          
          
          
          
          /*Agregamos estados  */
         $respuesta="";
            for ($k=0;$k<count($arregloPaisesUsuario);$k++){
                $estadoPais = Estado::where('id', '>', 0)->where('active', '=', 1)->where('countryid',$arregloPaisesUsuario[$k])->orderBy('name')->get(); 
                $dataPais=Pais::find($arregloPaisesUsuario[$k]);
                $nombrePais="";
                if(count($dataPais)>0){
                    $nombrePais=  ucwords($dataPais->name);
                }
                    
                $respuesta.="<div class='row'><p><b>$nombrePais</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type='checkbox' onchange='seleccionarTodo(this.id)' value='$arregloPaisesUsuario[$k]' name='chPaisTodo' id='chPaisTodo$arregloPaisesUsuario[$k]'>". trans("leng.Seleccionar todo").
                        "</p><div class='form-group'>";
                if(count($estadoPais)>0){
                    foreach($estadoPais as $rowEstadoPais){
                        $estadoSeleccionado="";
                        if (in_array($rowEstadoPais->id, $arregloEstadosUsuario)) {
                           $estadoSeleccionado="checked";
                        }
                        $respuesta.="<div class='col-sm-4'>
                                       <input type='checkbox' $estadoSeleccionado name='chEstado' value='$arregloPaisesUsuario[$k]' id='$rowEstadoPais->id'> $rowEstadoPais->name
                                 </div>";
                    }
                }else{
                    $respuesta.="<div class='col-sm-4'>
                                       El país seleccionado no tiene estados activos.
                                 </div>";
                }
                $respuesta.="</div></div><br>";
            }

           
          $divEstados="<div class='col-sm-12'>".$respuesta."</div>";
          
          $datosPreferencias=array();
          $datosPreferencias["paises"]=$divPaises;
          $datosPreferencias["estados"]=$divEstados; 
          $datosPreferencias["cadenaestados"]=$cadenaEstadosUsuario;
          
          return view('vvperfiltransportista.editarPreferencias')->with('datosPreferencias',$datosPreferencias);
         }
    }
    
    /* Muestra el formulario de las preferencias del transportista
     * Autor: OT
     * Fecha: 15-07-2016
    */
    public function cargarEstadosTab(Request $request){
        
        $cadenaPaises=trim($request["cadenaPaises"]);
        $cadenaEstados=trim($request["cadenaEstados"]);
        
        $idPerson = Auth::user()->personid;
         $dataPerson=Person::find($idPerson);
         if(count($dataPerson)==0){
             Auth::logout();
             return redirect('login/inicio');
         }else{
             $cadenaPaises=  substr($cadenaPaises,0, -1);
             $arregloPais = explode(",", $cadenaPaises);
             
             if($cadenaEstados!=""){
                 $cadenaEstados=  substr($cadenaEstados,0, -1);
                 $arregloEstados = explode(",", $cadenaEstados);
             }else{
                 $arregloEstados[]=0;
             }
             
            $respuesta="";
            for($k=0;$k<count($arregloPais);$k++){
                $estadoPais = Estado::where('id', '>', 0)->where('active', '=', 1)->where('countryid',$arregloPais[$k])->orderBy('name')->get(); 
                $dataPais=Pais::find($arregloPais[$k]);
                $nombrePais="";
                if(count($dataPais)>0){
                    $nombrePais=  ucwords($dataPais->name);
                }
                    
                $respuesta.="<div class='row'><p><b>$nombrePais</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type='checkbox' onchange='seleccionarTodo(this.id)' value='$arregloPais[$k]' name='chPaisTodo' id='chPaisTodo$arregloPais[$k]'>". trans("leng.Seleccionar todo").
                        "</p><div class='form-group'>";
                if(count($estadoPais)>0){
                    foreach($estadoPais as $rowEstadoPais){
                        $estadoSeleccionado="";
                        if (in_array($rowEstadoPais->id, $arregloEstados)) {
                           $estadoSeleccionado="checked";
                        }
                        $respuesta.="<div class='col-sm-4'>
                                       <input type='checkbox' $estadoSeleccionado name='chEstado' value='$arregloPais[$k]' id='$rowEstadoPais->id'> $rowEstadoPais->name
                                 </div>";
                    }
                }else{
                    $respuesta.="<div class='col-sm-4'>
                                       El país seleccionado no tiene estados activos.
                                 </div>";
                }
                $respuesta.="</div></div><br>";
            }

           
          $divEstados="<div class='col-sm-12'>".$respuesta."</div>";
             
          return $divEstados;
         }
    }
    
    
    /* Guarda las preferencias del transportista
     * Autor: OT
     * Fecha: 15-07-2016
    */
    public function guardarEstadosTransportista(Request $request){
        $idPerson = Auth::user()->personid;
        $fechaActual=date("Y-m-d H:i:s");
        
        $cadenaEstados=trim($request["cadenaEstados"]);
        
            try{
                DB::beginTransaction();
                
                $cadenaEstados=  substr($cadenaEstados,0, -1);
                $arregloEstados = explode(",", $cadenaEstados);
                
                $eliminados = Preferencias::where('personid', '=', $idPerson)->delete();
                
                for($k=0;$k<count($arregloEstados);$k++) {
                    $preferencia=  Preferencias::create([
                            'personid'=>$idPerson,
                            'administrativeunitid'=>$arregloEstados[$k],
                        ]);
                }
                
                DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'updated'=>$fechaActual
                                    ]);

                DB::commit();
                return "ok";
            }catch (Exception $ex) {
                DB::rollback();
                return $ex;
            }
    }
    
    
    /* Eliminar las preferencias del transportista
     * Autor: OT
     * Fecha: 16-07-2016
    */
    public function eliminarEstadosTransportista(){
        $idPerson = Auth::user()->personid;
        $fechaActual=date("Y-m-d H:i:s");
        
        
            try{
                DB::beginTransaction();
                
                $eliminados = Preferencias::where('personid', '=', $idPerson)->delete();
                
                DB::table('person')->where('id', $idPerson)
                            ->update([
                                        'updated'=>$fechaActual
                                    ]);

                DB::commit();
                return "ok";
            }catch (Exception $ex) {
                DB::rollback();
                return $ex;
            }
    }
    
    
}
