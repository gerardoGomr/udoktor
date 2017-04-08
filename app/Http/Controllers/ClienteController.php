<?php
/* Controlador para el inicio de sesion
 * Autor: OT
 * Fecha: 17-06-2016
*/
namespace Udoktor\Http\Controllers;
use Illuminate\Http\Request;
use Udoktor\Http\Requests;
use Udoktor\Pais;
use Udoktor\Estado;
use Udoktor\User;
use Udoktor\V_person;
use Udoktor\Funciones;
use Auth;
use Udoktor\Http\Controllers\Controller;
use DB;
use Yajra\Datatables\Datatables;
use Mail;
use Udoktor\Address;
use Udoktor\Ciudad;
use Udoktor\V_classifications;
use Udoktor\V_service;
use Udoktor\V_personDiary;
use Udoktor\V_dates;
use Udoktor\V_personservice;
use Udoktor\V_datesService;

class ClienteController extends Controller{
    
    /*
     * Muestra el index del cliente
     * Autor: Andres
     * 
     */
    public function Dashboard(){
        $person      = V_person::find(Auth::user()->personid);

        $Dashboard = array();
        
        /*$Dashboard["activos"]  = ShippingRequest::where('requesterid', $person->id)->where('status','=',1)->count(); 

        $Dashboard["recolectar"]  = ShippingRequest::where('requesterid', $person->id)->where('status','=',2)->count(); 

        $Dashboard["entregar"]  = ShippingRequest::where('requesterid', $person->id)->where('status','=',3)->count(); 

        $Dashboard["calf"] = $person->starrating==""?"Sin Calificación":$person->starrating;*/
        
        return view('vvcliente.index')->with('Dashboard',$Dashboard);
    }
    
        
    /* Muestra el formulario para buscar servicios
     * Autor: OT
     * Fecha: 20-12-2016
    */
    public function servicios(){
        if (Auth::check()) {
            
            $dPais=Address::select("state.countryid","address.stateid","address.cityid")
                    ->leftJoin("state","state.id","=","address.stateid")
                    ->where("address.personid",Auth::user()->personid)
                    ->get();
            
            $vPais=0;
            $vEstado=0;
            $vcity=0;
            if(count($dPais)>0){
                $vPais=$dPais[0]->countryid;
                $vEstado=$dPais[0]->stateid;
                $vcity=$dPais[0]->cityid;
            }
            // obtener paises
            $paises = Pais::where("active",1)->orderBy("name")->get();
            $cadenaPaises="";
            foreach($paises as $pais){
                if($pais->id==$vPais){
                    $cadenaPaises.="<option value='$pais->id' selected >$pais->name</option>";
                }else{
                    $cadenaPaises.="<option value='$pais->id'>$pais->name</option>";
                }
            }
            
            $cadenaEstados="";
            $estados = Estado::where("active",1)->where("countryid",$vPais)->orderBy("name")->get();
            foreach($estados as $estado){
                if($estado->id==$vEstado){
                    $cadenaEstados.="<option value='$estado->id' selected >$estado->name</option>";
                }else{
                    $cadenaEstados.="<option value='$estado->id'>$estado->name</option>";
                }
            }
            
            $cadenaCiudades="";
            $ciudades = Ciudad::where("active",1)->where("stateid",$vEstado)->orderBy("name")->get();
            foreach($ciudades as $ciudad){
                if($ciudad->id==$vcity){
                    $cadenaCiudades.="<option value='$ciudad->id' selected >$ciudad->name</option>";
                }else{
                    $cadenaCiudades.="<option value='$ciudad->id'>$ciudad->name</option>";
                }
            }
            
            $cadenaEspecialidad="";
            $especialidades = V_classifications::where("active",true)->whereNull("deleted")->orderBy("name")->get();
            foreach($especialidades as $especialidad){
               $cadenaEspecialidad.="<option value='$especialidad->id'>$especialidad->name</option>";
            }
            
            $cadenaServicio="";
            $servicios = V_service::where("active",true)->whereNull("deleted")->orderBy("name")->get();
            foreach($servicios as $servicio){
               $cadenaServicio.="<option value='$servicio->id'>$servicio->name</option>";
            }

            
            
            $person      = V_person::find(Auth::user()->personid);
            
            $vCombos=array();
            $vCombos["cadenaPaises"]=$cadenaPaises;
            $vCombos["cadenaEstados"]=$cadenaEstados;
            $vCombos["cadenaCiudades"]=$cadenaCiudades;
            $vCombos["cadenaEspecialidad"]=$cadenaEspecialidad;
            $vCombos["cadenaServicio"]=$cadenaServicio;
            
            return view('vvcliente.servicios')->with("person",$person)->with("vCombos",$vCombos);

        } else{
            return view('login.login');
        }
    }
    
    
    /**
     * buscar los estados correspondientes al país seleccionado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarEstados(Request $request)
    {
        $paisId    = (int)$request->get('paisId');
        
        
        $estados=  Estado::where("countryid",$paisId)->where("active",1)->orderBy("name")->get();
        $cadenaEstados="<option value='0'>Todos</option>";
        foreach($estados as $estado){
                $cadenaEstados.="<option value='$estado->id'>$estado->name</option>";
        }
        
        $respuesta = [];
        $html      = $cadenaEstados;

        $respuesta['estatus'] = 'OK';
        $respuesta['html']    = $html;

        return response()->json($respuesta);
    }
    
    /**
     * buscar las ciudades correspondientes al estado seleccionado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarCiudades(Request $request)
    {
        $estadoId    = (int)$request->get('estadoId');
        
        
        $ciudades= Ciudad::where("stateid",$estadoId)->where("active",1)->orderBy("name")->get();
        $cadenaCiudades="<option value='0'>Todos</option>";
        foreach($ciudades as $ciudade){
                $cadenaCiudades.="<option value='$ciudade->id'>$ciudade->name</option>";
        }
        
        $respuesta = [];
        $html      = $cadenaCiudades;

        $respuesta['estatus'] = 'OK';
        $respuesta['html']    = $html;

        return response()->json($respuesta);
    }
    
    /**
     * buscar servicios en base a los filtros de búsqueda.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function buscarServiciosMapa(Request $request)
    {
        $respuesta = [];
        
        $pais=$request->get('paisRecoleccion');
        $estado=$request->get('estadoRecoleccion');
        $ciudad=$request->get('ciudadRecoleccion');
        $vEspecialidad=$request->get('vEspecialidad');
        $vServicios=$request->get('vServicios');
        
        
        $wherePais="";
        if($pais!="0" && $pais!=""){
            $wherePais=" and state.countryid=$pais";
        }
        
        $whereEstado="";
        if($estado!="0" && $estado!=""){
            $whereEstado=" and address.stateid=$estado";
        }
        
        $whereCiudad="";
        if($ciudad!="0" && $ciudad!=""){
            $whereCiudad=" and address.cityid=$ciudad";
        }
        
        $whereEspecialidad="";
        if($vEspecialidad!="0" && $vEspecialidad!=""){
            $whereCiudad=" and person.id_classification=$vEspecialidad";
        }
        
        $whereServicio="";
        if($vServicios!="0" && $vServicios!=""){
            $whereServicio=" and services.id=$vServicios";
        }
        
        $personServices=  V_person::select("person.company","person.fullname","person.phone","person.id",
                "person.latitude","person.longitude","state.name as nombreestado","city.name as nombreciudad",
                "country.name as nombrepais"
                )
           ->leftJoin("address","address.personid","=","person.id")
           ->leftJoin("city","city.id","=","address.cityid")
           ->leftJoin("state","state.id","=","address.stateid")
           ->leftJoin("country","country.id","=","state.countryid")
           ->leftJoin("personservice","personservice.personid","=","person.id")
           ->leftJoin("services","services.id","=","personservice.serviceid")
           ->whereRaw(DB::raw("person.isserviceprovider=true and address.id is not null  $wherePais $whereEstado $whereCiudad $whereServicio"))
           ->distinct()->get();
        
        $registros=count($personServices);
        $tabla="";
        $arregloPrestador=array();
        if($registros>0){
            foreach($personServices as $personService){
                
                $datosPrestador="<table style='width: 250px'>
                            <tr>
                                <td><b>$personService->company</b></td>
                            </tr>
                            <tr>
                                <td>$personService->fullname</td>
                            </tr>
                            <tr>
                                <td>$personService->nombreciudad, $personService->nombreestado, $personService->nombrepais Tel.$personService->phone</td>
                            </tr>
                            <tr>
                                <td><b><i class='fa fa-calendar'></i>&nbsp;&nbsp;<a href='". url('/cliente/agendarCita/'.$personService->id). "'>". trans("leng.Agendar cita")."</a></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <b><i class='fa fa-h-square'></i>&nbsp;&nbsp;<a href='". url('/cliente/citaDomicilio/'.$personService->id). "'>". trans("leng.Cita a domicilio")."</a></b>
                                    </td>
                            </tr>
                        <table>";
                
                
                $pserv=array('info'=>$datosPrestador,'pos'=>$personService->latitude.','.$personService->longitude,'icono'=>'/img/green-dot.png');
                $arregloPrestador[]=$pserv;
                $vCam=($personService->company=="")?"-----":ucwords($personService->company);
                $vPer=($personService->fullname=="")?"-----":ucwords($personService->fullname);
                $tabla.="<tr>
                            <td>$vCam</td>
                            <td>$vPer</td>
                            <td>$personService->nombreciudad, $personService->nombreestado, $personService->nombrepais</td>
                            <td>$personService->phone</td>
                            <td><a type='button' class='btn btn-primary btn-xs' title='". trans("leng.Agendar cita")."' href='". url('/cliente/agendarCita/'.$personService->id). "'><i class='fa fa-calendar'></i></a>
                                <a type='button' class='btn btn-primary btn-xs' title='". trans("leng.Cita a domicilio")."' href='". url('/cliente/citaDomicilio/'.$personService->id). "'><i class='fa fa-h-square'></i></a>
                                </td>
                         </tr>
                        ";
            }
        }
                
        $respuesta['estatus']       = 'OK';
        $respuesta['registros']     = $registros;
        $respuesta['html'] = $tabla;
        $respuesta['marcadores'] = $arregloPrestador;

        return response()->json($respuesta);
    }
    
    
    
    /**
     * Muestra la pantalla para agendar cita
     * @author OT
     * Fecha: 21-12-2016
     */
    public function agendarCita($idPrestador=0){
        if($idPrestador==0){
            if (Auth::check()) {
                return redirect('cliente');
            }else{
                return view('login.login');
            }
        }
        
        $dataPerson=  V_person::find($idPrestador);
        $tipoHorario=$dataPerson->diarytype;
        
        $datosPrestador=  V_person::select("users.email","person.company","person.fullname","state.name as nombreestado","city.name as nombreciudad",
                "country.name as nombrepais","person.phone")
                ->leftJoin("users","users.personid","=","person.id")
                ->leftJoin("address","address.personid","=","person.id")
                ->leftJoin("city","city.id","=","address.cityid")
                ->leftJoin("state","state.id","=","address.stateid")
                ->leftJoin("country","country.id","=","state.countryid")
                ->where("person.id",$idPrestador)
                ->get();
        
        $arregloDatos=array();
        
        foreach($datosPrestador as $datos){
            $arregloDatos["compania"]=  ucfirst($datos->company);
            $arregloDatos["nombre"]= ucwords($datos->fullname);
            $arregloDatos["correo"]= $datos->email;
            $arregloDatos["ubicacion"]= ucfirst($datos->nombreciudad). ", " . ucfirst($datos->nombreestado).", ".ucfirst($datos->nombrepais).".";
            $arregloDatos["telefono"]=$datos->phone;
            $arregloDatos["tipohorario"]=$tipoHorario;
            $arregloDatos["idPrestador"]=$idPrestador;
        }
        
        
        $cadenaServicios="";
        $servicios= V_personservice::select("person.cost","person.priceservice","person.generalprice","services.name",
                "services.description","personservice.id","personservice.cost as precioser","services.id as idservicio")
                ->leftJoin("person","person.id","=","personservice.personid")
                ->leftJoin("services","services.id","=","personservice.serviceid")
                ->where("personservice.personid",$idPrestador)
                ->orderBy("services.name")
                ->get();
        
        foreach($servicios as $servicio){
            $idSer=$servicio->idservicio;
            $nombreSer=  ucfirst($servicio->name);
            $descSer=ucfirst($servicio->description);

            if($servicio->precioser==""){
                    $precioServicio="-----";
                }else{
                    $precioServicio="$ ".Funciones::formato_numeros($servicio->precioser, ",", ".");
                }
            
         $cadenaServicios.="<div class='panel panel-default'>
                <div class='panel-heading'>
                  <h5 class='panel-title'>
                    <a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='.accordion' href='#$idSer'>
                    <input type='checkbox' name='chservicios' value='$idSer' />
                    $nombreSer
                    </a>
                  </h5>
                </div>

                <div id='$idSer' class='panel-collapse collapse' style='height: 0px;'>
                  <div class='panel-body'>
                  <div align='justify'>
                        $descSer
                   </div>
                   
                    <b>".trans("leng.Precio").":</b>&nbsp $precioServicio
                  </div>
                </div>
              </div>";
        }
       $arregloDatos["servicios"]=$cadenaServicios;
        
        return view('vvcliente.agendarCita')->with("arregloDatos",$arregloDatos);
    }
    
    
    
    /* Obtener la disponibilidad de la fecha enviada
    * Autor: OT
    * Fecha: 22-12-2016
    */
    public function obtenerDisponibleCita(Request $request){
        
        if (Auth::check()) {
            $idCliente = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $fechaCita  = trim($request->get('fechaCita'));
        $idPrestador  = trim($request->get('idPrestador'));
        $dataPerson=  V_person::find($idPrestador);
        $tipoHorario=$dataPerson->diarytype;
        
        
        $cadenaHorarios="<div class='form-group'>";
        
        $error=0;
        $horaCitaProgramada="";
        $confirmada="";
        $idCita=0;
        $direccion="";
        $latitud="";
        $longitud="";
        $info="";
        if($tipoHorario==1){ // fecha fija
            $citaExistente=V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))->where("cancel",FALSE)->where("rejected",FALSE)
                    ->where("client",$idCliente)->where("serviceprovider",$idPrestador)
                    ->get();
            if(count($citaExistente)==0){
                $datosHorario= V_personDiary::where("personid",$idPrestador)->whereNull("deleted")->orderBy("secondsstart")->get();
                if(count($datosHorario)>0){
                    foreach($datosHorario as $horario){
                        $citasHora=  V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))
                                ->where("timedate",$horario->start)
                                ->where("cancel",FALSE)
                                ->where("rejected",FALSE)
                                ->where("serviceprovider",$idPrestador)
                                ->count();
                        if($citasHora<$horario->vlimit){
                            $cadenaHorarios.="<div class='radio'>
                                            <label>
                                                <input type='radio' name='hora_rio'  class='' value='$horario->start'>
                                                    $horario->start
                                            </label>
                                        </div>";
                            $error=1;
                        }
                    }
                    $cadenaHorarios.="</div>";
                }else{
                    $error=2;
                }
            }else{
                $error=3;
                foreach($citaExistente as $citaEx){
                    $horaCitaProgramada=$citaEx->timedate;
                    $confirmada=$citaEx->accepted;
                    $idCita=$citaEx->id;
                    $direccion= ucfirst(mb_strtolower($citaEx->address));
                    $latitud=$citaEx->latitude;
                    $longitud=$citaEx->longitude;
                    $info=ucfirst(mb_strtolower($citaEx->addressdetails));
                }
            }
            
        }else if($tipoHorario==2){ // rango de fechas
            
            $tiempoServicio=$dataPerson->timeservice * 60; // pasamos minutos a segundos
            
            $citaExistente=V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))->where("cancel",FALSE)->where("rejected",FALSE)
                    ->where("client",$idCliente)->where("serviceprovider",$idPrestador)
                    ->get();
            if(count($citaExistente)==0){
                $datosHorario= V_personDiary::where("personid",$idPrestador)->whereNull("deleted")->orderBy("secondsstart")->get();
                if(count($datosHorario)>0){
                    foreach($datosHorario as $horario){
                        $hInicio=$horario->secondsstart;
                        $hFinal=0;
                        while($hInicio<=$horario->secondsend){
                            $hFinal=$hInicio+$tiempoServicio;
                            $horaConFormato=Funciones::horaHm($hInicio);
                            
                            $citasHora=  V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))
                                ->where("secundsdate",$hInicio)
                                ->where("cancel",FALSE)
                                ->where("rejected",FALSE)
                                ->where("serviceprovider",$idPrestador)
                                ->count();
                            if($citasHora==0){
                                $cadenaHorarios.="<div class='radio'>
                                            <label>
                                                <input type='radio' name='hora_rio'  class='' value='$horaConFormato'>".
                                                $horaConFormato ."
                                            </label>
                                        </div>";
                                $error=1;
                            }
                            $hInicio=$hFinal;
                        }
                    }
                    $cadenaHorarios.="</div>";
                }else{
                    $error=2;
                }
                
            }else{
                $error=3;
                foreach($citaExistente as $citaEx){
                    $horaCitaProgramada=$citaEx->timedate;
                    $confirmada=$citaEx->accepted;
                    $idCita=$citaEx->id;
                    $direccion= ucfirst(mb_strtolower($citaEx->address));
                    $latitud=$citaEx->latitude;
                    $longitud=$citaEx->longitude;
                    $info=ucfirst(mb_strtolower($citaEx->addressdetails));
                }
            }
        }else{
            $error=4;
        }
        
        $texto="";
        if($error==2 || $error==4){
            $botones="<br><br>
            <div class='col-sm-12'>
              <a type='button' href='".url('/cliente/servicios')."' style='width: 110px;' class='btn btn-danger btn-sm'><i class='fa fa-times-circle'></i>&nbsp;".trans('leng.Salir')."</a>
            </div>";
            $texto="<h5>".trans("leng.El prestador del servicio no ha configurado su horario").".</h5>" .$botones;
            
        }else if($error==3){
            
            $serviciosCita=  V_datesService::select("services.name")
                    ->leftJoin("services","services.id","=","datesservice.serviceid")
                    ->where("datesservice.dateid",$idCita)
                    ->get();
            $serviciosEnCita="Servicios solicitados : ";
            if(count($serviciosCita)>0){
                foreach($serviciosCita as $cSer){
                    $serviciosEnCita.=ucfirst($cSer->name).", ";
                }
                $serviciosEnCita=  substr($serviciosEnCita, 0,-2).".";
            }
            
            $botones="<br><br>
            <div class='col-sm-12'>
              <a type='button' href='".url('/cliente/servicios')."' style='width: 110px;' class='btn btn-danger btn-sm'><i class='fa fa-times-circle'></i>&nbsp;".trans('leng.Salir')."</a>
            </div>";
            $texto="<h4><p class='text-danger'>".trans("leng.Ya tiene una cita para este día a la(s)").": ".$horaCitaProgramada."</p></h4>"; 
            
            if(strlen($latitud)>3){
                $texto.="<p><b>Dirección:</b> ".$direccion."</p>";
                $texto.="<p>$info</p>";
                $texto.="<p><a href='javascript:;' onclick='mostrarUbicacionCliente($latitud,$longitud);'><i class='fa fa-map-marker'></i>&nbsp;&nbsp;Ver ubicación</a></p>";
            }
            
            $texto.="<b>".$serviciosEnCita."</b><br><br>";
            if($confirmada==FALSE){
                $texto.="<h5><p class='text-danger'>".trans("leng.Espere la confirmación del prestador del servicio").".</p></h5>"; 
            }else{
                $texto.="<h4><p class='text-success'>".trans("leng.Su cita ha sido confirmada").".</p></h4>"; 
            }
            $texto.=$botones;
            
            
        }else{
            $botones="<br><br>
            <div class='col-sm-12'>
              <button type='button'  onclick='agendarCita();' style='width: 110px;' class='btn btn-success btn-sm'><i class='fa fa-check-circle'></i>&nbsp;".trans('leng.Agendar cita')."</button>
              <a type='button' href='".url('/cliente/servicios')."' style='width: 110px;' class='btn btn-danger btn-sm'><i class='fa fa-times-circle'></i>&nbsp;".trans('leng.Salir')."</a>
            </div>";
            
            $texto="<br><h5>".trans("leng.Seleccione la hora para hacer su cita").".</h5>".$cadenaHorarios.$botones;
        }
        
        
        $respuesta['horas']     = $texto;
        $respuesta['error']     = $error;
        
        return response()->json($respuesta);
    }
    
    
    /**
     * Guarda a cita del cliente
     * Autor: OT
     * Fecha: 22-12-2016
     */
    public function guardarCita(Request $request){
        
        if (Auth::check()) {
            $idCliente = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $dataPerson=  V_person::find($idCliente);
        $horario   = trim($request->get('horario'));
        $tipohorario   = trim($request->get('tipohorario'));
        $idPrestador   = trim($request->get('idPrestador'));
        $fechaCita   = trim($request->get('fechaCita'));
        $serviciosSeleccionados   = trim($request->get('serviciosSeleccionados'));
        
        $idUbicacion   = trim($request->get('idUbicacion'));
        $masinfo   = trim($request->get('masinfo'));
        $latitudUbicacion  = trim($request->get('latitudUbicacion'));
        $longitudUbicacion   = trim($request->get('longitudUbicacion'));
        
        $fechaCita = strtotime($fechaCita);
        $fechaCita = date('Y-m-d',$fechaCita);
        
        $fechaActual=date("Y-m-d H:i:s");
        $segundosTotales=0;
        
        $h1=  substr($horario,0,2);
        $m1=  substr($horario,3,2);
        $Udoktor=  substr($horario,6,2);
        if($Udoktor=="PM"){
             if($h1<12){
                 $h1=$h1+12;
             }
        }else{
            if($h1==12){
                $h1=0;
            }
        }
            
        $segundosTotales=($h1*3600)+($m1*60);
        
        try{
            DB::beginTransaction();
            
                $nuevaCita= V_dates ::create([
                        'client'=>$idCliente,
                        'serviceprovider'=>$idPrestador,
                        'date'=>$fechaCita,
                        'created'=>$fechaActual,
                        'updated'=>$fechaActual,
                        'timedate'=>$horario,
                        'secundsdate'=>$segundosTotales,
                        'latitude'=>$latitudUbicacion,
                        'longitude'=>$longitudUbicacion,
                        'address'=>$idUbicacion,
                        'addressdetails'=>$masinfo,
                 ]);
                $idCita=$nuevaCita->id;
                
                $arrelgoServicios= explode(",", $serviciosSeleccionados);
                if(count($arrelgoServicios)>0){
                    for($k=0;$k<count($arrelgoServicios)-1;$k++){
                        $servicioCita= V_datesService::create([
                        'dateid'=>$idCita,
                        'serviceid'=>$arrelgoServicios[$k],
                        'updated'=>$fechaActual,
                        ]);
                    }
                }
                
                 Funciones::enviarAlerta($idPrestador, 1, $idCita);
                
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
        
    }
    
    
    /*
     * Muestra la pantalla mis citas  cliente
     * Autor: OT
     * Fecha:26-12-2016
     */
    public function misCitas(){
        if (Auth::check()) {
            $idCliente = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $servicios=  V_service::whereNull("deleted")->where("active",true)->orderBy("name")->get();
        $cadenaServicios="";
        foreach($servicios as $vser){
            $cadenaServicios.="<option value='$vser->id'>$vser->name</option>";
        }
        
        return view('vvcliente.misCitas')->with("cadenaServicios",$cadenaServicios);
    }
    
    
    /* Listado de citas
     * Autor: OT
     * Fecha: 26-12-2016
    */
    public function listaCitasCliente(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
       $buscatitulo=trim($request["buscaTitulo"]);
       $fecha1=$request["fecha1"];
       $fecha2=$request["fecha2"];
       $servicios=$request["servicios"];
       $cita=$request["cita"];
       
        $filtroFechas="";
        if($fecha1!=""){
            $filtroFechas.=" and date(dates.date)>= '$fecha1'";
        }
        
        if($fecha2!=""){
            $filtroFechas.=" and date(dates.date)<= '$fecha2'";
        }
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (person.fullname ilike'%$buscatitulo%' 
                or person.company ilike'%$buscatitulo%'
                or dates.timedate ilike'%$buscatitulo%'
                 )";
        }
        
        //checks
       $pendiente=$request["pendiente"];
       $confirmada=$request["confirmada"];
       $rechazada=$request["rechazada"];

        $filtrosStatus="";
        $primerFiltro=0;
        
        if($pendiente=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (dates.accepted=false and dates.rejected=false and dates.cancel=false ";
                $primerFiltro=1;
            }
        }
        
        if($confirmada=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (dates.accepted=true and dates.cancel=false and dates.rejected=false";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or dates.accepted=true and dates.cancel=false and dates.rejected=false";
            }
        }
        
        if($rechazada=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (dates.rejected=true";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or dates.rejected=true";
            }
        }

        if($primerFiltro==1)$filtrosStatus.=")";
        
        $filtroTipo="";
        if($cita==1){
            $filtroTipo.=" and coalesce(char_length(dates.latitude),0)<3";
        }else if($cita==2){
            $filtroTipo.=" and coalesce(char_length(dates.latitude),0)>3";
        }
        
        $sWhere="dates.client=$idPerson". $filtrosStatus. $filtroFechas. $filtroTitulo .$filtroTipo;
        $cadenaServicios="";
        if(count($servicios)>0 && $servicios!=""){
            $cadenaServicios=  implode(",", $servicios);
            $cadenaServicios=" and datesservice.serviceid in($cadenaServicios)";
        }
        $sWhere2="1=1 $cadenaServicios";
        
        
        $listaCitas = V_dates::select("dates.id","person.fullname","person.company","dates.date","dates.timedate",
                "dates.accepted","dates.rejected","dates.longitude","dates.latitude","dates.address","dates.addressdetails")
            ->leftJoin('person', 'person.id', '=', 'dates.serviceprovider')
            ->leftJoin("datesservice","datesservice.dateid","=","dates.id")
            ->where("dates.cancel",false)
            ->whereRaw(DB::raw($sWhere))
            ->whereRaw(DB::raw($sWhere2))
            ->distinct()
            ->orderBy("dates.date","desc")
            ->get();
        
        
        return Datatables::of($listaCitas)
        ->addColumn('compania', function ($listaCitas) {
            
            if($listaCitas->company==""){
              $compania=  "- - - - -";
           }else{
              $compania= strlen($listaCitas->company)>30?substr(ucwords($listaCitas->company),0,27)."...":$listaCitas->company;
           }
            
            $ca="<font class='flet-lab'><b>".ucwords($compania)."</b> / " . ucfirst($listaCitas->fullname)."</font>";
            return $ca;
         })
         
         ->addColumn('servicios', function ($listaCitas) {
             $listaSer="";
             $serviciosEncita=  V_datesService::select("services.name")
                     ->leftJoin("services","services.id","=","datesservice.serviceid")
                     ->where("datesservice.dateid",$listaCitas->id)
                     ->get();
             foreach($serviciosEncita as $vser){
                 $listaSer.=ucfirst($vser->name) . ", ";
             }
             
             $listaDireccion="";
             if(strlen($listaCitas->latitude)>3){
              $listaDireccion.="<br><b>Dirección: </b>".ucfirst(mb_strtolower($listaCitas->address));
              $listaDireccion.=" ".ucfirst(mb_strtolower($listaCitas->addressdetails));
              $listaDireccion.="<br><a href='javascript:;' onclick='mostrarUbicacionCliente($listaCitas->latitude,$listaCitas->longitude);'>Ver ubicación</a>";
             }
             
             
            $ca="<font class='flet-lab'>".  substr($listaSer, 0,-2).$listaDireccion.".</font>";
            return $ca;
         })
         
         ->addColumn('fecha', function ($listaCitas) {
            $ca="<font class='flet-lab'>". Funciones::fechaF1($listaCitas->date) ."</font>";
            return $ca;
         })
         
         ->addColumn('hora', function ($listaCitas) {
            $ca="<font class='flet-lab'>". $listaCitas->timedate ."</font>";
            return $ca;
         })
         
         ->addColumn('estado', function ($listaCitas) {
             if($listaCitas->accepted==true){
                 $ca="<font class='flet-lab'>Confirmada</font>";
             }else if($listaCitas->rejected==true){
                 $ca="<font class='flet-lab'>Rechazada</font>";
             }else{
                 $ca="<font class='flet-lab'>Pendiente</font>";
             }
            
            return $ca;
         })
         
         ->addColumn('ver', function ($listaCitas) {
             $fechaActual=date("Y-m-d H:i:s");
             $ca="";
             if($listaCitas->date>$fechaActual && $listaCitas->accepted==false && $listaCitas->rejected==false){
                 if(strlen($listaCitas->latitude)>3){
                     $ca.="<a type='button' title='".trans("leng.Cambiar cita")."' href='".url("/cliente/cambiarCitaDomicilio/$listaCitas->id")."' class='btn btn-secondary btn-xs'><i class='fa fa-pencil-square-o'></i></a>&nbsp;";
                 }else{
                     $ca.="<a type='button' title='".trans("leng.Cambiar cita")."' href='".url("/cliente/cambiarCita/$listaCitas->id")."' class='btn btn-secondary btn-xs'><i class='fa fa-pencil-square-o'></i></a>&nbsp;";
                 }
                 
             }
             if($listaCitas->rejected==false){
                $ca.="<button type='button' title='".trans("leng.Cancelar cita")."' onclick='cancelarCitaCliente(".$listaCitas->id.")' class='btn btn-danger btn-xs'><i class='fa fa-times'></i></button>&nbsp;";
             }
             
             if($listaCitas->rejected==true){
                $ca.="<button type='button' title='".trans("leng.Ver motivo de rechazo")."' onclick='mostrarMotivoRechazo(".$listaCitas->id.")' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></button>&nbsp;";
             }
            return $ca;
         })
         
        ->make(true);
    }
    

    /* Cancela la cita del cliente
    * Autor: OT
    * Fecha: 26-12-2016
    */
    public function cancelarCita(Request $request){
        
        if (Auth::check()) {
            $idCliente = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $idCita   = trim($request->get('idCita'));
        $datosProveedor=  V_dates::find($idCita);
        $idProveedor=$datosProveedor->serviceprovider;
        $fechaActual=date("Y-m-d H:i:s");
        
        try{
            DB::beginTransaction();
            
                DB::table('dates')->where('id',$idCita)->update([
                    'updated' => $fechaActual,
                    'cancel' => true,
                ]);
               
             Funciones::enviarAlerta($idProveedor, 4, $idCita);
             
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
        
    }
    
    
    /**
     * Muestra la pantalla para cambiar la cita seleccionada
     * @author OT
     * Fecha: 21-12-2016
     */
    public function cambiarCita($idCita=0){
        if($idCita==0){
            if (Auth::check()) {
                return redirect('cliente');
            }else{
                return view('login.login');
            }
        }
        
        $datosCita= V_dates::find($idCita);
        $idPrestador=$datosCita->serviceprovider;
        $dataPerson= V_person::find($idPrestador);
        $fechaCita=$datosCita->date;
        
        $fechaCita = date_create($fechaCita);
        $fechaCita= date_format($fechaCita,'d-m-Y');

        
        $tipoHorario=$dataPerson->diarytype;
        
        $datosPrestador=  V_person::select("users.email","person.company","person.fullname","state.name as nombreestado","city.name as nombreciudad",
                "country.name as nombrepais","person.phone")
                ->leftJoin("users","users.personid","=","person.id")
                ->leftJoin("address","address.personid","=","person.id")
                ->leftJoin("city","city.id","=","address.cityid")
                ->leftJoin("state","state.id","=","address.stateid")
                ->leftJoin("country","country.id","=","state.countryid")
                ->where("person.id",$idPrestador)
                ->get();
        
        $arregloDatos=array();
        
        foreach($datosPrestador as $datos){
            $arregloDatos["compania"]=  ucfirst($datos->company);
            $arregloDatos["nombre"]= ucwords($datos->fullname);
            $arregloDatos["correo"]= $datos->email;
            $arregloDatos["ubicacion"]= ucfirst($datos->nombreciudad). ", " . ucfirst($datos->nombreestado).", ".ucfirst($datos->nombrepais).".";
            $arregloDatos["telefono"]=$datos->phone;
            $arregloDatos["tipohorario"]=$tipoHorario;
            $arregloDatos["idPrestador"]=$idPrestador;
            $arregloDatos["idCita"]=$idCita;
        }
        
        
        $serviciosCita=  V_datesService::select("services.name","services.id")
             ->leftJoin("services","services.id","=","datesservice.serviceid")
             ->where("datesservice.dateid",$idCita)
             ->get();
            $serviciosEnCita="Servicios solicitados : ";
            $idServiciosCita=array();
            if(count($serviciosCita)>0){
                foreach($serviciosCita as $cSer){
                    $serviciosEnCita.=ucfirst($cSer->name).", ";
                    $idServiciosCita[]=$cSer->id;
                }
                $serviciosEnCita=  substr($serviciosEnCita, 0,-2).".";
            }
            
        $arregloDatos["serviciosCita"]=$serviciosEnCita;
               
        $cadenaServicios="";
        $servicios= V_personservice::select("person.cost","person.priceservice","person.generalprice","services.name",
                "services.description","personservice.id","personservice.cost as precioser","services.id as idservicio")
                ->leftJoin("person","person.id","=","personservice.personid")
                ->leftJoin("services","services.id","=","personservice.serviceid")
                ->where("personservice.personid",$idPrestador)
                ->orderBy("services.name")
                ->get();
        
        foreach($servicios as $servicio){
            $idSer=$servicio->idservicio;
            $nombreSer=  ucfirst($servicio->name);
            $descSer=ucfirst($servicio->description);

            
            if($servicio->precioser==""){
                $precioServicio="-----";
            }else{
                $precioServicio="$ ".Funciones::formato_numeros($servicio->precioser, ",", ".");
            }
                
            
            $servSeleccionado="";
            if(in_array($idSer, $idServiciosCita)){
                $servSeleccionado="checked";
            }
            
         $cadenaServicios.="<div class='panel panel-default'>
                <div class='panel-heading'>
                  <h5 class='panel-title'>
                    <a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='.accordion' href='#$idSer'>
                    <input type='checkbox' value='$idSer' $servSeleccionado  name='checkservicio'>&nbsp;
                    $nombreSer
                    </a>
                  </h5>
                </div>

                <div id='$idSer' class='panel-collapse collapse' style='height: 0px;'>
                  <div class='panel-body'>
                  <div align='justify'>
                        $descSer
                   </div>
                   
                    <b>".trans("leng.Precio").":</b>&nbsp $precioServicio
                  </div>
                </div>
              </div>";
        }
       $arregloDatos["servicios"]=$cadenaServicios;

        
        return view('vvcliente.cambiarCita')
                ->with("arregloDatos",$arregloDatos)
                ->with("fechaCita",$fechaCita)
                ->with("horaCita",$datosCita->timedate)
            ;
    }    
    
    /* Obtener la disponibilidad de la fecha enviada sin tomar en cuenta la fecha a cancelar
    * Autor: OT
    * Fecha: 26-12-2016
    */
    public function obtenerDisponibleCita2(Request $request){
        
        if (Auth::check()) {
            $idCliente = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $fechaCita  = trim($request->get('fechaCita'));
        $idPrestador  = trim($request->get('idPrestador'));
        $idCita  = trim($request->get('idCita'));
        $dataPerson=  V_person::find($idPrestador);
        $tipoHorario=$dataPerson->diarytype;
        
        
        $cadenaHorarios="<div class='form-group'>";
        
        $error=0;
        $horaCitaProgramada="";
        $confirmada="";
        if($tipoHorario==1){ // fecha fija
            $citaExistente=V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))->where("cancel",FALSE)->where("rejected",FALSE)
                    ->where("client",$idCliente)->where("id","<>",$idCita)->where("serviceprovider",$idPrestador)
                    ->get();
            if(count($citaExistente)==0){
                $datosHorario= V_personDiary::where("personid",$idPrestador)->whereNull("deleted")->orderBy("secondsstart")->get();
                if(count($datosHorario)>0){
                    foreach($datosHorario as $horario){
                        $citasHora=  V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))
                                ->where("timedate",$horario->start)
                                ->where("serviceprovider",$idPrestador)
                                ->where("cancel",FALSE)
                                ->where("rejected",FALSE)
                                ->count();
                        if($citasHora<$horario->vlimit){
                            $cadenaHorarios.="<div class='radio'>
                                            <label>
                                                <input type='radio' name='hora_rio'  class='' value='$horario->start'>
                                                    $horario->start
                                            </label>
                                        </div>";
                            $error=1;
                        }
                    }
                    $cadenaHorarios.="</div>";
                }else{
                    $error=2;
                }
            }else{
                $error=3;
                foreach($citaExistente as $citaEx){
                    $horaCitaProgramada=$citaEx->timedate;
                    $confirmada=$citaEx->accepted;
                }
            }
            
        }else if($tipoHorario==2){ // rango de fechas
            
            $tiempoServicio=$dataPerson->timeservice * 60; // pasamos minutos a segundos
            
            $citaExistente=V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))->where("cancel",FALSE)->where("rejected",FALSE)
                    ->where("client",$idCliente)->where("id","<>",$idCita)->where("serviceprovider",$idPrestador)
                    ->get();
            if(count($citaExistente)==0){
                $datosHorario= V_personDiary::where("personid",$idPrestador)->whereNull("deleted")->orderBy("secondsstart")->get();
                if(count($datosHorario)>0){
                    foreach($datosHorario as $horario){
                        $hInicio=$horario->secondsstart;
                        $hFinal=0;
                        while($hInicio<=$horario->secondsend){
                            $hFinal=$hInicio+$tiempoServicio;
                            $horaConFormato=Funciones::horaHm($hInicio);
                            
                            $citasHora=  V_dates::whereRaw(DB::raw("to_char(date,'DD-MM-YYYY')='$fechaCita'"))
                                ->where("secundsdate",$hInicio)
                                ->where("cancel",FALSE)
                                ->where("rejected",FALSE)
                                ->where("serviceprovider",$idPrestador)
                                ->count();
                            if($citasHora==0){
                                $cadenaHorarios.="<div class='radio'>
                                            <label>
                                                <input type='radio' name='hora_rio'  class='' value='$horaConFormato'>".
                                                $horaConFormato ."
                                            </label>
                                        </div>";
                                $error=1;
                            }
                            $hInicio=$hFinal;
                        }
                    }
                    $cadenaHorarios.="</div>";
                }else{
                    $error=2;
                }
            }else{
                $error=3;
                foreach($citaExistente as $citaEx){
                    $horaCitaProgramada=$citaEx->timedate;
                    $confirmada=$citaEx->accepted;
                }
            }
        }else{
            $error=4;
        }
        
        $texto="";
        if($error==2 || $error==4){
            $botones="<br><br>
            <div class='col-sm-12'>
              <a type='button' href='".url('/cliente/misCitas')."' style='width: 110px;' class='btn btn-danger btn-sm'><i class='fa fa-times-circle'></i>&nbsp;".trans('leng.Salir')."</a>
            </div>";
            $texto="<h5>".trans("leng.El prestador del servicio no ha configurado su horario.")."</h5>" .$botones;
        }else if($error==3){

            $botones="<br><br>
            <div class='col-sm-12'>
              <a type='button' href='".url('/cliente/misCitas')."' style='width: 110px;' class='btn btn-danger btn-sm'><i class='fa fa-times-circle'></i>&nbsp;".trans('leng.Salir')."</a>
            </div>";
            $texto="<br><h4><p class='text-danger'>".trans("leng.Ya tiene una cita para este día a la(s)").": ".$horaCitaProgramada."</p></h4>";
            if($confirmada==FALSE){
                $texto.="<h5><p class='text-danger'>".trans("leng.Espere la confirmación del prestador del servicio").".</p></h5>"; 
            }else{
                $texto.="<h4><p class='text-success'>".trans("leng.Su cita ha sido confirmada").".</p></h4>"; 
            }
            $texto.=$botones;
            
        }else{
            $botones="<br><br>
            <div class='col-sm-12'>
              <button type='button'  onclick='cambiarCita();' style='width: 110px;' class='btn btn-success btn-sm'><i class='fa fa-check-circle'></i>&nbsp;".trans('leng.Agendar cita')."</button>
              <a type='button' href='".url('/cliente/misCitas')."' style='width: 110px;' class='btn btn-danger btn-sm'><i class='fa fa-times-circle'></i>&nbsp;".trans('leng.Salir')."</a>
            </div>";
            
            $texto="<br>".$cadenaHorarios.$botones;
        }
        
        
        $respuesta['horas']     = $texto;
        
        return response()->json($respuesta);
    }
    
    
    
    /**
     * Guarda el cambio de cita del cliente
     * Autor: OT
     * Fecha: 26-12-2016
     */
    public function guardarCambioCita(Request $request){
        
        if (Auth::check()) {
            $idCliente = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $dataPerson=  V_person::find($idCliente);
        $horario   = trim($request->get('horario'));
        $tipohorario   = trim($request->get('tipohorario'));
        $idPrestador   = trim($request->get('idPrestador'));
        $idCita   = trim($request->get('idCita'));
        $fechaCita   = trim($request->get('fechaCita'));
        $serviciosSeleccionados   = trim($request->get('serviciosSeleccionados'));
        
        $idUbicacion   = trim($request->get('idUbicacion'));
        $masinfo   = trim($request->get('masinfo'));
        $latitudUbicacion  = trim($request->get('latitudUbicacion'));
        $longitudUbicacion   = trim($request->get('longitudUbicacion'));

        
        $fechaCita = strtotime($fechaCita);
        $fechaCita = date('Y-m-d',$fechaCita);
        
        $fechaActual=date("Y-m-d H:i:s");
        $segundosTotales=0;
        
        $h1=  substr($horario,0,2);
        $m1=  substr($horario,3,2);
        $Udoktor=  substr($horario,6,2);
        if($Udoktor=="PM"){
             if($h1<12){
                 $h1=$h1+12;
             }
        }else{
            if($h1==12){
                $h1=0;
            }
        }
            
        $segundosTotales=($h1*3600)+($m1*60);
        
        try{
            DB::beginTransaction();
            
                $nuevaCita= V_dates ::create([
                        'client'=>$idCliente,
                        'serviceprovider'=>$idPrestador,
                        'date'=>$fechaCita,
                        'created'=>$fechaActual,
                        'updated'=>$fechaActual,
                        'timedate'=>$horario,
                        'secundsdate'=>$segundosTotales,
                        'latitude'=>$latitudUbicacion,
                        'longitude'=>$longitudUbicacion,
                        'address'=>$idUbicacion,
                        'addressdetails'=>$masinfo,
                 ]);
                $idCitaNueva=$nuevaCita->id;
                DB::table('dates')->where('id',$idCita)->update([
                    'updated' => $fechaActual,
                    'cancel' => true,
                ]);
                
                $arrelgoServicios= explode(",", $serviciosSeleccionados);
                if(count($arrelgoServicios)>0){
                    for($k=0;$k<count($arrelgoServicios)-1;$k++){
                        $servicioCita= V_datesService::create([
                        'dateid'=>$idCitaNueva,
                        'serviceid'=>$arrelgoServicios[$k],
                        'updated'=>$fechaActual,
                        ]);
                    }
                }
                
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
        
    }
    
    
    /* Genera la lista de notificaciones nuevas 
     * Autor: OT
     * Fecha: 27-12-2016
    */
    public function buscarNotificacionesNuevas(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            try{
                   DB::beginTransaction();
                   
                   $datosNotificaciones=DB::table('alert')->select("alert.id","alert.type","alert.relationid","alert.createdat")
                        ->where('alert.recipientid','=',$idPerson)
                        ->where('alert.read','=',null)
                        ->orderBy("alert.createdat", "desc")
                        ->get();
                    
                    $notificacionesNuevas=count($datosNotificaciones);
                    $cadenaNotificaciones="";
                    $iconoAlerta="";
                    $tituloAlerta="";
                    $subtituloAlerta="";
                    $tipoTexto="";
                    if($notificacionesNuevas>0){
                        foreach($datosNotificaciones as $rowNotificacion){
                           $fecha=Funciones::fechaF1Hora($rowNotificacion->createdat);
                           $idNotificacion=$rowNotificacion->id;
                           $idRelacion=($rowNotificacion->relationid=="")?0:$rowNotificacion->relationid;
                           $nombrePrestador="";
                           $citaInfo=DB::table('dates')->select("person.fullname","person.company","dates.date","dates.timedate")
                                   ->leftJoin("person","person.id","=","dates.serviceprovider")
                                   ->where('dates.id','=',$idRelacion)->get();
                           foreach ($citaInfo as $rowInfo){
                               $nombrePrestador=($rowInfo->company=="")?$rowInfo->fullname:$rowInfo->company;
                               $nombrePrestador= strlen($nombrePrestador)>30?substr(ucwords($nombrePrestador),0,27)."...":$nombrePrestador;
                           }
                           
                           switch($rowNotificacion->type){
                               case 2:
                                   $iconoAlerta="fa fa-check";
                                   $tituloAlerta=trans("leng.Cita confirmada");
                                   $subtituloAlerta=$nombrePrestador;
                                   $tipoTexto="success";
                                   break;
                              case 3:
                                   $iconoAlerta="fa fa-times";
                                   $tituloAlerta=trans("leng.Cita rechazada");
                                   $subtituloAlerta=$nombrePrestador;
                                   $tipoTexto="danger";
                                   break;
                           }
                           
                           
                          $cadenaNotificaciones.="<li>
                                                    <a href='javascript:;' onclick='leerNotificacionCliente($idNotificacion);' class='noticebar-item'>
                                                        <span class='noticebar-item-image'>
                                                          <i class='$iconoAlerta text-$tipoTexto'></i>
                                                        </span>
                                                        <span class='noticebar-item-body'>
                                                          <strong class='noticebar-item-title'>$tituloAlerta</strong>
                                                          <span class='noticebar-item-text'>$subtituloAlerta</span>
                                                          <span class='noticebar-item-time'><i class='fa fa-calendar'></i> $fecha</span>
                                                        </span>
                                                    </a>
                                                </li>";
                        }
                        
                    }else{
                       $cadenaNotificaciones.= "<li class='noticebar-empty'>
                            <h4 class='noticebar-empty-title'>".trans("leng.No hay notificaciones nuevas") .".</h4>
                      </li>";
                    }
                   
                   
                    $respuesta="<li class='dropdown'>
                    <a href='./page-notifications.html' class='dropdown-toggle' data-toggle='dropdown'>
                      <i class='fa fa-bell'></i>
                      <span class='navbar-visible-collapsed'>&nbsp;" . trans('leng.Notificaciones')."&nbsp;</span>
                      <span class='badge'>$notificacionesNuevas</span>
                    </a>
                    <ul class='dropdown-menu noticebar-menu' role='menu'>
                      <li class='nav-header'>
                        <div class='pull-left'>".trans('leng.Notificaciones')."</div>
                      </li>
                      
                      $cadenaNotificaciones
                    </ul>
                  </li>";
                   
                   DB::commit();

                   return $respuesta;
                   
               } catch (Exception $ex) {
                   DB::rollback();
                   return $ex;
               }
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
    }
    
    
   /**
     * Muestra la notificacion del cliente
     * @author OT
     * Fecha: 27-12-2016
     */
    public function mostrarNotificacionCliente($idNotificacion){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
        if($idNotificacion==0){
            return redirect('login/inicio');
        }
        
        
        $fechaNotificacion="";
        $tipo="";
        $idRelacion="";
        $idRelacion2="";
        $fechaActual=date("Y-m-d H:i:s");
        
        $datosNotificaciones=DB::table('alert')->select("alert.id","alert.type","alert.relationid","alert.createdat","alert.relationid2")
                        ->where('alert.recipientid','=',$idPerson)
                        ->where('alert.id','=',$idNotificacion)
                        ->get();
        
        
        if(count($datosNotificaciones)==0){
            return redirect('login/inicio');
        }
        
        
         DB::table('alert')->where('id', $idNotificacion)->update(['read' =>$fechaActual,'updated' =>$fechaActual]);

        
        $fechaAccion="";
        $cliente="";
        $tipo="";
        $idCita="";
        $fechaCita="";
        $horaCita="";
        $nombrePrestador="";
        $texto="";
        foreach($datosNotificaciones as $rowNotificacion){
            
            if($rowNotificacion->type==2){ // cita confirmada
                
                $tipo=$rowNotificacion->type;
                $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate")
                              ->leftJoin("person","person.id","=","dates.serviceprovider")
                              ->where("dates.id","=",$rowNotificacion->relationid)
                              ->get();
                foreach($datosCita as $rowCita){
                    $fechaCita=Funciones::fechaF2($rowCita->date);
                    $horaCita=$rowCita->timedate;
                    $nombrePrestador=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                    $idCita=$rowCita->id;
                }            
               
               $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
            }else if($rowNotificacion->type==3){ // cita cancelada
                
                $tipo=$rowNotificacion->type;
                $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate","dates.reasonrejection")
                              ->leftJoin("person","person.id","=","dates.serviceprovider")
                              ->where("dates.id","=",$rowNotificacion->relationid)
                              ->get();
                foreach($datosCita as $rowCita){
                    $fechaCita=Funciones::fechaF2($rowCita->date);
                    $horaCita=$rowCita->timedate;
                    $nombrePrestador=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                    $idCita=$rowCita->id;
                    $texto=  ucfirst($rowCita->reasonrejection);
                }            
               
               $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
            }
        
            $datosAlerta=array();
            $datosAlerta["fechaCita"]=$fechaCita;
            $datosAlerta["fechaAccion"]=$fechaAccion;
            $datosAlerta["horaCita"]=$horaCita;
            $datosAlerta["tipo"]=$tipo;
            $datosAlerta["idCita"]=$idCita;
            $datosAlerta["prestador"]=$nombrePrestador;
            $datosAlerta["texto"]=$texto;
        
        
        
            return view('vvnotificaciones.mostrarNotificacion')->with("datosAlerta",$datosAlerta);
        }
  }
    
    
    /*
    * Muestra el motivo de rechazo
    * Autor: OT
    * Fecha: 28-12-2016
    * 
    */

    public function verMotivoRechazoCita(Request $request){
        if (Auth::check()) {
            $idCliente = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $idCita=trim($request["idCita"]);
        $datos=  V_dates::find($idCita);
        $motivo=$datos->reasonrejection;
        
        return view('vvcliente.verRechazoCita')->with("motivo",$motivo);
    }
    
    
    /**
     * Muestra la pantalla para agendar cita a domicilio
     * @author OT
     * Fecha: 05-01-2017
     */
    public function citaDomicilio($idPrestador=0){
        if($idPrestador==0){
            if (Auth::check()) {
                return redirect('cliente');
            }else{
                return view('login.login');
            }
        }
        
        $dataPerson=  V_person::find($idPrestador);
        $tipoHorario=$dataPerson->diarytype;
        
        $datosPrestador=  V_person::select("users.email","person.company","person.fullname","state.name as nombreestado","city.name as nombreciudad",
                "country.name as nombrepais","person.phone")
                ->leftJoin("users","users.personid","=","person.id")
                ->leftJoin("address","address.personid","=","person.id")
                ->leftJoin("city","city.id","=","address.cityid")
                ->leftJoin("state","state.id","=","address.stateid")
                ->leftJoin("country","country.id","=","state.countryid")
                ->where("person.id",$idPrestador)
                ->get();
        
        $arregloDatos=array();
        
        foreach($datosPrestador as $datos){
            $arregloDatos["compania"]=  ucfirst($datos->company);
            $arregloDatos["nombre"]= ucwords($datos->fullname);
            $arregloDatos["correo"]= $datos->email;
            $arregloDatos["ubicacion"]= ucfirst($datos->nombreciudad). ", " . ucfirst($datos->nombreestado).", ".ucfirst($datos->nombrepais).".";
            $arregloDatos["telefono"]=$datos->phone;
            $arregloDatos["tipohorario"]=$tipoHorario;
            $arregloDatos["idPrestador"]=$idPrestador;
        }
        
        
        $cadenaServicios="";
        $servicios= V_personservice::select("person.cost","person.priceservice","person.generalprice","services.name",
                "services.description","personservice.id","personservice.cost as precioser","services.id as idservicio")
                ->leftJoin("person","person.id","=","personservice.personid")
                ->leftJoin("services","services.id","=","personservice.serviceid")
                ->where("personservice.personid",$idPrestador)
                ->orderBy("services.name")
                ->get();
        
        foreach($servicios as $servicio){
            $idSer=$servicio->idservicio;
            $nombreSer=  ucfirst($servicio->name);
            $descSer=ucfirst($servicio->description);

            if($servicio->precioser==""){
                    $precioServicio="-----";
                }else{
                    $precioServicio="$ ".Funciones::formato_numeros($servicio->precioser, ",", ".");
                }
            
         $cadenaServicios.="<div class='panel panel-default'>
                <div class='panel-heading'>
                  <h5 class='panel-title'>
                    <a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='.accordion' href='#$idSer'>
                    <input type='checkbox' name='chservicios' value='$idSer' />
                    $nombreSer
                    </a>
                  </h5>
                </div>

                <div id='$idSer' class='panel-collapse collapse' style='height: 0px;'>
                  <div class='panel-body'>
                  <div align='justify'>
                        $descSer
                   </div>
                   
                    <b>".trans("leng.Precio").":</b>&nbsp $precioServicio
                  </div>
                </div>
              </div>";
        }
       $arregloDatos["servicios"]=$cadenaServicios;
        
        return view('vvcliente.agendarCitaDomicilio')->with("arregloDatos",$arregloDatos);
    }
    
    
    
    /**
     * Muestra la pantalla para cambiar la cita a domicilio seleccionada
     * @author OT
     * Fecha: 06-01-2017
     */
    public function cambiarCitaDomicilio($idCita=0){
        if($idCita==0){
            if (Auth::check()) {
                return redirect('cliente');
            }else{
                return view('login.login');
            }
        }
        
        $datosCita= V_dates::find($idCita);
        $idPrestador=$datosCita->serviceprovider;
        $dataPerson= V_person::find($idPrestador);
        $fechaCita=$datosCita->date;
        
        $fechaCita = date_create($fechaCita);
        $fechaCita= date_format($fechaCita,'d-m-Y');

        
        $tipoHorario=$dataPerson->diarytype;
        
        $datosPrestador=  V_person::select("users.email","person.company","person.fullname","state.name as nombreestado","city.name as nombreciudad",
                "country.name as nombrepais","person.phone")
                ->leftJoin("users","users.personid","=","person.id")
                ->leftJoin("address","address.personid","=","person.id")
                ->leftJoin("city","city.id","=","address.cityid")
                ->leftJoin("state","state.id","=","address.stateid")
                ->leftJoin("country","country.id","=","state.countryid")
                ->where("person.id",$idPrestador)
                ->get();
        
        $arregloDatos=array();
        
        foreach($datosPrestador as $datos){
            $arregloDatos["compania"]=  ucfirst($datos->company);
            $arregloDatos["nombre"]= ucwords($datos->fullname);
            $arregloDatos["correo"]= $datos->email;
            $arregloDatos["ubicacion"]= ucfirst($datos->nombreciudad). ", " . ucfirst($datos->nombreestado).", ".ucfirst($datos->nombrepais).".";
            $arregloDatos["telefono"]=$datos->phone;
            $arregloDatos["tipohorario"]=$tipoHorario;
            $arregloDatos["idPrestador"]=$idPrestador;
            $arregloDatos["idCita"]=$idCita;
        }
        
        
        $serviciosCita=  V_datesService::select("services.name","services.id")
             ->leftJoin("services","services.id","=","datesservice.serviceid")
             ->where("datesservice.dateid",$idCita)
             ->get();
            $serviciosEnCita="Servicios solicitados : ";
            $idServiciosCita=array();
            if(count($serviciosCita)>0){
                foreach($serviciosCita as $cSer){
                    $serviciosEnCita.=ucfirst($cSer->name).", ";
                    $idServiciosCita[]=$cSer->id;
                }
                $serviciosEnCita=  substr($serviciosEnCita, 0,-2).".";
            }
            
        $arregloDatos["serviciosCita"]=$serviciosEnCita;
               
        $cadenaServicios="";
        $servicios= V_personservice::select("person.cost","person.priceservice","person.generalprice","services.name",
                "services.description","personservice.id","personservice.cost as precioser","services.id as idservicio")
                ->leftJoin("person","person.id","=","personservice.personid")
                ->leftJoin("services","services.id","=","personservice.serviceid")
                ->where("personservice.personid",$idPrestador)
                ->orderBy("services.name")
                ->get();
        
        foreach($servicios as $servicio){
            $idSer=$servicio->idservicio;
            $nombreSer=  ucfirst($servicio->name);
            $descSer=ucfirst($servicio->description);

            
            if($servicio->precioser==""){
                $precioServicio="-----";
            }else{
                $precioServicio="$ ".Funciones::formato_numeros($servicio->precioser, ",", ".");
            }
                
            
            $servSeleccionado="";
            if(in_array($idSer, $idServiciosCita)){
                $servSeleccionado="checked";
            }
            
         $cadenaServicios.="<div class='panel panel-default'>
                <div class='panel-heading'>
                  <h5 class='panel-title'>
                    <a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='.accordion' href='#$idSer'>
                    <input type='checkbox' value='$idSer' $servSeleccionado  name='checkservicio'>&nbsp;
                    $nombreSer
                    </a>
                  </h5>
                </div>

                <div id='$idSer' class='panel-collapse collapse' style='height: 0px;'>
                  <div class='panel-body'>
                  <div align='justify'>
                        $descSer
                   </div>
                   
                    <b>".trans("leng.Precio").":</b>&nbsp $precioServicio
                  </div>
                </div>
              </div>";
        }
       $arregloDatos["servicios"]=$cadenaServicios;

        
        return view('vvcliente.cambiarCitaDomicilio')
                ->with("arregloDatos",$arregloDatos)
                ->with("fechaCita",$fechaCita)
                ->with("datosCita",$datosCita)
            ;
    }    
    
    
    
}