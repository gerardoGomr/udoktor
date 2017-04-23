<?php

function getDashboard($datos){
    global $conexion;
    $data=json_decode($datos,true);
  
    try{
        if($data["isshipper"]){
            $activos=$conexion->Query("SELECT COUNT(id)total FROM serviceoffer WHERE status=1 AND shipperid={0}",array($data["shipperid"]));

            $recolectar=$conexion->Query("SELECT COUNT(shipment.id) total FROM shipment LEFT JOIN serviceoffer ON serviceoffer.id=shipment.acceptedserviceofferid LEFT JOIN shippingrequest ON shippingrequest.id=shipment.shippingrequestid WHERE serviceoffer.shipperid={0} AND shippingrequest.status=2",array($data["shipperid"]));

            $entregar=$conexion->Query("SELECT COUNT(shipment.id) total FROM shipment LEFT JOIN serviceoffer ON serviceoffer.id=shipment.acceptedserviceofferid LEFT JOIN shippingrequest ON shippingrequest.id=shipment.shippingrequestid WHERE serviceoffer.shipperid={0} AND shippingrequest.status=3",array($data["shipperid"])); 

            $calf=$conexion->Query("SELECT starrating FROM person WHERE id={0}",array($data["personid"]));
            
            $cuenta=$conexion->Query("SELECT balance,available FROM shipperaccount WHERE shipperid={0}",array($data["shipperid"]));
        }
        else{
            $activos=$conexion->Query("SELECT COUNT(id)total FROM shippingrequest WHERE status=1 AND requesterid={0}",array($data["personid"]));

            $recolectar=$conexion->Query("SELECT COUNT(id)total FROM shippingrequest WHERE status=2 AND requesterid={0}",array($data["personid"]));

            $entregar=$conexion->Query("SELECT COUNT(id)total FROM shippingrequest WHERE status=3 AND requesterid={0}",array($data["personid"]));

            $calf=$conexion->Query("SELECT starrating FROM person WHERE id={0}",array($data["personid"]));
            
            $cuenta=array(array("balance"=>0,"available"=>0));
        }
        
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>array("0"=>array("activos"=>$activos[0]["total"],"recolectar"=>$recolectar[0]["total"],"entregar"=>$entregar[0]["total"],"starrating"=>$calf[0]["starrating"],"balance"=>$cuenta[0]["balance"],"available"=>$cuenta[0]["available"]))));
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getMensajes($datos){
    global $conexion;
    $data=json_decode($datos,true);
  
    try{
        $res=$conexion->Query("SELECT * FROM 
        (SELECT message.*,CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername,1 as tipo FROM message LEFT JOIN person ON person.id=message.senderid WHERE message.recipientid={0} 
        UNION ALL (SELECT message.*,CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END requestername,2 FROM message LEFT JOIN person ON person.id=message.recipientid WHERE message.senderid={0}))
        mensajes ORDER BY updated DESC",array($data["personid"]));
        
        if(count($res)>0){
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay mensajes')));
        }
        
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}


function insertMensajeNuevo($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    try{
        if($data["offerid"]){
            $destino=$conexion->Query("SELECT shipper.personid FROM serviceoffer LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid WHERE serviceoffer.id={0}",array($data["offerid"]));
            
            $conexion->Insert("message",array("body"=>$data["body"],"senderid"=>$data["personid"],"recipientid"=>$destino[0]["personid"],"sent"=>$fechaactual,"shippingrequestid"=>$data["shippingrequestid"],"updated"=>$fechaactual));
        }
        else{
            if($data["isshipper"]){
                $destino=$conexion->Query("SELECT requesterid FROM shippingrequest WHERE id={0}",array($data["shippingrequestid"]));
                $conexion->Insert("message",array("body"=>$data["body"],"senderid"=>$data["personid"],"recipientid"=>$destino[0]["requesterid"],"sent"=>$fechaactual,"shippingrequestid"=>$data["shippingrequestid"],"updated"=>$fechaactual));
            }
            else{
                $destino=$conexion->Query("SELECT shipper.personid FROM shippingrequest 
                LEFT JOIN shipment ON shipment.shippingrequestid=shippingrequest.id 
                LEFT JOIN serviceoffer ON serviceoffer.id=shipment.acceptedserviceofferid 
                LEFT JOIN shipper ON shipper.id=serviceoffer.shipperid WHERE shippingrequest.id={0}",array($data["shippingrequestid"]));
                
                $conexion->Insert("message",array("body"=>$data["body"],"senderid"=>$data["personid"],"recipientid"=>$destino[0]["personid"],"sent"=>$fechaactual,"shippingrequestid"=>$data["shippingrequestid"],"updated"=>$fechaactual));
            }
        }
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'insertado')));  
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function insertMensajeRespuesta($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    try{
        $conexion->Insert("message",array("body"=>$data["body"],"senderid"=>$data["personid"],"recipientid"=>$data["recipientid"],"sent"=>$fechaactual,"shippingrequestid"=>$data["shippingrequestid"],"updated"=>$fechaactual));
        
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'insertado')));
        
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function insertMensajeLeido($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    try{
        $leido=$conexion->Query("SELECT read FROM message WHERE id={0}",array($data["messageid"]));
        //if($leido[0]["read"]!=null){
            $conexion->Update("message",array("read"=>$fechaactual),"id={?}",array($data["messageid"]));
        //}        
        
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'insertado')));
        
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getCalificacion($datos){
    global $conexion;
    $data=json_decode($datos,true);
  
    try{
        $res=$conexion->Query("SELECT feedback.id,feedback.comment,feedback.starrating,feedback.updated, 
        CASE WHEN person.company='' OR person.company IS NULL THEN CONCAT(person.firstname, ' ', person.lastname) ELSE person.company END client  FROM feedback 
        LEFT JOIN person ON person.id=feedback.authorid 
        WHERE feedback.recipientid={0} AND feedback.updated>'{1}'",array($data["personid"],$data["updated"]));
        
        if(count($res)>0){
            $updated=$conexion->Query("SELECT max(updated) as updated FROM feedback
        WHERE feedback.recipientid={0}",array($data["personid"]));
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res,'updated'=>$updated[0]["updated"]));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay calificaciones nuevas')));
        }
        
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getGrupos($datos){
    global $conexion;
    $data=json_decode($datos,true);
  
    try{
        if($data["isshipper"]){
            $res=$conexion->Query("SELECT groups.id,groups.name FROM groups LEFT JOIN groupsperson ON groupsperson.groupid=groups.id WHERE groupsperson.personid={0}",array($data["personid"]));
            
        }else{
            $res=$conexion->Query("SELECT groups.id,groups.name FROM groups WHERE groups.personid={0}",array($data["personid"]));
        }
        
        if(count($res)>0){
            for($i=0;$i<count($res);$i++){
                    $shippers=$conexion->Query("SELECT groupsperson.id, person.id personid,person.company FROM groupsperson LEFT JOIN person ON person.id=groupsperson.personid WHERE groupsperson.groupid={0}",array($res[$i]["id"]));
                    $res[$i]["miembros"]=$shippers;
            }
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No tienes grupos')));
        }
        
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function insertSolicitudGrupo($datos){
    global $conexion;
    $data=json_decode($datos,true);
  
    try{
        $fecha=date('Y-m-d H:i:s');
        $conexion->Insert("grouprequest",array("shippingrequestid"=>$data["shippingrequestid"],"personid"=>$data["personid"],"createdat"=>$fecha,"updated"=>$fecha));
        
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Solicitud enviada')));
        
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getAlertas($datos){
	global $conexion;
	$respuesta = array('datos'=>array('error'=>'0'));
    $data = json_decode($datos,true);
    try{
        $FEEDBACK_TYPE = 1;
		$resultData = array();
        if(isset($data['shipperid'])){
            $feedback = $conexion->Query("SELECT COUNT(shipment.id) total FROM shipment
                LEFT JOIN serviceoffer ON serviceoffer.id=shipment.acceptedserviceofferid
                LEFT JOIN shippingrequest ON shippingrequest.id=shipment.shippingrequestid
                WHERE serviceoffer.shipperid={0} AND clienthasfeedback IS NOT TRUE AND shippingrequest.status=4",
                array($data['shipperid'])
            );
            $total_feedback = $feedback[0]['total'];
            $row_feedback = array('id' => $FEEDBACK_TYPE, 'total' => $total_feedback);
            if(isset($data['row_'.$FEEDBACK_TYPE])){
                if($data['row_'.$FEEDBACK_TYPE] != $total_feedback){
                    $resultData[] = $row_feedback;
                }
            }else{
                $resultData[] = $row_feedback;
            }
        }else{
            $feedback = $conexion->Query("SELECT COUNT(shipment.id) total FROM shipment
                LEFT JOIN shippingrequest ON shippingrequest.id=shipment.shippingrequestid
                WHERE shippingrequest.requesterid={0} AND shipperhasfeedback IS NOT TRUE AND shippingrequest.status=4",
                array($data['requesterid'])
            );
            $total_feedback = $feedback[0]['total'];
            $row_feedback = array('id' => $FEEDBACK_TYPE, 'total' => $total_feedback);
            if(isset($data['row_'.$FEEDBACK_TYPE])){
                if($data['row_'.$FEEDBACK_TYPE] != $total_feedback){
                    $resultData[] = $row_feedback;
                }
            }else{
                $resultData[] = $row_feedback;
            }
        }
		if(count($resultData) > 0){
			$respuesta['data'] = $resultData;
		}
    }catch(Exception $e){
		$respuesta['datos'] = array('error'=>'1', 'mensaje' => $e->getMessage());
    }finally{
		return json_encode($respuesta);
	}
}

function fechaF1Hora($fecha){
    $dias = array("Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes","Wednesday"=>"Miercoles","Thursday"=>"Jueves","Friday"=>"Viernes","Saturday"=>"Sábado");
    $meses = array("January"=>"Ene.","February"=>"Feb.","March"=>"Mar.","April"=>"Abr.","May"=>"May.","June"=>"Jun.","July"=>"Jul.","August"=>"Ago.","September"=>"Sept.","October"=>"Oct.","November"=>"Nov.","December"=>"Dic.");
    
    $fechaFormato="";
    $hora=  substr($fecha, 11,8);
    $fecha=  strtotime($fecha);
    $fechaDia=strftime("%A", $fecha);
    $fechaDiaNumero=strftime("%d", $fecha);
    $fechaMes=strftime("%B", $fecha);
    $fechaAnio=strftime("%Y", $fecha);

    $fechaFormato=$fechaDiaNumero . " de ".$meses[$fechaMes] ." de ".$fechaAnio . " " . $hora;
    
    return $fechaFormato;
}

function insertCheck($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $fecha=date('Y-m-d H:i:s');
        foreach($data as $row){
           $conexion->Insert("shippingrequestcheck",array("shippingrequestid"=>$row["shippingrequestid"],"shipperid"=>$row["shipperid"],"latitude"=>$row["latitude"],"longitude"=>$row["longitude"],"datecheck"=>$row["datecheck"],"updated"=>$fecha,"driverid"=>$row["driverid"])); 
            
            $cliente=$conexion->Query("SELECT requesterid FROM shippingrequest WHERE id={0}",array($row["shippingrequestid"]));
            
            $conexion->Insert("alert",array(
                     'recipientid'=>$cliente[0]["requesterid"],
                     'createdat'=>$fecha,
                     'updated'=>$fecha,
                     'type'=>14,
                     'relationid'=>$row["shippingrequestid"]
            ));
        }

        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Check Insertado')));   
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function insertTracking($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $fecha=date('Y-m-d H:i:s');
        $conexion->Insert("tracking",array("shipperid"=>$data["shipperid"],"latitude"=>$data["latitude"],"longitude"=>$data["longitude"],"datetracking"=>$data["datetracking"],"updated"=>$fecha,"driverid"=>$data["driverid"]));
        
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Tracking Insertado')));   
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getTracking($datos){
    global $conexion;
    $hoy=date('Y-m-d').' 00:00:00';
    $data=json_decode($datos,true);
    $fecha=" AND DATE(datetracking)>='$hoy'";
    if($data["updated"]!=""){
        $fecha=" AND DATE(datetracking)>='".$data["updated"]."'";  
    }
    else if($data["fecha2"]!=""){
        $fecha=" AND DATE(datetracking) BETWEEN '".$data['fecha1']."' AND '".$data["fecha2"]."'";  
    }
    $where="";
    if($data["driverid"]>0){
        $where=" AND driverid=".$data["driverid"];
    }
    try{
        $res=$conexion->Query("SELECT * FROM tracking WHERE shipperid={0} {1} {2}",array($data["shipperid"],$fecha,$where));
        if(count($res)>0){
           $upd=$conexion->Query("SELECT MAX(updated) as updated FROM tracking WHERE shipperid={0} {1} {2}",array($data["personid"],$fecha,$where));
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res,'updated'=>$upd[0]["updated"]));    
        }else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay tracking')));
        }         
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getCheck($datos){
    global $conexion;
    $hoy=date('Y-m-d')." 00:00:00";
    $data=json_decode($datos,true);
    $fecha=" AND DATE(datecheck)>='$hoy'";
    if($data["updated"]!=""){
        $fecha=" AND DATE(updated)>='".$data["updated"]."'";  
    }
    else if($data["fecha2"]!=""){
        $fecha=" AND DATE(datecheck) BETWEEN '".$data['fecha1']."' AND '".$data["fecha2"]."'";  
    }
    try{
        if($data["shipperid"]==0){
            $res=$conexion->Query("SELECT * FROM shippingrequestcheck WHERE shipperid={0} {1}",array($data["shipperid"],$fecha));
            if(count($res)>0){
                $upd=$conexion->Query("SELECT MAX(updated) as updated FROM shippingrequestcheck WHERE shipperid={0} {1}",array($data["shipperid"],$fecha));
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res,'updated'=>$upd[0]["updated"]));    
            }else{
                return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay checks')));
            }   
        }
        else{
            $res=$conexion->Query("SELECT * FROM shippingrequestcheck WHERE shipperid={0} {1}",array($data["shipperid"],$fecha));
            if(count($res)>0){
                $upd=$conexion->Query("SELECT MAX(updated) as updated FROM shippingrequestcheck WHERE shipperid={0} {1}",array($data["shipperid"],$fecha));
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res,'updated'=>$upd[0]["updated"]));    
            }else{
                return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay checks')));
            }   
        }
              
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getTrackingEnvio($datos){
    global $conexion;
    $hoy=date('Y-m-d')." 00:00:00";
    $data=json_decode($datos,true);
    
    try{
        if($data["trackingtype"]==0){
            $res=$conexion->Query("SELECT tracking.latitude, tracking.longitude, tracking.datetracking fecha FROM tracking 
            LEFT JOIN serviceoffer ON serviceoffer.shipperid=tracking.shipperid 
            LEFT JOIN shipment ON shipment.acceptedserviceofferid=serviceoffer.id 
            WHERE shipment.shippingrequestid={0}",array($data["shippingrequestid"]));
            if(count($res)>0){
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res));    
            }else{
                return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay tracking')));
            }   
        }
        else{
            $res=$conexion->Query("SELECT latitude,longitude,datecheck as fecha FROM shippingrequestcheck WHERE shippingrequestid={0}",array($data["shippingrequestid"],$fecha));
            if(count($res)>0){
                return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'), 'data'=>$res));    
            }else{
                return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay checks')));
            }   
        }
              
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function updateExpiracion($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $fecha=date('Y-m-d H:i:s');
        $conexion->Update("shippingrequest",array("expirationdate"=>$data["fecha"],"updated"=>$fecha),"id={?}",array($data["shippingrequestid"]));
        
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Expiracion Actualizada')));   
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function insertVehiculo($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $conexion->Insert("vehicle",array("description"=>$data["placa"],"shipperid"=>$data["shipperid"],"active"=>true));
        
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Vehiculo insertado')));   
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function updateVehiculo($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        if(isset($data["active"])){
            $conexion->Update("vehicle",array("active"=>$data["active"]?1:0),"id={?}",array($data["vehicleid"]));
            $msj=$data["active"] ? "activado" : "eliminado";
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Vehiculo '.$msj)));   
        }else{
            $conexion->Update("vehicle",array("description"=>$data["placa"]),"id={?}",array($data["vehicleid"]));
        
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Vehiculo Actualizado')));   
        }
        
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function insertCupon($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    $conexion->StartTransaction();
    try{
        
        $shipper=$conexion->Query("SELECT groupid FROM groupsperson LEFT JOIN shipper ON shipper.personid=groupsperson.personid WHERE shipper.id={0}",array($data["shipperid"]));
        //valida el cupon
        $cupon=$conexion->Query("SELECT * FROM promotions WHERE code='{0}'",array(strtoupper(trim($data["codigo"]))));
        if(count($cupon)>0){
            if($cupon[0]["active"]==0){
                return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'El cupón ya no esta disponible')));
            }
            if($cupon[0]["expirationdate"]<$fechaactual){
                return json_encode(array('datos'=>array('error'=>'2','mensaje'=>'El cupón ha expirado')));
            }
            if($cupon[0]["groupid"]!=""){
                $entro=0;
                foreach($shipper as $ship){
                    if($ship["groupid"]==$cupon[0][$groupid]){
                        $entro=1;
                    }
                }
                if($entro==0){
                    return json_encode(array('datos'=>array('error'=>'3','mensaje'=>'No pertenece al grupo para usar el cupón')));
                }
            }
        }
        else{
            return json_encode(array('datos'=>array('error'=>'4','mensaje'=>'El código no es válido')));
        }
        
        //valida que el cupon no se haya usado antes
        $usado=$conexion->Query("SELECT count(promotionid)total FROM shipperpromotion WHERE shipperid={0} AND promotionid={1}",array($data["shipperid"],$cupon[0]["id"]));
        if($usado[0]["total"]>0){
            return json_encode(array('datos'=>array('error'=>'5','mensaje'=>'El cupón ya fue usado')));
        }
        
        $conexion->Insert("shipperpromotion",array("shipperid"=>$data["shipperid"],"promotionid"=>$cupon[0]["id"],"updated"=>$fechaactual));
        
        $conexion->Insert("shipperaccountdetail",array("shipperid"=>$data["shipperid"],"amount"=>$cupon[0]["amount"],"promotionid"=>$cupon[0]["id"],"created"=>$fechaactual));
        
        $balance=$conexion->query("SELECT balance,available FROM shipperaccount WHERE shipperid={0}",array($data["shipperid"]));
        $saldo=$balance[0]["balance"];
        $saldo+=$cupon[0]["amount"];
        $disponible=$balance[0]["available"];
        $disponible+=$cupon[0]["amount"];
        $conexion->Update("shipperaccount",array("balance"=>$saldo,"available"=>$disponible),"shipperid={?}",array($data["shipperid"]));
        
        $conexion->Commit();
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'El cupón ha sido abonado a tu cuenta')));
    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'6','mensaje'=>$e->getMessage())));
    }
}

function getHistorialCuenta($datos){
    global $conexion;
    $data=json_decode($datos,true);
    
    try{
        $detalle=$conexion->Query("SELECT * FROM shipperaccountdetail WHERE shipperid={0} AND created>'{1}'",array($data["shipperid"],$data["updated"]));
        
        foreach($detalle as $row){
            if($row["carriercreditid"]!=""){
                $row["tipo"]=1;
            }
            elseif($row["promotionid"]!=""){
                $row["tipo"]=2;
            }
            else{
                $row["tipo"]=3;
            }
        }
        
        if(count($detalle)>0){
            $upd=$conexion->Query("SELECT max(created) as upd FROM shipperaccountdetail WHERE shipperid={0} AND created>'{1}'",array($data["shipperid"],$data["updated"]));
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>"listado"),'data'=>$detalle,'updated'=>$upd[0]["upd"]));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No hay movimientos nuevos')));
        }      
    }
    catch(Exception $e){
      
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function insertDriver($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    $conexion->StartTransaction();
    try{
        $shipper=$conexion->Query("SELECT company FROM person WHERE id={0}",array($data["personid"]));
        $dni=strtoupper(trim($data["dni"]));
        
        $person=$conexion->Insert("person",array(
            "firstname"=>$data["firstname"],
            "middlename"=>$data["middlename"],
            "lastname"=>$data["lastname"],
            "secondlastname"=>$data["secondlastname"],
            "isdriver"=>1,
            "company"=>$shipper[0]["company"],
            'suspended'=>false,
            'newoffer'=>0,
            'offercanceled'=>0,
            'offeraccepted'=>0,
            'offerrejected'=>0,
            'newquestion'=>0,
            'newreply'=>0,
            'shipmentcollected'=>0,
            'shipmentdelivered'=>0,
            'feedback'=>0,
            'assignedvehicle'=>0,
            'updated'=>$fechaactual,
            'newshipping'=>0,
            'shippingexpiration'=>0,
            'shippingcheck'=>0,
            'competingoffer'=>0,
            'dni'=>$dni
        ));
        
        $usuario=substr($shipper[0]["company"],0,3).$dni."@efletex.com";
        
        $conexion->Insert("users",array(
            'email'=>$usuario,
            'password'=>password_hash($data["password"],PASSWORD_DEFAULT),
            'resetpasswordtoken'=>'',
            'resetpasswordsentat'=>$fechaactual, 
            'remembercreatedat'=>$fechaactual, 
            'signincount'=>0, 
            'currentsigninat'=>$fechaactual, 
            'lastsigninat'=>$fechaactual, 
            'currentsigninip'=>getRealIP(), 
            'lastsigninip'=>'', 
            'confirmationtoken'=>0, 
            'confirmedat'=>$fechaactual, 
            'confirmationsentat'=>$fechaactual, 
            'failedattempts'=>0, 
            'unlocktoken'=>'', 
            'lockedat'=>$fechaactual, 
            'personid'=>$person[0]["id"], 
            'remember_token'=>'', 
            'created_at'=>$fechaactual, 
            'updated_at'=>$fechaactual,
            'active'=>1,
            'verifiedaccount'=>1,
            'isverified'=>1
        ));
        
        $conexion->Insert("driver",array("personid"=>$person[0]["id"],"shipperid"=>$data["shipperid"],"updated"=>$fechaactual,"phone"=>$data["phone"]));
        
        $conexion->Commit();
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Chofer creado correctamente')));   
    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getDriver($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        $drivers=$conexion->Query("SELECT driver.id,person.firstname,person.middlename,person.lastname,person.secondlastname,person.dni,users.email,driver.phone FROM driver LEFT JOIN person ON person.id=driver.personid 
        LEFT JOIN users ON users.personid=driver.personid WHERE driver.shipperid={0} AND person.updated>'{1}'",array($data["shipperid"],$data["updated"]));
    
        if(count($drivers)>0){
            $upd=$conexion->Query("SELECT max (person.updated) as upd FROM driver LEFT JOIN person ON person.id=driver.personid 
        LEFT JOIN users ON users.personid=driver.personid WHERE driver.shipperid={0} AND person.updated>'{1}'",array($data["shipperid"],$data["updated"]));
            return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>$drivers,'updated'=>$upd[0]["upd"]));
        }
        else{
            return json_encode(array('datos'=>array('error'=>'1','mensaje'=>'No tiene choferes')));
        }   
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function updateDriver($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    $conexion->StartTransaction();
    try{
        $driver=$conexion->Query("SELECT personid FROM driver WHERE id={0}",array($data["driverid"]));
        
        $conexion->Update("person",array(
            "firstname"=>$data["firstname"],
            "middlename"=>$data["middlename"],
            "lastname"=>$data["lastname"],
            "secondlastname"=>$data["secondlastname"],
            "updated"=>$fechaactual
        ),"id={?}",array($driver[0]["personid"]));
        
        if($data["password"]!=""){
            $conexion->Update("users",array("password"=>password_hash(trim($data["password"],PASSWORD_DEFAULT))),"personid={?}",array($driver[0]["personid"]));   
        }
        
        $conexion->Update("driver",array("phone"=>$data["phone"]),"id={?}",array($data["driverid"]));
        
        $conexion->Commit();
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Chofer actualizado')));   
        
        
    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function asignarChofer($datos){
    global $conexion;
    $data=json_decode($datos,true);
    $fechaactual=date('Y-m-d H:i:s');
    $conexion->StartTransaction();
    try{
        $conexion->Query("UPDATE vehicle SET driverid=null,updated='$fechaactual' WHERE driverid={0}",array($data["driverid"]));
        $conexion->Update("vehicle",array("driverid"=>$data["driverid"],"updated"=>$fechaactual),"id={?}",array($data["vehicleid"]));
        
        $conexion->Commit();
        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'Chofer asignado')));   
        
        
    }
    catch(Exception $e){
        $conexion->Rollback();
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}

function getComisionOferta($datos){
    global $conexion;
    $data=json_decode($datos,true);
    try{
        if($data["ispublic"]){
            $comision=$conexion->Query("SELECT quantity,type FROM offercostpublic WHERE {0}>start AND {0}<=v_end AND groupid IS NULL",array($data["offercost"]));
        }
        else{
            $shipping_group=$conexion->Query("SELECT shippingrequestid,array_agg(groupid) as groups FROM shippingrequestgroup
            WHERE shippingrequestid={0} GROUP BY shippingrequestid",array($data["shippingrequestid"]));
            
            $comision=$conexion->Query("SELECT quantity,type FROM (SELECT offercostpublic.*,groups.priority 
            FROM offercostpublic LEFT JOIN groups ON groups.id=offercostpublic.groupid 
            WHERE offercostpublic.groupid IN ({1})) cost WHERE {0}>start AND {0}<=v_end ORDER by priority",array($data["offercost"],substr($shipping_group[0]["groups"],1,-1)));
        }
        
        
        $costo=$comision[0]["quantity"];
        if($comision[0]["type"]==1){
            $costo=$data["offercost"]*$comision[0]["quantity"]/100;
        }

        return json_encode(array('datos'=>array('error'=>'0','mensaje'=>'listado'),'data'=>round($costo,2)));
  
    }
    catch(Exception $e){
        return json_encode(array('datos'=>array('error'=>'2','mensaje'=>$e->getMessage())));
    }
}
?>