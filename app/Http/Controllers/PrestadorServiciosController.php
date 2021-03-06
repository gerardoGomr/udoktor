<?php
/* Controlador para prestadores de servicio
  Autor: OT
  Fecha: 04-01-2017
*/
namespace Udoktor\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Udoktor\Http\Requests;
use Auth;
use Udoktor\Http\Controllers\Controller;
use DB;
use Udoktor\Funciones;
use Udoktor\V_person;
use Udoktor\V_personservice;
use Udoktor\V_service;
use Udoktor\V_personDiary;
use Mail;
use Udoktor\User;
use Udoktor\RolUsuario;
use Udoktor\V_dates;
use Udoktor\V_datesService;

class PrestadorServiciosController extends Controller
{
    /*
     * Muestra pantalla princpial del prestador de servicios
     * Fecha: 08-12-2016
     * Autor: OT
     */
    
    public function Dashboard(){
         $fecha = date('D M j Y G:i:s',  strtotime("2017-01-09 13:20"));
         
        return view('vPrestadorServicios.index');
    }
    
    /*
     * Muestra pantalla princpial del prestador de servicios
     * Fecha: 08-12-2016
     * Autor: OT
     */
    
    public function misServicios(){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        
        $dataPerson=  V_person::find($idV_person);
        
        
        return view('vPrestadorServicios.misServicios')->with("dataPerson",$dataPerson);
    }
    
    
    /* Guarda los servicio del usuario prestador de servicios
     * Autor: OT
     * Fecha: 10-12-2016
    */
    public function guardarPrecioServicios(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $precioGeneral=trim($request["precioGeneral"]);
        $servicios=trim($request["servicios"]);
        $tipoCosto=$request["tipoCosto"];
       
        try{
            DB::beginTransaction();
            
                if($servicios!="") $servicios=  substr($servicios,0,-1);
                else return "sinServicios";
                
                $serviciosArray=  explode(",", $servicios);
                if(count($serviciosArray)>0){
                    
                    if($tipoCosto==0){ // precios definidos por el prestador del servicio
                        $error=0;
                        $nServicio="";
                        //Verificar que los precios esten dentro del rango
                        for($k=0;$k<count($serviciosArray);$k++){
                            list($vservicio, $vcosto) = explode('=', $serviciosArray[$k]);
                            $precioError=  V_service::where("id",$vservicio)->where("active",true)->where("minprice","<=",$vcosto)->where("maxprice",">=",$vcosto)->get();
                            if(count($precioError)==0){
                                $error=1;
                                $vServicio=$k+1;
                                break;
                            }
                        }

                        if($error==1){
                            return "errorServicio=".$vServicio;
                        }

                        $eliminados = V_personservice::where('personid', '=', $idV_person)->delete();
                        for($k=0;$k<count($serviciosArray);$k++){
                            list($vservicio, $vcosto) = explode('=', $serviciosArray[$k]);
                            $servicioPersona=  V_personservice::create([
                                   'personid'=>$idV_person,
                                   'serviceid'=>$vservicio,
                                   'updated'=>$fechaActual,
                                   'cost'=>$vcosto,
                              ]);
                        }
                        
                        DB::table('person')->where('id',$idV_person)->update([
                            'updated' => $fechaActual,
                            'priceservice' =>true,
                        ]);

                        
                    }else{
                        $eliminados = V_personservice::where('personid', '=', $idV_person)->delete();
                        for($k=0;$k<count($serviciosArray);$k++){
                            list($vservicio, $vcosto) = explode('=', $serviciosArray[$k]);
                            $dataService=  V_service::find($vservicio);
                            $servicioPersona=  V_personservice::create([
                                   'personid'=>$idV_person,
                                   'serviceid'=>$vservicio,
                                   'updated'=>$fechaActual,
                                   'cost'=>$dataService->price,
                              ]);
                        }
                        
                        DB::table('person')->where('id',$idV_person)->update([
                            'updated' => $fechaActual,
                            'priceservice' =>false,
                        ]);
                    }
                    
                    
                }else{
                    return "sinServicios";
                }
                
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Mustra el formulario para agregar un servicio al prestador
     * Autor: OT
     * Fecha: 10-12-2016
    */
    public function agregarServicioPrestador(){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $serviciosTodos=DB::table('services')->select("id","name")
                       ->where("deleted",null)
                       ->where("active",true)
                       ->OrderBy("services.name")
                       ->get();
        
        $dataPerson=  V_person::find($idV_person);
        
        return view('vPrestadorServicios.agregarServicio')->with("serviciosTodos",$serviciosTodos);
    }
    
    
    /* Asigna los servicios al usuario
     * Autor: OT
     * Fecha: 12-12-2016
    */
    public function guardarServicioUsuario(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $serviciosid=$request["serviciosid"];
        $tipoCosto=$request["tipoCosto"];
       
        try{
            DB::beginTransaction();
            
                if(count($serviciosid)==0)return "sinServicios";
                
                if(count($serviciosid)>0){
                    for($k=0;$k<count($serviciosid);$k++){
                        $cuentaServicio=V_personservice::where('personid', '=', $idV_person)
                                ->where("serviceid",$serviciosid[$k])
                                ->count();
                        if($cuentaServicio==0){
                            if($tipoCosto==1){
                                $dataSer=  V_service::find($serviciosid[$k]);
                                $vprecio=$dataSer->price;
                            }else{
                                $vprecio=null;
                            }
                            $servicioPersona=  V_personservice::create([
                                   'personid'=>$idV_person,
                                   'serviceid'=>$serviciosid[$k],
                                   'updated'=>$fechaActual,
                                   'cost'=>$vprecio,
                              ]);
                        }
                    }
                }
                    
                DB::table('person')->where('id',$idV_person)->update(['updated' => $fechaActual]);
                    
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Mustra el formulario para agregar un servicio nuevo al sistema y al usuario
     * Autor: OT
     * Fecha: 12-12-2016
    */
    public function agregarNuevoServicioPrestador(){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        return view('vPrestadorServicios.nuevoServicio');
    }
    
    /* Agregar el servicio y lo asigna al usuario
     * Autor: OT
     * Fecha: 12-12-2016
    */
    public function guardarNuevoServicioUsuario(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $nombreServicio=trim($request["nombreServicio"]);
        $descripcionservicio=trim($request["descripcionservicio"]);
       
        try{
            DB::beginTransaction();
            
            $nombreRepetido=DB::table('services')
                    ->whereRaw(DB::raw("upper(name)='".strtoupper($nombreServicio)."'"))
                    ->count();
            
            
            if(count($nombreRepetido)>0)return "nombreRepetido";
            
            $servicioD= V_service::create([
                        'name'=>$nombreServicio,
                        'description'=>$descripcionservicio,
                        'created'=>$fechaActual,
                        'updated'=>$fechaActual,
                        'deleted'=>null,
                        'active'=>true,
                    ]);
            
            $idInsertado=$servicioD->id;
            
            $servicioPersona=  V_personservice::create([
                                   'personid'=>$idV_person,
                                   'serviceid'=>$idInsertado,
                                   'updated'=>$fechaActual,
                                   'cost'=>null,
                              ]);
                
                    
            DB::table('person')->where('id',$idV_person)->update(['updated' => $fechaActual]);
                    
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Muestra la configuracion de la agenda 
     * Autor: OT
     * Fecha: 15-12-2016
    */
    
    public function agendaConfiguracion(){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $agendaConf=DB::table('persondiary')->select("persondiary.id","persondiary.start","persondiary.vend","persondiary.vlimit")
                      ->where('persondiary.personid','=',$idV_person)
                      ->whereNull('persondiary.deleted')
                      ->OrderBy("persondiary.secondsstart")
                      ->get();
        
        $dataPerson=  V_person::find($idV_person);
        
        return view('vPrestadorServicios.agendaConfiguracion')
                ->with("agendaConf",$agendaConf)
                ->with("dataPerson",$dataPerson);
    }
    
    
    /* Muestra el formulario para agregar horario
     * Autor: OT
     * Fecha: 16-12-2016
    */
    public function agregarHorarioPrestador(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $tipo=trim($request["tipo"]);
        
        
        return view('vPrestadorServicios.agregarHorario')->with("tipo",$tipo);
    }
    
    
/*
 * Guarda el horario
 * Fecha: 16-12-2016
 * Autor: OT
 */
    public function guardarHorario(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $tipo=trim($request["tipoOculto"]);
        $hora1=trim($request["hora1"]);
        $hora2=trim($request["hora2"]);
        $limite=trim($request["limite"]);
        
        $segundosTotalesInicio=0;
        $segundosTotalesFinal=0;
        
        if($tipo=="1"){
            $h1=  substr($hora1,0,2);
            $m1=  substr($hora1,3,2);
            $Udoktor=  substr($hora1,6,2);
            if($Udoktor=="PM"){
                  if($h1<12){
                      $h1=$h1+12;
                  }
            }else{
                if($h1==12){
                    $h1=0;
                }
            }
            
            $segundosTotalesInicio=($h1*3600)+($m1*60);
            
            $existeRango=  V_personDiary::where("secondsstart",$segundosTotalesInicio)
                    ->whereNull("deleted")
                    ->where("personid",$idV_person)
                    ->count();
            
            if($existeRango>0){
                return "rangoigual";
            }
            
            //1740 = 29 min
            $existeRango=  V_personDiary::whereRaw("secondsstart - $segundosTotalesInicio <= 1740 and secondsstart - $segundosTotalesInicio>=0 ")
                    ->whereNull("deleted")
                    ->where("personid",$idV_person)
                    ->count();
            if($existeRango>0){
                return "diferecia30min1";
            }
            
            $existeRango=  V_personDiary::whereRaw("secondsstart - $segundosTotalesInicio >= -1740 and secondsstart - $segundosTotalesInicio<=0")
                    ->whereNull("deleted")
                    ->where("personid",$idV_person)
                    ->count();
            if($existeRango>0){
                return "diferecia30min2";
            }

            
        }else{
            $limite=0;
            $h1=  substr($hora1,0,2);
            $m1=  substr($hora1,3,2);
            $Udoktor=  substr($hora1,6,2);
            if($Udoktor=="PM"){
                  if($h1<12){
                      $h1=$h1+12;
                  }
            }else{
                if($h1==12){
                    $h1=0;
                }
            }
            $segundosTotalesInicio=($h1*3600)+($m1*60);
            
            $h2=  substr($hora2,0,2);
            $m2=  substr($hora2,3,2);
            $t2=  substr($hora2,6,2);
            if($t2=="PM"){
                  if($h2<12){
                      $h2=$h2+12;
                  }
            }else{
                if($h2==12){
                    $h2=0;
                }
            }
            $segundosTotalesFinal=($h2*3600)+($m2*60);
            
            if($segundosTotalesInicio>=$segundosTotalesFinal){
                return "iniciomayorafin";
            }
            
            if(($segundosTotalesFinal-$segundosTotalesInicio) <= 1740){
                return "diferencia30min3";
            }
            
            $existeRango=  V_personDiary::whereRaw("$segundosTotalesInicio>=secondsstart and $segundosTotalesInicio<=secondsend")
                    ->whereNull("deleted")
                    ->where("personid",$idV_person)
                    ->count();
            if($existeRango>0){
                return "iniciodentroderango";
            }
            
            
            $existeRango=  V_personDiary::whereRaw("$segundosTotalesFinal>=secondsstart and $segundosTotalesFinal<=secondsend")
                    ->whereNull("deleted")
                    ->where("personid",$idV_person)
                    ->count();
            if($existeRango>0){
                return "findentroderango";
            }
            
            
            $existeRango=  V_personDiary::whereRaw("secondsstart>=$segundosTotalesInicio and secondsstart<=$segundosTotalesFinal")
                    ->whereNull("deleted")
                    ->where("personid",$idV_person)
                    ->count();
            if($existeRango>0){
                return "rangodentrodeotrorango";
            }
            
            $existeRango=  V_personDiary::whereRaw("secondsend>=$segundosTotalesInicio and secondsstart<=$segundosTotalesFinal")
                    ->whereNull("deleted")
                    ->where("personid",$idV_person)
                    ->count();
            if($existeRango>0){
                return "rangodentrodeotrorango";
            }
        }
        
        
        try{
            DB::beginTransaction();
            
            
            $horario= V_personDiary::create([
               'personid'=>$idV_person,
               'start'=>$hora1,
               'vend'=>$hora2,
               'vlimit'=>$limite,
               'updated'=>$fechaActual,
               'secondsstart'=>$segundosTotalesInicio,
               'secondsend'=>$segundosTotalesFinal,
            ]);
                
                    
            DB::table('person')->where('id',$idV_person)->update(['updated' => $fechaActual,'diarytype'=>$tipo]);
                    
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
        
    }
    
    /*
    * Guarda el horario
    * Fecha: 16-12-2016
    * Autor: OT
    */
    public function cargarHorario(){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $dataPerson=  V_person::find($idV_person);
        
        
        $agendaConf=DB::table('persondiary')->select("persondiary.id","persondiary.start","persondiary.vend",
               "persondiary.vlimit")
               ->where('persondiary.personid','=',$idV_person)
               ->whereNull('persondiary.deleted')
               ->OrderBy("persondiary.secondsstart")
               ->get();
        
        $cadenaHorario="";
        if(count($agendaConf)==0){
            $cadenaHorario.="<tr>
                <td colspan='4' style='text-align:center'>".trans("leng.No hay horarios establecidos")."</td>
                </tr>";
        }else{
            foreach($agendaConf as $dta){
                $vfinal=($dataPerson->diarytype=="1")?"-----":$dta->vend;
                $vlimite=($dataPerson->diarytype=="2")?"-----":$dta->vlimit;
                $vid=$dta->id;
                $cadenaHorario.="<tr>
                            <td>$dta->start</td>
                            <td>$vfinal</td>
                            <td>$vlimite</td>
                            <td>
                                <a href='javascript:;' class='elimina' title='Eliminar horario' onclick='eliminarHorario($vid)'><img src='/img/cancelado.png' width='22px;'></i></a>
                            </td>
                            </tr>
                            ";
            }
        }
        
        
        $respuesta="<table class='table table-bordered table-highlight' id='listaHorario'>
                            <thead>
                              <tr>
                                <th style='width: 150px;'>".trans('leng.Hora inicio')."</th>
                                <th style='width: 150px;'>".trans('leng.Hora fin')."</th>
                                <th style='width: 150px;'>".trans('leng.Número de clientes')."</th>
                                <th style='width: 20px;'></th>
                              </tr>
                            </thead>
                            <tbody>
                                 $cadenaHorario
                            </tbody>
                          </table>";
        
        return $respuesta;
    }
    
    
    /*
    * Eliminar horario
    * Fecha: 16-12-2016
    * Autor: OT
    */
    public function eliminarHorario(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $fechaActualSinhora=date("Y-m-d");
        $idDiario=trim($request["idDiario"]);
        
        $datosHorario=  V_personDiary::find((int)$idDiario);
        $prestadorServicio=$datosHorario->personid;
        $datosPrestador= V_person::find((int)$prestadorServicio);
        
        if($datosPrestador->diarytype=="1"){ // si es hora fija
            $citaExitente= V_dates::where("cancel",false)
               ->where("serviceprovider",$idV_person)
               ->where("date",">=",$fechaActualSinhora)
               ->where("secundsdate",$datosHorario->secondsstart)
               ->count();
            if($citaExitente>0){
                return "existecita";
            }
            
        }else{ // si es rango de horas
            $citaExitente= V_dates::where("cancel",false)
               ->where("serviceprovider",$idV_person)
               ->where("date",">=",$fechaActualSinhora)
               ->where("secundsdate",">=",$datosHorario->secondsstart)
               ->where("secundsdate","<=",$datosHorario->secondsend)
               ->count();
            if($citaExitente>0){
                return "existecita";
            }
        }
        
        try{
            DB::beginTransaction();
            
            DB::table('persondiary')
                    ->where('id',$idDiario)
                    ->where('personid',$idV_person)
                    ->update(['updated' => $fechaActual,'deleted' => $fechaActual]);
                
                    
            DB::table('person')->where('id',$idV_person)->update(['updated' => $fechaActual]);
                    
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    /*
    * Elimina todo el horario
    * Fecha: 17-12-2016
    * Autor: OT
    */
    public function eliminarHorarioTodo(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $fechaActualSinhora=date("Y-m-d");
        
        $citaExitente= V_dates::where("cancel",false)
               ->where("serviceprovider",$idV_person)
               ->where("date",">=",$fechaActualSinhora)
               ->count();
            if($citaExitente>0){
                return "existecita";
            }
        
        try{
            DB::beginTransaction();
            
            DB::table('persondiary')
                    ->where('personid',$idV_person)
                    ->update(['updated' => $fechaActual,'deleted' => $fechaActual]);
                
                    
            DB::table('person')->where('id',$idV_person)->update(['updated' => $fechaActual,'diarytype'=>'0']);
                    
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    
    /* Muestra el formulario para cambiar el tiempo del servicio
     * Autor: OT
     * Fecha: 19-12-2016
    */
    public function formularioCambioTiempo(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $dataPerson=  V_person::find($idV_person);
        
        
        return view('vPrestadorServicios.modificarTiempoServicio')->with("tiempo",$dataPerson->timeservice);
    }
    
    
    /*
    * Guarda el tiempo del servicio
    * Fecha: 19-12-2016
    * Autor: OT
    */
    public function guardarTiempoServicio(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $fechaActualSinhora=date("Y-m-d");
        $tiemposervicio=trim($request["tiemposervicio"]);
        
        $citaExitente= V_dates::where("cancel",false)
               ->where("serviceprovider",$idV_person)
               ->where("date",">=",$fechaActualSinhora)
               ->count();
            if($citaExitente>0){
                return "existecita";
            }
        
        try{
            DB::beginTransaction();
                                
            DB::table('person')->where('id',$idV_person)->update(['updated' => $fechaActual,
                'timeservice'=>$tiemposervicio,
                'diarytype'=>'2'
                ]);
                    
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
                           $nombreCliente="";
                           $citaInfo=DB::table('dates')->select("person.fullname","person.company","dates.date","dates.timedate")
                                   ->leftJoin("person","person.id","=","dates.client")
                                   ->where('dates.id','=',$idRelacion)->get();
                           foreach ($citaInfo as $rowInfo){
                               $nombreCliente=($rowInfo->company=="")?$rowInfo->fullname:$rowInfo->company;
                               $nombreCliente= strlen($nombreCliente)>30?substr(ucwords($nombreCliente),0,27)."...":$nombreCliente;
                           }
                           
                           switch($rowNotificacion->type){
                               case 1:
                                   $iconoAlerta="fa fa-stethoscope";
                                   $tituloAlerta=trans("leng.Nueva cita");
                                   $subtituloAlerta=$nombreCliente;
                                   $tipoTexto="success";
                                   break;
                               case 4:
                                   $iconoAlerta="fa fa-times";
                                   $tituloAlerta=trans("leng.Cita cancelada");
                                   $subtituloAlerta=$nombreCliente;
                                   $tipoTexto="danger";
                                   break;
                           }
                           
                          $cadenaNotificaciones.="<li>
                                                    <a href='javascript:;' onclick='leerNotificacionPrestador($idNotificacion);' class='noticebar-item'>
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
     * Muestra la notificacion del prestador
     * @author OT
     * Fecha: 26-12-2016
     */
    public function mostrarNotificacionPrestador($idNotificacion){
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
        $nombreCliente="";
        
        foreach($datosNotificaciones as $rowNotificacion){
            
            if($rowNotificacion->type==1){ // si es nueva cita
                
                $tipo=$rowNotificacion->type;
                $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate")
                              ->leftJoin("person","person.id","=","dates.client")
                              ->where("dates.id","=",$rowNotificacion->relationid)
                              ->get();
                foreach($datosCita as $rowCita){
                    $fechaCita=Funciones::fechaF2($rowCita->date);
                    $horaCita=$rowCita->timedate;
                    $nombreCliente=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                    $idCita=$rowCita->id;
                }            
               
               $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
            }
            
            if($rowNotificacion->type==4){ // cita cancelada por el cliente
                
                $tipo=$rowNotificacion->type;
                $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate")
                              ->leftJoin("person","person.id","=","dates.client")
                              ->where("dates.id","=",$rowNotificacion->relationid)
                              ->get();
                foreach($datosCita as $rowCita){
                    $fechaCita=Funciones::fechaF2($rowCita->date);
                    $horaCita=$rowCita->timedate;
                    $nombreCliente=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                    $idCita=$rowCita->id;
                }            
               
               $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
            }
        
            $datosAlerta=array();
            $datosAlerta["fechaCita"]=$fechaCita;
            $datosAlerta["fechaAccion"]=$fechaAccion;
            $datosAlerta["horaCita"]=$horaCita;
            $datosAlerta["tipo"]=$tipo;
            $datosAlerta["idCita"]=$idCita;
            $datosAlerta["cliente"]=$nombreCliente;
        
            return view('vvnotificaciones.mostrarNotificacion')->with("datosAlerta",$datosAlerta);
        }
  }
  
  /* Muestra la lista de citas del prestador de servicios
    * Autor: OT
    * Fecha: 26-12-2016
    */
    public function misCitas(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $servicios=  V_service::whereNull("deleted")->where("active",true)->orderBy("name")->get();
        $cadenaServicios="";
        foreach($servicios as $vser){
            $cadenaServicios.="<option value='$vser->id'>$vser->name</option>";
        }
        
        return view('vPrestadorServicios.misCitas')->with("cadenaServicios",$cadenaServicios);
    }
    
    
   /* Genera la lista de citas del prestador de servicios
    * Autor: OT
    * Fecha: 26-12-2016
    */
    public function listaCitas(Request $request){
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
       $cancelada=$request["cancelada"];

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
                $filtrosStatus.=" and (dates.accepted=true and dates.rejected=false and dates.cancel=false";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or dates.accepted=true and dates.rejected=false and dates.cancel=false";
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
        
        if($cancelada=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (dates.cancel=true";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or dates.cancel=true";
            }
        }

        if($primerFiltro==1)$filtrosStatus.=")";
        
        $filtroCita="";
        if($cita=="1"){
            $filtroCita.=" and coalesce(char_length(dates.latitude),0)<3";
        }else if($cita=="2"){
            $filtroCita.=" and coalesce(char_length(dates.latitude),0)>3";
        }
        
        $sWhere="dates.serviceprovider=$idPerson". $filtrosStatus.$filtroFechas. $filtroTitulo. $filtroCita;
        $cadenaServicios="";
        if(count($servicios)>0 && $servicios!=""){
            $cadenaServicios=  implode(",", $servicios);
            $cadenaServicios=" and datesservice.serviceid in($cadenaServicios)";
        }
        $sWhere2="1=1 $cadenaServicios";
        
        $listaCitas = V_dates::select("dates.id","person.fullname","person.company","dates.date","dates.timedate",
                "dates.accepted","dates.rejected","dates.cancel","dates.latitude","dates.longitude","dates.address","dates.addressdetails")
            ->leftJoin('person', 'person.id', '=', 'dates.client')
            ->leftJoin("datesservice","datesservice.dateid","=","dates.id")
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
            
            $ca="<font class='flet-lab'><b>".ucwords($compania)."</b> / ".ucfirst($listaCitas->fullname)."</font>";
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
             if($listaCitas->latitude!=""){
              $listaDireccion.="<br><b>Dirección: </b>".ucfirst(mb_strtolower($listaCitas->address));
              $listaDireccion.=" ".ucfirst(mb_strtolower($listaCitas->addressdetails));
              $listaDireccion.="<br><a href='javascript:;' onclick='mostrarUbicacionServicio($listaCitas->latitude,$listaCitas->longitude);'>Ver ubicación</a>";
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
             if($listaCitas->cancel==true){
                 $ca="<font class='flet-lab'>Cancelada por el cliente</font>";
             }else if($listaCitas->accepted==true){
                 $ca="<font class='flet-lab'>Confirmada</font>";
             }else if($listaCitas->rejected==true){
                 $ca="<font class='flet-lab'>Rechazada</font>";
             }else{
                 $ca="<font class='flet-lab'>Pendiente</font>";
             }
            
            return $ca;
         })
         
         ->addColumn('ver', function ($listaCitas) {
             
             $ca="";
             if($listaCitas->cancel==false){
                if($listaCitas->accepted==false && $listaCitas->rejected==false){
                    $ca.="<button type='button' title='".trans("leng.Aceptar cita")."' onclick='aceptarCita(".$listaCitas->id.")' class='btn btn-success btn-xs'><i class='fa fa-check'></i></button>&nbsp;";
                    $ca.="<button type='button' title='".trans("leng.Rechazar cita")."' onclick='rechazarCitaCliente(".$listaCitas->id.")' class='btn btn-danger btn-xs'><i class='fa fa-times'></i></button>&nbsp;";
                }
                if($listaCitas->rejected==true){
                   $ca.="<button type='button' title='".trans("leng.Ver motivo de rechazo")."' onclick='mostrarMotivoRechazo(".$listaCitas->id.")' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></button>&nbsp;";
                }
                
                if($listaCitas->accepted==true && $listaCitas->rejected==false){
                    $ca.="<button type='button' title='".trans("leng.Cancelar cita")."' onclick='rechazarCitaCliente(".$listaCitas->id.")' class='btn btn-danger btn-xs'><i class='fa fa-times'></i></button>&nbsp;";
                }
             }
            return $ca;
         })
         
        ->make(true);
    }
    
    
    /**
     * Aceptar cita del cliente
     * Autor: OT
     * Fecha: 26-12-2016
     */
    public function aceptarCita(Request $request){
        
        if (Auth::check()) {
            $idPrestador = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $idCita   = trim($request->get('idCita'));
        $datosCita= V_dates::find($idCita);
        $clienteId=$datosCita->client;
        
        try{
            DB::beginTransaction();
            
            DB::table('dates')->where('id',$idCita)->update([
                    'updated' => $fechaActual,
                    'accepted' => true,
                ]);
            
            Funciones::enviarAlerta($clienteId, 2, $idCita);
                
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
        
    }
    
    
    /**
     * Muestra el formulario para indicar el motivo de rechazo de cita
     * @author OT
     * Fecha: 28-12-2016
     */

    public function motivoRechazoCita(Request $request){
        if (Auth::check()) {
            $idPrestador = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $idCita=trim($request["idCita"]);
        
        return view('vPrestadorServicios.rechazarCita')->with("idCita",$idCita);
    }
    
    
    /* Guardar el rechazo de la cita
     * @author OT
     * Fecha: 28-12-2016
     */
    public function guardarRechazoCita(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $idCita=trim($request["idCita"]);
        $datosCita=  V_dates::find($idCita);
        $motivoid=trim($request["motivoid"]);
       
        try{
            DB::beginTransaction();
            
             DB::table('dates')->where('id',$idCita)
                     ->update(['updated' => $fechaActual,'rejected'=>TRUE,
                         'reasonrejection'=>$motivoid,
                         'accepted' => false,
                         ]);
             
             Funciones::enviarAlerta($datosCita->client, 3, $idCita);
                    
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
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
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $idCita=trim($request["idCita"]);
        $datos=  V_dates::find($idCita);
        $motivo=$datos->reasonrejection;
        
        return view('vPrestadorServicios.verRechazoCita')->with("motivo",$motivo);
    }
    
    
    /*
    * Carga la tabla de los servicios
    * Autor: OT
    * Fecha: 28-12-2016
    * 
    */

    public function cargarTablaServicios(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        
        $tipoCosto=trim($request["tipoCosto"]);
        $cadenaServicios="";
        $reg=1;
        $soloLectura="";
            $serviciosPrestador=  V_personservice::select("services.id","services.name","services.description","personservice.cost",
                    "services.price","services.minprice","services.maxprice")
                    ->leftJoin("services","services.id","=","personservice.serviceid")
                    ->distinct()
                    ->orderBy("services.name")
                    ->where("personservice.personid",$idV_person)
                    ->get();
            if(count($serviciosPrestador)>0){
                foreach($serviciosPrestador as $serv) {
                    if($tipoCosto==0){
                        $vprecio=($serv->cost=="")?"":$serv->cost;
                    }else{
                        $vprecio=$serv->price;
                        $soloLectura="readonly";
                    }
                    $caja="<input style='width:120px;' $soloLectura onkeypress='return soloNumerosConDecimal(event);' name='costoServicio' id='$serv->id' value='$vprecio' maxlength='7' class='form-control' />";
                    $cadenaServicios.="<tr>
                                 <td>$reg</td>
                                 <td>$serv->name</td>
                                 <td>$serv->description</td>
                                 <td><b>Sugerido: </b> $ ".Funciones::formato_numeros($serv->price, ",", ".").
                                    "<br><b> Mín: </b> $ ".Funciones::formato_numeros($serv->minprice, ",", ".").
                                    "&nbsp;&nbsp;<b> Máx: </b> $ ".Funciones::formato_numeros($serv->maxprice, ",", ".").
                                    "</td>
                                 <td>$caja</td>
                                 <td><a href='javascript:;' class='elimina' title='".trans("leng.Eliminar servicio")."' onclick='eliminarRegistro(this)'><img src='/img/cancelado.png' width='25px;'></i></a></td>
                                 </tr>";
                    $reg++;
                }
            }else{
                $cadenaServicios.="<tr>
                                    <td colspan='6' style='text-align:center'><b>No tiene servicios agregados<b></td>
                                 </tr>";
            }
        
        
        $respuesta="<table class='table table-bordered table-highlight' id='listaServicios'>
                            <thead>
                              <tr>
                                <th>#</th>
                                <th style='width: 300px;'>".trans("leng.Servicio")."</th>
                                <th style='width: 400px;'>".trans("leng.Descripción")."</th>
                                <th style='width: 400px;'>".trans("leng.Precios establecidos")."</th>
                                <th>".trans("leng.Precio")."</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                                 $cadenaServicios
                            </tbody>
                          </table>";
        return $respuesta;
    }
    
    /*
     * Carga las citas en el calendario principal
     * Fecha: 09-01-2017
     * Autor: OT
     */
    
    public function obtenerCitasCalendario(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
     
     $dataPerson=  V_person::find($idV_person);
     $fechaActual=date("Y-m-d");   
     $fechaActualCompleta=date("Y-m-d H:i:s");
     $listaCitas=array();
     
     $checkPendiente=trim($request["checkPendiente"]);
     $checkConfirmado=trim($request["checkConfirmado"]);
     $checkExpirado=trim($request["checkExpirado"]);
     
     
     if($dataPerson->diarytype==2){
         $citasProgramadas=  V_dates::where("date",'>=',$fechaActual)
                ->where("cancel",'false')
                ->where("rejected",'false')
                ->where("serviceprovider",$idV_person)
                ->get();
         
            foreach($citasProgramadas as $cita){
                $estado="";
                $tipo=0;
                $vInicio=date('Y-m-d',strtotime($cita->date)) ." ".  Funciones::hora24Hm($cita->secundsdate);
                $fechaInicio = date('D M j Y G:i:s',  strtotime($vInicio));
                
                $vFinal=date('Y-m-d',strtotime($cita->date)) ." ".  Funciones::hora24Hm($cita->secundsdate+1800);
                $fechaFinal = date('D M j Y G:i:s',  strtotime($vFinal));
                
                if(strlen($cita->longitude)>3){
                    $tipo=1;
                }
                
                if(strtotime($fechaActualCompleta)>  strtotime($vInicio)){
                    $estado="fc-red";
                    if($checkExpirado=="true"){
                        $listaCitas[]=array("id"=>$cita->id.",".$tipo,"title"=>'','start'=>$fechaInicio,'end'=>$fechaFinal,'className'=>$estado,'allDay'=>false,'url'=> 'javascript:;');
                    }
                }else{
                    if($cita->cancel==false && $cita->rejected==false && $cita->accepted==false){
                        $estado="fc-grey";
                        if($checkPendiente=="true"){
                            $listaCitas[]=array("id"=>$cita->id.",".$tipo,"title"=>'','start'=>$fechaInicio,'end'=>$fechaFinal,'className'=>$estado,'allDay'=>false,'url'=> 'javascript:;');
                        }
                    }else if($cita->cancel==false && $cita->rejected==false && $cita->accepted==true){
                        $estado="fc-yellow";
                        if($checkConfirmado=="true"){
                           $listaCitas[]=array("id"=>$cita->id.",".$tipo,"title"=>'','start'=>$fechaInicio,'end'=>$fechaFinal,'className'=>$estado,'allDay'=>false,'url'=> 'javascript:;');
                        }
                    }
                }
                
            }
     }else if($dataPerson->diarytype==1){
         $citasProgramadas=  V_dates::where("date",'>=',$fechaActual)
                ->where("cancel",'false')
                ->where("rejected",'false')
                ->where("serviceprovider",$idV_person)
                ->get();
         
            foreach($citasProgramadas as $cita){
                $estado="";
                $tipo=0;
                $vInicio=date('Y-m-d',strtotime($cita->date)) ." ".  Funciones::hora24Hm($cita->secundsdate);
                $fechaInicio = date('D M j Y G:i:s',  strtotime($vInicio));
                
                $vFinal=date('Y-m-d',strtotime($cita->date)) ." ".  Funciones::hora24Hm($cita->secundsdate+1800);
                $fechaFinal = date('D M j Y G:i:s',  strtotime($vFinal));
                
                if(strlen($cita->longitude)>3){
                    $tipo=1;
                }
                
                if(strtotime($fechaActualCompleta)>  strtotime($vInicio)){
                    $estado="fc-red";
                    if($checkExpirado=="true"){
                        $listaCitas[]=array("id"=>$cita->id.",".$tipo,"title"=>'','start'=>$fechaInicio,'end'=>$fechaFinal,'className'=>$estado,'allDay'=>false,'url'=> 'javascript:;');
                    }
                }else{
                    if($cita->cancel==false && $cita->rejected==false && $cita->accepted==false){
                        $estado="fc-grey";
                        if($checkPendiente=="true"){
                            $listaCitas[]=array("id"=>$cita->id.",".$tipo,"title"=>'','start'=>$fechaInicio,'end'=>$fechaFinal,'className'=>$estado,'allDay'=>false,'url'=> 'javascript:;');
                        }
                    }else if($cita->cancel==false && $cita->rejected==false && $cita->accepted==true){
                        $estado="fc-yellow";
                        if($checkConfirmado=="true"){
                           $listaCitas[]=array("id"=>$cita->id.",".$tipo,"title"=>'','start'=>$fechaInicio,'end'=>$fechaFinal,'className'=>$estado,'allDay'=>false,'url'=> 'javascript:;');
                        }
                    }
                }
            }
     }
         
        $respuesta=array();
        $respuesta["citas"]=$listaCitas;
        return response()->json($respuesta);
    }
    
    
    /*
    * Muestra el detalle de la cita recibida desde la pantalla principal
    * Autor: OT
    * Fecha: 09-01-2017
    * 
    */

    public function detalleCita(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return view('login.login');
        }
        $fechaActualCompleta=date("Y-m-d H:i:s");
        
        $idCita=trim($request["idCita"]);
        $cita=  V_dates::select("person.company","person.fullname","dates.date","dates.timedate",
                "dates.address","dates.addressdetails","dates.longitude","dates.accepted","dates.secundsdate")
                ->leftJoin("person","person.id","=","dates.client")
                ->where("dates.id",$idCita)
                ->get();
        foreach($cita as $dcita){
            $datosCita["id"]=$dcita->$idCita;
            if($dcita->company==""){
                $datosCita["compania"]="----";
            }else{
                $datosCita["compania"]=  ucfirst($dcita->company);
            }
            $datosCita["idCita"]=$idCita;
            $datosCita["nombre"]=$dcita->fullname;
            $datosCita["fecha"]=  Funciones::fechaF2($dcita->date);
            $datosCita["hora"]=$dcita->timedate;
            $datosCita["direccion"]=ucwords(mb_strtolower($dcita->address));
            $datosCita["info"]= ucfirst(mb_strtolower($dcita->addressdetails));
            if(strlen($dcita->longitude)>3){
                $datosCita["citaEn"]="A domicilio";
            }else{
               $datosCita["citaEn"]="En consultorio";
            }
            if($dcita->accepted==true){
                $datosCita["aceptada"]=1;
            }else{
                $datosCita["aceptada"]=0;
            }
            
            
            $vInicio=date('Y-m-d',strtotime($dcita->date)) ." ".  Funciones::hora24Hm($dcita->secundsdate);
            if(strtotime($fechaActualCompleta)>  strtotime($vInicio)){
                 $datosCita["expirado"]=1;
            }else{
                $datosCita["expirado"]=0;
            }
            
        }
        
        return view('vPrestadorServicios.detalleCita')->with("datosCita",$datosCita);
    }
    
    
    /*
    * Guarda el cambio de la cita desde la pantalla pricipal
    * Autor: OT
    * Fecha: 10-01-2017
    * 
    */
    public function guardaCambioCita(Request $request){
        if (Auth::check()) {
            $idV_person = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login');
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $idCita=trim($request["idCita"]);
        $motivoid=trim($request["motivoid"]);
        $opcion=trim($request["opcion"]);
        
        $datosCita=  V_dates::find($idCita);
        
       
        try{
            DB::beginTransaction();
            
            if($opcion==1){
                DB::table('dates')->where('id',$idCita)
                     ->update(['updated' => $fechaActual,'rejected'=>false,
                         'accepted' => true,
                         ]);
             
                Funciones::enviarAlerta($datosCita->client, 2, $idCita);
                
            }else if($opcion==2){
                DB::table('dates')->where('id',$idCita)
                     ->update(['updated' => $fechaActual,
                         'rejected'=>TRUE,
                         'reasonrejection'=>$motivoid,
                         'accepted' => false,
                         ]);
             
                Funciones::enviarAlerta($datosCita->client, 3, $idCita);
            }
             
                    
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }    
}