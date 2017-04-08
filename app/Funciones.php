<?php

namespace Udoktor;
use DB;
use Mail;
use Auth;
use Udoktor\ShipperAccount;
use Udoktor\Person;
use Udoktor\ShipperAccountDetail;
use Illuminate\Database\Eloquent\Model;
use Udoktor\ShippingRequest;

class Funciones extends Model
{
    /*
     * Esta funcion reemplaza letras acentuadas, elimina caracteres especiales,
     * quita los espacios y regresa la cadena en minusculas.
     */
    public static function limpiarCadena($cadena)
    {
		$cadena = preg_replace("/á|à|â|ã|ª/","a",$cadena);
		$cadena = preg_replace("/Á|À|Â|Ã/","A",$cadena);
		$cadena = preg_replace("/é|è|ê/","e",$cadena);
		$cadena = preg_replace("/É|È|Ê/","E",$cadena);
		$cadena = preg_replace("/í|ì|î/","i",$cadena);
		$cadena = preg_replace("/Í|Ì|Î/","I",$cadena);
		$cadena = preg_replace("/ó|ò|ô|õ|º/","o",$cadena);
		$cadena = preg_replace("/Ó|Ò|Ô|Õ/","O",$cadena);
		$cadena = preg_replace("/ú|ù|û/","u",$cadena);
		$cadena = preg_replace("/Ú|Ù|Û/","U",$cadena);
		//$cadena = str_replace(" ","",$cadena);
		$cadena = str_replace("ñ","n",$cadena);
		$cadena = str_replace("Ñ","N",$cadena);
		
		$cadena = preg_replace('/[^a-zA-Z0-9_.-]/', '', $cadena);
                $cadena= strtolower($cadena);
                
		return $cadena;
    }
    
    /*
     * Esta funcion reemplaza letras acentuadas, elimina caracteres especiales,
     * quita los espacios y regresa la cadena en minusculas.
     */
    public static function limpiarCadena2($cadena)
    {
        
		$cadena = preg_replace("/á|à|â|ã|ª/","a",$cadena);
		$cadena = preg_replace("/Á|À|Â|Ã/","A",$cadena);
		$cadena = preg_replace("/é|è|ê/","e",$cadena);
		$cadena = preg_replace("/É|È|Ê/","E",$cadena);
		$cadena = preg_replace("/í|ì|î/","i",$cadena);
		$cadena = preg_replace("/Í|Ì|Î/","I",$cadena);
		$cadena = preg_replace("/ó|ò|ô|õ|º/","o",$cadena);
		$cadena = preg_replace("/Ó|Ò|Ô|Õ/","O",$cadena);
		$cadena = preg_replace("/ú|ù|û/","u",$cadena);
		$cadena = preg_replace("/Ú|Ù|Û/","U",$cadena);
		//$cadena = str_replace(" ","",$cadena);
		$cadena = str_replace("ñ","n",$cadena);
		$cadena = str_replace("Ñ","N",$cadena);
		
		//$cadena = preg_replace('/[^a-zA-Z0-9_.-]/', '', $cadena);
                $cadena= strtolower($cadena);
                
		return $cadena;
    }
    
    /*
     * Esta funcion recibe un string y devuele la cadena con formato miles.
     */
    public static function formato_numeros ($numero,$miles,$fraccion){
        $numero=number_format((float)$numero, 2, '.', '');//cuando el numero no tine deciales, forza .00
        $numero=str_replace('.','',$numero); // Eliminar el punto de las fracciones
        $miles; // Separador de  miles
        $fraccion; // Separador fracciones
        $decimales=2; //Numero de decimales a mostrar
        $decimalesDIV=100; // Regresar los deciamles
        return number_format($numero / $decimalesDIV,$decimales, $fraccion, $miles);
    }
    
    /*
     * Esta funcion reemplaza letras acentuadas, elimina caracteres especiales
     */
    
    public static function enesyacentos($cadena)
    {
        
		$cadena = preg_replace("/á|à|â|ã|ª/","a",$cadena);
		$cadena = preg_replace("/Á|À|Â|Ã/","A",$cadena);
		$cadena = preg_replace("/é|è|ê/","e",$cadena);
		$cadena = preg_replace("/É|È|Ê/","E",$cadena);
		$cadena = preg_replace("/í|ì|î/","i",$cadena);
		$cadena = preg_replace("/Í|Ì|Î/","I",$cadena);
		$cadena = preg_replace("/ó|ò|ô|õ|º/","o",$cadena);
		$cadena = preg_replace("/Ó|Ò|Ô|Õ/","O",$cadena);
		$cadena = preg_replace("/ú|ù|û/","u",$cadena);
		$cadena = preg_replace("/Ú|Ù|Û/","U",$cadena);
		$cadena = str_replace("ñ","n",$cadena);
		$cadena = str_replace("Ñ","N",$cadena);
                
		return $cadena;
    }
    
    /*
     * Esta funcion recibe una fecha formato YYYY-mm-dd y retorna el string ('dia' de 'mes' de 'año')
     */
    
    public static function fechaF1($fecha){
        $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
        $meses = array("January"=>"Ene.","February"=>"Feb.","March"=>"Mar.","April"=>"Abr.","May"=>"May.","June"=>"Jun.","July"=>"Jul.","August"=>"Ago.","September"=>"Sept.","October"=>"Oct.","November"=>"Nov.","December"=>"Dic.");
        
        $fechaFormato="";
        $fecha=  strtotime($fecha);
        $fechaDia=strftime("%A", $fecha);
        $fechaDiaNumero=strftime("%d", $fecha);
        $fechaMes=strftime("%B", $fecha);
        $fechaAnio=strftime("%Y", $fecha);

        $fechaFormato=$fechaDiaNumero . " " .trans("leng.de")." ". trans('leng.'.$meses[$fechaMes]) . " ". trans("leng.de")." " . $fechaAnio;
        
   
        return $fechaFormato;
    }
    
    /*
     * Esta funcion recibe una fecha formato YYYY-mm-dd y retorna el string (dia-NombreMes-Año)
     */
    
    public static function fechaF2($fecha){
        $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
        $meses = array("January"=>"Ene.","February"=>"Feb.","March"=>"Mar.","April"=>"Abr.","May"=>"May.","June"=>"Jun.","July"=>"Jul.","August"=>"Ago.","September"=>"Sept.","October"=>"Oct.","November"=>"Nov.","December"=>"Dic.");
        
        $fechaFormato="";
        $fecha=  strtotime($fecha);
        $fechaDia=strftime("%A", $fecha);
        $fechaDiaNumero=strftime("%d", $fecha);
        $fechaMes=strftime("%B", $fecha);
        $fechaAnio=strftime("%Y", $fecha);

        $fechaFormato=$fechaDiaNumero . " - ". trans('leng.'.$meses[$fechaMes]) . " - " . $fechaAnio;
        
   
        return $fechaFormato;
    }
    
    
    /*
     * Recibe cadena hora formato 10:00 AM/PM
     * Retorna hora formato 24H
     * Autor: OT
     * Fecha 30-06-2016
     */
    
    public static function hora24H($cadenaHora){
        $cadenaHora=str_replace(" ",":",$cadenaHora);
        list($vHora,$vMinuto,$tipo)= explode(":", $cadenaHora);
        
        if($tipo=="AM" && ($vHora*1)==12){
              $vHora="00";
        }else if($tipo=="PM" && ($vHora*1)!=12){
              $vHora=($vHora*1)+12;
        }
        
            $cadenaHora=$vHora.":".$vMinuto.":00";
   
        return $cadenaHora;
    }
    
    /*
     * Esta funcion recibe una fecha formato YYYY-mm-dd hh:mm:ss y retorna el string ('dia' de 'mes' de 'año' hh:mm:ss)
     */
    
    public static function fechaF1Hora($fecha){
        $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
        $meses = array("January"=>"Ene.","February"=>"Feb.","March"=>"Mar.","April"=>"Abr.","May"=>"May.","June"=>"Jun.","July"=>"Jul.","August"=>"Ago.","September"=>"Sept.","October"=>"Oct.","November"=>"Nov.","December"=>"Dic.");
        
        $fechaFormato="";
        $hora=  substr($fecha, 11,8);
        $fecha=  strtotime($fecha);
        $fechaDia=strftime("%A", $fecha);
        $fechaDiaNumero=strftime("%d", $fecha);
        $fechaMes=strftime("%B", $fecha);
        $fechaAnio=strftime("%Y", $fecha);

        $fechaFormato=$fechaDiaNumero . " " .trans("leng.de")." ". trans('leng.'.$meses[$fechaMes]) . " ". trans("leng.de")." " . $fechaAnio . " " . $hora;
        
        return $fechaFormato;
    }
    
    /*
     * Enviar Alerta
     * Autor: OT
     * Fecha: 05-07-2016
     */
    public static function enviarAlerta($destinatario,$tipo,$idRelacion="",$idRelacion2="",$texto=""){
        $fechaActual=date("Y-m-d H:i:s");
        if($idRelacion=="")$idRelacion=null;
        if($idRelacion2=="")$idRelacion2=null;
        if($destinatario>0){
            $alerta=Alert::create([
                         'recipientid'=>$destinatario,
                         'createdat'=>$fechaActual,
                         'updated'=>$fechaActual,
                         'type'=>$tipo,
                         'relationid'=>$idRelacion,
                         'relationid2'=>$idRelacion2
                     ]);
        
                /* Tipo
                   * 1= Lo ve el prestador de servicios, nueva cita.
                   * 2= Lo ve el cliente, confirmacion de cita.
                   * 3= Lo ve el cliente, cita rechazada.
                   * 4= Lo ve el prestador, cita cancelada por el cliente.
                */
            
                $sWhere="";
                $mensaje="";
                $fechaAccion="";
                $fechaCita="";
                $horaCita="";
                $nombreCliente="";
                $nombrePrestador="";
                $texto="";
                switch($tipo){
                    case 1: // nueva  cita
                            $sWhere="person.newdate=true";
                            $motivoCorreo=trans("leng.Nueva cita");
                            $fechaAccion=Funciones::fechaF1Hora($fechaActual);
                            
                            $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate")
                              ->leftJoin("person","person.id","=","dates.client")
                              ->where("dates.id","=",$idRelacion)
                              ->get();
                            foreach($datosCita as $rowCita){
                                $fechaCita=$rowCita->date;
                                $horaCita=$rowCita->timedate;
                                $nombreCliente=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                            }
                        break;
                     case 2: // cita confirmada
                            $sWhere="person.confirmationdate=true";
                            $motivoCorreo=trans("leng.Cita confirmada");
                            $fechaAccion=Funciones::fechaF1Hora($fechaActual);
                            
                            $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate")
                              ->leftJoin("person","person.id","=","dates.serviceprovider")
                              ->where("dates.id","=",$idRelacion)
                              ->get();
                            foreach($datosCita as $rowCita){
                                $fechaCita=$rowCita->date;
                                $horaCita=$rowCita->timedate;
                                $nombrePrestador=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                            }
                        break;
                    
                    case 3: // cita cancelada por el prestador del servicio
                            $sWhere="person.rejectiondate=true";
                            $motivoCorreo=trans("leng.Cita rechazada");
                            $fechaAccion=Funciones::fechaF1Hora($fechaActual);
                            
                            $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate","dates.reasonrejection")
                              ->leftJoin("person","person.id","=","dates.serviceprovider")
                              ->where("dates.id","=",$idRelacion)
                              ->get();
                            foreach($datosCita as $rowCita){
                                $fechaCita=$rowCita->date;
                                $horaCita=$rowCita->timedate;
                                $nombrePrestador=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                                $texto=  ucfirst($rowCita->reasonrejection);
                            }
                        break;
                   case 4: // cita cancelada por el cliente
                            $sWhere="person.canceldate=true";
                            $motivoCorreo=trans("leng.Cita cancelada");
                            $fechaAccion=Funciones::fechaF1Hora($fechaActual);
                            
                            $datosCita=DB::table('dates')->select("dates.id","person.fullname","person.company","dates.date","dates.timedate")
                              ->leftJoin("person","person.id","=","dates.client")
                              ->where("dates.id","=",$idRelacion)
                              ->get();
                            foreach($datosCita as $rowCita){
                                $fechaCita=$rowCita->date;
                                $horaCita=$rowCita->timedate;
                                $nombreCliente=($rowCita->company=="")?$rowCita->fullname:$rowCita->company;
                            }
                        break;
                }
        
                    
                    $datosUsuario=DB::table('person')->select("person.company","users.email","person.fullname")   
                     ->leftJoin('users', 'users.personid', '=', 'person.id')
                     ->where('person.id','=',$destinatario)
                     ->whereRaw(DB::raw($sWhere))
                     ->get();
                
                        if(count($datosUsuario)>0){
                            $nombreRecibe="";
                            $correo="";
                            foreach($datosUsuario as $rowUsuario){
                                if($rowUsuario->company==""){
                                    $nombreRecibe=  ucwords($rowUsuario->fullname);
                                }else{
                                    $nombreRecibe=$rowUsuario->company;
                                }

                                $correo= $rowUsuario->email;
                            }

                            $datosCorreo=array();
                            $datosCorreo["nombreRecibe"]=$nombreRecibe;
                            $datosCorreo["nombrePrestador"]=$nombrePrestador;
                            $datosCorreo["fechaAccion"]=$fechaAccion;
                            $datosCorreo["fechaCita"]=  Funciones::fechaF2($fechaCita);
                            $datosCorreo["horaCita"]=$horaCita;
                            $datosCorreo["nombreCliente"]=$nombreCliente;
                            $datosCorreo["texto"]=$texto;
                            $datosCorreo["tipo"]=$tipo;

                            Mail::send('correos.notificacion',$datosCorreo,function($msj) use ($correo,$motivoCorreo){
                                   $msj->subject("Notificaciones - ".$motivoCorreo);
                                    $msj->to($correo);
                                });
                        }
        }
        
    }
    
    //recibe la hora en formato 24 horas y devuelve el formato 12 horas
    public static function hora12($hora){
        if($hora!=""){
            $hora=strtotime('2016-01-01 '.$hora);
            $horanueva=date('g:i a',$hora);
            return $horanueva;
        }
        else{
            return "";
        }
    }

    public static function fechaF3($fecha){
        $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
        $meses = array("January"=>"Ene.","February"=>"Feb.","March"=>"Mar.","April"=>"Abr.","May"=>"May.","June"=>"Jun.","July"=>"Jul.","August"=>"Ago.","September"=>"Sept.","October"=>"Oct.","November"=>"Nov.","December"=>"Dic.");
        
        $fechaFormato="";
        $fecha=  strtotime($fecha);
        $fechaDia=strftime("%A", $fecha);
        $fechaDiaNumero=strftime("%d", $fecha);
        $fechaMes=strftime("%B", $fecha);
        $fechaAnio=strftime("%Y", $fecha);

        $fechaFormato=$fechaDiaNumero . " ". trans('leng.'.$meses[$fechaMes]) . " " . $fechaAnio;
        
   
        return $fechaFormato;
    }
    
    /*
     * Envia correo al usuario por estado de su cuenta
     * Autor :OT
     * Fecha: 05-08-2016
     * idUsuario - Destino
     * tipo, 1=Activacion, 2=Verificacion,3=Desactivacion
     */
    public static function enviarMailCuenta ($idUsuario,$tipo){
        $notificacion="";
        
        if($tipo==1){
            $notificacion="Cuenta activada";
        }else if($tipo==2){
            $notificacion="Cuenta verificada";
        }else if($tipo==3){
            $notificacion="Cuenta desactivada";
        }
        
        $fechaActual=date("Y-m-d H:i:s");
        $datosPerson=Person::select(["person.company","person.firstname","person.middlename","person.lastname",
                    "person.secondlastname","person.dni","person.ruc","users.email","person.isshipper"])
                         ->leftJoin('users', 'users.personid', '=', 'person.id')
                         ->where("users.id",$idUsuario)
                         ->get();        
                    
                    $datosCorreo=array();
                    foreach($datosPerson as $datosPer){
                        if($datosPer->company==""){
                            $datosCorreo["usuario"]=  ucfirst($datosPer->firstname). " " . ucfirst($datosPer->lastname);
                        }else{
                            $datosCorreo["usuario"]=  ucfirst($datosPer->company);
                        }
                        $datosCorreo["fecha"]=  Funciones::fechaF1Hora($fechaActual);
                        $correo=$datosPer->email;
                        $datosCorreo["tipo"]=$tipo;
                        $datosCorreo["estransportista"]=$datosPer->isshipper;
                    }
                    
                    Mail::send('correos.estadocuenta',$datosCorreo,function($msj) use ($correo,$notificacion){
                           $msj->subject("Notificaciones Efletex - ".$notificacion);
                            $msj->to($correo);
                        });
    }
    
    
    /*
     * Valida la fecha de recojo del envio y de la oferta
     * Autor: OT
     * Fecha: 23-08-2016
     */
    
    public static function validadFechasOferta($idOferta,$tipo){
         $idEnvio="";
         $fechaDesdeRecoleccionOferta="";
         $fechaHastaRecoleccionOferta="";
         $fechaDesdeEntregaOferta="";
         $fechaHastaEntregarOferta="";
        

                $datosOferta=Ofertas::where("id","=","$idOferta")->get();
                foreach($datosOferta as $rowOferta){
                    
                    $idEnvio=$rowOferta->shippingrequestid;
                    
                    $fechaDesdeRecoleccionOferta=  substr($rowOferta->collectiondate,0,10);
                    $fechaDesdeRecoleccionOferta=  strtotime($fechaDesdeRecoleccionOferta." 00:00:00");
                    
                    if($rowOferta->collectionuntildate!=""){
                        $fechaHastaRecoleccionOferta=substr($rowOferta->collectionuntildate,0,10);
                        $fechaHastaRecoleccionOferta=  strtotime($fechaHastaRecoleccionOferta." 00:00:00");
                    }
                    
                    
                    $fechaDesdeEntregaOferta=substr($rowOferta->deliverydate,0,10);
                    $fechaDesdeEntregaOferta=  strtotime($fechaDesdeEntregaOferta." 00:00:00");
                   
                    if($rowOferta->deliveryuntildate!=""){
                        $fechaHastaEntregarOferta=substr($rowOferta->deliveryuntildate,0,10);
                        $fechaHastaEntregarOferta=  strtotime($fechaHastaEntregarOferta." 00:00:00");
                    }

                }
                
                
                $datosEnvio=DB::table('shippingrequest')->select("collectionaddress.collectiondate",
                        "collectionaddress.collectionuntildate",
                        "deliveryaddress.deliverydate",
                        "deliveryaddress.deliveryuntildate"
                )
                ->leftJoin('collectionaddress', 'collectionaddress.shippingrequestid', '=', 'shippingrequest.id')
                ->leftJoin('deliveryaddress', 'deliveryaddress.shippingrequestid', '=', 'shippingrequest.id')
                ->where('shippingrequest.id','=',$idEnvio)
                ->get();
                
                $fechaDesdeRecogerEnvio="";
                $fechaHastaRecogerEnvio="";
                $fechaDesdeEntregarEnvio="";
                $fechaHastaEntregarEnvio="";
                foreach($datosEnvio as $rowEnvio){
                    
                    $fechaDesdeRecogerEnvio=substr($rowEnvio->collectiondate,0,10);
                    $fechaDesdeRecogerEnvio=  strtotime($fechaDesdeRecogerEnvio." 00:00:00");
                    
                    if($rowEnvio->collectionuntildate!=""){
                        $fechaHastaRecogerEnvio=substr($rowEnvio->collectionuntildate,0,10);
                        $fechaHastaRecogerEnvio=  strtotime($fechaHastaRecogerEnvio." 00:00:00");
                    }
                    
                    
                    $fechaDesdeEntregarEnvio=substr($rowEnvio->deliverydate,0,10);
                    $fechaDesdeEntregarEnvio=  strtotime($fechaDesdeEntregarEnvio." 00:00:00");
                    
                    if($rowEnvio->deliveryuntildate!=""){
                        $fechaHastaEntregarEnvio=substr($rowEnvio->deliveryuntildate,0,10);
                        $fechaHastaEntregarEnvio=  strtotime($fechaHastaEntregarEnvio." 00:00:00");
                    }
                }
                
                $errorFecha=0;

                if($tipo==1){ 
                    /*  Validar fechas de recoleccion */
                
                        if($fechaHastaRecogerEnvio==""){ // si no hay fecha hasta recoger el envio
                           if($fechaHastaRecoleccionOferta==""){ // si no hay fecha hasta recoger en oferta
                               if($fechaDesdeRecoleccionOferta!=$fechaDesdeRecogerEnvio){
                                   $errorFecha=1;
                               }
                           }else{
                               if($fechaDesdeRecoleccionOferta!=$fechaDesdeRecogerEnvio || $fechaHastaRecoleccionOferta!=$fechaDesdeRecogerEnvio){
                                   $errorFecha=2;
                               }
                           }
                        }else{ // si hay fecha hasta recoger envio
                               if($fechaDesdeRecoleccionOferta>=$fechaDesdeRecogerEnvio && $fechaDesdeRecoleccionOferta<=$fechaHastaRecogerEnvio){
                                   if($fechaHastaRecoleccionOferta!=""){ // si  hay fecha hasta recoger en oferta
                                      if($fechaHastaRecoleccionOferta>=$fechaDesdeRecogerEnvio && $fechaHastaRecoleccionOferta<=$fechaHastaRecogerEnvio){
                                          $errorFecha=0;
                                      }else{
                                          $errorFecha=3;
                                      }
                                   }
                               }else{
                                   $errorFecha=4;
                               }
                           }
                }else{
                     /* validar fechas de entrega */ 
                   
                   
                   if($fechaHastaEntregarEnvio==""){ // si no hay fecha hasta recoger el envio
                        if($fechaHastaEntregarOferta==""){ // si no hay fecha hasta recoger en oferta
                            if($fechaDesdeEntregaOferta!=$fechaDesdeEntregarEnvio){
                                $errorFecha=5;
                            }
                        }else{
                            if($fechaDesdeEntregaOferta!=$fechaDesdeEntregarEnvio || $fechaHastaEntregarOferta!=$fechaDesdeEntregarEnvio){
                                $errorFecha=6;
                            }
                        }    
                    }else{ // si hay fecha hasta recoger envio
                       if($fechaDesdeEntregaOferta>=$fechaDesdeEntregarEnvio && $fechaDesdeEntregaOferta<=$fechaHastaEntregarEnvio){
                           if($fechaHastaEntregarOferta!=""){ // si  hay fecha hasta recoger en oferta
                              if($fechaHastaEntregarOferta>=$fechaDesdeEntregarEnvio && $fechaHastaEntregarOferta<=$fechaHastaEntregarEnvio){
                                  $errorFecha=0;
                              }else{
                                  $errorFecha=7;
                              }
                           }
                       }else{
                           $errorFecha=8;
                       }
                   }
                }
                  
                   
        return $errorFecha;
        
    }
    
    
    
    
    /* Obtiene el precio por ofertar
     * Autor: OT
     * Fecha: 29-09-2016
     */
    public static function obtenerPrecioPorOfertar($idPersonTransportista,$idEnvio,$precio){
        
        $respuesta=array();
        
        $precioEnvio=Funciones::obtenerPrecioEnvio($idPersonTransportista,$idEnvio,$precio);
        
        if($precioEnvio=="errorPrecio"){
            $respuesta["error"]=1;
            $respuesta["precioEnvio"]=0;
            return $respuesta; 
        }
        
       $respuesta["error"]=0;
       $respuesta["precioEnvio"]=$precioEnvio;
       return $respuesta; 

    }
    
    
    
    
    /* Valida el saldo del transportista para realizar la oferta
     * Autor: OT
     * Fecha: 21-09-2016
     */
    public static function validarSaldoDisponibleTransportista($idPersonTransportista,$precioPorOfertar){
        
        $respuesta=array();
        
        $dataPerson=Person::find($idPersonTransportista);
        if(count($dataPerson)>0){
             if($dataPerson->isshipper==true){
                 $idShipper=0;
                 $datosChofer=  Shipper::where("personid",$idPersonTransportista)->get();
                 foreach($datosChofer as $chofer){
                    $idShipper= $chofer->id;
                 }
                 $saldoActual=  ShipperAccount::where("shipperid",$idShipper)->get();
                 $vSaldo=0.00;
                 $vDisponible=0.00;
                 foreach($saldoActual as $rSaldo){
                    $vSaldo=$rSaldo->balance;
                    $vDisponible=$rSaldo->available;
                 }
                 if($vDisponible>=$precioPorOfertar){
                        $respuesta["error"]=0;
                        $respuesta["precioEnvio"]=$precioPorOfertar;
                        return $respuesta; 
                 }else{
                     $respuesta["error"]=1;
                     $respuesta["precioEnvio"]=0;
                     return $respuesta; 
                 }
             }
        }
       $respuesta["error"]=3;
       $respuesta["precioEnvio"]=0;
       return $respuesta; 

    }
    
    
    
    /* Obtiene el precio del envio para el transportista
     * Autor: OT
     * Fecha: 21-09-2016
     */
    public static function obtenerPrecioEnvio($idPersonTransportista,$idEnvio,$precio){
        $envioPublico="";
        $datosEnvio= DB::table('shippingrequest')->select("ispublic")->where("id","=",$idEnvio)->get();
        foreach($datosEnvio as $rowEnvio){
            $envioPublico=$rowEnvio->ispublic;
        }
        
        if($envioPublico==true){
            return Funciones::obtenerPrecioEnvioGeneral($precio);
        }else{
            
            $preciosGrupos= DB::table('shippingrequestgroup')
                    ->select(DB::raw("case when  offercostpublic.type=0 then round(offercostpublic.quantity::numeric,2)
                        else round(((offercostpublic.quantity/100) * $precio)::numeric,2) end as vprecio"))
                    ->leftJoin('groups', 'groups.id', '=', 'shippingrequestgroup.groupid')
                    ->leftJoin('offercostpublic', 'offercostpublic.groupid', '=', 'groups.id')
                    ->leftJoin('groupsperson', 'groupsperson.groupid', '=', 'groups.id')
                    ->where("groupsperson.personid","=",$idPersonTransportista)
                    ->where("shippingrequestgroup.shippingrequestid","=",$idEnvio)
                    ->where("offercostpublic.quantity",">",0)
                    ->whereRaw(DB::raw("$precio>offercostpublic.start and $precio<=offercostpublic.v_end"))
                    ->where("offercostpublic.groupid",">",0)
                    ->where("offercostpublic.deleted",null)
                    ->orderBy("groups.priority","asc")
                    ->limit(1)
                    ->get();
            
            if(count($preciosGrupos)==0){
                return Funciones::obtenerPrecioEnvioGeneral($precio);
            }else{
                foreach($preciosGrupos as $rowPrecio){
                    return $rowPrecio->vprecio;
                }
            }
        }
    }
    
    
    /* Obtiene el precio del envio para el transportista
     * Autor: OT
     * Fecha: 21-09-2016
     */
    public static function obtenerPrecioEnvioGeneral($precio){
        
       $precioGeneralDat= DB::table('offercostpublic')
               ->where("groupid",null)
               ->whereRaw(DB::raw("$precio>start and $precio<=v_end"))
               ->where("deleted",null)
               ->get();
       if(count($precioGeneralDat)>0){
           foreach($precioGeneralDat as $rowPrecio){
               if($rowPrecio->type==0){ // cantidad
                   return round($rowPrecio->quantity,2);
               }else{ // porcentaje
                   $vp=round(($rowPrecio->quantity/100) * $precio,2);
                   return $vp;
               }
           }
       }else{
           return "errorPrecio";
       }
    }
    
    
    
    
    /* Descuenta el monto del saldo disponible del transportista
     * Autor: OT
     * Fecha: 21-09-2016
     */
    public static function descontarSaldoDisponibleTransportista($idPersonTransportista,$costoEnvio){
        $precioEnvio=$costoEnvio;
        $dataPerson=Person::find($idPersonTransportista);
        $fechaActual=date("Y-m-d H:i:s");
        
        if(count($dataPerson)>0){
             if($dataPerson->isshipper==true){
                 $idShipper=0;
                 $datosChofer=  Shipper::where("personid",$idPersonTransportista)->get();
                 foreach($datosChofer as $chofer){
                    $idShipper= $chofer->id;
                 }
                 $saldoActual=  ShipperAccount::where("shipperid",$idShipper)->get();
                 $vSaldo=0.00;
                 $vDisponible=0.00;
                 $idCuentaTransportista=0;
                 foreach($saldoActual as $rSaldo){
                    $vSaldo=$rSaldo->balance;
                    $vDisponible=$rSaldo->available;
                    $idCuentaTransportista=$rSaldo->id;
                 }
                 $actualizados = DB::update("update shipperaccount set available = available- ? , updated=? where id = ? and shipperid=?", [$precioEnvio,$fechaActual,$idCuentaTransportista,$idShipper]);
                 
             }
        }
        return 0;
    }
    
    /* Regresar  el monto del envio al saldo disponible  del transportista
     * Autor: OT
     * Fecha: 21-09-2016
     */
    public static function sumarSaldoDisponibleTransportista($idPersonTransportista,$costoEnvio){
        $precioEnvio=$costoEnvio;
        $dataPerson=Person::find($idPersonTransportista);
        $fechaActual=date("Y-m-d H:i:s");
        
        if(count($dataPerson)>0){
             if($dataPerson->isshipper==true){
                 $idShipper=0;
                 $datosChofer=  Shipper::where("personid",$idPersonTransportista)->get();
                 foreach($datosChofer as $chofer){
                    $idShipper= $chofer->id;
                 }
                 $saldoActual=  ShipperAccount::where("shipperid",$idShipper)->get();
                 $vSaldo=0.00;
                 $vDisponible=0.00;
                 $idCuentaTransportista=0;
                 foreach($saldoActual as $rSaldo){
                    $vSaldo=$rSaldo->balance;
                    $vDisponible=$rSaldo->available;
                    $idCuentaTransportista=$rSaldo->id;
                 }
                 $actualizados = DB::update("update shipperaccount set available = available+ ? , updated=? where id = ? and shipperid=?", [$precioEnvio,$fechaActual,$idCuentaTransportista,$idShipper]);
                 
             }
        }
        return 0;
    }
    
    
    /* Resta el monto del saldo del transportista
     * Autor: OT
     * Fecha: 21-09-2016
     */
    public static function restaSaldoTransportista($idPersonTransportista,$idEnvio,$costoEnvio){
        $precioEnvio=$costoEnvio;
        $dataPerson=Person::find($idPersonTransportista);
        $fechaActual=date("Y-m-d H:i:s");
        
        if(count($dataPerson)>0){
             if($dataPerson->isshipper==true){
                 $idShipper=0;
                 $datosChofer=  Shipper::where("personid",$idPersonTransportista)->get();
                 foreach($datosChofer as $chofer){
                    $idShipper= $chofer->id;
                 }
                 $saldoActual=  ShipperAccount::where("shipperid",$idShipper)->get();
                 $vSaldo=0.00;
                 $vDisponible=0.00;
                 $idCuentaTransportista=0;
                 foreach($saldoActual as $rSaldo){
                    $vSaldo=$rSaldo->balance;
                    $vDisponible=$rSaldo->available;
                    $idCuentaTransportista=$rSaldo->id;
                 }
                 $actualizados = DB::update("update shipperaccount set balance = balance- ? , updated=? where id = ? and shipperid=?", [$precioEnvio,$fechaActual,$idCuentaTransportista,$idShipper]);
                 $historial =  ShipperAccountDetail::create([
                           'shipperid'=>$idShipper,
                           'amount'=>$precioEnvio,
                           'created'=>$fechaActual,
                           'carriercreditid'=>null,
                           'promotionid'=>null,
                           'shippingrequestid'=>$idEnvio,
                          ]);
             }
        }
        return 0;
    }
    
    
    public static function fechaDMY($fecha){
        $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
        $meses = array("January"=>"01","February"=>"02","March"=>"03","April"=>"04","May"=>"05","June"=>"06","July"=>"07","August"=>"08","September"=>"09","October"=>"10","November"=>"11","December"=>"12");
        
        $fechaFormato="";
        $fecha=  strtotime($fecha);
        $fechaDia=strftime("%A", $fecha);
        $fechaDiaNumero=strftime("%d", $fecha);
        $fechaMes=strftime("%B", $fecha);
        $fechaAnio=strftime("%Y", $fecha);

        $fechaFormato=$fechaDiaNumero . "/". $meses[$fechaMes] . "/" . $fechaAnio;
        
   
        return $fechaFormato;
    }
    
    /*
     * Devuele la hora formato H:m recibendo los segundos
     * Auto: OT
     * Fecha 23-12-2016
     */
    
    public static function horaHm($segundos){
        $tiempoHorario="AM";
        
        $hora=floor($segundos/3600);
        $minutos=($segundos%3600)/60;
        
        if($hora==12){
            $tiempoHorario="PM";
        }else if($hora>12){
            $tiempoHorario="PM";
            $hora=$hora-12;
            
        }
        if($hora<10) $hora="0".$hora;
        if($minutos<10) $minutos="0".$minutos;
        
        return $hora . ":" .  $minutos . " " . $tiempoHorario;
    }
    
    
    /*
     * Devuele la hora formato 24H:m recibendo los segundos
     * Auto: OT
     * Fecha 09-01-2017
     */
    
    public static function hora24Hm($segundos){
        $tiempoHorario="AM";
        
        $hora=floor($segundos/3600);
        $minutos=($segundos%3600)/60;
        
        if($hora<10) $hora="0".$hora;
        if($minutos<10) $minutos="0".$minutos;
        
        return $hora . ":" .  $minutos;
    }
    
    
}
