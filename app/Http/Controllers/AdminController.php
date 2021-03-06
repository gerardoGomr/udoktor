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
use Udoktor\Funciones;
use Udoktor\V_service;
use Auth;
use Udoktor\Http\Controllers\Controller;
use DB;
use Yajra\Datatables\Datatables;
use Mail;
use Udoktor\V_classifications;

class AdminController extends Controller{

    /* Muestra la pantalla principal del administrador
     * Autor OT
     * Fecha 25-07-2016
     */
    public function Dashboard(){
        if (Auth::check()) {
            return view('vvadmin.index');
        } else {
            return view('login.login');
        }
    }
    
    
    /* Muestra la pantalla de listado de clasificaciones
     * Autor: OT
     * Fecha: 14-12-2016
    */
    public function clasificacion(){
        return view('vvadmin.clasificacion');
    }
    
    
    /* Genera de datatable de clasificacion
     * Autor: OT
     * Fecha: 14-12-2016
    */
    public function listaClasificacion(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $buscatitulo=trim($request["buscaTitulo"]);
        $activo=trim($request["activo"]);
        $inactivo=trim($request["inactivo"]);
        
        $filtroTitulo="";
        $filtrosStatus="";
        $primerFiltro=0;
        
        if($buscatitulo!=""){
            $filtroTitulo.=" and (classifications.name ilike'%$buscatitulo%' or classifications.description ilike'%$buscatitulo%' )";
        }
        
        
        if($activo=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (classifications.active=true";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or classifications.active=true";
            }
        }

        if($inactivo=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (classifications.active=false";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or classifications.active=false";
            }
        }

        if($primerFiltro==1)$filtrosStatus.=")";
        
        $sWhere="1=1 ". $filtroTitulo . $filtrosStatus;
                
        $datosClasificacion = V_classifications::whereRaw(DB::raw($sWhere))
            ->orderBy("name","asc")
            ->get();
        
        return Datatables::of($datosClasificacion)
                
        ->addColumn('name', function ($datosClasificacion) {
            $ca="<font class='flet-lab'>".$datosClasificacion->name."</font>";
            
            return $ca;
         })
         
         ->addColumn('descripcion', function ($datosClasificacion) {
            
            $ca="<font class='flet-lab'>".$datosClasificacion->description."</font>";
            
            return $ca;
         })
         
         ->addColumn('estado', function ($datosClasificacion) {
            
             if($datosClasificacion->active==true){
                 $vestado=trans("leng.Activo");
             }else{
                 $vestado=trans("leng.Inactivo");
             }
            
            $ca="<font class='flet-lab'>".$vestado."</font>";
            return $ca;
         })
         
         ->addColumn('acciones', function ($datosClasificacion) {
            $ca="<button type='button' class='btn btn-secondary btn-xs' style='width:25px;' onclick='editarClasificacion(".$datosClasificacion->id.")' title='".trans('leng.Editar clasificación')."'><i class='fa fa-pencil-square-o'></i></button>&nbsp;";
            
            if($datosClasificacion->active==true){
                $ca.="<button type='button' class='btn btn-danger btn-xs' style='width:25px;' onclick='activarDesactivarClasificacion(".$datosClasificacion->id.",1)' title='".trans('leng.Desactivar clasificación')."'><i class='fa fa-times'></i></button>&nbsp;";
            }else{
                $ca.="<button type='button' class='btn btn-success btn-xs' style='width:25px;' onclick='activarDesactivarClasificacion(".$datosClasificacion->id.",0)' title='".trans('leng.Activar clasificación')."'><i class='fa fa-check'></i></button>&nbsp;";
            }
            
            return $ca;
         })
         
        ->make(true);
    }

    
    /* Nueva clasificacion
     * Autor: OT
     * Fecha: 14-12-2016
    */
    public function nuevaClasificacion(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $datosVehiculo=array();
        $datosVehiculo["id"]="";
        $datosVehiculo["name"]="";
        $datosVehiculo["description"]="";
       
        return view('vvadmin.clasificacionEdicion')->with("datosVehiculo",$datosVehiculo);
    }
    
    /* Guarda el tipo vehiculo
     * Autor OT
     * Fecha: 14-12-2016
     */
    public function guardarClasificacion(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $nombreClasificacion=$request["nombreClasificacion"];
        $descripcionClasificacion=$request["descripcionClasificacion"];
        $idClasificacion=$request["idClasificacion"];
        
        if($idClasificacion==0){
            $existeNombre=DB::table('classifications')
                    ->whereRaw(DB::raw("upper(name)='".strtoupper($nombreClasificacion)."'"))
                    ->count();
            
            if($existeNombre>0){
                return "existenombre";
            }
        }else{
            $existeNombre=DB::table('classifications')
                    ->whereRaw(DB::raw("upper(name)='".strtoupper($nombreClasificacion)."'"))
                    ->where("id","<>",$idClasificacion)
                    ->count();
            
            if($existeNombre>0){
                return "existenombre";
            }
        }
        
        try{
            DB::beginTransaction();
            
            if($idClasificacion==0){
                $clasificacion=V_classifications::create([
                    'name'=>$nombreClasificacion,
                    'description'=>$descripcionClasificacion,
                    'active'=>TRUE,
                    'created'=>$fechaActual,
                    'updated'=>$fechaActual,
               ]);
            }else{
                V_classifications::where('id', $idClasificacion)->update([
                    'name'=>$nombreClasificacion,
                    'description'=>$descripcionClasificacion,
                    'updated'=>$fechaActual,
                ]);
            }
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Editar clasificacion
     * Autor: OT
     * Fecha: 14-12-2016
    */
    public function editarClasificacion(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $idClasificacion=$request["idClasificacion"];
        $datosVehiculo=array();
        
        $dClasificacion= V_classifications::where("id",$idClasificacion)->get();
        foreach($dClasificacion as $rowClasificacion){
            $datosVehiculo["id"]=$rowClasificacion->id;
            $datosVehiculo["name"]=$rowClasificacion->name;
            $datosVehiculo["description"]=$rowClasificacion->description;

        }
        
        return view('vvadmin.clasificacionEdicion')
                ->with("datosVehiculo",$datosVehiculo);
    }
    
    
    /* Activa o desactiva la clasificacion
     * Autor: OT
     * Fecha: 14-12-2016
    */
    public function activarDesactivarClasificacion(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $idClasificacion=$request["idClasificacion"];
        $opcion=$request["opcion"];
        
        $activo=FALSE;
        
        if($opcion==0)$activo=TRUE;
        
        try{
            DB::beginTransaction();
            
            DB::table('classifications')
                   ->where('id', $idClasificacion)
                   ->update([
                            'updated' => $fechaActual,
                            'active' => $activo,
                           ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Muestra la pantalla de listado de servicios
     * Autor: OT
     * Fecha: 29-12-2016
    */
    public function servicios(){
        return view('vvadmin.servicios');
    }
    
    
    /* Genera de datatable de servicios
     * Autor: OT
     * Fecha: 29-12-2016
    */
    public function listaServicios(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $buscatitulo=trim($request["buscaTitulo"]);
        $activo=trim($request["activo"]);
        $inactivo=trim($request["inactivo"]);
        
        $filtroTitulo="";
        $filtrosStatus="";
        $primerFiltro=0;
        
        if($buscatitulo!=""){
            $filtroTitulo.=" and (services.name ilike'%$buscatitulo%' or services.description ilike'%$buscatitulo%' )";
        }
        
        
        if($activo=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (services.active=true";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or services.active=true";
            }
        }

        if($inactivo=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (services.active=false";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or services.active=false";
            }
        }

        if($primerFiltro==1)$filtrosStatus.=")";
        
        $sWhere="1=1 ". $filtroTitulo . $filtrosStatus;
                
        $datosServicios = V_service::whereRaw(DB::raw($sWhere))->orderBy("name","asc")->get();
        
        return Datatables::of($datosServicios)
                
        ->addColumn('name', function ($datosServicios) {
            $ca="<font class='flet-lab'>".$datosServicios->name."</font>";
            
            return $ca;
         })
         
         ->addColumn('descripcion', function ($datosServicios) {
            
            $ca="<font class='flet-lab'>".$datosServicios->description."</font>";
            
            return $ca;
         })
         
         ->addColumn('sugerido', function ($datosServicios) {
            if($datosServicios->price=="" && $datosServicios->price==0){
                $ca="";
            }else{
                $ca="<font class='flet-lab'>".Funciones::formato_numeros($datosServicios->price, ",", ".")."</font>";
            }
            
            return $ca;
         })
         
         
         ->addColumn('min', function ($datosServicios) {
             if($datosServicios->price=="" && $datosServicios->price==0){
                $ca="";
            }else{
                $ca="<font class='flet-lab'>".Funciones::formato_numeros($datosServicios->minprice, ",", ".")."</font>";
            }
            
            return $ca;
         })
         
         ->addColumn('max', function ($datosServicios) {
             
           if($datosServicios->price=="" && $datosServicios->price==0){
                $ca="";
            }else{
                $ca="<font class='flet-lab'>".Funciones::formato_numeros($datosServicios->maxprice, ",", ".")."</font>";
            }

            return $ca;
         })
         
         
         
         ->addColumn('estado', function ($datosServicios) {
            
             if($datosServicios->active==true){
                 $vestado=trans("leng.Activo");
             }else{
                 $vestado=trans("leng.Inactivo");
             }
            
            $ca="<font class='flet-lab'>".$vestado."</font>";
            return $ca;
         })
         
         ->addColumn('acciones', function ($datosServicios) {
            $ca="<button type='button' class='btn btn-secondary btn-xs' style='width:25px;' onclick='editarServicioAdmin(".$datosServicios->id.")' title='".trans('leng.Editar servicio')."'><i class='fa fa-pencil-square-o'></i></button>&nbsp;";
            
            if($datosServicios->active==true){
                $ca.="<button type='button' class='btn btn-danger btn-xs' style='width:25px;' onclick='activarDesactivarServicio(".$datosServicios->id.",1)' title='".trans('leng.Desactivar servicio')."'><i class='fa fa-times'></i></button>&nbsp;";
            }else{
                $ca.="<button type='button' class='btn btn-success btn-xs' style='width:25px;' onclick='activarDesactivarServicio(".$datosServicios->id.",0)' title='".trans('leng.Activar servicio')."'><i class='fa fa-check'></i></button>&nbsp;";
            }
            
            return $ca;
         })
         
        ->make(true);
    }
    
    /* Nuevo servicio
     * Autor: OT
     * Fecha: 30-12-2016
    */
    public function nuevoServicioAdmin(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $datosServicio=array();
        $datosServicio["id"]=0;
        $datosServicio["name"]="";
        $datosServicio["description"]="";
        $datosServicio["sugerido"]="";
        $datosServicio["minimo"]="";
        $datosServicio["maximo"]="";
       
        return view('vvadmin.servicioEdicion')->with("datosServicio",$datosServicio);
    }
    
    /* Guarda el servicio
     * Autor: OT
     * Fecha: 30-12-2016
    */
    public function guardarServicioAdmin(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $nombreServicio=$request["nombreServicio"];
        $descripcionServicio=$request["descripcionServicio"];
        $sugerido=$request["sugerido"];
        $minimo=$request["minimo"];
        $maximo=$request["maximo"];
        $idServicio=$request["idServicio"];
        
        if($idServicio==0){
            $existeNombre=DB::table('services')
                    ->whereRaw(DB::raw("upper(name)='".strtoupper($nombreServicio)."'"))
                    ->whereNull("deleted")
                    ->count();
            
            if($existeNombre>0){
                return "existenombre";
            }
        }else{
            $existeNombre=DB::table('services')
                    ->whereRaw(DB::raw("upper(name)='".strtoupper($nombreServicio)."'"))
                    ->where("id","<>",$idServicio)
                    ->whereNull("deleted")
                    ->count();
            
            if($existeNombre>0){
                return "existenombre2";
            }
        }
        
        try{
            DB::beginTransaction();
            
            if($idServicio==0){
                $servicio=  V_service::create([
                    'name'=>$nombreServicio,
                    'description'=>$descripcionServicio,
                    'active'=>TRUE,
                    'price'=>$sugerido,
                    'minprice'=>$minimo,
                    'maxprice'=>$maximo,
                    'created'=>$fechaActual,
                    'updated'=>$fechaActual,
               ]);
            }else{
                V_service::where('id', $idServicio)->update([
                    'name'=>$nombreServicio,
                    'description'=>$descripcionServicio,
                    'price'=>$sugerido,
                    'minprice'=>$minimo,
                    'maxprice'=>$maximo,
                    'updated'=>$fechaActual,
                ]);
            }
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Editar servicio
     * Autor: OT
     * Fecha: 30-12-2016
    */
    public function editarServicioAdmin(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect("admin");   
        }
        
        $idServicio=$request["idServicio"];
        $datosServicio=array();
        
        $dServicio= V_service::where("id",$idServicio)->get();
        foreach($dServicio as $rowServicio){
            $datosServicio["id"]=$rowServicio->id;
            $datosServicio["name"]=$rowServicio->name;
            $datosServicio["description"]=$rowServicio->description;
            $datosServicio["sugerido"]=$rowServicio->price;
            $datosServicio["minimo"]=$rowServicio->minprice;
            $datosServicio["maximo"]=$rowServicio->maxprice;

        }
        
        return view('vvadmin.servicioEdicion')->with("datosServicio",$datosServicio);
    }
    
    
    /* Activa o desactiva servicio
     * Autor: OT
     * Fecha: 14-12-2016
    */
    public function activarDesactivarServicio(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $idServicio=$request["idServicio"];
        $opcion=$request["opcion"];
        
        $activo=FALSE;
        
        if($opcion==0)$activo=TRUE;
        
        try{
            DB::beginTransaction();
            
            DB::table('services')
                   ->where('id', $idServicio)
                   ->update([
                            'updated' => $fechaActual,
                            'active' => $activo,
                           ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    
}