<?php
/* Controlador para el inicio de sesion
 * Autor: OT
 * Fecha: 17-06-2016
*/
namespace Udoktor\Http\Controllers;
use Illuminate\Http\Request;
use Udoktor\Http\Requests;
use Udoktor\Http\Controllers\Controller;
use Udoktor\Login;
use Udoktor\Pais;
use Udoktor\Estado;
use Udoktor\Ciudad;
use Auth;
use Udoktor\V_person;
use Udoktor\Mensajes;
use Udoktor\Question;
use Udoktor\Shipper;
use DB;
use Udoktor\Funciones;
use Udoktor\Alert;
use Yajra\Datatables\Datatables;

class GeneralController extends Controller
{
    public function estados(Request $request){
        $idPais= $request["idPais"];
        $listaEstado = Estado::where('countryid', $idPais)
                ->where('active',1)
                ->orderBy('name')
                ->get();
        $cadenaEstado="<option value='0' selected>Seleccione estado..</option>";
        foreach($listaEstado as $rowEstados){
            $cadenaEstado.= "<option value='$rowEstados->id'>$rowEstados->name</option>";
        }
        return $cadenaEstado;
    }
    
    public function ciudades(Request $request){
        $idEstado= $request["idEstado"];
        $listaCiudad = Ciudad::where('stateid', $idEstado)->get();
        $cadenaCiudad="<option value='0' selected>Seleccione ciudad..</option>";
        foreach($listaCiudad as $rowCiudad){
            $cadenaCiudad.= "<option value='$rowCiudad->id'>$rowCiudad->name</option>";
        }
        return $cadenaCiudad;
    }
    
    
    /* Enviar mensaje
     * Autor: OT
     * Fecha: 09-06-2016
    */
    public function enviarMensaje(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        $fechaActual=date("Y-m-d H:i:s");
        $mensaje=trim($request["mensaje"]);
        $idPersonaEnvia=trim($request["idPersonaEnvia"]);
        $idPersonaRecibe=trim($request["idPersonaRecibe"]);
        $idEnvio=trim($request["idEnvio"]);
        $idOferta=trim($request["idOferta"]);
        try{
               DB::beginTransaction();
               
               Mensajes::create([
                   'body'=>$mensaje,
                   'senderid'=>$idPersonaEnvia,
                   'recipientid'=>$idPersonaRecibe,
                   'shippingrequestid'=>$idEnvio,
                   'sent'=>$fechaActual,
                   'updated'=>$fechaActual,
              ]);
               
               DB::commit();
       
               return "ok";
           } catch (Exception $ex) {
               DB::rollback();
               return $ex;
           }
    }
    
    
    /* Formulario para leer el mensaje
     * Autor: OT
     * Fecha: 04-07-2016
    */
    public function leerMensajeFormulario($idMensaje){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
        if($idMensaje==0){
            return redirect('bandejaEntrada');
        }
        
        $nombreEnvia="";
        $fechaEnvia="";
        $idRecibe="";
        $idEnvia="";
        $idEnvio="";
        $tituloEnvio="";
        $fechaActual=date("Y-m-d H:i:s");
        
        $datosMensaje=DB::table('message')->select("shippingrequest.title","message.id","person.company", "person.fullname","message.sent","message.body",
                "message.senderid","message.recipientid","message.shippingrequestid")
                  ->leftJoin('person', 'person.id', '=', 'message.senderid')
                  ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'message.shippingrequestid')
                  ->where('message.id','=',$idMensaje)
                  ->where('message.recipientid','=',$idPerson)
                  ->get();
        
        if(count($datosMensaje)==0){
            return redirect('bandejaEntrada');
        }
        
        foreach($datosMensaje as $rowMensaje){
            
            if($rowMensaje->company==""){
              $nombreEnvia=  ucwords($rowMensaje->fullname);
           }else{
              $nombreEnvia= $rowMensaje->company;
           }
            
            
            $mensaje= $rowMensaje->body;
            $fechaEnvia=Funciones::fechaF1Hora($rowMensaje->sent);
            $idRecibe= $rowMensaje->recipientid;
            $idEnvia= $rowMensaje->senderid;
            $idEnvio=$rowMensaje->shippingrequestid;
            $tituloEnvio=  ucfirst($rowMensaje->title);
        }
        
        DB::table('message')->where('id', $idMensaje)
               ->update(['read' =>$fechaActual,
                         'updated' =>$fechaActual,
                        ]);
        
        
        return view('vvmensajes.mostrarMensaje')
        ->with("nombreEnvia",$nombreEnvia)
        ->with("idPersonaEnvia",$idRecibe)
        ->with("idPersonaRecibe",$idEnvia)
        ->with("idEnvio",$idEnvio)
        ->with("tituloEnvio",$tituloEnvio)
        ->with("mensaje",$mensaje)
        ->with("fechaEnvia",$fechaEnvia);
    }



    /* Enviar mensaje
     * Autor: OT
     * Fecha: 09-06-2016
    */
    public function buscarMensajesNuevos(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            try{
                   DB::beginTransaction();
                   
                   $datosMensajes=DB::table('message')->select("person.company","message.id","person.fullname","message.sent","message.body")
                        ->leftJoin('person', 'person.id', '=', 'message.senderid')
                        ->where('message.recipientid','=',$idPerson)
                        ->where('message.read','=',null)
                        ->orderBy("message.sent", "desc")
                        ->get();
                    
                    $mensajesNuevos=count($datosMensajes);
                    $cadenaMensajes="";
                    if($mensajesNuevos>0){
                        foreach($datosMensajes as $rowMensajes){
                           if($rowMensajes->company==""){
                                $nombreEnvia=  ucwords($rowMensajes->fullname);
                             }else{
                                $nombreEnvia= strlen($rowMensajes->company)>30?substr(ucwords($rowMensajes->company),0,27)."...":$rowMensajes->company;
                             } 
                            
                           
                           $textoMsj=substr($rowMensajes->body, 0, 40);
                           $fecha=Funciones::fechaF1Hora($rowMensajes->sent);
                           $idMensaje=$rowMensajes->id;
                          $cadenaMensajes.="<li>
                                                <a href='javascript:;' onclick='leerMensajeClienteGeneral($idMensaje);' class='noticebar-item'>
                                                  <span class='noticebar-item-body'>
                                                    <strong class='noticebar-item-title'>$nombreEnvia</strong>
                                                    <span class='noticebar-item-text'>$textoMsj..</span>
                                                    <span class='noticebar-item-time'><i class='fa fa-calendar'></i> $fecha</span>
                                                  </span>
                                                </a>
                                              </li>";
                        }
                        
                    }else{
                       $cadenaMensajes.= "<li class='noticebar-empty'>
                            <h4 class='noticebar-empty-title'>".trans("leng.No hay mensajes nuevos") .".</h4>
                      </li>";
                    }
                   
                   
                    $respuesta="<li class='dropdown'>
                      <a href='./page-notifications.html' class='dropdown-toggle' data-toggle='dropdown'>
                        <i class='fa fa-envelope'></i>
                        <span class='navbar-visible-collapsed'>&nbsp;".trans('leng.Mensajes') ."&nbsp;</span>
                        <span class='badge'>$mensajesNuevos</span>
                      </a>

                      <ul class='dropdown-menu noticebar-menu' role='menu'>                
                        <li class='nav-header'>
                          <div class='pull-left'>" . trans('leng.Mensajes') ."
                          </div>
                        </li>
                          $cadenaMensajes
                        <li class='noticebar-menu-view-all'>
                          <a href='/bandejaEntrada'>".trans('leng.Ver todos los mensajes')."</a>
                        </li>
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
    
    
    /* Muestra los mensajes recibidos del usuario loguedo
     * Autor: OT
     * Fecha: 09-06-2016
    */
    public function misMensajesRecibidos(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            $dataPerson=V_person::find($idPerson);

            if(count($dataPerson)>0){
               if($dataPerson->isshipper==true){
                    return view('vvmensajes.misMensajesRecibidosTransportista');
               }else{
                    return view('vvmensajes.misMensajesRecibidosCliente');
                }
            }
            
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
       
        
    }
    
    /* Listado de mensajes recibidos
     * Autor: OT
     * Fecha: 22-06-2016
    */
    public function listaMensajesRecibidos(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
       $buscatitulo=trim($request["buscaTitulo"]);
        $fecha1=$request["fecha1"];
        $fecha2=$request["fecha2"];
        $sinleer=$request["sinleer"];
        
        
        $filtrosStatus="";
        if($sinleer=="true"){
             $filtrosStatus.=" and message.read is  null";
        }
        
        
        $filtroFechas="";
        if($fecha1!=""){
            $filtroFechas.=" and date(message.sent)>= '$fecha1'";
        }
        
        if($fecha2!=""){
            $filtroFechas.=" and date(message.sent)<= '$fecha2'";
        }
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (person.fullname ilike'%$buscatitulo%' or message.body ilike'%$buscatitulo%')";
        }
        
        $sWhere="message.recipientid=$idPerson" . $filtrosStatus . $filtroFechas. $filtroTitulo;
        
        
        $listaMensajes = Mensajes::select(["message.id","person.company","person.fullname","message.sent","message.read","message.body"])
            ->leftJoin('person', 'person.id', '=', 'message.senderid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("message.sent","desc")
            ->get();
        
        
        
        return Datatables::of($listaMensajes)
        ->addColumn('emisor', function ($listaMensajes) {
            
            if($listaMensajes->company==""){
              $nomEmisor=  ucwords($listaMensajes->fullname);
           }else{
              $nomEmisor= strlen($listaMensajes->company)>30?substr(ucwords($listaMensajes->company),0,27)."...":$listaMensajes->company;
           }
            
            
            
            if($listaMensajes->read=="")$ca="<font class='flet-lab'><b>".ucwords($nomEmisor)."</b></font>";
            else $ca="<font class='flet-lab'>".ucwords($nomEmisor)."</font>";
            
            return $ca;
         })
         ->addColumn('mensaje', function ($listaMensajes) {
             if($listaMensajes->read=="")$ca="<font class='flet-lab'><b>". substr(ucfirst($listaMensajes->body),0,70)."..</b></font>";
             else $ca="<font class='flet-lab'>". substr(ucfirst($listaMensajes->body),0,70)."..</font>";
             
            return $ca;
         })
         
         ->addColumn('fecha', function ($listaMensajes) {
             if($listaMensajes->read=="")$ca="<font class='flet-lab'><b>". Funciones::fechaF1Hora($listaMensajes->sent) ."</b></font>";
             else $ca="<font class='flet-lab'>". Funciones::fechaF1Hora($listaMensajes->sent) ."</font>";
                 
            return $ca;
         })
         ->addColumn('ver', function ($listaMensajes) {
             $ca="<button type='button' onclick='leerMensajeCliente(".$listaMensajes->id.")' class='btn btn-secondary btn-sm'><i class='fa fa-eye'></i></button>";
            return $ca;
         })
         
        ->make(true);
    }
    
    
    /* Muestra los mensajes recibidos del usuario loguedo
     * Autor: OT
     * Fecha: 05-07-2016
    */
    public function misMensajesEnviados(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            $dataPerson=V_person::find($idPerson);

            if(count($dataPerson)>0){
               if($dataPerson->isshipper==true){
                    return view('vvmensajes.misMensajesEnviadosTransportista');
               }else{
                    return view('vvmensajes.misMensajesEnviadosCliente');
                }
            }
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
    }
    
    /* Listado de mensajes enviados
     * Autor: OT
     * Fecha: 22-06-2016
    */
    public function listaMensajesEnviados(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
       $buscatitulo=trim($request["buscaTitulo"]);
        $fecha1=$request["fecha1"];
        $fecha2=$request["fecha2"];
        
        $filtroFechas="";
        if($fecha1!=""){
            $filtroFechas.=" and date(message.sent)>= '$fecha1'";
        }
        
        if($fecha2!=""){
            $filtroFechas.=" and date(message.sent)<= '$fecha2'";
        }
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (person.fullname ilike'%$buscatitulo%' or
                message.body ilike'%$buscatitulo%')";
        }
        
        $sWhere="message.senderid=$idPerson" . $filtroFechas. $filtroTitulo;
        
        
        $listaMensajes = Mensajes::select(["message.id","person.company","person.fullname","message.sent","message.read","message.body"])
            ->leftJoin('person', 'person.id', '=', 'message.recipientid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("message.sent","desc")
            ->get();
        
        
        
        return Datatables::of($listaMensajes)
        ->addColumn('emisor', function ($listaMensajes) {
            
            if($listaMensajes->company==""){
              $nomEmisor=  ucwords($listaMensajes->fullname);
           }else{
              $nomEmisor= strlen($listaMensajes->company)>30?substr(ucwords($listaMensajes->company),0,27)."...":$listaMensajes->company;
           }
            
            $ca="<font class='flet-lab'>".ucwords($nomEmisor)."</font>";
            return $ca;
         })
         ->addColumn('mensaje', function ($listaMensajes) {
            $ca="<font class='flet-lab'>". substr(ucfirst($listaMensajes->body),0,70)."..</font>";
            return $ca;
         })
         
         ->addColumn('fecha', function ($listaMensajes) {
            $ca="<font class='flet-lab'>". Funciones::fechaF1Hora($listaMensajes->sent) ."</font>";
            return $ca;
         })
         
         ->addColumn('ver', function ($listaMensajes) {
             $ca="<button type='button' onclick='leerMensajeClienteEnviado(".$listaMensajes->id.")' class='btn btn-secondary btn-sm'><i class='fa fa-eye'></i></button>";
            return $ca;
         })
         
        ->make(true);
    }
    
    
    /* Formulario para leer el mensaje
     * Autor: OT
     * Fecha: 05-07-2016
    */
    public function verMensajeEnviadoFormulario($idMensaje){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
        if($idMensaje==0){
            return redirect('bandejaSalida');
        }
        
        $nombreEnvia="";
        $fechaEnvia="";
        $idRecibe="";
        $idEnvia="";
        $idEnvio="";
        $fechaActual=date("Y-m-d H:i:s");
        
        $datosMensaje=DB::table('message')->select("shippingrequest.title","message.id","person.company","person.fullname", "message.sent","message.body",
                "message.senderid","message.recipientid","message.shippingrequestid")
                  ->leftJoin('person', 'person.id', '=', 'message.recipientid')
                  ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'message.shippingrequestid')
                  ->where('message.id','=',$idMensaje)
                  ->where('message.senderid','=',$idPerson)
                  ->get();
        
        if(count($datosMensaje)==0){
            return redirect('bandejaSalida');
        }
        
        foreach($datosMensaje as $rowMensaje){
            if($rowMensaje->company==""){
              $nomEmisor=  ucwords($rowMensaje->fullname);
           }else{
              $nomEmisor= $rowMensaje->company;
           }
            
            
            $nombreEnvia=  ucwords($nomEmisor);
            $mensaje= $rowMensaje->body;
            $fechaEnvia=Funciones::fechaF1Hora($rowMensaje->sent);
            $idRecibe= $rowMensaje->recipientid;
            $idEnvia= $rowMensaje->senderid;
            $idEnvio=$rowMensaje->shippingrequestid;
            $tituloEnvio=  ucfirst($rowMensaje->title);
        }
        
        return view('vvmensajes.mostrarMensaje')
        ->with("nombreEnvia",$nombreEnvia)
        ->with("idPersonaEnvia",$idRecibe)
        ->with("idPersonaRecibe",$idEnvia)
        ->with("idEnvio",$idEnvio)
        ->with("tituloEnvio",$tituloEnvio)
        ->with("mensaje",$mensaje)
        ->with("enviado",1)
        ->with("fechaEnvia",$fechaEnvia);
    }
    
    
    /* Genera la lista de notificaciones nuevas
     * Autor: OT
     * Fecha: 09-06-2016
    */
    public function buscarNotificacionesNuevos(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            try{
                   DB::beginTransaction();
                   
                   $datosNotificaciones=DB::table('alert')->select("alert.id","alert.type","alert.relationid","alert.createdat")
                       // ->leftJoin('person', 'person.id', '=', 'message.senderid')
                        ->where('alert.recipientid','=',$idPerson)
                        ->where('alert.read','=',null)
                        ->orderBy("alert.createdat", "desc")
                        ->get();
                    
                    $notificacionesNuevas=count($datosNotificaciones);
                    $cadenaNotificaciones="";
                    $iconoAlerta="";
                    $tituloAlerta="";
                    $subtituloAlerta="";
                    if($notificacionesNuevas>0){
                        foreach($datosNotificaciones as $rowNotificacion){
                           $fecha=Funciones::fechaF1Hora($rowNotificacion->createdat);
                           $idNotificacion=$rowNotificacion->id;
                           $idRelacion=($rowNotificacion->relationid=="")?0:$rowNotificacion->relationid;
                           $tituloEnvio="";
                           $envioInfo=DB::table('shippingrequest')->where('id','=',$idRelacion)->get();
                           foreach ($envioInfo as $rowInfo){
                               $tituloEnvio= strlen($rowInfo->title)>30?substr(ucwords($rowInfo->title),0,27)."...":$rowInfo->title;
                           }
                           
                           switch($rowNotificacion->type){
                               case 1:
                                   $iconoAlerta="fa fa-compass";
                                   $tituloAlerta=trans("leng.Nueva oferta");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 2:
                                   $iconoAlerta="fa fa-compass";
                                   $tituloAlerta=trans("leng.Oferta cancelada");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 3:
                                   $iconoAlerta="fa fa-compass";
                                   $tituloAlerta=trans("leng.Oferta aceptada");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 4:
                                   $iconoAlerta="fa fa-compass";
                                   $tituloAlerta=trans("leng.Oferta rechazada");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 5:
                                   $iconoAlerta="fa fa-question-circle";
                                   $tituloAlerta=trans("leng.Nueva pregunta");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 6:
                                   $iconoAlerta="fa fa-question-circle";
                                   $tituloAlerta=trans("leng.Nueva respuesta");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 7:
                                   $iconoAlerta="fa fa-truck";
                                   $tituloAlerta=trans("leng.Envío recogido");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                              case 8:
                                   $iconoAlerta="fa fa-truck";
                                   $tituloAlerta=trans("leng.Envío entregado");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                              case 9:
                                   $iconoAlerta="fa fa-star";
                                   $tituloAlerta=trans("leng.Nueva calificación");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                              case 10:
                                   $iconoAlerta="fa fa-star";
                                   $tituloAlerta=trans("leng.Nueva calificación");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                             case 11:
                                   $iconoAlerta="fa fa-truck";
                                   $tituloAlerta=trans("leng.Vehículo asignado");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 12:
                                   $iconoAlerta="fa fa-bolt";
                                   $tituloAlerta=trans("leng.Nuevo envío");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 13:
                                   $iconoAlerta="fa fa-bolt";
                                   $tituloAlerta=trans("leng.Envío por expirar");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                               case 14:
                                   $iconoAlerta="fa fa-map-marker";
                                   $tituloAlerta=trans("leng.Check envío");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                              case 15:
                                   $iconoAlerta="fa fa-compass";
                                   $tituloAlerta=trans("leng.Oferta en competencia");
                                   $subtituloAlerta=$tituloEnvio;
                                   break;
                           }
                           
                           
                          $cadenaNotificaciones.="<li>
                                                    <a href='javascript:;' onclick='leerNotificacionesGeneral($idNotificacion);' class='noticebar-item'>
                                                        <span class='noticebar-item-image'>
                                                          <i class='$iconoAlerta text-success'></i>
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
    
    
    
    /* Muestra la notificacion y la marca como leida
     * Autor: OT
     * Fecha: 05-07-2016
    */
    public function mostrarNotificacion($idNotificacion){
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
        
        
         DB::table('alert')->where('id', $idNotificacion)
             ->update(['read' =>$fechaActual,
                       'updated' =>$fechaActual,
              ]);

        
        $tituloEnvio="";
        $fechaAccion="";
        $pregunta="";
        $respuesta="";
        $transportista="";
        $cliente="";
        $precioOferta="";
        $fechaRecoger="";
        $fechaEntregar="";
        $motivoCorreo="";
        $estrellas="";
        $idEnvio="";
        $tipo="";
        $texto="";
        $vehiculo="";
        $precioEnvio="";
        $fechaExpiracion="";
        $recogerEn="";
        $entregarEn="";
        foreach($datosNotificaciones as $rowNotificacion){
            
            if($rowNotificacion->type==1){ // si es nueva oferta
                
                $tipo=$rowNotificacion->type;
                $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                            foreach($datosEnvio as $rowEnvio){
                                $tituloEnvio=$rowEnvio->title;
                                $idEnvio=$rowEnvio->id;
                            }
                
                $datosOferta=DB::table('serviceoffer')->select("person.company","serviceoffer.shipmentcost","person.fullname",
                                    "serviceoffer.collectiondate","serviceoffer.collectionuntildate","serviceoffer.collectiontype",
                                    "serviceoffer.deliverydate","serviceoffer.deliveryuntildate","serviceoffer.deliverytype",
                                    "currency.symbol")
                                    ->leftJoin('shipper', 'shipper.id', '=', 'serviceoffer.shipperid')
                                    ->leftJoin('person', 'person.id', '=', 'shipper.personid')
                                    ->leftJoin('currency', 'currency.id', '=', 'serviceoffer.currencyid')
                                    ->where("serviceoffer.id","=",$rowNotificacion->relationid2)
                                    ->get();
                            foreach($datosOferta as $rowOferta){
                                
                                if($rowOferta->company==""){
                                    $transportista=ucwords($rowOferta->fullname);
                                }else{
                                    $transportista=ucwords($rowOferta->company);
                                }

                                $precioOferta= $rowOferta->symbol." ".Funciones::formato_numeros($rowOferta->shipmentcost,",", ".");
                                
                                $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                            }
                            
            }else if($rowNotificacion->type==2){ // Oferta cancelada
                
                    $tipo=$rowNotificacion->type;
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                    foreach($datosEnvio as $rowEnvio){
                          $tituloEnvio=$rowEnvio->title;
                          $idEnvio=$rowEnvio->id;
                    }

                    $datosOferta=DB::table('serviceoffer')->select("person.company","serviceoffer.shipmentcost","person.fullname",
                         "serviceoffer.collectiondate","serviceoffer.collectionuntildate","serviceoffer.collectiontype",
                         "serviceoffer.deliverydate","serviceoffer.deliveryuntildate","serviceoffer.deliverytype",
                         "currency.symbol")
                         ->leftJoin('shipper', 'shipper.id', '=', 'serviceoffer.shipperid')
                         ->leftJoin('person', 'person.id', '=', 'shipper.personid')
                         ->leftJoin('currency', 'currency.id', '=', 'serviceoffer.currencyid')
                         ->where("serviceoffer.id","=",$rowNotificacion->relationid2)
                         ->get();
                    
                    foreach($datosOferta as $rowOferta){
                        
                        if($rowOferta->company==""){
                            $transportista=ucwords($rowOferta->fullname);
                        }else{
                            $transportista=ucwords($rowOferta->company);
                        }
                                
                        $precioOferta= $rowOferta->symbol." ".Funciones::formato_numeros($rowOferta->shipmentcost,",", ".");
                        
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                    }
                    
            }else if($rowNotificacion->type==3){ // Oferta aceptada
                
                    $tipo=$rowNotificacion->type;
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                        foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                        }
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                        
            }else if($rowNotificacion->type==4){ // Oferta rechazada
                
                    $tipo=$rowNotificacion->type;
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                        foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                        }
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                        
            }else if($rowNotificacion->type==5){ // Nueva pregunta
                
                    $tipo=$rowNotificacion->type;
                    
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                        foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                        }
                        
                        $datosPregunta=DB::table('question')->select("person.company","question.body","person.fullname")
                                ->leftJoin('shipper', 'shipper.id', '=', 'question.shipperid')
                                ->leftJoin('person', 'person.id', '=', 'shipper.personid')
                                ->where("question.id","=",$rowNotificacion->relationid2)
                                ->get();
                        
                        foreach($datosPregunta as $rowPregunta){
                            $pregunta=  ucfirst($rowPregunta->body);
                            if($rowPregunta->company==""){
                                $transportista=ucwords($rowPregunta->fullname);
                            }else{
                                $transportista=ucwords($rowPregunta->company);
                            }
                        }
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                        
            }else if($rowNotificacion->type==6){ // Nueva respuesta
                
                    $tipo=$rowNotificacion->type;
                    
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                        foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                        }
                        
                        $datosPregunta=DB::table('question')->where("id","=",$rowNotificacion->relationid2)->get();
                        foreach($datosPregunta as $rowPregunta){
                            $pregunta=  ucfirst($rowPregunta->body);
                            $respuesta=ucfirst($rowPregunta->answer);
                        }
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                        
            }else if($rowNotificacion->type==7){ // Envio recogido
                
                    $tipo=$rowNotificacion->type;
                    
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                    foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                    }
                   $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                   
            }else if($rowNotificacion->type==8){ // Envio entregado
                
                    $tipo=$rowNotificacion->type;
                    
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                    foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                    }
                   $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                   
            }else if($rowNotificacion->type==9){ // Transportista califica al cliente
                
                    $tipo=$rowNotificacion->type;
                    
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                    foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                    }
                        
                        $datosCalificacion=DB::table('feedback')->select("person.company","feedback.comment","feedback.starrating","person.fullname")
                                ->leftJoin('person', 'person.id', '=', 'feedback.authorid')
                                ->where("feedback.id","=",$rowNotificacion->relationid2)
                                ->get();
                        
                        foreach($datosCalificacion as $rowCalificacion){
                            $texto=  ucfirst($rowCalificacion->comment);
                            
                            if($rowCalificacion->company==""){
                                $transportista=ucwords($rowCalificacion->fullname);
                            }else{
                                $transportista=ucwords($rowCalificacion->company);
                            }
                            
                            $estrellas=$rowCalificacion->starrating;
                        }
                        
                        
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                        
            }else if($rowNotificacion->type==10){ // Cliente califica al transportista
                
                    $tipo=$rowNotificacion->type;
                    
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                    foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                            $idEnvio=$rowEnvio->id;
                    }
                        
                        $datosCalificacion=DB::table('feedback')->select("person.company","feedback.comment","feedback.starrating","person.fullname")
                                ->leftJoin('person', 'person.id', '=', 'feedback.authorid')
                                ->where("feedback.id","=",$rowNotificacion->relationid2)
                                ->get();
                        
                        foreach($datosCalificacion as $rowCalificacion){
                            $texto=  ucfirst($rowCalificacion->comment);
                            
                            if($rowCalificacion->company==""){
                                $cliente=ucwords($rowCalificacion->fullname);
                            }else{
                                $cliente=ucwords($rowCalificacion->company);
                            }
                            
                            $estrellas=$rowCalificacion->starrating;
                        }
                        
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                        
                        
            }else if($rowNotificacion->type==11){ // Vehiculo asignado
                
                    $tipo=$rowNotificacion->type;
                    
                    $datosEnvio=DB::table('shipment')->select("vehicle.description","person.company","shippingrequest.title")
                                ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'shipment.shippingrequestid')
                                ->leftJoin('vehicle', 'vehicle.id', '=', 'shipment.vehicleid')
                                ->leftJoin('shipper', 'shipper.id', '=', 'vehicle.shipperid')
                                ->leftJoin('person', 'person.id', '=', 'shipper.personid')
                                ->where("shipment.shippingrequestid","=",$rowNotificacion->relationid)
                                ->get();

                        
                        foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=ucfirst($rowEnvio->title);
                            $vehiculo=ucfirst($rowEnvio->description);
                            $transportista=ucfirst($rowEnvio->company);
                            $idEnvio=$rowNotificacion->relationid;
                        }
                        
                        $fechaAccion=Funciones::fechaF1Hora($fechaActual);
                        
            }else if($rowNotificacion->type==12){ // Nuevo envio
                
                    $tipo=$rowNotificacion->type;
                    $idEnvio=$rowNotificacion->relationid;
                    $infoEnvio = DB::table('shippingrequest')
                                        ->select( DB::raw("to_char(shippingrequest.createdat,'DD/MM/YYYY HH24:MI:SS') as creacion"),
                                                  DB::raw("to_char(collectionaddress.collectiondate,'DD/MM/YYYY') as fecharecoger"),
                                                  DB::raw("to_char(collectionaddress.collectionuntildate,'DD/MM/YYYY') as fecharecogerhasta"),
                                                  DB::raw("to_char(deliveryaddress.deliverydate,'DD/MM/YYYY') as fechaentregar"),
                                                  DB::raw("to_char(deliveryaddress.deliveryuntildate,'DD/MM/YYYY') as fechaentregarhasta"),
                                                  DB::raw("to_char(to_timestamp(expirationdate,'YYYY-mm-dd HH24:MI:SS'),'dd/mm/YYYY HH24:MI:SS') as expira"),
                                                  "shippingrequest.costtype as costtype",
                                                  "shippingrequest.cost as costo",
                                                  "shippingrequest.title as titulo",
                                                  "collectionaddress.city as ciudad",
                                                  "vestado.name as estado",
                                                  "collectionaddress.stateid as estadorecogerid",
                                                  "collectionaddress.streeUdoktor as calle",
                                                  "deliveryaddress.streeUdoktor as calle_e",
                                                  "deliveryaddress.city as ciudad_e",
                                                  "eestado.name as estado_e",
                                                  "deliveryaddress.stateid as estadoentregarid",
                                                  "person.fullname",
                                                  "person.company",
                                                  "paisrecoger.name as npaisrecoger",
                                                  "paisentregar.name as npaisentregar",
                                                  "collectionaddress.generalubication as origen",
                                                  "deliveryaddress.generalubication as destino"
                                                )
                                        ->leftJoin('collectionaddress', 'collectionaddress.shippingrequestid', '=', 'shippingrequest.id')
                                        ->leftJoin('deliveryaddress', 'deliveryaddress.shippingrequestid', '=', 'shippingrequest.id')
                                        ->leftJoin('administrativeunit as vestado', 'vestado.id', '=', 'collectionaddress.stateid')
                                        ->leftJoin('country as paisrecoger', 'paisrecoger.id', '=', 'vestado.countryid')
                                        ->leftJoin('administrativeunit as eestado', 'eestado.id', '=', 'deliveryaddress.stateid')
                                        ->leftJoin('country as paisentregar', 'paisentregar.id', '=', 'eestado.countryid')
                                        ->leftJoin('person','person.id','=','shippingrequest.requesterid')
                                        ->where('shippingrequest.id',"=",$rowNotificacion->relationid)
                                        ->get();


                                    foreach($infoEnvio as $rowEnvio){
                                        
                                        
                                        if($rowEnvio->fecharecogerhasta==""){
                                            $fechaRecoger=$rowEnvio->fecharecoger;
                                        }else{
                                            $fechaRecoger=$rowEnvio->fecharecoger . " - " . $rowEnvio->fecharecogerhasta;
                                        }

                                        if($rowEnvio->fechaentregarhasta==""){
                                            $fechaEntregar=$rowEnvio->fechaentregar;
                                        }else{
                                            $fechaEntregar=$rowEnvio->fechaentregar . " - " . $rowEnvio->fechaentregarhasta;
                                        }

                                        if($rowEnvio->costtype==1){
                                            $precioEnvio=Funciones::formato_numeros($rowEnvio->costo,",",".");
                                        }else{
                                            $precioEnvio=trans("leng.Sin precio");
                                        }
                                        $fechaExpiracion=$rowEnvio->expira;
                                        $tituloEnvio=  ucfirst($rowEnvio->titulo);
                                        $recogerEn=$rowEnvio->origen;
                                        $entregarEn=$rowEnvio->destino;

                                        if($rowEnvio->company==""){
                                            $cliente=  ucwords($rowEnvio->fullname);
                                        }else{
                                            $cliente= ucfirst($rowEnvio->company);
                                        }
                                    }
                        $fechaAccion=Funciones::fechaF1Hora($fechaActual);
                        
            }else if($rowNotificacion->type==13){ // Envio por expirar
                
                    $tipo=$rowNotificacion->type;
                    $idEnvio=$rowNotificacion->relationid;
                    $infoEnvio = DB::table('shippingrequest')
                                        ->select( DB::raw("to_char(shippingrequest.createdat,'DD/MM/YYYY HH24:MI:SS') as creacion"),
                                                  DB::raw("to_char(collectionaddress.collectiondate,'DD/MM/YYYY') as fecharecoger"),
                                                  DB::raw("to_char(collectionaddress.collectionuntildate,'DD/MM/YYYY') as fecharecogerhasta"),
                                                  DB::raw("to_char(deliveryaddress.deliverydate,'DD/MM/YYYY') as fechaentregar"),
                                                  DB::raw("to_char(deliveryaddress.deliveryuntildate,'DD/MM/YYYY') as fechaentregarhasta"),
                                                  DB::raw("to_char(to_timestamp(expirationdate,'YYYY-mm-dd HH24:MI:SS'),'dd/mm/YYYY HH24:MI:SS') as expira"),
                                                  "shippingrequest.costtype as costtype",
                                                  "shippingrequest.cost as costo",
                                                  "shippingrequest.title as titulo",
                                                  "collectionaddress.city as ciudad",
                                                  "vestado.name as estado",
                                                  "collectionaddress.stateid as estadorecogerid",
                                                  "collectionaddress.streeUdoktor as calle",
                                                  "deliveryaddress.streeUdoktor as calle_e",
                                                  "deliveryaddress.city as ciudad_e",
                                                  "eestado.name as estado_e",
                                                  "deliveryaddress.stateid as estadoentregarid",
                                                  "person.fullname",
                                                  "person.company",
                                                  "paisrecoger.name as npaisrecoger",
                                                  "paisentregar.name as npaisentregar",
                                                  "collectionaddress.generalubication as origen",
                                                  "deliveryaddress.generalubication as destino"
                                                )
                                        ->leftJoin('collectionaddress', 'collectionaddress.shippingrequestid', '=', 'shippingrequest.id')
                                        ->leftJoin('deliveryaddress', 'deliveryaddress.shippingrequestid', '=', 'shippingrequest.id')
                                        ->leftJoin('administrativeunit as vestado', 'vestado.id', '=', 'collectionaddress.stateid')
                                        ->leftJoin('country as paisrecoger', 'paisrecoger.id', '=', 'vestado.countryid')
                                        ->leftJoin('administrativeunit as eestado', 'eestado.id', '=', 'deliveryaddress.stateid')
                                        ->leftJoin('country as paisentregar', 'paisentregar.id', '=', 'eestado.countryid')
                                        ->leftJoin('person','person.id','=','shippingrequest.requesterid')
                                        ->where('shippingrequest.id',"=",$rowNotificacion->relationid)
                                        ->get();


                                    foreach($infoEnvio as $rowEnvio){
                                        
                                        
                                        if($rowEnvio->fecharecogerhasta==""){
                                            $fechaRecoger=$rowEnvio->fecharecoger;
                                        }else{
                                            $fechaRecoger=$rowEnvio->fecharecoger . " - " . $rowEnvio->fecharecogerhasta;
                                        }

                                        if($rowEnvio->fechaentregarhasta==""){
                                            $fechaEntregar=$rowEnvio->fechaentregar;
                                        }else{
                                            $fechaEntregar=$rowEnvio->fechaentregar . " - " . $rowEnvio->fechaentregarhasta;
                                        }

                                        if($rowEnvio->costtype==1){
                                            $precioEnvio=Funciones::formato_numeros($rowEnvio->costo,",",".");
                                        }else{
                                            $precioEnvio=trans("leng.Sin precio");
                                        }
                                        $fechaExpiracion=$rowEnvio->expira;
                                        $tituloEnvio=  ucfirst($rowEnvio->titulo);
                                        $recogerEn=$rowEnvio->origen;
                                        $entregarEn=$rowEnvio->destino;

                                        if($rowEnvio->company==""){
                                            $cliente=  ucwords($rowEnvio->fullname);
                                        }else{
                                            $cliente= ucfirst($rowEnvio->company);
                                        }
                                    }
                        $fechaAccion=Funciones::fechaF1Hora($fechaActual);
                        
            }else if($rowNotificacion->type==15){ // Oferta en compentencia
                
                    $tipo=$rowNotificacion->type;
                    $idEnvio=$rowNotificacion->relationid;
                    
                    $datosEnvio=DB::table('shippingrequest')->where("id","=",$rowNotificacion->relationid)->get();
                    foreach($datosEnvio as $rowEnvio){
                            $tituloEnvio=$rowEnvio->title;
                    }
                    
                    $datosOferta=DB::table('serviceoffer')->select("person.company","serviceoffer.shipmentcost","person.fullname",
                         "serviceoffer.collectiondate","serviceoffer.collectionuntildate","serviceoffer.collectiontype",
                         "serviceoffer.deliverydate","serviceoffer.deliveryuntildate","serviceoffer.deliverytype",
                         "currency.symbol")
                         ->leftJoin('shipper', 'shipper.id', '=', 'serviceoffer.shipperid')
                         ->leftJoin('person', 'person.id', '=', 'shipper.personid')
                         ->leftJoin('currency', 'currency.id', '=', 'serviceoffer.currencyid')
                         ->where("serviceoffer.id","=",$rowNotificacion->relationid2)
                         ->get();
                    
                    foreach($datosOferta as $rowOferta){
                        
                        if($rowOferta->company==""){
                            $transportista=ucwords($rowOferta->fullname);
                        }else{
                            $transportista=ucwords($rowOferta->company);
                        }
                                
                        $precioOferta= $rowOferta->symbol." ".Funciones::formato_numeros($rowOferta->shipmentcost,",", ".");
                        
                        $fechaAccion=Funciones::fechaF1Hora($rowNotificacion->createdat);
                    }

                        
                    
                    $fechaAccion=Funciones::fechaF1Hora($fechaActual);
            }
            
            
        }
        
        $datosAlerta=array();
        $datosAlerta["tituloEnvio"]=$tituloEnvio;
        $datosAlerta["fechaAccion"]=$fechaAccion;
        $datosAlerta["pregunta"]=$pregunta;
        $datosAlerta["respuesta"]=$respuesta;
        $datosAlerta["transportista"]=$transportista;
        $datosAlerta["cliente"]=$cliente;
        $datosAlerta["precioOferta"]=$precioOferta;
        $datosAlerta["fechaRecoger"]=$fechaRecoger;
        $datosAlerta["fechaEntregar"]=$fechaEntregar;
        $datosAlerta["calificacion"]=$estrellas;
        $datosAlerta["tipo"]=$tipo;
        $datosAlerta["idEnvio"]=$idEnvio;
        $datosAlerta["texto"]=$texto;
        $datosAlerta["vehiculo"]=$vehiculo;
        $datosAlerta["precioEnvio"]=$precioEnvio;
        $datosAlerta["recogerEn"]=$recogerEn;
        $datosAlerta["entregarEn"]=$entregarEn;
        $datosAlerta["fechaExpiracion"]=$fechaExpiracion;
        
        
        
        return view('vvnotificaciones.mostrarNotificacion')->with("datosAlerta",$datosAlerta);
    }
    
    
    /* Muestra los warnings en el principal
     * Autor: OT
     * Fecha: 12-07-2016
    */
    public function buscarWarningsNuevos(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            $dataPerson = V_person::find($idPerson);
            try{
                DB::beginTransaction();
                $cadenaWarnings="";
                $iconoAlerta="";
                $tituloAlerta="";
                $subtituloAlerta="";
                $totalAlertas=0;
                
                if ($dataPerson->isshipper == true) {
                    $calificacionesPendientes=DB::table('shipment')
                        ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'shipment.shippingrequestid')
                        ->leftJoin('serviceoffer', 'serviceoffer.id', '=', 'shipment.acceptedserviceofferid')
                        ->leftJoin('shipper', 'shipper.id', '=', 'serviceoffer.shipperid')
                        ->where('shippingrequest.status','=',4)
                        ->where('shipper.personid','=',$idPerson)
                        ->whereRaw(DB::raw('shipment.clienthasfeedback is not true'))
                        ->count();
                    if($calificacionesPendientes>0){
                         $iconoAlerta="fa fa-star";
                         $tituloAlerta=trans("leng.Envíos sin calificar");
                         $subtituloAlerta=$calificacionesPendientes." ". trans("leng.pendiente(s)");
                         $cadenaWarnings.="<li>
                                                    <a href='".url("/transportista/mis-envios") ."'  class='noticebar-item'>
                                                        <span class='noticebar-item-image'>
                                                          <i class='$iconoAlerta text-success'></i>
                                                        </span>
                                                        <span class='noticebar-item-body'>
                                                          <strong class='noticebar-item-title'>$tituloAlerta</strong>
                                                          <span class='noticebar-item-text'>$subtituloAlerta</span>
                                                        </span>
                                                    </a>
                                                </li>";
                        
                        
                    }
                    
                    
                    
                } else {
                    $calificacionesPendientes=DB::table('shipment')
                        ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'shipment.shippingrequestid')
                        ->where('shippingrequest.status','=',4)
                        ->where('shippingrequest.requesterid','=',$idPerson)
                        ->whereRaw(DB::raw('shipment.shipperhasfeedback is not true'))
                        ->count();
                    if($calificacionesPendientes>0){
                         $iconoAlerta="fa fa-star";
                         $tituloAlerta=trans("leng.Envíos sin calificar");
                         $subtituloAlerta=$calificacionesPendientes." ". trans("leng.pendiente(s)");
                         $cadenaWarnings.="<li>
                                                    <a href='".url("/cliente/misenvios") ."'  class='noticebar-item'>
                                                        <span class='noticebar-item-image'>
                                                          <i class='$iconoAlerta text-success'></i>
                                                        </span>
                                                        <span class='noticebar-item-body'>
                                                          <strong class='noticebar-item-title'>$tituloAlerta</strong>
                                                          <span class='noticebar-item-text'>$subtituloAlerta</span>
                                                        </span>
                                                    </a>
                                                </li>";
                        
                        
                    }
                    
                }
                
                $totalAlertas=$totalAlertas+$calificacionesPendientes;
                if($totalAlertas==0){
                       $cadenaWarnings= "<li class='noticebar-empty'>
                            <h4 class='noticebar-empty-title'>".trans("leng.No hay alertas nuevas") .".</h4>
                      </li>";
                }
                    
                   
                   
                    $respuesta="<li class='dropdown'>
                    <a href='./page-notifications.html' class='dropdown-toggle' data-toggle='dropdown'>
                      <i class='fa fa-exclamation-triangle'></i>
                      <span class='navbar-visible-collapsed'>&nbsp;" . trans('leng.Alertas')."&nbsp;</span>
                      <span class='badge'>$calificacionesPendientes</span>
                    </a>
                    <ul class='dropdown-menu noticebar-menu' role='menu'>
                      <li class='nav-header'>
                        <div class='pull-left'>".trans('leng.Alertas')."</div>
                      </li>
                      
                      $cadenaWarnings
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
    
    
    /* Cambia la contraseña del usuario
     * Autor: OT
     * Fecha: 15-07-2016
    */
    public function guardarCambioContrasena(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        $fechaActual=date("Y-m-d H:i:s");
        $passAnterior=trim($request["passAnterior"]);
        $passNueva=trim($request["passNueva"]);
        $passNuevaConfirmar=trim($request["passNuevaConfirmar"]);
        
        $existeUsuario=DB::table('users')->where('personid','=',$idPerson)->where('password','=', bcrypt($passAnterior))->count();
        
        if(!Auth::attempt(['password'=>$passAnterior,'personid'=>$idPerson])){
           return "errorpassanterior";
        }
        
        if($passNueva!=$passNuevaConfirmar){
            return "nocoincidenpass";
        }
        
        try{
               DB::beginTransaction();
               
               DB::table('users')->where('personid', $idPerson)
                        ->update(['password' =>bcrypt($passNueva),
                                  'updated_at'=>$fechaActual,
                                ]);
               
               DB::table('person')->where('id', $idPerson)
                        ->update([
                                  'updated'=>$fechaActual,
                                ]);
               
               DB::commit();
       
               return "ok";
           } catch (Exception $ex) {
               DB::rollback();
               return $ex;
           }
    }
    
    
    /* Muestra las preguntas hechas del usuario loguedado
     * Autor: OT
     * Fecha: 09-06-2016
    */
    public function misPreguntas(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            $dataPerson=V_person::find($idPerson);

            if(count($dataPerson)>0){
               if($dataPerson->isshipper==true){
                    return view('vvpreguntas.misPreguntasTransportista');
               }else{
                    return view('vvpreguntas.misPreguntasCliente');
                }
            }
            
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
       
        
    }
    
    
    /* Listado de preguntas recibidas cliente
     * Autor: OT
     * Fecha: 22-06-2016
    */
    public function listaPreguntasRecibidasCliente(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
       $buscatitulo=trim($request["buscaTitulo"]);
        $fecha1=$request["fecha1"];
        $fecha2=$request["fecha2"];
        $respodidas=$request["respodidas"];
        
        
        $filtrosStatus="";
        if($respodidas=="true"){
             $filtrosStatus.=" and question.respondedat is  null";
        }
        
        
        $filtroFechas="";
        if($fecha1!=""){
            $filtroFechas.=" and date(question.createdat)>= '$fecha1'";
        }
        
        if($fecha2!=""){
            $filtroFechas.=" and date(question.createdat)<= '$fecha2'";
        }
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (shippingrequest.title ilike'%$buscatitulo%'
                or trim(to_char(shippingrequest.id,'999999999')) ilike'%$buscatitulo%'
                or question.body ilike'%$buscatitulo%'
                or person.company ilike'%$buscatitulo%'
               )";
        }
        
       
        $sWhere="shippingrequest.requesterid=$idPerson" .$filtroFechas. $filtroTitulo.$filtrosStatus;
                
        
        $listaPreguntas = Question::select(["person.company","shippingrequest.id as idenvio","question.body","question.id","shippingrequest.title","question.createdat","question.answer","question.respondedat"])
            ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'question.shippingrequestid')
            ->leftJoin('shipper', 'shipper.id', '=', 'question.shipperid')
            ->leftJoin('person', 'person.id', '=', 'shipper.personid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("question.createdat","desc")
            ->get();
        
        
        
        return Datatables::of($listaPreguntas)
        ->addColumn('transportista', function ($listaPreguntas) {
            $emisorPregunta="";
            if(strlen ($listaPreguntas->company)>50){
                $emisorPregunta.= " " . substr(ucfirst($listaPreguntas->company),0,50)."...";
            }else{
                $emisorPregunta.= " " . ucfirst($listaPreguntas->company);
            }
            
            $ca="<font class='flet-lab'>". $emisorPregunta ."</font>";
            return $ca;
         })
        ->addColumn('envio', function ($listaPreguntas) {
            $tituloEnvivo="Ref ".$listaPreguntas->idenvio;
            if(strlen ($listaPreguntas->title)>50){
                $tituloEnvivo.= " " . substr(ucfirst($listaPreguntas->title),0,50)."...";
            }else{
                $tituloEnvivo.= " " . ucfirst($listaPreguntas->title);
            }
            
            $ca="<font class='flet-lab'>". $tituloEnvivo ."</font>";
            return $ca;
         })
         ->addColumn('pregunta', function ($listaPreguntas) {
            
            if(strlen ($listaPreguntas->body)>50){
                $tituloPregunta= substr(ucfirst($listaPreguntas->body),0,50)."...";
            }else{
                $tituloPregunta= ucfirst($listaPreguntas->body);
            }
            if($listaPreguntas->answer!=""){
                $ca="<font class='flet-lab'><i class='fa fa-reply'></i>&nbsp;&nbsp;". $tituloPregunta."</font>";
            }else{
                $ca="<font class='flet-lab'>". $tituloPregunta."</font>";
            }
                
            
            return $ca;
         })
         
         ->addColumn('fecha', function ($listaPreguntas) {
            $ca="<font class='flet-lab'>". Funciones::fechaF1Hora($listaPreguntas->createdat) ."</font>";
            return $ca;
         })
         
         ->addColumn('ver', function ($listaPreguntas) {
             $ca="<button type='button' onclick='mostrarPeguntaTransportista(".$listaPreguntas->id.")' class='btn btn-secondary btn-sm'><i class='fa fa-eye'></i></button>";
            return $ca;
         })
         
        ->make(true);
    }
    
    
    /* Listado de preguntas hechas por el trasportista
     * Autor: OT
     * Fecha: 22-06-2016
    */
    public function listaPreguntasRealizadasTransportista(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            $idTransportista=0;
            $dataShipper= Shipper::where('personid',$idPerson)->get();
            if(count($dataShipper)>0){
                foreach($dataShipper as $rowShipper){
                    $idTransportista=$rowShipper->id;
                }
            }else{
                return redirect('transportista');
            }
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
        
       $buscatitulo=trim($request["buscaTitulo"]);
        $fecha1=$request["fecha1"];
        $fecha2=$request["fecha2"];
        $respodidas=$request["respondidas"];
        
        
        $filtrosStatus="";
        if($respodidas=="true"){
             $filtrosStatus.=" and question.answer!=''";
        }
        
        
        $filtroFechas="";
        if($fecha1!=""){
            $filtroFechas.=" and date(question.createdat)>= '$fecha1'";
        }
        
        if($fecha2!=""){
            $filtroFechas.=" and date(question.createdat)<= '$fecha2'";
        }
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (shippingrequest.title ilike'%$buscatitulo%'
                or trim(to_char(shippingrequest.id,'999999999')) ilike'%$buscatitulo%'
                or question.body ilike'%$buscatitulo%'
                or person.company ilike'%$buscatitulo%'
               )";
        }
        
       
        $sWhere="question.shipperid=$idTransportista" .$filtroFechas. $filtroTitulo.$filtrosStatus;
                
        
        $listaPreguntas = Question::select(["person.company","shippingrequest.id as idenvio","question.body","question.id","shippingrequest.title","question.createdat","question.answer","question.respondedat"])
            ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'question.shippingrequestid')
            ->leftJoin('person', 'person.id', '=', 'shippingrequest.requesterid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("question.createdat","desc")
            ->get();
        
        
        
        return Datatables::of($listaPreguntas)
        ->addColumn('cliente', function ($listaPreguntas) {
            $emisorPregunta="";
            if(strlen ($listaPreguntas->company)>50){
                $emisorPregunta.= " " . substr(ucfirst($listaPreguntas->company),0,50)."...";
            }else{
                $emisorPregunta.= " " . ucfirst($listaPreguntas->company);
            }
            
            $ca="<font class='flet-lab'>". $emisorPregunta ."</font>";
            return $ca;
         })
        ->addColumn('envio', function ($listaPreguntas) {
            $tituloEnvivo="Ref ".$listaPreguntas->idenvio;
            if(strlen ($listaPreguntas->title)>50){
                $tituloEnvivo.= " " . substr(ucfirst($listaPreguntas->title),0,50)."...";
            }else{
                $tituloEnvivo.= " " . ucfirst($listaPreguntas->title);
            }
            
            $ca="<font class='flet-lab'>". $tituloEnvivo ."</font>";
            return $ca;
         })
         ->addColumn('pregunta', function ($listaPreguntas) {
            
            if(strlen ($listaPreguntas->body)>50){
                $tituloPregunta= substr(ucfirst($listaPreguntas->body),0,50)."...";
            }else{
                $tituloPregunta= ucfirst($listaPreguntas->body);
            }
            if($listaPreguntas->answer!=""){
                $ca="<font class='flet-lab'><i class='fa fa-reply'></i>&nbsp;&nbsp;". $tituloPregunta."</font>";
            }else{
                $ca="<font class='flet-lab'>". $tituloPregunta."</font>";
            }
                
            
            return $ca;
         })
         
         ->addColumn('fecha', function ($listaPreguntas) {
            $ca="<font class='flet-lab'>". Funciones::fechaF1Hora($listaPreguntas->createdat) ."</font>";
            return $ca;
         })
         
         ->addColumn('ver', function ($listaPreguntas) {
             $ca="<button type='button' onclick='mostrarPeguntaTransportistaLista(".$listaPreguntas->id.")' class='btn btn-secondary btn-sm'><i class='fa fa-eye'></i></button>";
            return $ca;
         })
         
        ->make(true);
    }
    
    /* Genera la lista de notificaciones para el administrador
     * Autor: OT
     * Fecha: 09-06-2016
    */
    public function buscarNotificacionesAdmin(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
            try{
                   DB::beginTransaction();
                   
                   $datosSolicitudes=DB::table('grouprequest')
                        ->select(
                                 "person.fullname",
                                 "person.company as compania",
                                 "grouprequest.id as idprincipal",
                                 "grouprequest.createdat as fechaaccion",
                                 "grouprequest.shippingrequestid as idrelacion",
                                 DB::raw("1 as tipo")
                                )
                        ->leftJoin('person', 'person.id', '=', 'grouprequest.personid')
                        ->where('grouprequest.accepted','=',false)
                        ->where('grouprequest.rejected','=',false);
                        //->orderBy("grouprequest.createdat", "desc")
                       
                   
                   $datosCuentas=DB::table('users')
                        ->select("person.fullname",
                                 "person.company as compania",
                                 "users.id as idprincipal",
                                 "users.created_at as fechaaccion",
                                 "users.personid as idrelacion",
                                 DB::raw("2 as tipo")
                                )
                        ->leftJoin('person', 'person.id', '=', 'users.personid')
                        ->where('users.active','=',true)
                        ->where('users.isverified','=',false)
                        ->where('person.isadmin','=',false)
                        ->unionAll($datosSolicitudes)
                        ->orderBy("fechaaccion", "desc")
                        ->get();
                   
                   
                   
                    $notificacionesNuevas=count($datosCuentas);
                    $cadenaNotificaciones="";
                    $iconoAlerta="";
                    $tituloAlerta="";
                    $subtituloAlerta="";
                    if($notificacionesNuevas>0){
                        foreach($datosCuentas as $rowNotificacion){
                           $fecha=Funciones::fechaF1Hora($rowNotificacion->fechaaccion);
                           $idNotificacion=$rowNotificacion->idprincipal;
                           $idRelacion=$rowNotificacion->idrelacion;
                           $tipo=$rowNotificacion->tipo;
                           
                           switch($tipo){
                               case "1": // Solicitudes de transportistas
                                   $iconoAlerta="fa fa-edit";
                                   $solicitante= strlen($rowNotificacion->compania)>30?substr(ucwords($rowNotificacion->compania),0,27)."...":$rowNotificacion->compania;
                                   $tituloAlerta=trans("leng.Solicitud de ingreso");
                                   $subtituloAlerta=$solicitante;
                                   
                                   break;
                               case "2": // Cuentas sin verificar
                                   $iconoAlerta="fa fa-user";
                                   if($rowNotificacion->compania==""){
                                       $nombreContacto=$rowNotificacion->fullname;
                                       $solicitante= strlen($nombreContacto)>30?substr(ucwords($nombreContacto),0,27)."...":$nombreContacto;
                                   }else{
                                       $solicitante= strlen($rowNotificacion->compania)>30?substr(ucwords($rowNotificacion->compania),0,27)."...":$rowNotificacion->compania;
                                   }
                                   $tituloAlerta=trans("leng.Verificar cuenta");
                                   $subtituloAlerta=$solicitante;
                                   break;
                           }
                           
                          $cadenaNotificaciones.="<li>
                                                    <a href='javascript:;' onclick='leerNotificacionesGeneralAdmin($idNotificacion,$tipo);' class='noticebar-item'>
                                                        <span class='noticebar-item-image'>
                                                          <i class='$iconoAlerta text-success'></i>
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
    
    /* Muestra la notificacion del admin
     * Autor: OT
     * Fecha: 05-07-2016
    */
    public function mostrarNotificacionAdmin($idPricipal=0,$tipo=0){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('admin');
        }
        
        if($idPricipal==0 || $tipo==0 ){
            return redirect('admin');
        }
        
        $fechaNotificacion="";
        $solicitante="";
        $idRelacion="";
        $idNotificacion="";
        $tipoCuenta="";
        if($tipo==1){
            $datosNotificaciones=DB::table('grouprequest')->select("person.company","grouprequest.id","grouprequest.createdat","grouprequest.shippingrequestid")
                        ->leftJoin('person', 'person.id', '=', 'grouprequest.personid')
                        ->where('grouprequest.id','=',$idPricipal)
                        ->get();
        
            if(count($datosNotificaciones)==0){
                return redirect('admin');
            }
        
            foreach($datosNotificaciones as $rowNotificacion){
               $fechaNotificacion=Funciones::fechaF1Hora($rowNotificacion->createdat);
               $idNotificacion=$rowNotificacion->id;
               $idRelacion=$rowNotificacion->shippingrequestid;
               $solicitante=  ucfirst($rowNotificacion->company);
            }
        }else if($tipo==2){
            $datosNotificaciones=DB::table('users')->select("users.id","person.company","person.id as idperson","person.firstname",
                    "person.lastname","person.isshipper","users.created_at")
                        ->leftJoin('person', 'person.id', '=', 'users.personid')
                        ->where('users.id','=',$idPricipal)
                        ->get();
        
            if(count($datosNotificaciones)==0){
                return redirect('admin');
            }
        
            foreach($datosNotificaciones as $rowNotificacion){
               $fechaNotificacion=Funciones::fechaF1Hora($rowNotificacion->created_at);
               $idNotificacion=$rowNotificacion->id;
               $idRelacion=0;
               if($rowNotificacion->isshipper){
                   $tipoCuenta=trans("leng.Transportista");
                   $solicitante=  ucfirst($rowNotificacion->company);
               }else{
                   if($rowNotificacion->company==""){
                       $tipoCuenta=trans("leng.Cliente - Personal");
                       $solicitante=  ucfirst($rowNotificacion->firstname . " " . $rowNotificacion->lastname);
                   }else{
                       $tipoCuenta=trans("leng.Cliente - Negocio");
                       $solicitante=  ucfirst($rowNotificacion->company);
                   }
               }
               
            }
        }
        
        
        $datosAlerta=array();
        $datosAlerta["solicitante"]=$solicitante;
        $datosAlerta["fechaAccion"]=$fechaNotificacion;
        $datosAlerta["idEnvio"]=$idRelacion;
        $datosAlerta["idsolicitud"]=$idNotificacion;
        $datosAlerta["tipo"]=$tipo;
        $datosAlerta["tipoCuenta"]=$tipoCuenta;
        
        
        
        return view('vvnotificaciones.mostrarNotificacionAdmin')->with("datosAlerta",$datosAlerta);
    }
    


    

}