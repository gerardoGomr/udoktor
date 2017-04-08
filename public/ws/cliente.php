<?php
 
    require_once('lib/nusoap.php');
     
    //$cliente = new nusoap_client('http://localhost/ServerWEB/t1/public/ws');
    $cliente = new nusoap_client('http://efletex.symonit.com/ws');
    
    /*$datos=array('datos'=>'{"fecha":"2016-01-01 00:00:00","requesterid":39,"ids":"0"}');

    $resultado = $cliente->call('getPreguntasCliente',$datos);*/

    //test WS2
    /*$datos=array("id"=>19);
    
    $resultado = $cliente->call('getUser',$datos);*/

    
    //test WS3
    /*$datos=array("datos"=>'{"usuario":"an_merob@hotmail.com","password":"123"}');
    
    $resultado = $cliente->call('getUser',$datos);*/

    //echo json_encode($datos);


    //test WS4
    /*$datos=array('datos'=>'{"firstname":"andres","middlename":"","lastname":"medina","secondlastname":"roblero",
        "isemployee":false,"isshipper":true,"isdriver":false,"email":"an_merob@hotmail.com","password":123}');


    $resultado = $cliente->call('insertCliente',$datos);*/


    //echo password_hash("test",PASSWORD_DEFAULT);

    
    //test getEnvios
    //$datos=array('datos'=>'{"id":39,"fecha":"2016-05-01 12:00:00"}');
    
    //$resultado = $cliente->call('getEnvios',$datos);

    //test insertEnvios
    /*$datos=array('datos'=>'{"requesterid":"39","createdat":"2016-06-23 12:06:47","coldprotection":"1","sortout":"0","blindperson":"0","costtype":"1","cost":"500.00","firstofferdiscount":"1","discountrate":"15","status":"1","deleted":"","title":"10 cajas de leche congelada","updated":"2016-06-23 12:06:47","km":"56.12","tiempo":"06:35:00","peso_total":"250.00","contactfullnameOrigen":"Xpeke","contactnumber1Origen":"9611234567","street1Origen":"Av Central #16","street2Origen":"","stateidOrigen":"2431","latitudeOrigen":"15.6594289","longitudeOrigen":"-92.141402","collectiondateOrigen":"2016-06-25","collectionuntildateOrigen":"2016-06-30","placeOrigen":"1","elevatorOrigen":"0","collectinsideOrigen":"0","updatedOrigen":"2016-06-23 16:20:22","cityOrigen":"Frontera Comalapa","collecttimefromOrigen":"10:00:00","collecttimeuntilOrigen":"18:00:00","generalubicationOrigen":"Frontera Comalapa,Chiapas, Mexico","contactfullnameDestino":"Soaz","contactnumber1Destino":"3654128","street1Destino":"Av Uno #209","street2Destino":"","stateidDestino":"2457","latitudeDestino":"18.1282261","longitudeDestino":"-94.447001","deliverydateDestino":"2016-07-01","deliveryuntildateDestino":"2016-07-15","placeDestino":"2","elevatorDestino":"0","callbeforeDestino":"0","deliverytimeDestino":"0","deliverywithinDestino":"0","updatedDestino":"2016-06-23 16:20:22","cityDestino":"Coatzacoalcos","deliverytimefromDestino":"10:00:00","deliverytimeuntilDestino":"18:00:00","generalubicationDestino":"Coatzacoalcos, Veracruz, Mexico","items": [{
            "description": "Cajas",
            "perishable": false,
            "livingbeing": false,
            "shippingitemcategoryid": 1,
            "quantity": 5,
            "dangerous": false,
            "stackble": false,
            "long": "25",
            "high": "40",
            "width": "60",
            "unitdimensions": "cm",
            "weight": "15",
            "unitweight": "kg",
            "comments": "",
            "updated": "2016-06-25 13:35:40"
        },{"description": "Cajones",
            "perishable": false,
            "livingbeing": false,
            "shippingitemcategoryid": 1,
            "quantity": 2,
            "dangerous": false,
            "stackble": false,
            "long": "25",
            "high": "40",
            "width": "30",
            "unitdimensions": "cm",
            "weight": "10",
            "unitweight": "kg",
            "comments": "botellas",
            "updated": "2016-06-25 13:35:40",
            "images":[{"updated":"2016-06-26 13:35:40","ext":"jpg","imagen_64":""}]
        }
        ]}');

    $resultado = $cliente->call('insertEnvio',$datos);*/


    //$datos=array("datos"=>'{"shippingrequestid":"76"}');
    
    //$resultado = $cliente->call('deleteEnvios',$datos);

    //test getBuscarCargas
    //$datos=array('datos'=>'{"id":39,"collectionCity":"tuxtla","deliveryCity":"guadalajara"}');
    
    //$resultado = $cliente->call('getBuscarCargas',$datos);


    //test publicar oferta
    //$datos=array('datos'=>'{"shipperid":"5","shippingrequestid":"12","shipmentcost":"200","conditions":"pago contra entrega, no hago descargas","collectiondate":"2016-07-06","collectionuntildate":"","collectiontype":"1","deliverydate":"2016-07-11","deliveryuntildate":"2016-07-12","deliverytype":"3","paymentmethodid":2,"paymentinformation":"Banco Santander, CTA. 2165315"}');

    //$resultado = $cliente->call('insertOferta',$datos);

    //test listado ofertas
    /*$datos=array('datos'=>'{"shipperid":"5","updated":"2016-07-06 07:42:30"}');

    $resultado = $cliente->call('getOfertas',$datos);*/

    //test reseteo password
    /*$datos=array('datos'=>'{"email":"an_merob@hotmail.com"}');
    
    $resultado = $cliente->call('getPass',$datos);*/

    //test getEstados
    /*$datos=array('datos'=>'{"fecha":"2016-01-01 00:00:00"}');
    
    $resultado = $cliente->call('getEstados',$datos);*/

    //test status oferta
    //$datos=array('datos'=>'{"offerid":"22","status":"2","reason":""}');
    
    //$resultado = $cliente->call('insertStatusOferta',$datos);

/*
    $datos=array('datos'=>'{"fecha":"2016-01-01 00:00:00","ids":"0,24","requesterid":"39"}');
    
    $resultado = $cliente->call('getOfertasCliente',$datos);*/

    /*$datos=array('datos'=>'{"fecha":"2016-07-15 00:29:44","id":"175","isshipper":false}');
    
    $resultado = $cliente->call('getPerfil',$datos);*/

    //$datos=array('datos'=>'{"shipperid":5,"fecha":"2016-05-01 12:00:00"}');
    
    //$resultado = $cliente->call('getCargas',$datos);

    //$datos=array('datos'=>'{"fecha":"2016-05-01 12:00:00"}');
    
    //$resultado = $cliente->call('getMetodoPago',$datos);

    /*$datos=array('datos'=>'{"shippingrequestid":13,"shipmentid":10,"vehicleid":4,"status":4,"placa":"","comment":"sadada","starrating":2,"personid":101}');
    
    $resultado = $cliente->call('insertStatusCarga',$datos);*/

    /*$datos=array('datos'=>'{"personid":175,"imagen":"","ext":"jpg"}');
    
    $resultado = $cliente->call('insertPerfilImagen',$datos);*/

    /*$datos=array('datos'=>'{"personid":98,"comment":"asaffd","starrating":4,"shippingrequestid":13}');
    
    $resultado = $cliente->call('insertCalificacion',$datos);*/

    /*$datos=array('datos'=>'{"personid":98,"newoffer":true,"offercanceled":true,"offeraccepted":false,"offerrejected":false,"newquestion":true,"newreply":false,"shipmentcollected":true,"shipmentdelivered":true,"feedback":true}');
    
    $resultado = $cliente->call('insertConfigNotificaciones',$datos);*/

    /*$datos=array('datos'=>'{"personid":98,"states":"2431"}');
    
    $resultado = $cliente->call('insertCobertura',$datos);*/

    $datos=array('datos'=>'{"shipperid":75,"codigo":"PROMO1"}');
    
    $resultado = $cliente->call('insertCupon',$datos);

    echo json_encode($datos);

    echo $resultado;

     
?>