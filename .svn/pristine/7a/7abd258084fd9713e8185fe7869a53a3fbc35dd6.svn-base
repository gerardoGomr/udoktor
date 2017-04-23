<?php 
header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);
require_once('pdo_pgsql_connection.php');
require_once('utils.php');
require_once('PHPMailer/PHPMailerAutoload.php');
 
$conexion=new DbConnection($_GET["instance"]);

error_reporting(0);
$fechaactual=date('Y-m-d H:i:s');
    try{
        $conexion->StartTransaction();
		
        $enviosPorExpirar=$conexion->Query("SELECT shippingrequest.title,shippingrequest.id,
                                        to_char(shippingrequest.createdat,'DD/MM/YYYY HH24:MI:SS') as creacion,
                                        to_char(collectionaddress.collectiondate,'DD/MM/YYYY') as fecharecoger,
                                        to_char(collectionaddress.collectionuntildate,'DD/MM/YYYY') as fecharecogerhasta,
                                        to_char(deliveryaddress.deliverydate,'DD/MM/YYYY') as fechaentregar,
                                        to_char(deliveryaddress.deliveryuntildate,'DD/MM/YYYY') as fechaentregarhasta,
                                        to_char(to_timestamp(expirationdate,'YYYY-mm-dd HH24:MI:SS'),'dd/mm/YYYY HH24:MI:SS') as expira,
                                        shippingrequest.costtype as costtype,
                                        shippingrequest.cost as costo,
                                        shippingrequest.title as titulo,
                                        collectionaddress.city as ciudad,
                                        vestado.name as estado,
                                        collectionaddress.stateid as estadorecogerid,
                                        collectionaddress.street1 as calle,
                                        deliveryaddress.street1 as calle_e,
                                        deliveryaddress.city as ciudad_e,
                                        eestado.name as estado_e,
                                        deliveryaddress.stateid as estadoentregarid,
                                        person.firstname,
                                        person.lastname,
                                        person.company,
                                        paisrecoger.name as npaisrecoger,
                                        paisentregar.name as npaisentregar,
                                        collectionaddress.generalubication as origen,
                                        deliveryaddress.generalubication as destino
                                        from shippingrequest 
                                        left join collectionaddress on collectionaddress.shippingrequestid=shippingrequest.id
                                        left join deliveryaddress on deliveryaddress.shippingrequestid=shippingrequest.id
                                        left join administrativeunit as vestado on vestado.id=collectionaddress.stateid
                                        left join country as paisrecoger on paisrecoger.id=vestado.countryid
                                        left join administrativeunit as eestado on eestado.id=deliveryaddress.stateid
                                        left join country as paisentregar on paisentregar.id=eestado.countryid
                                        left join person on person.id=shippingrequest.requesterid
                                        where (extract (epoch from (
                                                shippingrequest.expirationdate::timestamp - '$fechaactual'::timestamp 
                                                                 )
                                                     )
                                            )::integer<=1800 and (extract (epoch from (
                                                shippingrequest.expirationdate::timestamp - '$fechaactual'::timestamp 
                                                                 )
                                                     )
                                            ) ::integer>0 and shippingrequest.status=1 
                                            
                                            
                                        ");
            if(count($enviosPorExpirar)>0){
                foreach($enviosPorExpirar as $rowEnvio){
                    
                    $titulo="";
                    $fechaPublicacion="";
                    $fechaRecogerEnvio="";
                    $fechaEntregarEnvio="";
                    $costo="";
                    $ciudadRecoger= "";
                    $estadoRecoger="";
                    $calleRecoger="";
                    $calleEntrega="";
                    $ciudadEntrega="";
                    $estadoEntrega="";
                    $idEstadoRecoger="";
                    $idEstadoEntregar="";
                    $nombreCliente="";
                    $paisRecoger="";
                    $paisEntregar="";
                    $fechaExpiracion="";
                    
                    
                    $idEnvio=$rowEnvio["id"];
                    echo "idEnvio" . " " . $idEnvio ."<br>";
                            
                    if($rowEnvio["company"]==""){
                        $nombreCliente=  ucwords($rowEnvio["firstname"] . " " . $rowEnvio["lastname"]);
                    }else{
                        $nombreCliente= ucfirst($rowEnvio["company"]);
                    }
                    
                    $titulo=  ucfirst($rowEnvio["titulo"]);
                    $recogerEn=$rowEnvio["origen"];
                    
                    if($rowEnvio["fecharecogerhasta"]==""){
                        $fechaRecogerEnvio=$rowEnvio["fecharecoger"];
                    }else{
                        $fechaRecogerEnvio=$rowEnvio["fecharecoger"] . " - " . $rowEnvio["fecharecogerhasta"];
                    }
                    
                    $entregarEn=$rowEnvio["destino"];

                    if($rowEnvio["fechaentregarhasta"]==""){
                        $fechaEntregarEnvio=$rowEnvio["fechaentregar"];
                    }else{
                        $fechaEntregarEnvio=$rowEnvio["fechaentregar"] . " - " . $rowEnvio["fechaentregarhasta"];
                    }
                    
                    $fechaExpiracion=$rowEnvio["expira"];
                    
                    if($rowEnvio["costtype"]==1){
                        $costo=formato_numeros($rowEnvio["costo"],",",".");
                    }else{
                        $costo="";
                    }
                    
                    $fechaPublicacion=$rowEnvio["creacion"];

                    $idEstadoRecoger=$rowEnvio["estadorecogerid"];
                    $idEstadoEntregar=$rowEnvio["estadoentregarid"];
                    $paisRecoger=ucfirst($rowEnvio["npaisrecoger"]);
                    $paisEntregar=ucfirst($rowEnvio["npaisentregar"]);
                    
                    $datosTransportista=$conexion->Query("select person.id,person.company,users.email,person.shippingexpiration
                        from preferences
                        left join person on person.id=preferences.personid
                        left join users on users.personid=person.id
                        where users.confirmationtoken='1'
                        and preferences.administrativeunitid=$idEstadoRecoger");
                    
                      foreach($datosTransportista as $rowDatosTransportista){
                          
                          $coberturaDestino=$conexion->Query("select preferences.id as total from preferences
                                  where administrativeunitid=$idEstadoEntregar and personid=$rowDatosTransportista[id]");
                          
                          if(count($coberturaDestino)>0){
                              
                              $nombreRecibe=  ucwords($rowDatosTransportista["company"]);
                              $correo=$rowDatosTransportista["email"];

                              
                                $body='<p>Hola <b>'.$nombreRecibe.'</b> el envío publicado por el cliente '.ucwords($nombreCliente).' está por expirar.</p>
                                <table>
                                        <tr><td>Título: </td><td>'.$titulo.'</td><tr>
                                        <tr><td>Recoger en: </td><td>'.$recogerEn.'</td><tr>
                                        <tr><td>Entregar en: </td><td>'.$entregarEn.'</td><tr>
                                        <tr><td>Fecha de recojo: </td><td>'.$fechaRecogerEnvio.'</td><tr>
                                        <tr><td>Fecha de entrega: </td><td>'.$fechaEntregarEnvio.'</td><tr>
                                        <tr><td>Fecha de expiración: </td><td>'.$fechaExpiracion.'</td><tr>
                                        <tr><td>Precio: </td><td>'.($costo==""?"Sin precio":"S/ ".$costo).'</td><tr>
                                </table><p><b>Para más detalles:</b> <a href="http://'.$_SERVER['HTTP_HOST'].'/transportista/ofertas/'.$idEnvio.'/detalle" target="_blank">clic aquí</a></p>';


                                $mail = new PHPMailer();
                                //$mail->SMTPDebug = 3;                               // Enable verbose debug output

                                $mail->isSMTP();                                      // Set mailer to use SMTP
                                $mail->Host = 'host.recargasys.com';  // Specify main and backup SMTP servers
                                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                                $mail->Username = 'noreply@efletex.com';                 // SMTP username
                                $mail->Password = 'OSg)LeG[-lnu';                           // SMTP password
                                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                                $mail->Port = 465;                                    // TCP port to connect to
                                $mail->CharSet = 'UTF-8';
                                $mail->setFrom('noreply@efletex.com', 'Notificaciones Efletex');
                                $mail->addAddress($correo);

                                     // Add a recipient

                                $mail->isHTML(true);                                  // Set email format to HTML

                                $mail->Subject = "Efletex - Envío por expirar";
                                $mail->Body    = '<html><body>'.$body.'</body></html>';
                                $mail->AltBody = 'Revisa este correo desde otro navegador para poder ver la liga de confirmación.';

                                if($rowDatosTransportista["shippingexpiration"]==true){
                                    echo "enviar " . $correo ."<br>";
                                    if(!$mail->send()) {

                                        throw new Exception('No se pudo enviar el correo');
                                    }else{
                                        $conexion->Insert("alert",array(
                                        'recipientid'=>$rowDatosTransportista["id"],
                                        'createdat'=>$fechaactual,
                                        'updated'=>$fechaactual,
                                        'type'=>13,
                                        'relationid'=>$idEnvio,
                                        ));
                                    }
                                }else{
                                        $conexion->Insert("alert",array(
                                        'recipientid'=>$rowDatosTransportista["id"],
                                        'createdat'=>$fechaactual,
                                        'updated'=>$fechaactual,
                                        'type'=>13,
                                        'relationid'=>$idEnvio,
                                        ));
                                }
                          }
                      }
                }
            }
            
            
            
            

            $conexion->Commit();
            echo "ok";
            

    }
    catch(Exception $e){
        $conexion->Rollback();
        //return json_encode(array('datos'=>array('error'=>'1','mensaje'=>$e->getMessage())));
    }


 function formato_numeros ($numero,$miles,$fraccion){
        $numero=number_format((float)$numero, 2, '.', '');//cuando el numero no tine deciales, forza .00
        $numero=str_replace('.','',$numero); // Eliminar el punto de las fracciones
        $miles; // Separador de  miles
        $fraccion; // Separador fracciones
        $decimales=2; //Numero de decimales a mostrar
        $decimalesDIV=100; // Regresar los deciamles
        return number_format($numero / $decimalesDIV,$decimales, $fraccion, $miles);
    }

?>