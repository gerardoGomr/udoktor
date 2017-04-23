<?php 
header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);
require_once('lib/nusoap.php');
require_once('pdo_pgsql_connection.php');
require_once('utils.php');
include ('wscomplemento.php');
require_once('PHPMailer/PHPMailerAutoload.php');
 
$conexion=new DbConnection();
$server = new nusoap_server();

$server->configureWSDL('Web Services', 'urn:mi_ws1');

error_reporting(0);
//recuper los datos del usuario
$server->register(  'getUser', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getUser', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recibe el email y password, devuelve los datos del usuario' // documentation
);

function getUser($datos){
    global $conexion;
    file_put_contents("log.txt",$datos,FILE_APPEND);
    try{
        $data=json_decode($datos,true);
        $user=$data["usuario"];
        $pass=$data["password"];
        $res=$conexion->Query("SELECT id,password FROM users WHERE LOWER(users.email)='{0}'",array(strtolower(trim($user))));
        if(count($res)>0){
            if (password_verify($pass, $res[0]["password"])) {
                $usuario=$conexion->Query("SELECT person.*,shipper.id shipperid FROM person LEFT JOIN users ON users.personid=person.id LEFT JOIN shipper ON shipper.personid=person.id WHERE users.id={0}",array($res[0]['id']));
                unset($usuario[0]["isadmin"]);
                unset($usuario[0]["assignedvehicle"]);
                unset($usuario[0]["newshipping"]);
                unset($usuario[0]["shippingexpiration"]);
                unset($usuario[0]["shippingcheck"]);
                unset($usuario[0]["competingoffer"]);
                $vehicleid=0;
                $driverid=0;
                
                if($usuario[0]["isdriver"]){
                    $shipper=$conexion->Query("SELECT id,shipperid FROM driver WHERE personid={0}",array($usuario[0]["id"]));
                    $vehiculo=$conexion->Query("SELECT id FROM vehicle WHERE shipperid={0} AND driverid={1}",array($shipper[0]["shipperid"],$shipper[0]["id"]));
                    $usuario[0]["shipperid"]=$shipper[0]["shipperid"];
                    $vehicleid=$vehiculo[0]["id"];
                    $driverid=$shipper[0]["id"];
                    if($vehicleid==""){
                        return json_encode(array('datos'=>array('error'=>'5','mensaje'=>'No tiene un vehículo asignado'),'data'=>''));
                    }
                }
                $usuario[0]["vehicleid"]=$vehicleid;
                $usuario[0]["driverid"]=$driverid;
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>''),'data'=>$usuario[0]));
            } else {
                return json_encode(array('datos'=>array('error'=>'2','mensaje'=>'Contrasena no valida'),'data'=>''));
            }
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'Usuario no existe'),'data'=>''));
        }
    }catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'3','mensaje'=>$e->getMessage()),'data'=>''));
    }
}


//inserta datos de un cliente
$server->register(  'insertCliente', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertCliente', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta un cliente' // documentation
);

function insertCliente($datos) {
    //return $datos;
	global $conexion;
	$data=json_decode($datos,true);
    $conexion->StartTransaction();
	try{
        //valida el email

        if($data["dni"]!=""){
            $res=$conexion->Query("SELECT id FROM person WHERE dni='{0}'",array(strtoupper(trim($data["dni"]))));
            if(count($res)>0){
                return json_encode(array('datos'=>array('error'=>'3','mensaje'=>'DNI ya existe')));
            }
        }
        
        if($data["ruc"]!=""){
            $res=$conexion->Query("SELECT id FROM person WHERE ruc='{0}'",array(strtoupper(trim($data["ruc"]))));
            if(count($res)>0){
                return json_encode(array('datos'=>array('error'=>'4','mensaje'=>'RUC ya existe')));
            }
        }
        
        $res=$conexion->Query("SELECT id FROM users WHERE email='{0}'",array(strtolower($data['email'])));
        if(count($res)>0){
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'existe')));
        }

        else{
            $fechaactual=date('Y-m-d H:i:s');
            //insert person
            $insert=array(
                'firstname'=>trim($data["firstname"]),
                'middlename'=>trim($data["secondname"]),
                'lastname'=>trim($data["lastname"]),
                'secondlastname'=>trim($data["secondlastname"]),
                'isemployee'=>$data["isemployee"],
                'isshipper'=>$data["isshipper"],
                'isdriver'=>$data["isdriver"],
                'suspended'=>false,
                'company'=>$data["company"],
                'newoffer'=>$data["isshipper"]?0:1,
                'offercanceled'=>$data["isshipper"]?0:1,
                'offeraccepted'=>$data["isshipper"]?1:0,
                'offerrejected'=>$data["isshipper"]?1:0,
                'newquestion'=>$data["isshipper"]?0:1,
                'newreply'=>$data["isshipper"]?1:0,
                'shipmentcollected'=>$data["isshipper"]?0:1,
                'shipmentdelivered'=>$data["isshipper"]?0:1,
                'feedback'=>1,
                'assignedvehicle'=>1,
                'updated'=>$fechaactual
            );
            
            if($data["dni"]!=""){
                $insert["dni"]=strtoupper(trim($data["dni"]));
            }
            
            if($data["ruc"]!=""){
                $insert['ruc']=strtoupper(trim($data["ruc"]));
            }
            
        	$id=$conexion->Insert("person",$insert);

            $pass=trim($data["email"]).rand(1,100000);
            $pass=str_replace('/', '', password_hash($pass,PASSWORD_DEFAULT));

            $id_user=$conexion->Insert("users",array(
                'email'=>strtolower(trim($data["email"])), 
                'password'=>$pass, 
                'resetpasswordtoken'=>'',
                'resetpasswordsentat'=>$fechaactual, 
                'remembercreatedat'=>'NOW()', 
                'signincount'=>0, 
                'currentsigninat'=>'NOW()', 
                'lastsigninat'=>'NOW()', 
                'currentsigninip'=>getRealIP(), 
                'lastsigninip'=>'', 
                'confirmationtoken'=>0, 
                'confirmedat'=>'NOW()', 
                'confirmationsentat'=>'NOW()', 
                'failedattempts'=>0, 
                'unlocktoken'=>'', 
                'lockedat'=>'NOW()', 
                'personid'=>$id[0]["id"], 
                'remember_token'=>'', 
                'created_at'=>$fechaactual, 
                'updated_at'=>$fechaactual,
                'active'=>1,
                'verifiedaccount'=>1,
                'isverified'=>1
            ));

            if($data["isshipper"]){
                $shipper=$conexion->Insert("shipper",array(
                    'personid'=>$id[0]["id"],
                    'paymentbank'=>'',
                    'paymentbankaccountownerfullname'=>'',
                    'paymentbankaccountnumber'=>'',
                    'paymentbankbranchnumber'=>'',
                    'paymentbankroutingnumber'=>''
                ));

               $conexion->Insert("address",array(
                    'street1'=>$data["street1"],
                    'street2'=>$data["street2"],
                    'stateid'=>$data["stateid"],
                    'personid'=>$id[0]["id"],
                    'postalcode'=>$data["postalcode"],
                    'telephone'=>$data["telephone"],
                    'cityid'=>0
                ));
                
                $country=$conexion->Query("SELECT countryid FROM administrativeunit WHERE id={0}",array($data["stateid"]));
                $states=$conexion->Query("SELECT id FROM administrativeunit WHERE countryid={0} AND active=1",array($country[0]["countryid"]));
                
                foreach($states as $row){
                    $conexion->Insert("preferences",array(
                        "personid"=>$id[0]["id"],
                        "administrativeunitid"=>$row["id"]
                     ));

                }
                
                $conexion->Insert("user_role",array(
                    "created_at"=>$fechaactual,
                    "updated_at"=>$fechaactual,
                    "userid"=>$id_user[0]["id"],
                    "roleid"=>2
                ));
                
                $conexion->Insert("shipperaccount",array("shipperid"=>$shipper[0]["id"],"balance"=>0.0,"createdat"=>$fechaactual,"currencyid"=>1,"updated"=>$fechaactual,"available"=>0.0));
            }
            else{
                $conexion->Insert("user_role",array(
                    "created_at"=>$fechaactual,
                    "updated_at"=>$fechaactual,
                    "userid"=>$id_user[0]["id"],
                    "roleid"=>1
                ));
            }


            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'host.recargasys.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'noreply@efletex.com';                 // SMTP username
            $mail->Password = 'OSg)LeG[-lnu';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('noreply@efletex.com', 'Efletex');
            $mail->addAddress($data["email"], $data["firstname"].' '.$data["lastname"]);     // Add a recipient

            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Efletex - Verificación de cuenta';
            $mail->Body    = '<html>
                <body>
                    <b>Bienvenido a Efletex</b><br><br>
                    Gracias '.ucwords($data["firstname"]) . ' ' . ucwords($data["lastname"]).' por utilizar este servicio.
                    Para verificar tu cuenta da clic <a href="http://'.$_SERVER['HTTP_HOST'].'/crearcuenta/verificarCuenta/'.$data["email"].'/'.$pass .'">aquí</a>.
                    
                </body>
            </html>';
            $mail->AltBody = 'Revisa este correo desde otro navegador para poder ver la liga de confirmación.';

            if(!$mail->send()) {
                throw new Exception("No se pudo enviar el Correo de Confirmación");
                
            } else {
                $conexion->Commit();
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'insertado')));
            }
            
        }
    }
    catch(Exception $e){
        $conexion->Rollback();
    	return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }

}

//devuelve la ip
function getRealIP(){
    if (isset($_SERVER["HTTP_CLIENT_IP"])){
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"])){
        return $_SERVER["HTTP_FORWARDED"];
    }
    else{
        return $_SERVER["REMOTE_ADDR"];
    }
}


//devuelve los envios de un usuario
$server->register(  'getEnvios', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getEnvios', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de envios pasando el id del usuario' // documentation
);

function getEnvios($datos){
    global $conexion;
	global $profile_image_width;
	$data=json_decode($datos,true);
    $where="";
    if($data["id"]!=0){
        $where=" AND shipping.requesterid=".$data["id"];
    }
    if($data["fecha"]!=""){
        $where.=" AND shipping.updated>'".$data["fecha"]."'";
    }
    if($data["shippingrequestid"]!=0){
        $where=" AND shipping.id=".$data["shippingrequestid"];
    }
    try{
        $res=$conexion->Query("SELECT shipping.id,shipping.title,shipping.status,shipping.expirationdate,shipping.createdat,shipping.coldprotection,shipping.sortout,shipping.blindperson,shipping.costtype,shipping.cost,shipping.firstofferdiscount,shipping.discountrate,shipping.km,shipping.tiempo,shipping.peso_total,shipping.totalprice,
            shipping.updated, collection.contactfullname,collection.contactnumber1,collectionState.name stateOrigen, shipping.requesterid,
            collectionCountry.name countryOrigen, collection.latitude latitudOrigen, collection.longitude longitudOrigen, collection.collectiondate collectiondateOrigen,collection.collectionuntildate collectionuntildateOrigen,collection.place placeOrigen,collection.elevator elevatorOrigen,collection.collectinside collectinsideOrigen,collection.city cityOrigen,collection.collecttimefrom collecttimefromOrigen,collection.collecttimeuntil collecttimeuntilOrigen, collection.street1 street1Origen,collection.stateid stateidOrigen, 
            delivery.recipientfullname recipientfullnameDestino,delivery.recipientcontactnumber1 recipientcontactnumber1Destino,delivery.street1 street1Destino,delivery.stateid stateidDestino,deliveryState.name stateDestino,deliveryCountry.name countryDestino,
            delivery.longitude longitudDestino, delivery.latitude latitudDestino,delivery.deliverydate deliverydateDestino,delivery.deliveryuntildate deliveryuntildateDestino, delivery.place placeDestino,delivery.elevator elevatorDestino,delivery.callbefore callbeforeDestino,delivery.deliverywithin deliverywithinDestino,delivery.deliverytimefrom deliverytimeDestino,delivery.deliverytimeuntil deliverytimeuntilDestino,delivery.city cityDestino,
			/*(SELECT CASE WHEN company='' OR company IS NULL THEN CONCAT(firstname, ' ', lastname) ELSE company END FROM person WHERE id=shipping.requesterid) requestername*/
			CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername, person.starrating, 
            shipping.ispublic, collection.anotherplace anotherplaceOrigen, delivery.anotherplace anotherplaceDestino, shipping.paymentmethodid, shipping.paymentconditions, 
            shipment.trackingtype,shipping.paymentmethodid, shipping.paymentconditions, (SELECT method FROM paymentmethod WHERE id=shipping.paymentmethodid) paymentmethod,
            person.img profileimage 
            FROM shippingrequest shipping
            LEFT JOIN deliveryaddress delivery on delivery.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit deliveryState ON deliveryState.id=delivery.stateid
            LEFT JOIN country deliveryCountry ON deliveryCountry.id=deliveryState.countryid 
            LEFT JOIN collectionaddress collection ON collection.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit collectionState ON collectionState.id=collection.stateid 
            LEFT JOIN country collectionCountry ON collectionCountry.id=collectionState.countryid
			LEFT JOIN person ON person.id=shipping.requesterid 
            LEFT JOIN paymentmethod ON paymentmethod.id=shipping.paymentmethodid 
            LEFT JOIN shipment ON shipment.shippingrequestid=shipping.id 
            WHERE shipping.deleted IS NULL {0}",array($where));
        if(count($res)>0){
            
            for($i=0;$i<count($res);$i++){
                $res[$i]["images"]=array();
                $items=$conexion->Query("SELECT * FROM shippingitem WHERE shippingrequestid={0}",array($res[$i]["id"]));
                $res[$i]["items"]=$items;
                foreach($items as $itemid){
                    $images=$conexion->Query("SELECT * FROM fileattachment WHERE shippingitemid={0}",array($itemid["id"]));

                    foreach($images as $img){
                        if($img["filenameminiature"]==""){
                            $filename=$img["filename"];
                        }
                        else{
                            $filename=$img["filenameminiature"];
                        }
                        $imgBase64=base64_encode(file_get_contents('../imagenesEnvio/'.$res[$i]["id"].'/'.$filename));

						/*try{
							$archivo = createThumbnail('../imagenesEnvio/'.$res[$i]["id"].'/'.$img['filename'], 50);
							$imgBase64 = base64_encode(file_get_contents($archivo));
							//$imgBase64=base64_encode(file_get_contents());
							$img["imagen_64"]=$imgBase64;
							$res[$i]["images"][]=$img;
						}catch(Exception $e){
							unset($res[$i]['profileimage']);
						}*/

                        $img["imagen_64"]=$imgBase64;
                        $res[$i]["images"][]=$img;
                    }
                }
				//file_put_contents("logProfileImage.txt", $res[$i]['profileimage']."\r\n", FILE_APPEND);
				if(!is_null($res[$i]['profileimage']) && $res[$i]['profileimage'] != ""){
					//file_put_contents("logProfileImage.txt", "entra\r\n", FILE_APPEND);
					try{
						$archivo = createBase64Thumbnail("../".$res[$i]['profileimage'], $profile_image_width);
						$res[$i]['profileimage'] = $archivo['base64'];
						$res[$i]['profileimageextension'] = $archivo['extension'];
					}catch(Exception $e){
						//file_put_contents("logProfileImage.txt", "error".$e."\r\n", FILE_APPEND);
						unset($res[$i]['profileimage']);
					}
				}else{
					unset($res[$i]['profileimage']);
				}
                
                $res[$i]["grupos"]=array();
            }
			$where="";
			if($data["id"]!=0){
				$where=" AND shipping.requesterid=".$data["id"];
			}
			if($data["shippingrequestid"]!=0){
				$where=" AND shipping.id=".$data["shippingrequestid"];
			}
            $upd=$conexion->Query("SELECT MAX(shipping.updated)updated FROM shippingrequest shipping WHERE shipping.deleted IS NULL {0}",array($where));
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res,'updated'=>$upd[0]['updated']));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay envÍos nuevos'),'data'=>''));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage()),'data'=>''));
    }
}


//devuelve los paises
$server->register(  'getPais', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getPais', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de paises' // documentation
);

function getPais($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
		$res=$conexion->Query("SELECT * FROM country WHERE active=1 AND updated>'{0}'",array($data["fecha"]));
		$upd=$conexion->Query("SELECT MAX(updated) updated FROM country WHERE active=1");
        if(count($res>0)){
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res, 'updated' => $upd[0]['updated']));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay datos')));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//devuelve los estados por paises
$server->register(  'getEstados', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getEstados', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de Estados' // documentation
);

function getEstados($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $res=$conexion->Query("SELECT administrativeunit.* FROM administrativeunit LEFT JOIN country ON country.id=administrativeunit.countryid WHERE administrativeunit.updated>'{0}' AND country.active=1 AND administrativeunit.active=1",array($data["fecha"]));
		$upd=$conexion->Query("SELECT MAX(updated) updated FROM administrativeunit WHERE administrativeunit.countryid IN(SELECT id FROM country WHERE active=1)");
        if(count($res>0)){
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res, 'updated' => $upd[0]['updated']));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay datos')));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//devuelve las unidades de los artículos
$server->register(  'getUnidades', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getUnidades', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de unidades para los articulos' // documentation
);

function getUnidades($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $res=$conexion->Query("SELECT * FROM unit WHERE updated>='{0}'",array($data["fecha"]));
        if(count($res>0)){
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay datos')));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//inserta los envios del Cliente
$server->register(  'insertEnvio', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertEnvio', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta un Envio' // documentation
);

function insertEnvio($datos) {
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    $conexion->StartTransaction();
    try{
            if($data["costtype"]==1){
                if($data["firstofferdiscount"]){
                    $costo=round($data["cost"]-($data['cost']*$data['discountrate']/100),2);
                }
                else if($data["cost"]==0){
                    return json_encode(array('datos'=>array('error'=>'2','mensaje'=>'El precio no puede ser 0')));
                }
                else{
                    $costo=$data["cost"];
                }
            }else{
				$costo = "";
			}

            if($data["expirationdate"]<$fechaactual){
                return json_encode(array('datos'=>array('error'=>'2','mensaje'=>'La fecha y/o hora de expiración deben ser mayores a la fecha y hora actual, '.$fechaactual)));
            }
            
            //equivalencias
            $peso_total=0;
            $lb="0.45359237";
            $tn="1000";
            
            //calculando el peso total
            foreach($data["items"] as $art){
                $peso=$art["quantity"]*$art["weight"];
                if($art["unitweight"]=="Libras"){
                    $peso=$art["quantity"]*$art["weight"]*$lb;   
                }
                if($art["unitweight"]=="Toneladas"){
                    $peso=$art["quantity"]*$art["weight"]*$tn;   
                }
                $peso_total+=$peso;
            }

            $insert=array(
                'requesterid'=>$data['requesterid'],
                'deliverydaterequired'=>date('Y-m-d'),
                'deliverytimerequired'=>date('H:i:s'),
                'createdat'=>$fechaactual,
                'coldprotection'=>$data['coldprotection'],
                'sortout'=>$data['sortout'],
                'blindperson'=>$data['blindperson'],
                'costtype'=>$data['costtype'],
                'firstofferdiscount'=>$data['firstofferdiscount'],
                'discountrate'=>$data['discountrate']==""?0:$data['discountrate'],
                'status'=>$data['status'],
                'title'=>trim($data['title']),
                'updated'=>$fechaactual,
                'km'=>round($data['km'],2),
                'tiempo'=>$data['tiempo'],
                'peso_total'=>round($peso_total,2),
                'expirationdate'=>$data['expirationdate'],
                'ispublic'=>$data["ispublic"]==1?"t":"f",
                'paymentconditions'=>$data["paymentinformation"]

            );

            if($data['deleted']!=""){
                $insert['deleted']=$data['deleted'];
            }
            
            if($data["costtype"]==1){

                if($data['cost']!=""){
                    $insert['cost']=$data['cost'];
                    $insert['totalprice']=$costo;
                }
            }

            if($data["paymentmethodid"]>0){
                $insert["paymentmethodid"]=$data["paymentmethodid"];
            }

            
            $id=$conexion->Insert("shippingrequest",$insert);

            $insert_collection=array(
                'contactfullname'=>trim($data["contactfullnameOrigen"]), 
                'contactnumber1'=>trim($data["contactnumber1Origen"]),
                'street1'=>trim($data["street1Origen"]),
                'street2'=>trim($data["street2Origen"]), 
                'boroughid'=>'0', 
                'cityid'=>'0', 
                'municipalityid'=>'0', 
                'stateid'=>$data["stateidOrigen"], 
                'latitude'=>$data["latitudeOrigen"], 
                'longitude'=>$data["longitudeOrigen"], 
                'shippingrequestid'=>$id[0]["id"], 
                'collectiondate'=>$data["collectiondateOrigen"], 
                //'collectionuntildate'=>$data["collectionuntildateOrigen"]==""?null:$data["collectionuntildateOrigen"], 
                'place'=>$data["placeOrigen"], 
                'elevator'=>$data["elevatorOrigen"], 
                'collectinside'=>$data["collectinsideOrigen"], 
                'updated'=>$data["updatedOrigen"], 
                'city'=>$data["cityOrigen"], 
                'collecttimefrom'=>$data["collecttimefromOrigen"], 
                'collecttimeuntil'=>$data["collecttimeuntilOrigen"],
                'generalubication'=>$data["generalubicationOrigen"],
                'collectionrandomubication'=>$data["collectionrandomubication"],
            );

            if($data['collectionuntildateOrigen']!=""){
                $insert_collection['collectionuntildate']=$data['collectionuntildateOrigen'];
            }
            
            if($data['collectionother']!=""){
                $insert_collection['anotherplace']=$data["collectionother"];
            }
            
         
            $collection=$conexion->Insert("collectionaddress",$insert_collection);


            $insert_delivery=array(
                'recipientfullname'=>trim($data["recipientfullnameDestino"]), 
                'recipientcontactnumber1'=>trim($data["contactnumber1Destino"]),
                'street1'=>trim($data["street1Destino"]),
                'street2'=>trim($data["street2Destino"]), 
                'boroughid'=>0, 
                'cityid'=>0, 
                'municipalityid'=>0, 
                'stateid'=>$data["stateidDestino"], 
                'latitude'=>$data["latitudeDestino"], 
                'longitude'=>$data["longitudeDestino"], 
                'shippingrequestid'=>$id[0]["id"], 
                'deliverydate'=>$data["deliverydateDestino"], 
                //'deliveryuntildate'=>$data["deliveryuntildateDestino"]==""?null:$data["deliveryuntildateDestino"], 
                'place'=>$data["placeDestino"], 
                'elevator'=>$data["elevatorDestino"], 
                'callbefore'=>$data["callbeforeDestino"], 
                'deliverywithin'=>$data["deliverywithinDestino"],
                'updated'=>$data["updatedDestino"], 
                'city'=>$data["cityDestino"], 
                'deliverytimefrom'=>$data["deliverytimefromDestino"], 
                'deliverytimeuntil'=>$data["deliverytimeuntilDestino"],
                'generalubication'=>$data["generalubicationDestino"],
                'deliveryrandomubication'=>$data["deliveryrandomubication"]
            );
            

            if($data['deliveryuntildateDestino']!=""){
                $insert_delivery['deliveryuntildate']=$data['deliveryuntildateDestino'];
            }
            
            if($data['deliveryother']!=""){
                $insert_delivery['anotherplace']=$data["deliveryother"];
            }
            
            $delivery=$conexion->Insert("deliveryaddress",$insert_delivery);
            
            $dirEnvio='../imagenesEnvio/'.$id[0]["id"];
            if(!file_exists($dirEnvio)){
                mkdir($dirEnvio, 0777); 
            }

            if(count($data["items"])>0){
                $cont=0;
                foreach ($data["items"] as $item) {
                    $itemid=$conexion->Insert("shippingitem",array(
                        "description"=>$item["description"],
                        "perishable"=>$item["perishable"],
                        "livingbeing"=>$item["livingbeing"],
                        "shippingitemcategoryid"=>$item["shippingitemcategoryid"],
                        "shippingrequestid"=>$id[0]["id"],
                        "collectionaddressid"=>$collection[0]["id"],
                        "deliveryaddressid"=>$delivery[0]["id"],
                        "quantity"=>$item["quantity"],
                        "dangerous"=>$item["dangerous"],
                        "stackble"=>$item["stackble"],
                        "long"=>$item["long"],
                        "high"=>$item["high"],
                        "width"=>$item["width"],
                        "unitdimensions"=>strtolower($item["unitdimensions"]),
                        "weight"=>$item["weight"],
                        "unitweight"=>strtolower($item["unitweight"]),
                        "comments"=>$item["comments"],
                        "updated"=>$fechaactual
                    ));


                    if(count($item["images"])>0){
                        $i=1;
                        foreach($item["images"] as $img) {
                            if($img["imagen_64"]!=""){
                                $archivo=$cont.'img'.$i.'.'.$img["ext"];  
                                file_put_contents($dirEnvio.'/'.$archivo,base64_decode($img["imagen_64"]));
                                $i++;
                                chmod($dirEnvio.'/'.$archivo,0777);
                                $conexion->Insert("fileattachment",array("filename"=>$archivo,"filetypeid"=>"1","shippingitemid"=>$itemid[0]["id"],"updated"=>$fechaactual));
                            }
                        }
                    }
                    $cont++;
                }  
            }

            if($data["ispublic"]==0){
                
                foreach($data["grupos"] as $row){
                    $conexion->Insert("shippingrequestgroup",array("shippingrequestid"=>$id[0]["id"],"groupid"=>$row['id'],"updated"=>$fechaactual));
                }
            }
            //se inserta el log de status
            $conexion->Insert("shippingrequestlog",array(
                "shippingrequestid"=>$id[0]["id"],"status"=>"1","createdat"=>$fechaactual,"updated"=>$fechaactual
            ));
            
            
            //se notifica a los transportistas
            $shippers=$conexion->Query("SELECT users.email,person.company,person.id FROM (SELECT * FROM preferences where administrativeunitid IN ({0}))preferences 
                LEFT JOIN users ON users.personid=preferences.personid 
                LEFT JOIN person ON person.id=users.personid WHERE preferences.administrativeunitid IN ({1})",array($data["stateidOrigen"],$data["stateidDestino"]));
            if(count($shippers)>0){
                              
                
            $cliente=$conexion->Query("SELECT CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername FROM person WHERE person.id={0}",array($data["requesterid"]));
                
            $recoleccion=$conexion->Query("SELECT administrativeunit.name estado,country.name pais FROM administrativeunit LEFT JOIN country on country.id=administrativeunit.countryid WHERE administrativeunit.id={0}",array($data["stateidDestino"]));
                                
                
                foreach($shippers as $row){
                    $body='<p>Hola <b>'.ucwords($row["company"]).'</b> el cliente '.ucwords($cliente[0]["requestername"]).' publicó un nuevo envío.</p>
                    <table>
                            <tr><td>Título: </td><td>'.$data["title"].'</td><tr>
                            <tr><td>Recoger en: </td><td>'.$data["generalubicationOrigen"].'</td><tr>
                            <tr><td>Entregar en: </td><td>'.$data["generalubicationDestino"].'</td><tr>
                            <tr><td>Fecha de recojo: </td><td>'.date('d/m/Y',strtotime($data["collectiondateOrigen"])).($data["collectionuntildateOrigen"]==""?"":" - ".date('d/m/Y',strtotime($data["collectionuntildateOrigen"]))).'</td><tr>
                            <tr><td>Fecha de entrega: </td><td>'.date('d/m/Y',strtotime($data["deliverydateDestino"])).($data["deliveryuntildateDestino"]==""?"":" - ".date('d/m/Y',strtotime($data["deliveryuntildateDestino"]))).'</td><tr>
                            <tr><td>Fecha de expiración: </td><td>'.date('d/m/Y H:i:s',strtotime($data['expirationdate'])).'</td></tr>
                            <tr><td>Precio: </td><td>'.($data["cost"]==""?"Sin precio":"S/ ".$data["cost"]).'</td><tr>
                    </table><p><b>Para más detalles:</b> <a href="http://'.$_SERVER['HTTP_HOST'].'/transportista/ofertas/'.$id[0]["id"].'/detalle" target="_blank">clic aquí</a></p>';
                   
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
                    $mail->setFrom('noreply@efletex.com', 'Efletex');
                    $mail->addAddress($row["email"]);
                                    
                         // Add a recipient
    
                    $mail->isHTML(true);                                  // Set email format to HTML
    
                    $mail->Subject = "Efletex - Nuevo envío";
                    $mail->Body    = '<html><body>'.$body.'</body></html>';
                    $mail->AltBody = 'Revisa este correo desde otro navegador para poder ver la liga de confirmación.';
    
                    if(!$mail->send()) {
                        
                        throw new Exception('No se pudo enviar el correo');
                    }
                    
                    $conexion->Insert("alert",array(
                     'recipientid'=>$row["id"],
                     'createdat'=>$fechaactual,
                     'updated'=>$fechaactual,
                     'type'=>12,
                     'relationid'=>$id[0]["id"]));
                } 
            }
            

            $conexion->Commit();
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'insertado')));

    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'1','mensaje'=>$e->getMessage())));
    }

}

//devuelve las categorias de servicios
$server->register(  'getServicios', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getServicios', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de unidades para los articulos' // documentation
);

function getServicios($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $res=$conexion->Query("SELECT * FROM shippingitemcategory WHERE updated>='{0}'",array($data["fecha"]));
        if(count($res>0)){
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay datos')));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//elimina los envios
$server->register(  'deleteEnvios', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#deleteEnvios', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de unidades para los articulos' // documentation
);

function deleteEnvios($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
		$shipping = $conexion->Query("SELECT id FROM shippingrequest WHERE id={0} AND status=1", array($data["shippingrequestid"]));
		if(count($shipping) == 0){
			return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No puede elimnar el envío, no tiene estado activo')));
		}
		$fecha = date('Y-m-d H:i:s');
        $conexion->Update("shippingrequest",array('deleted'=>$fecha, 'status' => 6, 'updated' => $fecha),"id={?}",array($data["shippingrequestid"]));
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Envio Eliminado')));
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

//devuelve las preguntas a los clientes
$server->register(  'getPreguntasCliente', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getPreguntasCliente', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene las preguntas a los clientes' // documentation
);

function getPreguntasCliente($datos){
    global $conexion;
	global $profile_image_width;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
        $resultData = $conexion->Query("SELECT question.id, question.shippingrequestid, question.shipperid,
			(SELECT title FROM shippingrequest WHERE id=question.shippingrequestid LIMIT 1) shippingrequesttitle,
			/*(SELECT person.company FROM shipper JOIN person ON person.id=shipper.personid WHERE shipper.id=question.shipperid) createdby,*/
			person.company shippername, person.img profileimage,
			/*(SELECT CASE WHEN person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END FROM shippingrequest JOIN person ON person.id=shippingrequest.requesterid WHERE shippingrequest.id=question.shippingrequestid) respondedby,*/
			question.body, question.createdat, question.answer, question.respondedat, question.updated
			FROM question
			LEFT JOIN shipper ON shipper.id=question.shipperid
			LEFT JOIN person ON person.id=shipper.personid
			WHERE question.shippingrequestid IN(SELECT id FROM shippingrequest WHERE requesterid={0} AND deleted IS NULL) AND (question.id NOT IN({1}) OR question.updated > '{2}')",
			array($data['requesterid'], $data['ids'], $data['fecha'])
		);
		if(count($resultData) > 0){
			foreach($resultData as &$row){
				if(!is_null($row['profileimage']) && $row['profileimage'] != ""){
					try{
						$archivo = createBase64Thumbnail("../$row[profileimage]", $profile_image_width);
						$row['profileimage'] = $archivo['base64'];
						$row['profileimageextension'] = $archivo['extension'];
					}catch(Exception $e){
						unset($row['profileimage']);
					}
				}else{
					unset($row['profileimage']);
				}
			}
			$respuesta['data'] = $resultData;
		}
		$idRows = $conexion->Query("SELECT id, updated FROM question
			WHERE question.shippingrequestid IN(SELECT id FROM shippingrequest WHERE requesterid={0} AND deleted IS NULL) ORDER BY updated DESC",
			array($data['requesterid'])
		);
		getUpdatedDeletedId($respuesta, $idRows, $data);
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

//Recibe la pregunta y respuesta de envío
$server->register(  'insertPregunta', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertPregunta', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recibe la pregunta y respuesta de envío' // documentation
);

function insertPregunta($datos){
    global $conexion;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
		$fecha = date('Y-m-d H:i:s');
		if(isset($data['questionid'])){
			$conexion->Update("question", array(
				'answer' => $data['answer'],
				'respondedat' => $fecha,
				'updated' => $fecha
			), "id={?}", array($data['questionid']));

            //Envia la alerta
            $trans=$conexion->Query("SELECT person.id,person.company,person.newreply,users.email,shippingrequest.title,question.body,question.shippingrequestid FROM question LEFT JOIN shipper ON shipper.id=question.shipperid LEFT JOIN person ON person.id=shipper.personid LEFT JOIN users ON users.personid=person.id LEFT JOIN shippingrequest ON shippingrequest.id=question.shippingrequestid WHERE question.id={0}",array($data["questionid"]));

            
            $cliente=$conexion->Query("SELECT person.company,person.firstname,person.lastname FROM question LEFT JOIN shippingrequest ON shippingrequest.id=question.shippingrequestid LEFT JOIN person ON person.id=shippingrequest.requesterid WHERE question.id={0}",array($data["questionid"]));
            $nombre=$cliente[0]["company"]==""?$cliente[0]["firstname"].' '.$cliente[0]["lastname"]:$cliente[0]["company"];
        
            $mensaje='<p>Hola <b>'.ucwords($trans[0]["company"]).'</b>.</p><p>Respondieron a tu pregunta en el envío '.$trans[0]["title"].'</p>';
            $mensaje.='<table>
                          <tr><td>Pregunta: </td><td>'.$trans[0]["body"].'</td></tr>
                          <tr><td>Respuesta </td><td>'.$data["answer"].'</td></tr>
                          <tr><td>Fecha y hora:  </td><td>'.formatearFecha($fecha).'</td></tr>
                      </table>';
            $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';             
            
            
            $alerta=enviarAlerta($trans[0]["id"],6,$trans[0]["shippingrequestid"],$data["questionid"],$trans[0]["newreply"],$trans[0]["email"],$mensaje,"Efletex - Respondieron tu pregunta");
            if($alerta!="insertado"){
                throw new Exception($alerta);
            }

		}else{
			$question=$conexion->Insert('question', array(
				'shippingrequestid' => $data['shippingrequestid'],
				'shipperid' => $data['shipperid'],
				'body' => $data['body'],
				'createdat' => $fecha,
				'updated' => $fecha
			));
			
            //Envia la alerta
			$cliente=$conexion->Query("SELECT person.id,person.newquestion,users.email,shippingrequest.title, 
            CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END personname 
            FROM shippingrequest LEFT JOIN person ON person.id=shippingrequest.requesterid LEFT JOIN users ON users.personid=person.id WHERE shippingrequest.id={0}",array($data["shippingrequestid"]));

            
            $transportista=$conexion->Query("SELECT person.company FROM person LEFT JOIN shipper ON shipper.personid=person.id WHERE shipper.id={0}",array($data["shipperid"]));
        
            $mensaje='<p>Hola <b>'.ucwords($cliente[0]["personname"]).'</b>.</p><p>El transportista '.$transportista[0]["company"].' hizo una pregunta en el envío '.$cliente[0]["title"].'.</p>';
            $mensaje.='<table>
                          <tr><td>Pregunta: </td><td>'.$data["body"].'</td></tr>
                          <tr><td>Fecha y hora:  </td><td>'.formatearFecha($fecha).'</td></tr>
                      </table>';
            $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';
        
            
            $alerta=enviarAlerta($cliente[0]["id"],5,$data["shippingrequestid"],$question[0]["id"],$cliente[0]["newquestion"],$cliente[0]["email"],$mensaje,"Efletex - Nueva Pregunta");
            if($alerta!="insertado"){
                throw new Exception($alerta);
            }
		}
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

//devuelve las preguntas hechas por el transportista
$server->register(  'getPreguntasTransportista', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getPreguntasTransportista', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene las preguntas a los clientes' // documentation
);

/**
 * @param shipperid
 * @param shippingrequestid
 * @param ids
 * @param fecha
 */
function getPreguntasTransportista($datos){
    global $conexion;
	global $profile_image_width;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
        $resultData = $conexion->Query("SELECT question.id, question.shippingrequestid, question.shipperid,
			(SELECT title FROM shippingrequest WHERE id=question.shippingrequestid LIMIT 1) shippingrequesttitle,
			/*(SELECT person.company FROM shipper JOIN person ON person.id=shipper.personid WHERE shipper.id=question.shipperid) createdby,
			(SELECT CASE WHEN person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END FROM shippingrequest JOIN person ON person.id=shippingrequest.requesterid WHERE shippingrequest.id=question.shippingrequestid) respondedby,*/
			person.company shippername, person.img profileimage,
			question.body, question.createdat, question.answer, question.respondedat, question.updated
			FROM question
			LEFT JOIN shipper ON shipper.id=question.shipperid
			LEFT JOIN person ON person.id=shipper.personid
			WHERE (question.shipperid={0} OR question.shippingrequestid={1}) AND (question.id NOT IN({2}) OR question.updated>'{3}')",
			array($data['shipperid'], $data['shippingrequestid'], $data['ids'], $data['fecha'])
		);
		if(count($resultData) > 0){
			foreach($resultData as &$row){
				if(!is_null($row['profileimage']) && $row['profileimage'] != ""){
					try{
						$archivo = createBase64Thumbnail("../$row[profileimage]", $profile_image_width);
						$row['profileimage'] = $archivo['base64'];
						$row['profileimageextension'] = $archivo['extension'];
					}catch(Exception $e){
						unset($row['profileimage']);
					}
				}else{
					unset($row['profileimage']);
				}
			}
			$respuesta['data'] = $resultData;
		}
		$idRows = $conexion->Query("SELECT question.id, question.updated FROM question
			WHERE (question.shipperid={0} OR question.shippingrequestid={1}) ORDER BY updated DESC",
			array($data['shipperid'], $data['shippingrequestid'])
		);
		getUpdatedDeletedId($respuesta, $idRows, $data);
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

//devuelve el listado de envios para los transportistas
$server->register(  'getBuscarCargas', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getBuscarCargas', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve la busqueda de cargas de los transportistas. <br>Definicion: collectionCountry integer, collectionState integer, collectionCity string, deliveryCountry integer, deliveryState integer, deliveryCity string, collectionDate date, deliveryDate date, costinit integer, costmax integer, open boolean <br> 
                        Cadena: "datos"=>{"collectionCountry":"", "collectionState":"", "collectionCity":"", "deliveryCountry":"", "deliveryState":"", "deliveryCity":"", "collectionDate":"", "deliveryDate":"", "costinit":"", "costmax":"", "open":""}' // documentation
);

function getBuscarCargas($datos){
    //file_put_contents("log.txt", $datos, FILE_APPEND);
    global $conexion;
	global $profile_image_width;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');

    $whereOrigen="";
    $whereDestino="";
    $where="";

    //filtros Origen
    if($data["collectionCountry"]!=0)$whereOrigen="AND collectionState.countryid=".$data["collectionCountry"];
    
    if($data["collectionState"]!=0)$whereOrigen=" AND collection.stateid=".$data["collectionState"];
    
    if($data["collectionCity"]!="")$whereOrigen.=" AND LOWER(collection.city) like LOWER('%%".$data["collectionCity"]."%%')";

    if($data["collectionDate"]!="")$whereOrigen.=" AND collection.collectiondate='".$data["collectionDate"]."'";
                
           
    //filtros Destino
    if($data["deliveryCountry"]!=0)$whereDestino="AND deliveryState.countryid=".$data["deliveryCountry"];
    
    if($data["deliveryState"]!=0) $whereDestino="AND delivery.stateid=".$data["deliveryState"];
    
    if($data["deliveryCity"]!="")$whereDestino.=" AND LOWER(delivery.city) like LOWER('%%".$data["deliveryCity"]."%%')"; 

    if($data["deliveryDate"]!="")$whereDestino.=" AND delivery.deliverydate='".$data["deliveryDate"]."'";
         
                
    //filtro general
    if($data["costmax"]!=0){
        $where.=" AND cost BETWEEN '".$data['costinit']."' AND '".$data['costmax']."'";
    }
    else if($data["costmax"]==0 && $data["costinit"]==0){
        $where.=" AND costtype=1";
    }
    else{
        if($data["costinit"]>0)$where.=" AND cost >= ".$data["costinit"];
    }

    if($data["open"]){
        $where.=$where==""?" AND costtype=2":" OR costtype=2";
    }


    $preferences=$conexion->Query("SELECT administrativeunitid FROM preferences WHERE personid={0}",array($data["personid"]));
    $administrativeUnitId    = array();
    $administrativeUnitQuery = '';

    if (count($preferences > 0)) {
        foreach ($preferences as $preference) {
            $administrativeUnitId[] = $preference["administrativeunitid"];
        }

        $implode = '(' . implode(',', $administrativeUnitId) . ')';
        $administrativeUnitQuery = ' AND collection.stateid IN ' . $implode . ' AND delivery.stateid IN ' . $implode;
        $administrativeUnitQuery2 = ' AND stateidOrigen IN ' . $implode . ' AND stateidDestino IN ' . $implode;
    }


    $sqlOrigen="SELECT shipping.id,shipping.title,shipping.status,shipping.expirationdate,shipping.createdat,shipping.coldprotection,shipping.sortout,shipping.blindperson,shipping.costtype,shipping.totalprice,shipping.cost,shipping.firstofferdiscount,shipping.discountrate,shipping.km,shipping.tiempo,shipping.peso_total,
            shipping.updated, collection.contactfullname,collection.contactnumber1,collectionState.name stateOrigen, shipping.requesterid,
            collectionCountry.name countryOrigen, collection.latitude latitudOrigen, collection.longitude longitudOrigen, collection.collectiondate collectiondateOrigen,collection.collectionuntildate collectionuntildateOrigen,collection.place placeOrigen,collection.elevator elevatorOrigen,collection.collectinside collectinsideOrigen,collection.city cityOrigen,collection.collecttimefrom collecttimefromOrigen,collection.collecttimeuntil collecttimeuntilOrigen, collection.street1 street1Origen,collection.stateid stateidOrigen, 
            delivery.recipientfullname recipientfullnameDestino,delivery.recipientcontactnumber1 recipientcontactnumber1Destino,delivery.street1 street1Destino,delivery.stateid stateidDestino,deliveryState.name stateDestino,deliveryCountry.name countryDestino,
            delivery.longitude longitudDestino, delivery.latitude latitudDestino,delivery.deliverydate deliverydateDestino,delivery.deliveryuntildate deliveryuntildateDestino, delivery.place placeDestino,delivery.elevator elevatorDestino,delivery.callbefore callbeforeDestino,delivery.deliverywithin deliverywithinDestino,delivery.deliverytimefrom deliverytimeDestino,delivery.deliverytimeuntil deliverytimeuntilDestino,delivery.city cityDestino,
			/*(SELECT CASE WHEN company='' OR company IS NULL THEN CONCAT(firstname, ' ', lastname) ELSE company END FROM person WHERE id=shipping.requesterid) requestername*/
			CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername, person.starrating, 
            shipping.ispublic, collection.anotherplace anotherplaceOrigen, delivery.anotherplace anotherplaceDestino,shipping.paymentmethodid, shipping.paymentconditions, 
            shipment.trackingtype,shipping.paymentmethodid, shipping.paymentconditions, (SELECT method FROM paymentmethod WHERE id=shipping.paymentmethodid) paymentmethod,
            person.img profileimage
            FROM shippingrequest shipping
            LEFT JOIN deliveryaddress delivery on delivery.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit deliveryState ON deliveryState.id=delivery.stateid
            LEFT JOIN country deliveryCountry ON deliveryCountry.id=deliveryState.countryid 
            LEFT JOIN collectionaddress collection ON collection.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit collectionState ON collectionState.id=collection.stateid 
            LEFT JOIN country collectionCountry ON collectionCountry.id=collectionState.countryid
			LEFT JOIN person ON person.id=shipping.requesterid 
            LEFT JOIN shipment ON shipment.shippingrequestid=shipping.id 
            WHERE shipping.status=1 AND shipping.expirationdate>'$fechaactual' {0}";

    $sqlDestino="SELECT shipping.id,shipping.title,shipping.status,shipping.expirationdate,shipping.createdat,shipping.coldprotection,shipping.sortout,shipping.blindperson,shipping.costtype,shipping.totalprice,shipping.cost,shipping.firstofferdiscount,shipping.discountrate,shipping.km,shipping.tiempo,shipping.peso_total,
            shipping.updated, collection.contactfullname,collection.contactnumber1,collectionState.name stateOrigen, shipping.requesterid,
            collectionCountry.name countryOrigen, collection.latitude latitudOrigen, collection.longitude longitudOrigen, collection.collectiondate collectiondateOrigen,collection.collectionuntildate collectionuntildateOrigen,collection.place placeOrigen,collection.elevator elevatorOrigen,collection.collectinside collectinsideOrigen,collection.city cityOrigen,collection.collecttimefrom collecttimefromOrigen,collection.collecttimeuntil collecttimeuntilOrigen, collection.street1 street1Origen,collection.stateid stateidOrigen, 
            delivery.recipientfullname recipientfullnameDestino,delivery.recipientcontactnumber1 recipientcontactnumber1Destino,delivery.street1 street1Destino,delivery.stateid stateidDestino,deliveryState.name stateDestino,deliveryCountry.name countryDestino,
            delivery.longitude longitudDestino, delivery.latitude latitudDestino,delivery.deliverydate deliverydateDestino,delivery.deliveryuntildate deliveryuntildateDestino, delivery.place placeDestino,delivery.elevator elevatorDestino,delivery.callbefore callbeforeDestino,delivery.deliverywithin deliverywithinDestino,delivery.deliverytimefrom deliverytimeDestino,delivery.deliverytimeuntil deliverytimeuntilDestino,delivery.city cityDestino,
			/*(SELECT CASE WHEN company='' OR company IS NULL THEN CONCAT(firstname, ' ', lastname) ELSE company END FROM person WHERE id=shipping.requesterid) requestername*/
			CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername, person.starrating,
             shipping.ispublic, collection.anotherplace anotherplaceOrigen, delivery.anotherplace anotherplaceDestino,
             shipping.paymentmethodid, shipping.paymentconditions, (SELECT method FROM paymentmethod WHERE id=shipping.paymentmethodid) paymentmethod, 
            person.img profileimage
            FROM shippingrequest shipping
            LEFT JOIN deliveryaddress delivery on delivery.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit deliveryState ON deliveryState.id=delivery.stateid
            LEFT JOIN country deliveryCountry ON deliveryCountry.id=deliveryState.countryid 
            LEFT JOIN collectionaddress collection ON collection.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit collectionState ON collectionState.id=collection.stateid 
            LEFT JOIN country collectionCountry ON collectionCountry.id=collectionState.countryid
			LEFT JOIN person ON person.id=shipping.requesterid
            WHERE shipping.status=1 AND shipping.expirationdate>'$fechaactual' {1}";
    try{

        if($whereOrigen!=""){
            if($whereDestino!=""){
                $sql=$sqlOrigen.' UNION ('.$sqlDestino.')';
            }
            else{
                $sql=$sqlOrigen;
            }
        }
        else if($whereDestino!=""){
            $sql=$sqlDestino;
        }
        else if($where!=""){
            $sql=$sqlOrigen;
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No ha seleccionado filtros')));
        }
        $conexion->write_sql();
        $res=$conexion->Query("SELECT * FROM ($sql)envios WHERE 1=1 {2} {3}",array($whereOrigen,$whereDestino,$where,$administrativeUnitQuery2));
        if(count($res)>0){
            
            for($i=0;$i<count($res);$i++){
                $res[$i]["images"]=array();
                $items=$conexion->Query("SELECT * FROM shippingitem WHERE shippingrequestid={0}",array($res[$i]["id"]));
                $res[$i]["items"]=$items;
                foreach($items as $itemid){
                    $images=$conexion->Query("SELECT * FROM fileattachment WHERE shippingitemid={0}",array($itemid["id"]));

                    foreach($images as $img){
                        $imgBase64=base64_encode(file_get_contents('../imagenesEnvio/'.$res[$i]["id"].'/'.$img['filename']));
                        $img["imagen_64"]=$imgBase64;
                        $res[$i]["images"][]=$img;
                    }
                }
				if(!is_null($res[$i]['profileimage']) && $res[$i]['profileimage'] != ""){
					try{
						$archivo = createBase64Thumbnail("../".$res[$i]['profileimage'], $profile_image_width);
						$res[$i]['profileimage'] = $archivo['base64'];
						$res[$i]['profileimageextension'] = $archivo['extension'];
					}catch(Exception $e){
						unset($res[$i]['profileimage']);
					}
				}else{
					unset($res[$i]['profileimage']);
				}
                $res[$i]["grupos"]=array();
                $grupos=$conexion->Query("SELECT groupid FROM shippingrequestgroup WHERE shippingrequestid={0}",array($res[$i]["id"]));
                $res[$i]["grupos"]=$grupos;
            }
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay envios nuevos'),'data'=>''));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage()),'data'=>''));
    }
}


//inserta las ofertas del transportista
$server->register(  'insertOferta', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertOferta', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta un Envio. <br>Definicion:<br> shipperid integer,
  shippingrequestid integer,
  shipmentcost real,
  conditions string,
  collectiondate date,
  collectionuntildate date,
  collectiontype integer (1=en,2=antes,3=entre), 
  deliverydate date,
  deliveryuntildate date,
  deliverytype integer (1=en,2=antes,3=entre),paymentmethodid integer,paymentinformation string,offercost decimal <br> Cadena: "datos"=>{"shipperid":"", "shippingrequestid":"", "shipmentcost":"", "conditions":"", "collectiondate":"", "collectionuntildate":"", "collectiontype":"", "deliverydate":"", "deliveryuntildate":"", "deliverytype":"", "paymentmethodid":"", "paymentinformation":"","offercost":""}'  // documentation
);

function insertOferta($datos) {
    global $conexion;
    $data=json_decode($datos,true);
    $conexion->StartTransaction();
    try{      
        $shipping=$conexion->Query("SELECT status,totalprice,costtype,paymentmethodid,ispublic FROM shippingrequest WHERE id={0}",array($data["shippingrequestid"]));
        $idpago=$shipping[0]["paymentmethodid"];
        //valida que el envio siga activo
        if($shipping[0]["status"]!=1){
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'La publicación ya no esta disponible y no puede recibir ofertas')));
        }

        //valida que el envio no haya expirado
        $expira=$conexion->Query("SELECT expirationdate FROM shippingrequest WHERE id={0}",array($data["shippingrequestid"]));
        $fechaexpiracion=$expira[0]["expirationdate"];
        $fechaactual=date('Y-m-d H:i:s');
        if(strtotime($fechaexpiracion)<strtotime($fechaactual)){
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'La publicación ha expirado y no puede recibir ofertas')));
        }
        
        //validamos que tenga saldo
        $saldo=$conexion->Query("SELECT available FROM shipperaccount WHERE shipperid={0}",array($data["shipperid"]));
        if($saldo[0]["available"]<$data["offercost"]){
            return json_encode(array('datos'=>array('error'=>'10','mensaje'=>'No tiene saldo disponible para ofertar')));
        }
        
        //valida el descuento de la primer oferta
        if($shipping[0]["costtype"]==1){
            $costo=$shipping[0]["totalprice"];
            $primeraoferta=$conexion->Query("SELECT id FROM serviceoffer WHERE shippingrequestid={0} AND status=1",array($data["shippingrequestid"]));
            if(count($primeraoferta)==0){
                //valida la primer oferta
                if($data["shipmentcost"]>$costo){
                    return json_encode(array('datos'=>array('error'=>'110','mensaje'=>'El precio de la oferta debe ser menor o igual a '.$costo)));
                }
            }else{
                //valida que sea menor a la oferta más baja
                $minoffer=$conexion->Query("SELECT MIN(shipmentcost) as cost FROM serviceoffer WHERE shippingrequestid={0} AND status=1",array($data["shippingrequestid"]));
                if($data["shipmentcost"]>=$minoffer[0]["cost"]) return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'Ya hay otra oferta. El precio de la oferta ahora debe ser menor a '.$minoffer[0]["cost"])));
            }
        }

        //cancela la oferta anterior del transportista
        $existe=$conexion->Query("SELECT id,offerprice FROM serviceoffer WHERE shipperid={0} AND shippingrequestid={1} AND status=1",array($data["shipperid"],$data["shippingrequestid"]));
        $idoferta=$existe[0]["id"];
        if(count($existe)>0){
            $conexion->Update("serviceoffer",array("status"=>4),"id={?}",array($idoferta));
            $conexion->Query("UPDATE shipperaccount SET available = available + ".$existe[0]["offerprice"]." WHERE shipperid={0}",array($data["shipperid"]));
        }
            $insertar=array(
                "shipperid"=>$data["shipperid"],
                "shippingrequestid"=>$data["shippingrequestid"],
                "shipmentcost"=>$data["shipmentcost"],
                "currencyid"=>1,
                "conditions"=>$data["conditions"],
                "collectiondate"=>$data["collectiondate"],
                "collectiontype"=>$data["collectiontype"],
                "deliverydate"=>$data["deliverydate"],
                "deliverytype"=>$data["deliverytype"],
                "status"=>1,
                "createdat"=>$fechaactual,
                "updated"=>$fechaactual,
                "paymentinformation"=>$data["paymentinformation"],
                "offerprice"=>$data["offercost"]
            );
            
            if($data["collectionuntildate"]!=""){
                $insertar["collectionuntildate"]=$data["collectionuntildate"];
            }
            if($data["deliveryuntildate"]!=""){
                $insertar["deliveryuntildate"]=$data["deliveryuntildate"];
            }
            if($idpago!=""){
                $insertar["paymentmethodid"]=$idpago;
            }

            $nuevaOferta=$conexion->Insert("serviceoffer",$insertar);
            
            //Se actualiza la cuenta
            $conexion->Query("UPDATE shipperaccount SET available = available - ".$data["offercost"]." WHERE shipperid={0}",array($data["shipperid"]));


            //Envia la alerta
            $cliente=$conexion->Query("SELECT person.id,person.newoffer,users.email,shippingrequest.title,
            CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername 
            FROM shippingrequest LEFT JOIN person ON person.id=shippingrequest.requesterid LEFT JOIN users ON users.personid=person.id WHERE shippingrequest.id={0}",array($data["shippingrequestid"]));


                $transportista=$conexion->Query("SELECT person.company,person.id FROM person LEFT JOIN shipper ON shipper.personid=person.id WHERE shipper.id={0}",array($data["shipperid"]));

                $fechaOrigen="";
                if($data["collectiontype"]==1){
                    $fechaOrigen='El '.formatearFecha($data["collectiondate"]);
                }
                else{
                    $fechaOrigen='Entre el '.formatearFecha($data["collectiondate"]).' y el '.formatearFecha($data["collectionuntildate"]);  
                }

                $fechaDestino="";
                if($data["deliverytype"]==1){
                    $fechaDestino='El '.formatearFecha($data["deliverydate"]);
                }
                else{
                    $fechaDestino='Entre el '.formatearFecha($data["deliverydate"]).' y el '.formatearFecha($data["deliveryuntildate"]);  
                }
                
                $mensaje='<p>Hola <b>'.ucwords($cliente[0]["requestername"]).'</b>.</p><p>Su envío: <b>'.$cliente[0]["title"].'</b> recibió una oferta</p>';
                $mensaje.='<table>
                              <tr><td>Transportista: </td><td>'.$transportista[0]["company"].'</td></tr>
                              <tr><td>Fecha(s) de recolección: </td><td>'.$fechaOrigen.'</td></tr>
                              <tr><td>Fecha(s) de entrega: </td><td>'.$fechaDestino.'</td></tr>
                              <tr><td>Precio: </td><td>S/ '.$data["shipmentcost"].'</td></tr>
                              <tr><td>Condiciones: </td><td>'.$data["conditions"].'</td></tr>
                          </table>';
                $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';
            

            $alerta=enviarAlerta($cliente[0]["id"],1,$data["shippingrequestid"],$nuevaOferta[0]["id"],$cliente[0]["newoffer"],$cliente[0]["email"],$mensaje,"Notificaciones Efletex - Nueva Oferta");
            if($alerta!="insertado"){
                throw new Exception($alerta);
            }
            
            $competencia=$conexion->Query("SELECT DISTINCT users.email,person.id FROM serviceoffer 
            LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid 
            LEFT JOIN person ON person.id=shipper.personid 
            LEFT JOIN users ON users.personid=person.id WHERE serviceoffer.shippingrequestid={0} 
            AND person.id NOT IN ({1})",array($data["shippingrequestid"],$transportista[0]["id"]));
            
            $msj="<p>Publicaron una oferta en un envío donde estas compitiendo.</p>
        <table><tr><td>Envío :</td><td>".$cliente[0]["title"]."</td></tr>
        <tr><td>Precio :</td><td>S/ ".$data["shipmentcost"]."</td></tr></table>
        
        <p><b>Para más detalles:</b>&nbsp;<a href='".$_SERVER['HTTP_HOST']."transportista/ofertas/".$data["shippingrequestid"]."/detalle'>clic aqui</a>.</p>";
            foreach($competencia as $row){
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
                $mail->setFrom('noreply@efletex.com', 'Efletex');
                $mail->addAddress($row["email"]);
                                
                     // Add a recipient

                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = "Notificaciones Efletex - Nueva oferta de competencia";
                $mail->Body    = '<html><body>'.$msj.'</body></html>';
                $mail->AltBody = 'Revisa este correo desde otro navegador para poder ver la liga de confirmación.';

                
                if(!$mail->send()) {
                    
                    throw new Exception('No se pudo enviar el correo');
                }
                
                $conexion->Insert("alert",array(
                     'recipientid'=>$row["id"],
                     'createdat'=>$fechaactual,
                     'updated'=>$fechaactual,
                     'type'=>15,
                     'relationid'=>$data["shippingrequestid"],
                     'relationid2'=>$nuevaOferta[0]["id"]));
            }
            
			$conexion->Commit();

            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Oferta Publicada')));

    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }

}


//devuelve el listado de ofertas del transportista
$server->register(  'getOfertas', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getOfertas', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve el listado de Ofertas del transportista de los Envíos activos.<br>
                    Definicion: shipperid integer, updated datatime'  // documentation
);

function getOfertas($datos) {
    global $conexion;
    $data=json_decode($datos,true);
    $nuevafecha = strtotime ( '-15 day' , strtotime ( $data["fecha"] ) ) ;
    $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
    try{
        if($data["isshipper"]){
            $res=$conexion->Query("SELECT serviceoffer.*,person.firstname,person.lastname,person.secondlastname,person.company,shippingrequest.title FROM serviceoffer LEFT JOIN shippingrequest ON shippingrequest.id=serviceoffer.shippingrequestid LEFT JOIN person ON person.id=shippingrequest.requesterid WHERE serviceoffer.shipperid={0} AND serviceoffer.updated>='$nuevafecha'",array($data["shipperid"]));
            if(count($res)>0){
    
    
                $updated=$conexion->Query("SELECT MAX(serviceoffer.updated) as updated FROM serviceoffer LEFT JOIN shippingrequest ON shippingrequest.id=serviceoffer.shippingrequestid WHERE serviceoffer.shipperid={0} AND serviceoffer.status<>'4' AND serviceoffer.updated>='$nuevafecha' AND shippingrequest.status=1",array($data["shipperid"]));
    
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res,'updated'=>$updated[0]["updated"]));
            }
            else{
                return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No tiene ofertas recientes o los envíos donde ofertó ya no están disponibles')));   
            }
        }
        else{
            $res=$conexion->Query("SELECT serviceoffer.*,person.firstname,person.lastname,person.secondlastname,person.company,shippingrequest.title FROM serviceoffer LEFT JOIN shippingrequest ON shippingrequest.id=serviceoffer.shippingrequestid LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid LEFT JOIN person ON person.id=shipper.personid WHERE shippingrequest.requesterid={0} AND serviceoffer.updated>='$nuevafecha'",array($data["shipperid"]));
            if(count($res)>0){
    
    
                $updated=$conexion->Query("SELECT MAX(serviceoffer.updated) as updated FROM serviceoffer LEFT JOIN shippingrequest ON shippingrequest.id=serviceoffer.shippingrequestid WHERE serviceoffer.shipperid={0} AND serviceoffer.status<>'4' AND serviceoffer.updated>='$nuevafecha' AND shippingrequest.status=1",array($data["shipperid"]));
    
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res,'updated'=>$updated[0]["updated"]));
            }
            else{
                return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No tiene ofertas recientes o los envíos donde ofertó ya no están disponibles')));   
            }
        }

    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }

}

/** devuelve las preguntas a los clientes
 * @param requesterid
 * @param ids
 * @param fecha
 */
$server->register(  'getOfertasCliente', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getOfertasCliente', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene las ofertas a los clientes' // documentation
);

function getOfertasCliente($datos){
    global $conexion;
	global $profile_image_width;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
        $resultData = $conexion->Query("SELECT serviceoffer.id, serviceoffer.shippingrequestid, serviceoffer.shipperid, serviceoffer.shipmentcost, serviceoffer.currencyid, serviceoffer.conditions, serviceoffer.collectiondate,
			serviceoffer.collectionuntildate, serviceoffer.collectiontype, serviceoffer.deliverydate, serviceoffer.deliveryuntildate, serviceoffer.deliverytype, serviceoffer.status, serviceoffer.createdat, serviceoffer.reasonrejection, serviceoffer.updated,
			serviceoffer.paymentmethodid, serviceoffer.paymentinformation, (SELECT method FROM paymentmethod WHERE id=serviceoffer.paymentmethodid) paymentmethod,
			(SELECT symbol FROM currency WHERE id=serviceoffer.currencyid) currencysymbol,
			/*(SELECT person.company FROM shipper JOIN person ON person.id=shipper.personid WHERE shipper.id=serviceoffer.shipperid) shippername,*/
			/*(SELECT CASE WHEN person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END FROM shippingrequest JOIN person ON person.id=shippingrequest.requesterid WHERE shippingrequest.id=serviceoffer.shippingrequestid) requestername*/
			person.company shippername, person.starrating, person.img profileimage
			FROM serviceoffer
			LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid
			LEFT JOIN person ON person.id=shipper.personid
			WHERE serviceoffer.status<>4 AND serviceoffer.shippingrequestid IN(SELECT id FROM shippingrequest WHERE requesterid={0} AND deleted IS NULL AND status=1) AND (serviceoffer.id NOT IN({1}) OR serviceoffer.updated > '{2}')",
			array($data['requesterid'], $data['ids'], $data['fecha'])
		);
		if(count($resultData) > 0){
			foreach($resultData as &$row){
				if(!is_null($row['profileimage']) && $row['profileimage'] != ""){
					try{
						$archivo = createBase64Thumbnail("../$row[profileimage]", $profile_image_width);
						$row['profileimage'] = $archivo['base64'];
						$row['profileimageextension'] = $archivo['extension'];
					}catch(Exception $e){
						unset($row['profileimage']);
					}
				}else{
					unset($row['profileimage']);
				}
			}
			$respuesta['data'] = $resultData;
		}
		$idRows = $conexion->Query("SELECT id, updated FROM serviceoffer
			WHERE status<>4 AND shippingrequestid IN(SELECT id FROM shippingrequest WHERE requesterid={0} AND deleted IS NULL AND status=1) ORDER BY updated DESC",
			array($data['requesterid'])
		);
		getUpdatedDeletedId($respuesta, $idRows, $data);
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

//devuelve las ofertas hechas por el transportista
$server->register(  'getOfertasTransportista', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getOfertasTransportista', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene las preguntas a los clientes' // documentation
);

/**
 * @param shipperid
 * @param shippingrequestid
 * @param ids
 * @param fecha
 */
function getOfertasTransportista($datos){
    global $conexion;
	global $profile_image_width;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
        $resultData = $conexion->Query("SELECT serviceoffer.id, serviceoffer.shippingrequestid, serviceoffer.shipperid, serviceoffer.shipmentcost, serviceoffer.currencyid, serviceoffer.conditions, serviceoffer.collectiondate,
			serviceoffer.collectionuntildate, serviceoffer.collectiontype, serviceoffer.deliverydate, serviceoffer.deliveryuntildate, serviceoffer.deliverytype, serviceoffer.status, serviceoffer.createdat, serviceoffer.reasonrejection, serviceoffer.updated,
			paymentmethodid, paymentinformation, (SELECT method FROM paymentmethod WHERE id=serviceoffer.paymentmethodid) paymentmethod,
			(SELECT symbol FROM currency WHERE id=serviceoffer.currencyid) currencysymbol,
			/*(SELECT person.company FROM shipper JOIN person ON person.id=shipper.personid WHERE shipper.id=serviceoffer.shipperid) shippername,
			(SELECT CASE WHEN person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END FROM shippingrequest JOIN person ON person.id=shippingrequest.requesterid WHERE shippingrequest.id=serviceoffer.shippingrequestid) requestername*/
			person.company shippername, person.starrating, person.img profileimage
			FROM serviceoffer
			LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid
			LEFT JOIN person ON person.id=shipper.personid
			WHERE serviceoffer.status<>4 AND (serviceoffer.shipperid={0} OR serviceoffer.shippingrequestid={1}) AND (serviceoffer.id NOT IN({2}) OR serviceoffer.updated>'{3}')",
			array($data['shipperid'], $data['shippingrequestid'], $data['ids'], $data['fecha'])
		);
		if(count($resultData) > 0){
			foreach($resultData as &$row){
				if(!is_null($row['profileimage']) && $row['profileimage'] != ""){
					try{
						$archivo = createBase64Thumbnail("../$row[profileimage]", $profile_image_width);
						$row['profileimage'] = $archivo['base64'];
						$row['profileimageextension'] = $archivo['extension'];
					}catch(Exception $e){
						unset($row['profileimage']);
					}
				}else{
					unset($row['profileimage']);
				}
			}
			$respuesta['data'] = $resultData;
		}
		$idRows = $conexion->Query("SELECT id, updated FROM serviceoffer
			WHERE status<>4 AND (shipperid={0} OR shippingrequestid={1}) ORDER BY updated DESC",
			array($data['shipperid'], $data['shippingrequestid'])
		);
		getUpdatedDeletedId($respuesta, $idRows, $data);
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}


$server->register(  'getPass', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getPass', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recibe el email y envia un correo electrónico para el reseteo de la contraseña' // documentation
);

function getPass($datos){
    global $conexion;
    $conexion->StartTransaction();
    try{
        $data=json_decode($datos,true);
        $email=trim($data["email"]);
        $res=$conexion->Query("SELECT users.id,person.firstname, person.lastname FROM users LEFT JOIN person ON person.id=users.personid WHERE users.email='{0}'",array($email));
        if(count($res)>0){
            $cliente=ucwords($res[0]["firstname"]). " " . ucwords($res[0]["lastname"]);
            $token=password_hash($cliente.rand(1,100000),PASSWORD_DEFAULT);
            $token=str_replace("/","",$token);

            $conexion->Update("users",array("tokentemp"=>$token),"id={?}",array($res[0]["id"]));

            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'host.recargasys.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'noreply@efletex.com';                 // SMTP username
            $mail->Password = 'OSg)LeG[-lnu';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('noreply@efletex.com', 'Efletex');
            $mail->addAddress($data["email"], $data["firstname"].' '.$data["lastname"]);     // Add a recipient

            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Efletex - Reestablecer contraseña';
            $mail->Body    = '<html>
                <body>
                    <b>Hola '.ucwords($cliente).'</b><br><br>
                    Para poder ingresar a Efletex deberas establecer una nueva contraseña <a href="http://'.$_SERVER['HTTP_HOST'].'/crearcuenta/reestablecerContrasena/'.$email.'/'.$token .'">aquí</a>.
                    
                </body>
            </html>';
            $mail->AltBody = 'Revisa este correo desde otro navegador para poder ver la liga de confirmación.';

            if(!$mail->send()) {
                throw new Exception("No se pudo enviar el correo para reestablecer la constraseña");
                
            } else {
                $conexion->Commit();
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'enviado')));
            }
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'Usuario no existe'),'data'=>''));
        }
    }catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage()),'data'=>''));
    }
}


//devuelve el listado de ofertas del transportista
$server->register(  'insertStatusOferta', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertStatusOferta', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Cambia el status de la oferta, los valores para el status son 2=Aceptado, 3=Rechazado, 4=Cancelado <br> Definición: offerid integer, status integer, reason string (solo se manda cuando sea rechazo)<br> Cadena: "datos"=>{"offerid":"", "status":"", "reason":""}'  // documentation
);

function insertStatusOferta($datos) {
    global $conexion;
    $data=json_decode($datos,true);
    $conexion->StartTransaction();
    $fechaactual=date('Y-m-d H:i:s');
    try{
        $isactive=$conexion->Query("SELECT shipperid,status,shipmentcost,paymentmethodid,offerprice FROM serviceoffer WHERE id={0}",array($data["offerid"]));
        if($isactive[0]["status"]==1){
            $update=array("status"=>$data["status"],'updated'=>$fechaactual);
            $res="";
            switch($data["status"]){
                case "2":$res="Aceptada";break;
                case "3":$res="Rechazada";$update["reasonrejection"]=$data["reason"];break;
                case "4":$res="Cancelada"; break;
            } 
            $conexion->Update("serviceoffer",$update,"id={?}",array($data["offerid"]));
            //oferta aceptada
            if($data["status"]=="2"){
                
                //si la oferta fue aceptada, el envio se reserva
                $shippingrequest=$conexion->Query("SELECT shippingrequestid 
                FROM serviceoffer WHERE id={0}",array($data["offerid"]));
                $idShippingRequest=$shippingrequest[0]["shippingrequestid"];
                $pago=$conexion->query("SELECT paymentmethodid FROM shippingrequest WHERE id=$idShippingRequest");
                $idpago=$pago[0]["paymentmethodid"];
                $conexion->Update("shippingrequest",array("status"=>2,"updated"=>$fechaactual),"id={?}",array($idShippingRequest));
                //se pasan las relaciones a shipment
                
                $insert=array(
                    "shippingrequestid"=>$idShippingRequest,
                    "acceptedserviceofferid"=>$data["offerid"],
                    "shipmentcost"=>$isactive[0]["shipmentcost"],
                    "currencyid"=>1,
                    "servicefee"=>0,
                    "servicefeeaspercentageofshipmentcost"=>0,
                    "financingcompanyid"=>1,
                    "paymentpromotionid"=>1,
                    "copyofshippertaxinformation"=>'',
                    "createdat"=>$fechaactual,
                    "updated"=>$fechaactual
                );
                
                if($idpago!=""){
                    $insert["paymentmethodid"]=$idpago;
                }
                
                $conexion->Insert("shipment",$insert);
                
                //se descuenta la cuota de oferta
                $conexion->Query("UPDATE shipperaccount SET balance= balance - ".$isactive[0]["offerprice"]." WHERE shipperid={0}",array($isactive[0]["shipperid"]));
                
                //se guarda el historial de la cuenta
                $conexion->Insert("shipperaccountdetail",array("shipperid"=>$isactive[0]["shipperid"],"amount"=>$isactive[0]["offerprice"],"created"=>$fechaactual,"shippingrequestid"=>$idShippingRequest));

                //se inserta el status en el log
                $conexion->Insert("shippingrequestlog",array("shippingrequestid"=>$idShippingRequest,"status"=>"2","createdat"=>$fechaactual,"updated"=>$fechaactual));
  
                $oferta=$conexion->Query("SELECT users.email,person.offeraccepted,serviceoffer.*,person.id as personid,person.company FROM serviceoffer LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid LEFT JOIN person ON person.id=shipper.personid LEFT JOIN users ON users.personid=person.id WHERE serviceoffer.id={0}",array($data["offerid"]));
                $oferta=$oferta[0];
                $ship=$conexion->Query("SELECT shippingrequest.title,person.company,person.firstname,person.lastname FROM shippingrequest LEFT JOIN serviceoffer ON serviceoffer.shippingrequestid=shippingrequest.id LEFT JOIN person ON person.id=shippingrequest.requesterid WHERE serviceoffer.id={0}",array($data["offerid"]));
                
                //se rechazan los activos que quedaron
                $rechazados=$conexion->Query("SELECT users.email,person.id,person.company,serviceoffer.shipmentcost,serviceoffer.collectiontype,
                serviceoffer.collectiondate,serviceoffer.collectionuntildate,serviceoffer.deliverytype,serviceoffer.deliverydate,
                serviceoffer.deliveryuntildate,serviceoffer.conditions,serviceoffer.offerprice,serviceoffer.shipperid FROM serviceoffer 
                LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid 
                LEFT JOIN person ON person.id=shipper.personid 
                LEFT JOIN users ON users.personid=person.id  
                WHERE serviceoffer.status=1 AND serviceoffer.shippingrequestid=$idShippingRequest");
                $reason="El envío ya fue asignado a otro transportista";
                $conexion->Query("UPDATE serviceoffer SET status=3,reasonrejection='{0}',updated='{1}' WHERE id<>{2} AND status=1 AND shippingrequestid={3}",array($reason,$fechaactual,$data["offerid"],$idShippingRequest));

                $fechaOrigen="";
                if($oferta["collectiontype"]==1){
                    $fechaOrigen='El '.formatearFecha($oferta["collectiondate"]);
                }
                else{
                    $fechaOrigen='Entre el '.formatearFecha($oferta["collectiondate"]).' y el '.formatearFecha($oferta["collectionuntildate"]);  
                }

                $fechaDestino="";
                if($oferta["deliverytype"]==1){
                    $fechaDestino='El '.formatearFecha($oferta["deliverydate"]);
                }
                else{
                    $fechaDestino='Entre el '.formatearFecha($oferta["deliverydate"]).' y el '.formatearFecha($oferta["deliveryuntildate"]);  
                }

                //$cliente=$ship[0]["company"]==""?$ship[0]["firstname"].' '.$ship[0]["lastname"]:$ship[0]["company"];
                $mensaje='<p>Hola <b>'.ucwords($oferta["company"]).'</b>.</p><p>La oferta que hizo para el envío '.$ship[0]["title"].' fue aceptada el '.fechaF1Hora($fechaactual);
                $mensaje.='<table>
                              <tr><td>Fecha(s) de Recojo: </td><td>'.$fechaOrigen.'</td></tr>
                              <tr><td>Fecha(s) de Entrega: </td><td>'.$fechaDestino.'</td></tr>
                              <tr><td>Precio: </td><td>S/ '.$oferta["shipmentcost"].'</td></tr>
                              <tr><td>Condiciones: </td><td>'.$oferta["conditions"].'</td></tr>
                          </table>';
                $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';

                $alerta=enviarAlerta($oferta["personid"],3,$oferta["shippingrequestid"],'',$oferta["offeraccepted"],$oferta["email"],$mensaje,"Notificaciones Efletex - Oferta Aceptada");
                if($alerta!="insertado"){
                    throw new Exception('No se pudo enviar el correo');
                }
                
                //se envia el correo a los rechazados
                foreach($rechazados as $row){
                    $fechaOrigen="";
                    if($row["collectiontype"]==1){
                        $fechaOrigen='El '.formatearFecha($row["collectiondate"]);
                    }
                    else{
                        $fechaOrigen='Entre el '.formatearFecha($row["collectiondate"]).' y el '.formatearFecha($row["collectionuntildate"]);  
                    }
    
                    $fechaDestino="";
                    if($row["deliverytype"]==1){
                        $fechaDestino='El '.formatearFecha($row["deliverydate"]);
                    }
                    else{
                        $fechaDestino='Entre el '.formatearFecha($row["deliverydate"]).' y el '.formatearFecha($row["deliveryuntildate"]);  
                    }
    
                    $mensaje='<p>Hola <b>'.ucwords($row["company"]).'</b>.</p><p>La oferta que hizo para el envío '.$ship[0]["title"].' fue rechazada el '.fechaF1Hora($fechaactual);
                    $mensaje.='<table>
                                  <tr><td>Motivo dado por el cliente: </td><td>'.$reason.'</td></tr>
                                  <tr><td>Fecha(s) de Recolección: </td><td>'.$fechaOrigen.'</td></tr>
                                  <tr><td>Fecha(s) de Entrega: </td><td>'.$fechaDestino.'</td></tr>
                                  <tr><td>Precio: </td><td>S/ '.$row["shipmentcost"].'</td></tr>
                                  <tr><td>Condiciones: </td><td>'.$row["conditions"].'</td></tr>
                              </table>';
                    $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';

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
                    $mail->setFrom('noreply@efletex.com', 'Efletex');
                    $mail->addAddress($row["email"]);
                                    
                         // Add a recipient
    
                    $mail->isHTML(true);                                  // Set email format to HTML
    
                    $mail->Subject = "Notificaciones Efletex - Oferta rechazada";
                    $mail->Body    = '<html><body>'.$mensaje.'</body></html>';
                    $mail->AltBody = 'Revisa este correo desde otro navegador para poder ver la liga de confirmación.';
    
                    
                    if(!$mail->send()) {
                        
                        throw new Exception('No se pudo enviar el correo');
                    }
                    
                    $conexion->Insert("alert",array(
                         'recipientid'=>$row["id"],
                         'createdat'=>$fechaactual,
                         'updated'=>$fechaactual,
                         'type'=>4,
                         'relationid'=>$idShippingRequest));
                         
                    //se regresa el saldo disponible
                    $conexion->Query("UPDATE shipperaccount SET available = available + ".$row["offerprice"]." WHERE shipperid={0}",array($row["shipperid"]));
                }
                                
            }
            else if($data["status"]=="3"){
                $oferta=$conexion->Query("SELECT users.email,person.offerrejected,serviceoffer.*,person.id as personid,person.company FROM serviceoffer LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid LEFT JOIN person ON person.id=shipper.personid LEFT JOIN users ON users.personid=person.id WHERE serviceoffer.id={0}",array($data["offerid"]));
                $oferta=$oferta[0];

                $ship=$conexion->Query("SELECT shippingrequest.id,shippingrequest.title,person.company,person.firstname,person.lastname FROM shippingrequest LEFT JOIN serviceoffer ON serviceoffer.shippingrequestid=shippingrequest.id LEFT JOIN person ON person.id=shippingrequest.requesterid WHERE serviceoffer.id={0}",array($data["offerid"]));

                $fechaOrigen="";
                if($oferta["collectiontype"]==1){
                    $fechaOrigen='El '.formatearFecha($oferta["collectiondate"]);
                }
                else{
                    $fechaOrigen='Entre el '.formatearFecha($oferta["collectiondate"]).' y el '.formatearFecha($oferta["collectionuntildate"]);  
                }

                $fechaDestino="";
                if($oferta["deliverytype"]==1){
                    $fechaDestino='El '.formatearFecha($oferta["deliverydate"]);
                }
                else{
                    $fechaDestino='Entre el '.formatearFecha($oferta["deliverydate"]).' y el '.formatearFecha($oferta["deliveryuntildate"]);  
                }

                $cliente=$ship[0]["company"]==""?$ship[0]["firstname"].' '.$ship[0]["lastname"]:$ship[0]["company"];
                $mensaje='<p>Hola <b>'.ucwords($oferta["company"]).'</b>.</p><p>La oferta que hizo para el envío '.$ship[0]["title"].' fue rechazada el '.fechaF1Hora($fechaactual);
                $mensaje.='<table>
                              <tr><td>Cliente: </td><td>'.$cliente.'</td></tr>
                              <tr><td>Motivo de Rechazo: </td><td>'.$data["reason"].'</td></tr>
                              <tr><td>Fecha(s) de Recolección: </td><td>'.$fechaOrigen.'</td></tr>
                              <tr><td>Fecha(s) de Entrega: </td><td>'.$fechaDestino.'</td></tr>
                              <tr><td>Precio: </td><td>$ '.$oferta["shipmentcost"].'</td></tr>
                              <tr><td>Condiciones: </td><td>'.$oferta["conditions"].'</td></tr>
                          </table>';
                $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';
                $alerta=enviarAlerta($oferta["personid"],4,$ship[0]["id"],'',$oferta["offerrejected"],$oferta["email"],$mensaje,"Notificaciones Efletex - Oferta rechazada");
                if($alerta!="insertado"){
                    throw new Exception('No se pudo enviar el correo');
                }
            }else{
                $oferta=$conexion->Query("SELECT person.company,serviceoffer.*, paymentmethod.method 
                FROM serviceoffer LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid 
                LEFT JOIN person ON person.id=shipper.personid 
                LEFT JOIN paymentmethod ON paymentmethod.id=serviceoffer.paymentmethodid 
                WHERE serviceoffer.id={0}",array($data["offerid"]));
                $oferta=$oferta[0];

                $ship=$conexion->Query("SELECT shippingrequest.id,shippingrequest.title,users.email,person.offercanceled,person.id as personid,
                CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END personname 
                 FROM shippingrequest LEFT JOIN serviceoffer ON serviceoffer.shippingrequestid=shippingrequest.id LEFT JOIN person ON person.id=shippingrequest.requesterid LEFT JOIN users ON users.personid=person.id WHERE serviceoffer.id={0}",array($data["offerid"]));

                $fechaOrigen="";
                if($oferta["collectiontype"]==1){
                    $fechaOrigen='El '.formatearFecha($oferta["collectiondate"]);
                }
                else{
                    $fechaOrigen='Entre el '.formatearFecha($oferta["collectiondate"]).' y el '.formatearFecha($oferta["collectionuntildate"]);  
                }

                $fechaDestino="";
                if($oferta["deliverytype"]==1){
                    $fechaDestino='El '.formatearFecha($oferta["deliverydate"]);
                }
                else{
                    $fechaDestino='Entre el '.formatearFecha($oferta["deliverydate"]).' y el '.formatearFecha($oferta["deliveryuntildate"]);  
                }

                $mensaje='<p>Hola <b>'.ucwords($ship[0]["personname"]).'</b></p><p>La oferta hecha al envío <b>'.$ship[0]["title"].'</b> fue cancelada por el transportista.</p>';
                $mensaje.='<table>
                              <tr><td>Transportista: </td><td>'.$oferta["company"].'</td></tr>
                              <tr><td>Fecha de Recojo: </td><td>'.$fechaOrigen.'</td></tr>
                              <tr><td>Fecha de Entrega: </td><td>'.$fechaDestino.'</td></tr>
                              <tr><td>Precio: </td><td>$ '.$oferta["shipmentcost"].'</td></tr>
                              <tr><td>Condiciones: </td><td>'.$oferta["conditions"].'</td></tr>
                          </table>';
                $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';

                $alerta=enviarAlerta($ship[0]["personid"],2,$ship[0]["id"],$data["offerid"],$ship[0]["offercanceled"],$ship[0]["email"],$mensaje,"Efletex - Oferta cancelada");
                if($alerta!="insertado"){
                    throw new Exception('No se pudo enviar el correo');
                }
            }

            $conexion->Commit();
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'La oferta fue '.$res.' correctamente'), 'data' => array('status' => $data["status"], 'updated' => $fechaactual) ));
        }
        else if($isactive[0]["status"]==4){
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'La oferta ha sido cancelada por el transportista, ya no puedes realizar la operación')));   
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'La oferta ha sido aceptada o rechazada anteriormente')));   
        }

    }
    catch(Exception $e){
        $conexion->Rollaback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }

}

//devuelve las notificaciones del cliente o transportista
$server->register(  'getNotificaciones', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getNotificaciones', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene las notificaciones del cliente o transportista'  // documentation
);

function getNotificaciones($datos){
	global $conexion;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
		$type = $data['isshipper'] ? '2,3,4,6,10,12,13,15' : '1,5,7,8,9,11,14';
        $resultData = $conexion->Query("SELECT alert.id, alert.createdat, alert.type, alert.relationid, alert.relationid2, shippingrequest.title relationname
			FROM alert
			JOIN shippingrequest ON shippingrequest.id=alert.relationid
			WHERE alert.read IS NULL AND alert.type IN($type) AND alert.recipientid={0} AND alert.createdat>'{1}'",
			array($data['id'], $data['fecha'])
		);
		if(count($resultData) > 0){
			$respuesta['data'] = $resultData;
		}
		$resultUpdated = $conexion->Query("SELECT MAX(createdat) updated FROM alert WHERE read IS NULL AND type IN($type) AND recipientid={0}",
			array($data['id'])
		);
		if(count($resultUpdated) > 0 && $resultUpdated[0]['updated'] != $data['fecha']){
			$respuesta['updated'] = $resultUpdated[0]['updated'];
		}
		//getUpdatedDeletedId($respuesta, $idRows, $data);
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

//Actualiza el status de la notificación
$server->register(  'updateNotificacion', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#updateNotificacion', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Actualiza el status de la notificación' // documentation
);

function updateNotificacion($datos){
    global $conexion;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
		$fecha = date('Y-m-d H:i:s');
		$conexion->Update("alert", array(
			'read' => $fecha,
			'updated' => $fecha
		), "id={?}", array($data['alertid']));
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

//devuelve el status del envío
$server->register(  'getStatusEnvio', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getStatusEnvio', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene el status del envío'  // documentation
);

function getStatusEnvio($datos){
	global $conexion;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
		$type = $data['isshipper'] ? '2,3,4,6' : '1,5';
        $resultData = $conexion->Query("SELECT alert.id, alert.createdat, alert.type, alert.relationid, alert.relationid2, shippingrequest.title relationname
			
			FROM alert
			JOIN shippingrequest ON shippingrequest.id=alert.relationid AND shippingrequest.status=1
			WHERE alert.read IS NULL AND alert.type IN($type) AND alert.recipientid={0} AND alert.createdat>'{1}'",
			array($data['id'], $data['fecha'])
		);
		if(count($resultData) > 0){
			$respuesta['data'] = $resultData;
		}
		$resultUpdated = $conexion->Query("SELECT MAX(createdat) updated FROM alert WHERE read IS NULL AND type IN($type) AND recipientid={0}",
			array($data['id'])
		);
		if(count($resultUpdated) > 0 && $resultUpdated[0]['updated'] != $data['fecha']){
			$respuesta['updated'] = $resultUpdated[0]['updated'];
		}
		//getUpdatedDeletedId($respuesta, $idRows, $data);
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

//convierte yyyy-mm-dd a dd-mm-yy
function formatearFecha($fecha){
    $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
    $meses = array("January"=>"Ene.","February"=>"Feb.","March"=>"Mar.","April"=>"Abr.","May"=>"May.","June"=>"Jun.","July"=>"Jul.","August"=>"Ago.","September"=>"Sept.","October"=>"Oct.","November"=>"Nov.","December"=>"Dic.");
    
    $fechaFormato="";
    $fecha=  strtotime($fecha);
    $fechaDia=strftime("%A", $fecha);
    $fechaDiaNumero=strftime("%d", $fecha);
    $fechaMes=strftime("%B", $fecha);
    $fechaAnio=strftime("%Y", $fecha);

    $fechaFormato=$fechaDiaNumero . " ". $meses[$fechaMes] . " " . $fechaAnio;
    

    return $fechaFormato;
}

//convierte yyyy-mm-dd a dd-mm-yy
function formatearFechaHora($fecha){
    $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
    $meses = array("January"=>"Ene.","February"=>"Feb.","March"=>"Mar.","April"=>"Abr.","May"=>"May.","June"=>"Jun.","July"=>"Jul.","August"=>"Ago.","September"=>"Sept.","October"=>"Oct.","November"=>"Nov.","December"=>"Dic.");
    
    $fechaFormato="";
    $fecha=  strtotime($fecha);
    $fechaDia=strftime("%A", $fecha);
    $fechaDiaNumero=strftime("%d", $fecha);
    $fechaMes=strftime("%B", $fecha);
    $fechaAnio=strftime("%Y", $fecha);
    $fechaHora=strftime("%T", $fecha);

    $fechaFormato=$fechaDiaNumero . " de ". $meses[$fechaMes] . " de " . $fechaAnio . " ".$fechaHora;
    

    return $fechaFormato;
}

//function general para insertar alertas
function enviarAlerta($destinatario,$tipo,$idRelacion="",$idRelacion2="",$conf,$email,$mensaje,$asunto){
    global $conexion;
     /*
       * 1= Lo ve cliente, nueva oferta
       * 2= Lo ve cliente, oferta cancelada
       * 3= Lo ve Transportista, oferta aceptada
       * 4= Lo ve Transportista, oferta rechazada
       * 5= Lo ve cliente, nueva pregunta
       * 6= Lo ve Transportista, nueva respuesta
       * 7= Lo ve el cliente, envío recogido
       * 8= Lo ve el cliente, envío entregado
    */    
        $fechaActual=date("Y-m-d H:i:s");
        if($idRelacion=="")$idRelacion=null;
        
        if($destinatario>0){
            try{
                $insert=array(
                             'recipientid'=>$destinatario,
                             'createdat'=>$fechaActual,
                             'updated'=>$fechaActual,
                             'type'=>$tipo,
                             'relationid'=>$idRelacion,
                         );

                if($idRelacion2!="")$insert['relationid2']=$idRelacion2;
                
                $alerta=$conexion->Insert('alert',$insert);

                if($conf==true){

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
    
                        $mail->setFrom('noreply@efletex.com', 'Efletex');
                        $mail->addAddress($email);     // Add a recipient
                        //$mail->AddEmbeddedImage('../img/estrellaDorada.jpg', 'dorada');
                        //$mail->AddEmbeddedImage('../img/estrellaBlanca.jpg', 'blanca');
    
                        $mail->isHTML(true);                                  // Set email format to HTML
    
                        $mail->Subject = $asunto;
                        $mail->Body    = '<html><body>'.$mensaje.'</body></html>';
                        $mail->AltBody = 'Revisa este correo desde otro navegador para poder ver la liga de confirmación.';
        
                        
                        if(!$mail->send()) {
                            
                            throw new Exception("No se pudo enviar el correo");
                        } 
                    
                }

                return "insertado";
                
            }
            catch(Exception $e){
                return $e->getMessage();
            }
        }
        else{
            return "sin destinatario";
            
        }
}

//devuelve los envios reservados del transportista
$server->register(  'getCargas', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getCargas', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de cargas del trasportista. <br> 
                    Definicion: shipperid integer, fecha datetime, isdriver bool, driverid int, vehicleid int<br> 
                    Cadena: datos=>{"shipperid":,"fecha":,"isdriver":,"driverid":,"vehicleid":}' // documentation
);

function getCargas($datos){
    global $conexion;
	global $profile_image_width;
    $data=json_decode($datos,true);

    $where="";
    if($data["isdriver"]!=0){
        $vehiculoActual=$conexion->Query("SELECT id FROM vehicle WHERE shipperid={0} AND driverid={1}",array($data["shipperid"],$data["driverid"]));
        if($vehiculoActual[0]["id"]==$data["vehicleid"]){
            $where.=" AND shipping.status IN (7,3,4) AND shipment.vehicleid=".$data["vehicleid"];
        }
        else{
            return json_encode(array('datos'=>array('error'=>'3','mensaje'=>'Te han asignado otro vehiculo, vuelve a iniciar sesión para cargar los datos nuevos.')));
        }
    }
    if($data["shipperid"]!=0){
        $where.=" AND serviceoffer.shipperid=".$data["shipperid"];
    }
    if($data["fecha"]!=""){
        $where.=" AND shipping.updated>'".$data["fecha"]."'";
    }
    if($data["shippingrequestid"]!=0){
        $where=" AND shipping.id=".$data["shippingrequestid"];
    }
    try{
        $res=$conexion->Query("SELECT shipping.id,shipping.title,shipping.status,shipping.expirationdate,shipping.createdat,shipping.coldprotection,shipping.sortout,shipping.blindperson,shipping.costtype,serviceoffer.shipmentcost as totalprice,shipping.cost,shipping.firstofferdiscount,shipping.discountrate,shipping.km,shipping.tiempo,shipping.peso_total,shipment.id as shipmentid,
            shipping.updated, collection.contactfullname,collection.contactnumber1,collectionState.name stateOrigen, shipping.requesterid,
            collectionCountry.name countryOrigen, collection.latitude latitudOrigen, collection.longitude longitudOrigen, serviceoffer.collectiondate collectiondateOrigen,serviceoffer.collectionuntildate collectionuntildateOrigen,collection.place placeOrigen,collection.elevator elevatorOrigen,collection.collectinside collectinsideOrigen,collection.city cityOrigen,collection.collecttimefrom collecttimefromOrigen,collection.collecttimeuntil collecttimeuntilOrigen, collection.street1 street1Origen,collection.stateid stateidOrigen, 
            delivery.recipientfullname recipientfullnameDestino,delivery.recipientcontactnumber1 recipientcontactnumber1Destino,delivery.street1 street1Destino,delivery.stateid stateidDestino,deliveryState.name stateDestino,deliveryCountry.name countryDestino,
            delivery.longitude longitudDestino, delivery.latitude latitudDestino,serviceoffer.deliverydate deliverydateDestino,serviceoffer.deliveryuntildate deliveryuntildateDestino, delivery.place placeDestino,delivery.elevator elevatorDestino,delivery.callbefore callbeforeDestino,delivery.deliverywithin deliverywithinDestino,delivery.deliverytimefrom deliverytimeDestino,delivery.deliverytimeuntil deliverytimeuntilDestino,delivery.city cityDestino,
			CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername, person.starrating, 
            shipping.ispublic, collection.anotherplace anotherplaceOrigen, delivery.anotherplace anotherplaceDestino,shipping.paymentmethodid,shipping.paymentconditions, 
            shipment.trackingtype,shipping.paymentmethodid, shipping.paymentconditions, (SELECT method FROM paymentmethod WHERE id=shipping.paymentmethodid) paymentmethod,
            person.img profileimage
            FROM shippingrequest shipping 
            LEFT JOIN deliveryaddress delivery on delivery.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit deliveryState ON deliveryState.id=delivery.stateid
            LEFT JOIN country deliveryCountry ON deliveryCountry.id=deliveryState.countryid 
            LEFT JOIN collectionaddress collection ON collection.shippingrequestid=shipping.id 
            LEFT JOIN administrativeunit collectionState ON collectionState.id=collection.stateid 
            LEFT JOIN country collectionCountry ON collectionCountry.id=collectionState.countryid 
            LEFT JOIN shipment ON shipment.shippingrequestid=shipping.id 
            LEFT JOIN serviceoffer ON serviceoffer.id=shipment.acceptedserviceofferid
			LEFT JOIN person ON person.id=shipping.requesterid
            WHERE shipping.deleted IS NULL {0}",array($where));
        if(count($res)>0){
            
            for($i=0;$i<count($res);$i++){
                $res[$i]["images"]=array();
                $items=$conexion->Query("SELECT * FROM shippingitem WHERE shippingrequestid={0}",array($res[$i]["id"]));
                $res[$i]["items"]=$items;
                foreach($items as $itemid){
                    $images=$conexion->Query("SELECT * FROM fileattachment WHERE shippingitemid={0}",array($itemid["id"]));

                    foreach($images as $img){
                        $imgBase64=base64_encode(file_get_contents('../imagenesEnvio/'.$res[$i]["id"].'/'.$img['filename']));
                        $img["imagen_64"]=$imgBase64;
                        $res[$i]["images"][]=$img;
                    }
                }
				if(!is_null($res[$i]['profileimage']) && $res[$i]['profileimage'] != ""){
					try{
						$archivo = createBase64Thumbnail("../".$res[$i]['profileimage'], $profile_image_width);
						$res[$i]['profileimage'] = $archivo['base64'];
						$res[$i]['profileimageextension'] = $archivo['extension'];
					}catch(Exception $e){
						unset($res[$i]['profileimage']);
					}
				}else{
					unset($res[$i]['profileimage']);
				}
                
                $res[$i]["grupos"]=array();
            }
            $upd=$conexion->Query("SELECT MAX(shippingrequest.updated)updated FROM shippingrequest LEFT JOIN shipment ON shipment.shippingrequestid=shippingrequest.id LEFT JOIN serviceoffer ON serviceoffer.id=shipment.acceptedserviceofferid WHERE serviceoffer.shipperid={0} AND shippingrequest.updated>'{1}'",array($data['shipperid'],$data['fecha']));
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res,'updated'=>$upd[0]['updated']));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay envÍos nuevos'),'data'=>''));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage()),'data'=>''));
    }
}


//devuelve los envios reservados del transportista
$server->register(  'getPerfil', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getPerfil', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de cargas del trasportista. <br> Definición: id integer, fecha datetime, isshipper boolean <br/> Cadena: datos=>{"fecha":"","id":"","isshipper":}' // documentation
);

function getPerfil($datos){
    global $conexion;
    $data=json_decode($datos,true);
 
    try{
        if($data["isshipper"]){
            $res=$conexion->Query("SELECT person.company,person.firstname,person.middlename,person.lastname,person.secondlastname,person.starrating,users.email,person.dni,person.ruc,person.img,address.street1,address.street2 city,administrativeunit.name state,country.name country,address.telephone,address.postalcode,address.stateid,administrativeunit.countryid FROM person LEFT JOIN users ON users.personid=person.id LEFT JOIN address ON address.personid=person.id LEFT JOIN administrativeunit ON administrativeunit.id=address.stateid LEFT JOIN country ON country.id=administrativeunit.countryid WHERE person.id={0} AND person.updated>='{1}'",array($data["id"],$data["fecha"]));
        }else{
            $res=$conexion->Query("SELECT person.company,person.firstname,person.middlename,person.lastname,person.secondlastname,person.starrating,users.email,person.dni,person.ruc,person.img FROM person LEFT JOIN users ON users.personid=person.id WHERE person.id={0} AND person.updated>='{1}'",array($data["id"],$data["fecha"]));
        }

        if(count($res)>0){
            $img=$res[0]["img"];
            $imgBase64=base64_encode(file_get_contents('../'.$img));
            $res[0]["img"]=$imgBase64;
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'Sin datos')));
        }
        
    }catch(Exception $e){
         return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage()),'data'=>''));
    }
}


//devuelve los metodos de pago
$server->register(  'getMetodoPago', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getMetodoPago', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de Métodos de Pagos activos, recibe el updated. Este es un catalogo general}' // documentation
);

function getMetodoPago($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $res=$conexion->Query("SELECT * FROM paymentmethod WHERE updated>'{0}'",array($data["fecha"]));
        $upd=$conexion->Query("SELECT MAX(updated) updated FROM paymentmethod");
        if(count($res>0)){
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res, 'updated' => $upd[0]['updated']));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay datos')));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//inserta el status de recogido y entregado
$server->register(  'insertStatusCarga', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertStatusCarga', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Cambia el status a recogido y entregado de un envío. 
                    Provisional se califica al cliente junto con la entrega. <br>
                    Definición: status integer, vehicleid integer, placa string, 
                    shipperid integer, shippingrequestid integer, shipmentid integer, 
                    comment string, starrating int, img1 base64, sing base64, contact string,
                    latitude double, longitude double, comment string, dni string, driverid int<br> 
                    Cadena: datos=>{"shippingrequestid":,"shipmentid":,"vehicleid":,"status":,"placa":"","shipperid":,"img1":,"sing":,"contact":,"latitude":,"longitude":,"comment":}' // documentation
);

function insertStatusCarga($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $conexion->StartTransaction();
    try{
        
        if($data["status"]=="3"){ //Recolección
            $fechaactual=date('Y-m-d H:i:s');
            $fechaactual2=date('d-m-Y H:i:s',strtotime($fechaactual));
            
            $dirEnvio='../imagenesEnvio/'.$data["shippingrequestid"];
            if(!file_exists($dirEnvio)){
                mkdir($dirEnvio, 0777); 
            }
            
            $archivo=null;
            if($data["img1"]!=""){
                $archivo='collect1.'.$data["ext"];  
                file_put_contents($dirEnvio.'/'.$archivo,base64_decode($data["img1"]));
                chmod($dirEnvio.'/'.$archivo,0777);
            }
            
            $firma='singcollect.'.$data["singext"];  
            file_put_contents($dirEnvio.'/'.$firma,base64_decode($data["sing"]));
            chmod($dirEnvio.'/'.$firma,0777);
            
            $conexion->Update("shippingrequest",array("status"=>"3","updated"=>$fechaactual),"id={?}",array($data["shippingrequestid"]));

            $conexion->Insert("shippingrequestlog",array("shippingrequestid"=>$data["shippingrequestid"],"status"=>"3","createdat"=>$fechaactual,"updated"=>$fechaactual,"contact"=>$data["contact"],"img1"=>$archivo,"sing"=>$firma,"latitude"=>$data["latitude"],"longitude"=>$data["longitude"],"comment"=>$data["comment"],"dni"=>$data["dni"],"driverid"=>$data["driverid"]));

            //Envia la alerta
            $cliente=$conexion->Query("SELECT person.id,CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END personname ,person.shipmentcollected,users.email,shippingrequest.title FROM shippingrequest LEFT JOIN person ON person.id=shippingrequest.requesterid LEFT JOIN users ON users.personid=person.id WHERE shippingrequest.id={0}",array($data["shippingrequestid"]));

            
            $transportista=$conexion->Query("SELECT person.company FROM person LEFT JOIN shipper ON shipper.personid=person.id WHERE shipper.id={0}",array($data["shipperid"]));

            $mensaje='<p>Hola '.ucwords($cliente[0]["personname"]).'</p><p>El envío '.$cliente[0]["title"].' fue recogido por el transportista</p>';
            $mensaje.='<table>
                          <tr><td>Transportista: </td><td>'.$transportista[0]["company"].'</td></tr>
                          <tr><td>Contacto: </td><td>'.$data["contact"].'</td></tr>
                          <tr><td>Comentarios: </td><td>'.$data["comment"].'</td></tr>
                          <tr><td>Fecha: </td><td>'.formatearFechaHora($fechaactual).'</td></tr>
                      </table>';
            $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';
                      
            $alerta=enviarAlerta($cliente[0]["id"],7,$data["shippingrequestid"],'',$cliente[0]["shipmentcollected"],$cliente[0]["email"],$mensaje,"Efletex - Envío recogido");
            if($alerta!="insertado"){
                throw new Exception($alerta);
            }
            

            
            
            $conexion->Commit();

            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'El envío ha sido marcado como recolectado'), 'data' => $fechaactual));
        }
        else if($data["status"]=="4"){ //Entrega
            $fechaactual=date('Y-m-d H:i:s');
            $fechaactual2=date('d-m-Y H:i:s',strtotime($fechaactual));
            $conexion->Update("shippingrequest",array("status"=>"4","updated"=>$fechaactual),"id={?}",array($data["shippingrequestid"]));
            
            $dirEnvio='../imagenesEnvio/'.$data["shippingrequestid"];
            $archivo=null;
            if($data["img1"]!=""){
                $archivo='delivery1.'.$data["ext"];  
                file_put_contents($dirEnvio.'/'.$archivo,base64_decode($data["img1"]));
                chmod($dirEnvio.'/'.$archivo,0777);
            }
            
            $firma='singdelivery.'.$data["singext"];  
            file_put_contents($dirEnvio.'/'.$firma,base64_decode($data["sing"]));
            chmod($dirEnvio.'/'.$firma,0777);
            
            $conexion->Insert("shippingrequestlog",array("shippingrequestid"=>$data["shippingrequestid"],"status"=>"4","createdat"=>$fechaactual,"updated"=>$fechaactual,"img1"=>$archivo,"sing"=>$firma,"contact"=>$data["contact"],"latitude"=>$data["latitude"],"longitude"=>$data["longitude"],"comment"=>$data["comment"],"dni"=>$data["dni"],"driverid"=>$data["driverid"]));

            $cliente=$conexion->Query("SELECT shippingrequest.requesterid,person.shipmentdelivered,person.feedback,users.email,shippingrequest.title, 
            CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END personname 
             FROM shippingrequest 
            LEFT JOIN shipment ON shipment.shippingrequestid=shippingrequest.id 
            LEFT JOIN person ON person.id=shippingrequest.requesterid 
            LEFT JOIN users ON users.personid=person.id WHERE shipment.id={0}",array($data["shipmentid"]));
            
            $calf=$conexion->Insert("feedback",array(
                'comment'=>$data["comment"],
                'starrating'=>$data["starrating"],
                'authorid'=>$data["personid"],
                'recipientid'=>$cliente[0]["requesterid"],
                'shipmentid'=>$data["shipmentid"],
                'updated'=>$fechaactual
            ));

            $conexion->Update("shipment",array("clienthasfeedback"=>true,"updated"=>$fechaactual),"id={?}",array($data["shipmentid"]));

            //calculamos el starrating
            $feedback=$conexion->Query("SELECT SUM(starrating) as starrating,COUNT(id) as total FROM feedback WHERE recipientid={0}",array($cliente[0]["requesterid"]));
            $starrating=round($feedback[0]["starrating"]/$feedback[0]["total"],1);

            $conexion->Update("person",array("starrating"=>$starrating,"updated"=>$fechaactual),"id={?}",array($cliente[0]["requesterid"]));
            
            //Envia la alerta de recolección
            $transportista=$conexion->Query("SELECT person.company FROM person LEFT JOIN shipper ON shipper.personid=person.id WHERE shipper.id={0}",array($data["shipperid"]));
            

            $mensaje='<p>Hola <b>'.ucwords($cliente[0]["personname"]).'</b></p><p>El envío "'.$cliente[0]["title"].'" fue entregado por el transportista</p>';
            $mensaje.='<table>
                        <tr><td>Transportista: </td><td>'.$transportista[0]["company"].'</td></tr>
                        <tr><td>Contacto: </td><td>'.$data["contact"].'</td></tr>
                          <tr><td>Comentarios: </td><td>'.$data["comment"].'</td></tr>
                          <tr><td>Fecha: </td><td>'.formatearFechaHora($fechaactual).'</td></tr>
                      </table>';
            $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';
            
            $alerta=enviarAlerta($cliente[0]["requesterid"],8,$data["shippingrequestid"],'',$cliente[0]["shipmentdelivered"],$cliente[0]["email"],$mensaje,"Notificaciones Efletex - Envío entregado");
            if($alerta!="insertado"){
                throw new Exception($alerta);
            }
            

            $mensaje='<p>Hola <b>'.ucwords($cliente[0]["personname"]).'</b></p><p>El transportista '.$transportista[0]["company"].' del envío "'.$cliente[0]["title"].'" te ha calificado</p>';
            $mensaje.='<table>
                          <tr><td>Fecha: </td><td>'.formatearFechaHora($fechaactual).'</td></tr>
                          <tr><td>Calificación: </td><td>('.$data["starrating"].' estrellas)</td></tr>
                          <tr><td>Comentarios: </td><td>'.$data["comment"].'</td></tr>
                      </table>';
            $mensaje.='<p><b>Para mayor información ingrese a:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>';
            $alerta=enviarAlerta($cliente[0]["requesterid"],9,$data["shippingrequestid"],$calf[0]["id"],$cliente[0]["feedback"],$cliente[0]["email"],$mensaje,"Notificaciones Efletex - Calificación recibida");
            if($alerta!="insertado"){
                throw new Exception($alerta);
            }
            

            
            
            $conexion->Commit();

            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'El envío ha sido marcado como entregado'), 'data' => $fechaactual));
        }
        else{// asignación vehículo
            $fechaactual=date('Y-m-d H:i:s');
            $fechaactual2=date('d-m-Y H:i:s',strtotime($fechaactual));
            
            if($data["vehicleid"]=="0"){
                $placa=strtoupper(trim($data["placa"]));
                $vehicle=$conexion->Query("SELECT id FROM vehicle WHERE description='{0}' AND shipperid={1}",array($placa,$data["shipperid"]));
                
                //verifica que el vehículo no exista y lo inserta
                if(count($vehicle)>0){
                    return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'La placa ya existe')));
                }else{
                    $vehicle=$conexion->Insert("vehicle",array("shipperid"=>$data["shipperid"],"description"=>$placa));
                    $vehicleid=$vehicle[0]["id"];
                }
                
                
            }
            else{
                $vehicleid=$data["vehicleid"];
            }
            
            $cliente=$conexion->Query("SELECT shippingrequest.requesterid,person.assignedvehicle,users.email,shippingrequest.title, 
            CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END personname 
             FROM shippingrequest 
            LEFT JOIN shipment ON shipment.shippingrequestid=shippingrequest.id 
            LEFT JOIN person ON person.id=shippingrequest.requesterid 
            LEFT JOIN users ON users.personid=person.id WHERE shipment.id={0}",array($data["shipmentid"]));
            
            $conexion->Insert("shippingrequestlog",array("shippingrequestid"=>$data["shippingrequestid"],"status"=>"7","createdat"=>$fechaactual,"updated"=>$fechaactual));
            
            $conexion->Update("shippingrequest",array("status"=>"7","updated"=>$fechaactual),"id={?}",array($data["shippingrequestid"]));
            
            $conexion->Update("shipment",array("vehicleid"=>$vehicleid,"trackingtype"=>$data["trackingtype"],"updated"=>$fechaactual),"id={?}",array($data["shipmentid"]));
            
             //Envia la alerta de recolección
            $transportista=$conexion->Query("SELECT person.company FROM person LEFT JOIN shipper ON shipper.personid=person.id WHERE shipper.id={0}",array($data["shipperid"]));
            $placa=$conexion->Query("SELECT description FROM vehicle WHERE id={0}",array($vehicleid));
            $mensaje='<p>Hola <b>'.ucwords($cliente[0]["personname"]).'</b></p><p>Al envío "'.$cliente[0]["title"].'" se le asigno un vehículo</p>';
            $mensaje.='<table>
                          <tr><td>Vehículo asignado: </td><td>'.$placa[0]["description"].'</td></tr>
                          <tr><td>Transportista: </td><td>'.$transportista[0]["company"].'</td></tr>
                          <tr><td>Fecha: </td><td>'.formatearFechaHora($fechaactual).'</td></tr>
                      </table>';
            $alerta=enviarAlerta($cliente[0]["requesterid"],11,$data["shippingrequestid"],'',$cliente[0]["assignedvehicle"],$cliente[0]["email"],$mensaje,"Norificaciones Efletex - Vehículo asignado");
            if($alerta!="insertado"){
                throw new Exception($alerta);
            }
            

            
            $conexion->Commit();

            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'El envío ha sido asignado a un vehïculo'), 'data' => $fechaactual));
        }
    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//devuelve los vehiculos del transportista
$server->register(  'getVehiculos', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getVehiculos', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el listado de vehiculos del transportista. Definicion: shipperid integer <br> Cadena datos=>{"shipperid":""}' // documentation
);

function getVehiculos($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $res=$conexion->Query("SELECT vehicle.*,person.firstname || ' ' || person.lastname as drivername FROM vehicle LEFT JOIN driver ON driver.id=vehicle.driverid LEFT JOIN person ON person.id=driver.personid WHERE vehicle.shipperid={0}",array($data["shipperid"]));
        if(count($res>0)){
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay datos')));
        }
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

//devuelve los vehiculos del transportista
$server->register(  'getShippingRequestLog', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getShippingRequestLog', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el historial de status del envío. Definicion: shippingrequestid integer, fecha datetime <br> Cadena datos=>{"shippingrequestid":"","fecha":""}' // documentation
);

function getShippingRequestLog($datos){
    //global $conexion;
    //$data=json_decode($datos,true);
    //try{
    //    $res=$conexion->Query("SELECT * FROM shippingrequestlog WHERE shippingrequestid={0} AND updated>'{1}'",array($data["shippingrequestid"],$data["fecha"]));
    //    if(count($res>0)){
    //        $upd=$conexion->Query("SELECT MAX(updated) updated FROM shippingrequestlog WHERE shippingrequestid={0} AND updated>'{1}'",array($data["shippingrequestid"],$data["fecha"]));
    //        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res));
    //    }
    //    else{
    //        return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay datos nuevos')));
    //    }
    //}
    //catch(Exception $e){
    //    return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    //}
	global $conexion;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
		//(shipperid={0} OR shippingrequestid={1})
        $resultData = $conexion->Query("SELECT shippingrequestlog.id, shippingrequestlog.shippingrequestid, shippingrequestlog.status, 
        shippingrequestlog.createdat, shippingrequestlog.contact, 
        shippingrequestlog.latitude, shippingrequestlog.longitude, 
        shippingrequestlog.comment, shippingrequestlog.dni, person.firstname || ' ' || person.lastname as drivername, 
        shippingrequestlog.sing, shippingrequestlog.img1 
        FROM shippingrequestlog LEFT JOIN driver ON driver.id=shippingrequestlog.driverid LEFT JOIN person ON person.id=driver.personid WHERE shippingrequestlog.shippingrequestid IN({0}) AND shippingrequestlog.id NOT IN({1})",
			array($data['shippingrequestid'], $data['ids'])
		);
		if(count($resultData) > 0){
            for($i=0;$i<count($resultData);$i++){
                if($resultData[$i]["img1"]!=""){
                    $imgBase64=base64_encode(file_get_contents('../imagenesEnvio/'.$resultData[$i]["shippingrequestid"].'/'.$resultData[$i]["img1"]));
                    $resultData[$i]["img1"]=$imgBase64;
                }
                if($resultData[$i]["sing"]!=""){
                    $imgBase64=base64_encode(file_get_contents('../imagenesEnvio/'.$resultData[$i]["shippingrequestid"].'/'.$resultData[$i]["sing"]));
                    $resultData[$i]["sing"]=$imgBase64;
                }
                
            }
            
			$respuesta['data'] = $resultData;
		}
		$idRows = $conexion->Query("SELECT id, updated FROM shippingrequestlog WHERE shippingrequestid IN({0}) ORDER BY updated DESC",
			array($data['shippingrequestid'])
		);
		getUpdatedDeletedId($respuesta, $idRows, $data);
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}


//actualiza la foto de perfil
$server->register(  'insertPerfilImagen', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertPerfilImagen', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta o actualiza la imagen de perfil. Definicion: personid integer, img string (base64), ext string (extencion de la imagen jpg,png,etc) <br> Cadena datos=>{"personid":"","img":"","ext":""}' // documentation
);

function insertPerfilImagen($datos){
    global $conexion;
	file_put_contents("imagenPerfiljson.txt", $datos."\r\n", FILE_APPEND);
    $data=json_decode($datos,true);
    try{
        $fechaactual=date('Y-m-d H:i:s');
        if($data["img"]!=""){
            $dirImagen="imagenPerfil/".$data["personid"];
            if(!file_exists('../'.$dirImagen)){
                mkdir('../'.$dirImagen, 0777);
            }
            
            $imgBase64=base64_decode($data["img"]);
            $nombre=$dirImagen.'/'.$data['personid'].'img.'.$data["ext"];
            file_put_contents('../'.$nombre,$imgBase64);
            chmod('../'.$nombre,0777);
            $conexion->Update("person",array("img"=>$nombre,"updated"=>$fechaactual),"id={?}",array($data["personid"]));
        }
        else{
            $conexion->write_sql();
            $conexion->Update("person",array("img"=>"","updated"=>$fechaactual),"id={?}",array($data["personid"]));
        }
        

        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Imagen de perfil actualizada')));
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//actualiza los datos del perfil
$server->register(  'insertPerfilDatos', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertPerfilDatos', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Actualiza los datos del perfil. Definicion: personid integer, isshipper boolean, firstname string, secondname string, lastname string, secondlastname string, street1 string, city string, stateid integer, postalcode string, telephone string <br> Cadena datos=>{"personid":"", "isshipper":"", "firstname":"", "secondname":"", "lastname":"", "secondlastname":"", "street1":"", "city":"", "stateid": "", "postalcode": "", "telephone":""}' // documentation
);

function insertPerfilDatos($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $conexion->StartTransaction();
    try{
        $fechaactual=date('Y-m-d H:i:s');
        $conexion->Update("person",array(
                "firstname"=>$data["firstname"],
                "middlename"=>$data["secondname"],
                "lastname"=>$data["lastname"],
                "secondlastname"=>$data["secondlastname"],
                "updated"=>$fechaactual
            ),"id={?}",array($data["personid"]));

        if($data["isshipper"]){
            $conexion->Update("address",array(
                "street1"=>$data["street1"],
                "street2"=>$data["city"],
                "stateid"=>$data["stateid"],
                "postalcode"=>$data["postalcode"],
                "telephone"=>$data["telephone"]
            ),"personid={?}",array($data["personid"]));
        }

        $conexion->Commit();
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Imagen de perfil actualizada')));
    }
    catch(Exception $e){

        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//califica al cliente
$server->register(  'insertCalificacion', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertCalificacion', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Calificacion al cliente. Definicion: personid integer, starrating int, comment string, shippingrequestid id <br> Cadena datos=>{"personid":"", "starrating":"", "comment":"", "shippingrequestid":""}' // documentation
);

function insertCalificacion($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $conexion->StartTransaction();
    try{
        $fechaactual=date('Y-m-d H:i:s');
        $fechaactual2=date('d-m-Y H:i:s',strtotime($fechaactual));
        $trans=$conexion->Query("SELECT shipment.id,shipper.personid,person.feedback,users.email,shippingrequest.title,person.company FROM shippingrequest 
        LEFT JOIN shipment ON shipment.shippingrequestid=shippingrequest.id 
        LEFT JOIN serviceoffer ON serviceoffer.id=shipment.acceptedserviceofferid 
        LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid 
        LEFT JOIN person ON person.id=shipper.personid 
        LEFT JOIN users ON users.personid=person.id
        WHERE shipment.shippingrequestid={0}",array($data["shippingrequestid"]));

        $calf=$conexion->Insert("feedback",array(
            'comment'=>$data["comment"],
            'starrating'=>$data["starrating"],
            'authorid'=>$data["personid"],
            'recipientid'=>$trans[0]["personid"],
            'shipmentid'=>$trans[0]["id"],
            'updated'=>$fechaactual
        ));

        $conexion->Update("shipment",array("shipperhasfeedback"=>true,"updated"=>$fechaactual),"id={?}",array($trans[0]["id"]));

        //calculamos el starrating
        $feedback=$conexion->Query("SELECT SUM(starrating) as starrating,COUNT(id) as total FROM feedback WHERE recipientid={0}",array($trans[0]["personid"]));
        $starrating=round($feedback[0]["starrating"]/$feedback[0]["total"],1);

        $conexion->Update("person",array("starrating"=>$starrating,"updated"=>$fechaactual),"id={?}",array($trans[0]["personid"]));
        
        //Envia la alerta
        $cliente=$conexion->Query("SELECT person.company,person.firstname,person.lastname FROM  person WHERE person.id={0}",array($data["personid"]));
        $nombre=$cliente[0]["company"]==""?$cliente[0]["firstname"].' '.$cliente[0]["lastname"]:$cliente[0]["company"];
    
        $mensaje='<p>Hola <b>'.$trans[0]["company"].'</b>.</p>';
        $mensaje.='<p>El cliente '.$nombre.' del envío '.$trans[0]["title"].' te ha calificado</p>';
        $mensaje.='<p>Fecha '.formatearFechaHora($fechaactual).'</p>';
        $mensaje.='<p>Calificación: ';
            /*for($i=0;$i<5;$i++){
                if($data["starrating"]!=0){
                    if($data["starrating"]<=5){
                        $mensaje.='<img src="cid:dorada">';
                    }
                    else{
                        $mensaje.='<img src="cid:blanca">';
                    }
                }
                else{
                    $mensaje.='<img src=\"cid:blanca\" />';
                }
            }*/
        $mensaje.=' ('.$data["starrating"].' estrellas)</p>';
        $mensaje.='<p>Comentarios: '.$data["comment"].'</p>';
                      
        
        $alerta=enviarAlerta($trans[0]["personid"],10,$data["shippingrequestid"],$calf[0]["id"],$trans[0]["feedback"],$trans[0]["email"],$mensaje,"Notificaciones Efletex - Calificación recibida");
        if($alerta!="insertado"){
            throw new Exception($alerta);
        }

        $conexion->Commit();
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Transportista calificado correctamente'), 'data' => $fechaactual));
    }
    catch(Exception $e){

        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//inserta la configuración de notificaciones
$server->register(  'insertConfigNotificaciones', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertConfigNotificaciones', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta la configuración de notificaciones. Definicion: personid integer, newoffer bool,offercanceled bool, offeraccepted bool, offerrejected bool, newquestion bool, newreply bool, shipmentcollected bool, shipmentdelivered bool, feedback bool <br> Cadena datos=>{"personid":,"newoffer":, "offercanceled":, "offeraccepted":, "offerrejected":, "newquestion":, "newreply":, "shipmentcollected":, "shipmentdelivered":,"feedback":}' // documentation
);

function insertConfigNotificaciones($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $fechaactual=date('Y-m-d H:i:s');
        $conexion->Update("person",array(
              'newoffer'=>$data["newoffer"]?1:0,
              'offercanceled'=>$data["offercanceled"]?1:0,
              'offeraccepted'=>$data["offeraccepted"]?1:0,
              'offerrejected'=>$data["offerrejected"]?1:0,
              'newquestion'=>$data["newquestion"]?1:0,
              'newreply'=>$data["newreply"]?1:0,
              'shipmentcollected'=>$data["shipmentcollected"]?1:0,
              'shipmentdelivered'=>$data["shipmentdelivered"]?1:0,
              'feedback'=>$data["feedback"]?1:0,
              'updated'=>$fechaactual
        ),"id={?}",array($data["personid"]));

        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado')));
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//inserta la configuración de notificaciones
$server->register(  'getCobertura', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getCobertura', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene la cobertura (estados favoritos) del transportista. Definicion: personid integer<br> Cadena datos=>{"personid":}' // documentation
);

function getCobertura($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
		$existe=$conexion->Query("SELECT id FROM preferences WHERE personid={0} AND id IN({1}) LIMIT 1",array($data["personid"],$data['ids']));
		$res = array();
		if(count($existe) == 0){
			$res=$conexion->Query("SELECT id, administrativeunitid FROM preferences WHERE personid={0}",array($data["personid"]));
		}
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$res));
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//inserta la cobertura del transportista
$server->register(  'insertCobertura', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertCobertura', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta los estados donde operará el transportista. Definicion: personid integer, states string<br> Cadena datos=>{"personid":,"states":}' // documentation
);

function insertCobertura($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $conexion->StartTransaction();
    try{
		if($data["states"] == ""){
			throw new Exception("No ha seleccionado ninguna cobertura", 3);
		}
        $fechaactual=date('Y-m-d H:i:s');
        $conexion->Delete("preferences","personid={0}",array($data["personid"]));

        $estados=explode(',',$data["states"]);
        
        for($i=0;$i<count($estados);$i++){
            $conexion->Insert("preferences",array("personid"=>$data["personid"],"administrativeunitid"=>$estados[$i]));
        }
        $conexion->Commit();
		$res=$conexion->Query("SELECT id, administrativeunitid FROM preferences WHERE personid={0}",array($data["personid"]));
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Cobertura Actualizada'), 'data'=>$res));
    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


//manda el dashboard
$server->register(  'getDashboard', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getDashboard', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta los estados donde operará el transportista. Definicion: personid integer, isshipper bool, shipperid integer<br> Cadena datos=>{"personid":,"isshipper","shipperid":}' // documentation
);


//devuelve los mensajes
$server->register(  'getMensajes', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getMensajes', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve los mensajes del usuario. Definicion: personid integer<br> Cadena datos=>{"personid":}' // documentation
);


//inserta los mensajes
$server->register(  'insertMensajeNuevo', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertMensajeNuevo', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta los mensajes del usuario. Definicion: personid integer, shippingrequestid integer, offerid integer, isshipper bool, body string <br> Cadena datos=>{"personid":,"shippingrequest":,"offerid":,"isshipper":,"body":}' // documentation
);


//inserta los mensajes provenientes de una respuesta
$server->register(  'insertMensajeRespuesta', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertMensajeRespuesta', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta los mensajes del usuario. Definicion: personid integer, shippingrequestid integer, recipient integer, body string<br> Cadena datos=>{"personid":,"shippingrequestid":,"recipientid":,"body":}' // documentation
);


//inserta los mensajes
$server->register(  'insertMensajeLeido', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertMensajeLeido', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Marca como leido el mensaje del usuario. Definicion: messageid integer<br> Cadena datos=>{"messageid":}' // documentation
);


//Actualiza la contraseña del Usuario
$server->register(  'updatePassword', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#updatePassword', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Actualiza la contraseña del Usuario' // documentation
);

function updatePassword($datos){
    global $conexion;
    try{
        $data=json_decode($datos,true);
		$person_id = $data["personid"];
        $current_password = $data["currentpassword"];
		$new_password = $data["newpassword"];
        $res=$conexion->Query("SELECT password FROM users WHERE personid={0}",array($person_id));
        if(count($res)>0){
            if (password_verify($current_password, $res[0]["password"])) {
				$new_password = password_hash($new_password,PASSWORD_DEFAULT);
				$conexion->Update('users', array('password' => $new_password, 'updated_at' => 'NOW()'), 'personid={?}', array($person_id));
                return json_encode(array('datos'=>array('error'=>'0')));
            }else{
                return json_encode(array('datos'=>array('error'=>'2','mensaje'=>'Contraseña actual es incorrecta')));
            }
        }else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'Usuario no existe')));
        }
    }catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'3','mensaje'=>$e->getMessage())));
    }
}

//Actualiza la contraseña del Usuario
$server->register(  'getCalificacion', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getCalificacion', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve las calificaciones del usuario. <br>Definicion: personid integer<br>' // documentation
);

//Actualiza la contraseña del Usuario
$server->register(  'getGrupos', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getGrupos', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve las grupos del usuario. <br>Definicion: personid integer, isshipper bool<br>datos=>{"personid":,"isshipper":}' // documentation
);

//
$server->register(  'insertSolicitudGrupo', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertSolicitudGrupo', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta la solicitud del transportista' // documentation
);

//devuelve las alertas del sistema
$server->register(  'getAlertas', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getNotificaciones', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Obtiene las alertas del sistema'  // documentation
);


//inserta el check del envio
$server->register(  'insertCheck', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertCheck', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta el check del envio. <br>Definición: shippingrequestid integer,shipperid integer,datecheck datetime, latitude decimal, longitude decimal<br> 
                    Cadena: datos=>{"shippingrequestid":,"shipperid":,"datecheck":,"latitude":,"longitude":}'  // documentation
);

//inserta el tracking del transportista
$server->register(  'insertTracking', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertTracking', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta el tracking del trasportista <br>Definición: personid integer, latitude decimal, longitude decimal,datetracking datetime<br> 
                    Cadena: datos=>{"personid":,"latitude":,"longitude":,"datetracking":}'  // documentation
);

//recupera el tracking del transportista
$server->register(  'getTracking', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getTracking', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el tracking del trasportista <br>Definición: shipperid integer, fecha1 date, fecha2 date<br> 
                    Cadena: datos=>{"personid":,"fecha1":,"fecha2":}'  // documentation
);

//recupera el check del trasportista
$server->register(  'getCheck', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getCheck', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Recupera el check del trasportista <br>Definición: shipperid integer, fecha1 date, fecha2 date<br> 
                    Cadena: datos=>{"shipperid":,"fecha1":,"fecha2":}'  // documentation
);

//devuelve el trackin por envio
$server->register(  'getTrackingEnvio', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getTrackingEnvio', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve el tracking del envio <br>Definición: shippingrequestid integer, trackingtype integer<br> 
                    Cadena: datos=>{"shippingrequestid":,"trackingtype":}'  // documentation
);

//edita la fecha de expiración
$server->register(  'updateExpiracion', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#updateExpiracion', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Actualiza la fecha de expiracion <br>Definición: shippingrequestid integer, fecha datetime<br> 
                    Cadena: datos=>{"shippingrequestid":,"fecha":}'  // documentation
);

//edita la fecha de expiración
$server->register(  'insertVehiculo', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertVehiculo', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta un nuevo vehiculo <br>Definición: shipperid integer, placa string<br> 
                    Cadena: datos=>{"shipperid":,"placa":}'  // documentation
);

//edita la fecha de expiración
$server->register(  'updateVehiculo', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#updateVehiculo', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta un nuevo vehiculo <br>Definición: vehicleid integer, placa string, active boolean<br> 
                    Cadena: datos=>{"vehicleid":,"placa":,"active":}'  // documentation
);

//valida el cupon y agrega el saldo
$server->register(  'insertCupon', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertCupon', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Ingresa el codigo del cupon y agrega el saldo <br>Definición: codigo string, shipperid int<br> 
                    Cadena: datos=>{"codigo":,"shipperid":}'  // documentation
);

//valida el cupon y agrega el saldo
$server->register(  'getHistorialCuenta', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getHistorialCuenta', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve el historial del transportista <br>Definición: shipperid int<br> 
                    Cadena: datos=>{"shipperid":}'  // documentation
);

//crea un chofer
$server->register(  'insertDriver', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#insertDriver', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Inserta un nuevo chofer <br>Definición: shipperid int, personid int, firstname string, middlename string, lastname string, secondlastname string, dni string, password string,phone numeric<br> 
                    Cadena: datos=>{"shipperid":,"personid":,"firstname":,"middlename":,"lastname":,"secondlastname":,"dni":,"password":,"phone":}'  // documentation
);

//devuelve los choferes del transportista
$server->register(  'getDriver', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getDriver', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve el listado de choferes del transportista <br>Definición: shipperid int, updated datetime<br> 
                    Cadena: datos=>{"shipperid":,"updated":}'  // documentation
);

//actualiza los datos del chofer
$server->register(  'updateDriver', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#updateDriver', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Actualiza los datos de un chofer <br>Definición: driverid int, firstname string, middlename string, lastname string, secondlastname string, dni string, password string,phone numeric<br> 
                    Cadena: datos=>{"driverid":,"firstname":,"middlename":,"lastname":,"secondlastname":,"dni":,"password":,"phone":}'  // documentation
);


//asigna un vehiculo a un chofer
$server->register(  'asignarChofer', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#asignarChofer', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Asigna un vehículo a un chofer <br>Definición: driverid int, vehicleid int<br> 
                    Cadena: datos=>{"driverid":,"vehicleid":}'  // documentation
);

//devuelve el costo de la comision
$server->register(  'getComisionOferta', // nombre del metodo o funcion
                    array('datos' => 'xsd:string'), // parametros de entrada
                    array('return' => 'xsd:string'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:efletex#getComisionOferta', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Devuelve la comision que le corresponde por ofertar <br>Definición: shippingrequestid int, offercost decimal, ispublic bool<br> 
                    Cadena: datos=>{"shippingrequestid":,"offercost":,"ispublic":}'  // documentation
);


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);


?>