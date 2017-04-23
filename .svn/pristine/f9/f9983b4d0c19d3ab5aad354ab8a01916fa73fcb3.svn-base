/*
 * Script para inicio de sesión y creación de cuentas
 * Autor: OT
 * Fecha: 05-12-2016
 * 
 */

var geocoder = new google.maps.Geocoder();
 
/*
 * Valida los datos para iniciar sesion
 * Autor: OT
 * Fecha: 05-07-2016
 * 
 */
function iniciarSesion(){
 var usuario=$("#usuario").val();
 var pass=$("#password").val();
 $("#divmensaje").html("");
 
 if(usuario.replace(/\s/g,"")==''){
   $("#divmensaje").html("<div class='alert alert-danger'>Escriba el nombre de usuario.</div>");
   $("#usuario").focus();
   return false;
 }
 
 if(pass.replace(/\s/g,"")==''){
   $("#divmensaje").html("<div class='alert alert-danger'>Escriba la contraseña.</div>");
   $("#password").focus();
   return false;
 }
 waitingDialog(); 
return true;
}

/*
 * Verifica la tecla enter en el formulario de inicio de sesión
 * Autor: OT
 * Fecha: 05-12-2016
 */
function validarEnter(e){
 tecla = (document.all) ? e.keyCode : e.which;
 if (tecla==13){
     iniciarSesion();
 }
}

/*
 * Muestra el formulario cliente
 * Autor: OT
 * Fecha: 05-12-2016
 */
function mostrarTipoCuenta(){
    
    var nombreCompleto=$("#nombreCompleto").val();
    var emailCuenta=$("#emailCuenta").val();
    var companiaCuenta=$("#companiaCuenta").val();
    var telefonoCuenta=$("#telefonoCuenta").val();
    var passCuenta=$("#passCuenta").val();
    var paisCuenta=$("#paisCuenta").val();
    var estadoCuenta=$("#estadoCuenta").val();
    var ciudadCuenta=$("#ciudadCuenta").val();
    
    $("#divmensaje").html("");
    
    if(nombreCompleto.replace(/\s/g,"")==''){
       $("#divmensaje").html("<br><div class='alert alert-danger'>Escriba el nombre completo.</div>");
       $("#nombreCompleto").focus();
       return;
    }
    
    if(emailCuenta.replace(/\s/g,"")==''){
       $("#divmensaje").html("<br><div class='alert alert-danger'>Escriba el email.</div>");
       $("#emailCuenta").focus();
       return;
    }
    
    var esValido=validarEmail(emailCuenta);
        if(esValido==0){
            $("#divmensaje").html("<br><div class='alert alert-danger'>La dirección email ingresada es inválida.</div>");
            $("#emailCuenta").focus();
            return;
        }
    
    if(paisCuenta=="0"){
        $("#divmensaje").html("<br><div class='alert alert-danger'>Seleccione el País.</div>");
       $("#paisCuenta").focus();
    }
    
    if(estadoCuenta=="0"){
       $("#divmensaje").html("<br><div class='alert alert-danger'>Seleccione el Estado.</div>");
       $("#estadoCuenta").focus();
    }
    
    if(ciudadCuenta=="0"){
       $("#divmensaje").html("<br><div class='alert alert-danger'>Seleccione el Municipio.</div>");
       $("#ciudadCuenta").focus();
    }
    
    
    
    if(telefonoCuenta.replace(/\s/g,"")==''){
       $("#divmensaje").html("<br><div class='alert alert-danger'>Escriba el teléfono.</div>");
       $("#telefonoCuenta").focus();
       return;
    }
    
    if(passCuenta.replace(/\s/g,"")==''){
      $("#divmensaje").html("<br><div class='alert alert-danger'>Escriba la contraseña.</div>");
      $("#passCuenta").focus();
      return false;
    }
    
    var longitudPass= passCuenta.length;
    
    if(parseFloat(longitudPass)<8){
      $("#divmensaje").html("<br><div class='alert alert-danger'>La contraseña debe tener al menos 8 caracteres sin espacios.</div>");
      $("#passCuenta").focus();
      return false;
    }
    
    if(!$('#aceptaTerminos').is(':checked') ) {
        $("#divmensaje").html("<br><div class='alert alert-danger'>Acepte los términos y condiciones.</div>");
        return false;
    }
    
    $("#divGeneral").attr("style","display:none");
    $("#cargaTipoCuenta").removeAttr("style");
    grecaptcha.reset();
}

/*
 * Muestra el boton y capcha para crear cuenta cliente
 * Autor: OT
 * Fecha: 12-07-2016
 */
function crearCuentaClienteDiv(){
    $("#divGeneral,#cargaTipoCuenta").attr("style","display:none");
    $("#divCaptcha,#divCrearCuentaCliente").removeAttr("style");
}


/*
 * Limpia el formularion de creacion de cuenta
 * Autor: OT
 * Fecha: 12-07-2016
 */
function cancelarCreacionCuenta(){
    $("#divCaptcha,#divCrearCuentaCliente").attr("style","display:none");
    $("#divGeneral").removeAttr("style");
    $("#nombreCompleto").val("");
    $("#emailCuenta").val("");
    $("#companiaCuenta").val("");
    $("#telefonoCuenta").val("");
    $("#passCuenta").val("");
    $("#aceptaTerminos").attr("checked", false);
    $("#divmensaje").html('');
    grecaptcha.reset();
}


/*
 * Limpia el formularion de creacion de cuenta prestador de servicios
 * Autor: OT
 * Fecha: 12-07-2016
 */
function cancelarCreacionCuenta2(){
    $("#divCaptcha,#divCrearCuentaPrestador,#divDatosPrestador").attr("style","display:none");
    $("#divGeneral").removeAttr("style");
    $("#nombreCompleto").val("");
    $("#emailCuenta").val("");
    $("#companiaCuenta").val("");
    $("#telefonoCuenta").val("");
    $("#passCuenta").val("");
    $("#aceptaTerminos").attr("checked", false);
    $("#divmensaje").html('');
    grecaptcha.reset();
}




/*
     * Muestra el mapa para elegir la ubicacion
     * Fecha: 06-12-2016
     * Autor: OT
     */
    function mostrarMapaLogin(idEnvio,tipo){
        var mapaEnvio="";
        
        $("#divMapaLogin").dialog({
            autoOpen: false,
            title: "Seleccione su ubicación",
            modal:true,
            width: 600,
            height: 550,
            close: function(event,ui){
                 $("#divMapaLogin").html('');
                 $("#divMapaLogin").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/crearcuenta/verMapaUbicacion",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#divMapaLogin").html(respuesta);
                            
                             
                             if($("#latitudUbicacion").val()!=""){
                                var myLatlng = new google.maps.LatLng($("#latitudUbicacion").val(),$("#longitudUbicacion").val());
                                var mapOptions = {
                                  center: myLatlng,
                                  zoom: 10,
                                  mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                
                                var mapaEnvio = new google.maps.Map(document.getElementById("mapidUb"),mapOptions);
                                var marker = new google.maps.Marker({
                                         position: myLatlng,
                                         map: mapaEnvio,
                                         draggable: true
                                     });
                                     
                                     google.maps.event.addListener(marker, 'dragend', function(event) {
                                       $("#latitudUbicacion").val(event.latLng.lat());
                                       $("#longitudUbicacion").val(event.latLng.lng());
                                       obtenerDireccionPunto(event.latLng);
                                   });

                                   google.maps.event.addListener(marker, 'click', function(event) {
                                       marker.setMap(null);
                                       $("#latitudUbicacion").val('');
                                       $("#longitudUbicacion").val('');
                                       $("#idUbicacion").val('');
                                   });
                                     
                              }else{
                                  mapaEnvio = new google.maps.Map(document.getElementById("mapidUb"),
                                    {
                                      center: {lat: 19.432608, lng: -99.133208},
                                      zoom: 7
                                    });
                              }
                             
                              google.maps.event.addListener(mapaEnvio, 'click', function(event) {
                                  
                                  if($("#latitudUbicacion").val()==""){
                                       var marker = new google.maps.Marker({
                                           position: event.latLng,
                                           map: mapaEnvio,
                                           draggable: true
                                       });
                                       $("#latitudUbicacion").val(event.latLng.lat());
                                       $("#longitudUbicacion").val(event.latLng.lng());
                                      obtenerDireccionPunto(event.latLng);
                                  }
                                   google.maps.event.addListener(marker, 'dragend', function(event) {
                                       $("#latitudUbicacion").val(event.latLng.lat());
                                       $("#longitudUbicacion").val(event.latLng.lng());
                                       obtenerDireccionPunto(event.latLng);
                                   });

                                   google.maps.event.addListener(marker, 'click', function(event) {
                                       marker.setMap(null);
                                       $("#latitudUbicacion").val('');
                                       $("#longitudUbicacion").val('');
                                       $("#idUbicacion").val('');
                                   });
                              });
                   }
                });
            }
	});
	$("#divMapaLogin").dialog('open');
    }
    
/*
 * Obtiene la direcion del punto recibido
 * Fecha: 06-12-2016
 * Autor: OT
 */

function obtenerDireccionPunto(punto){
    geocoder.geocode({ 'latLng': punto }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
               if (results[1]) {
                   var addressComponent = results[0].address_components;
                    var aCompareAdress = {
                            'locality' : 'municipio',
                            'administrative_area_level_1':'estado',
                            'administrative_area_level_2' : 'estado2',
                            'country' : 'pais'
                    };
                    var cadenaOrigen1="";
                    for(var iAddress in addressComponent){
                            var type = aCompareAdress[addressComponent[iAddress].types[0]];
                            if(type != null){
                               if(type=="municipio") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                               if(type=="estado") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                               if(type=="estado2") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                               if(type=="pais") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].long_name.toUpperCase()+",";
                            }
                    }
                    $("#idUbicacion").val(cadenaOrigen1);
               }
            }
         });
    
}

/*
 * Agrega el servicio
 * Autor: OT
 * Fecha 06-12-2016
 */
function agregarServicio(){
    var nuevoServicio =$("#nuevoServicio").val();
    var serviciosid=$("#serviciosid").val();
    if(nuevoServicio.replace(/\s/g,"")==''){
      $("#divmensaje").html("<br><div class='alert alert-danger'>Escriba el nombre del servicio.</div>");
      $("#nuevoServicio").focus();
      return false;
    }
    
    waitingDialog(); 
       $.ajax({
          datatype:"json",
          type:"GET",
          data:{'nuevoServicio':nuevoServicio,'serviciosid':serviciosid},
          url:"/crearcuenta/nuevoServicio",
          success:function(respuesta){
              setTimeout(function(){closeWaitingDialog();},100);
              $("#nuevoServicio").val('');
              if(respuesta.error=="0"){
                 $("#divmensaje").html("<br><div class='alert alert-success'>Servicio agregado correctamente.</div>");
                 $('#serviciosid').html(respuesta.servicios);
                 $('#serviciosid').multiselect('rebuild');
                 $('#serviciosid').multiselect({maxHeight: 200,
                      includeSelectAllOption: true,
                      selectAllJustVisible: false
                 });
              }else if(respuesta.error=="1"){
                 $("#divmensaje").html("<br><div class='alert alert-danger'>El servicio ya existe, intente con otro.</div>");
              }else{
                 $("#divmensaje").html("<br><div class='alert alert-danger'>Ocurrio un error en el proceso, contacte al administrador del sistema.</div>");
              }
          }
      });
}





/*
 * Crea la cuenta de usuario tipo cliente
 * Autor: OT
 * Fecha 06-12-2016
 */

function crearCuentaCliente(){
    var nombreCompleto=$("#nombreCompleto").val();
    var emailCuenta=$("#emailCuenta").val();
    var companiaCuenta=$("#companiaCuenta").val();
    var telefonoCuenta=$("#telefonoCuenta").val();
    var passCuenta=$("#passCuenta").val();
    var paisCuenta=$("#paisCuenta").val();
    var estadoCuenta=$("#estadoCuenta").val();
    var ciudadCuenta=$("#ciudadCuenta").val();
    
       waitingDialog(); 
       $.ajax({
          datatype:"json",
          type:"POST",
          headers:{'X-CSRF-TOKEN':$('input[name="_token"]').val()},
          data:{'nombreCompleto':nombreCompleto,'emailCuenta':emailCuenta,'companiaCuenta':companiaCuenta,'telefonoCuenta':telefonoCuenta,'passCuenta':passCuenta,
              'valorCaptcha':$("#g-recaptcha-response").val(),'paisCuenta':paisCuenta,'estadoCuenta':estadoCuenta,'ciudadCuenta':ciudadCuenta},
          url:"../crearcuenta/cliente",
          success:function(respuesta){
              setTimeout(function(){closeWaitingDialog();},100);
              if(respuesta.error=="1"){
                 $("#divmensaje").html("<br><div class='alert alert-danger'>La dirección email ingresada ya está en uso, favor de verificar.</div>");
                 grecaptcha.reset();
                 $("#divCaptcha,#cargaTipoCuenta,#divCrearCuentaCliente").attr("style","display:none");
                 $("#divGeneral").removeAttr("style");
             }else if(respuesta.error=="2"){
                 $("#divmensaje").html("<br><div class='alert alert-danger'>Indique que no es un robot.</div>");
                 grecaptcha.reset();
              }else{
                 $("#divPrincipal").hide();
                 //$("#labelRespuesta").html('Hemos enviado un correo de confirmación a tu bandeja, una vez válidado podrás ser parte de la comunidad Efletex')
                 $("#labelRespuesta").html('Su cuenta ha sido creada correctamente.');
                 $("#divRespuesta").show();
                 setTimeout(function(){location.href="/login";},10000);
              }
          }
      });
}

/*
 * Crea la cuenta de usuario del prestador de servicios
 * Autor: OT
 * Fecha 06-12-2016
 */

function crearCuentaPrestador(){
    var nombreCompleto=$("#nombreCompleto").val();
    var emailCuenta=$("#emailCuenta").val();
    var companiaCuenta=$("#companiaCuenta").val();
    var telefonoCuenta=$("#telefonoCuenta").val();
    var passCuenta=$("#passCuenta").val();
    var idClasificacion=$("#idClasificacion").val();
    var serviciosid=$("#serviciosid").val();
    var latitudUbicacion=$("#latitudUbicacion").val();
    var longitudUbicacion=$("#longitudUbicacion").val();
    var paisCuenta=$("#paisCuenta").val();
    var estadoCuenta=$("#estadoCuenta").val();
    var ciudadCuenta=$("#ciudadCuenta").val();
    
    if(idClasificacion==0){
        $("#divmensaje").html("<br><div class='alert alert-danger'>Seleccione la clasificación.</div>");
        return false;
    }
    
    if(latitudUbicacion.replace(/\s/g,"")==''){
        $("#divmensaje").html("<br><div class='alert alert-danger'>Seleccione su ubicación.</div>");
        return false;
    }
    
    if(serviciosid==null){
        $("#divmensaje").html("<br><div class='alert alert-danger'>Seleccione el(los) servicio(s) a ofrecer.</div>");
        return false;
    }
    
    
       waitingDialog(); 
       $.ajax({
          datatype:"json",
          type:"POST",
          headers:{'X-CSRF-TOKEN':$('input[name="_token"]').val()},
          data:{'nombreCompleto':nombreCompleto,'emailCuenta':emailCuenta,'companiaCuenta':companiaCuenta,'telefonoCuenta':telefonoCuenta,'passCuenta':passCuenta,
              'valorCaptcha':$("#g-recaptcha-response").val(),'idClasificacion':idClasificacion,'serviciosid':serviciosid,
              'latitudUbicacion':latitudUbicacion,'longitudUbicacion':longitudUbicacion,
          'paisCuenta':paisCuenta,'estadoCuenta':estadoCuenta,'ciudadCuenta':ciudadCuenta},
          url:"../crearcuenta/prestadorServicio",
          success:function(respuesta){
              setTimeout(function(){closeWaitingDialog();},100);
              if(respuesta.error=="1"){
                 $("#divmensaje").html("<br><div class='alert alert-danger'>La dirección email ingresada ya está en uso, favor de verificar.</div>");
                 grecaptcha.reset();
                 $("#divCaptcha,#cargaTipoCuenta,#divCrearCuentaCliente").attr("style","display:none");
                 $("#divGeneral").removeAttr("style");
             }else if(respuesta.error=="2"){
                 $("#divmensaje").html("<br><div class='alert alert-danger'>Indique que no es un robot.</div>");
                 grecaptcha.reset();
              }else{
                 $("#divPrincipal").hide();
                 //$("#labelRespuesta").html('Hemos enviado un correo de confirmación a tu bandeja, una vez válidado podrás ser parte de la comunidad Efletex')
                 $("#labelRespuesta").html('Su cuenta ha sido creada correctamente.');
                 $("#divRespuesta").show();
                 setTimeout(function(){location.href="/login";},10000);
              }
          }
      });
    
}

/*
 * Valida la estructura del correo
 * Autor: OT
 * Fecha 18-06-2016
 */
function validarEmail( email ) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) ){
        return 0;
    }else{
        return 1;
    }
}



/*
 * Muestra el formulario para crear al prestador de servicios
 * Autor: OT
 * Fecha 07-23-2016
 */
function masDatosPrestador() {
    $('#serviciosid').multiselect({
            maxHeight: 200,
            includeSelectAllOption: true,
            selectAllJustVisible: false
        });
    
    $("#divGeneral,#cargaTipoCuenta,#divCrearCuentaCliente").attr("style","display:none");
    $("#divDatosPrestador,#divCaptcha,#divCrearCuentaPrestador").removeAttr("style");
        
}






/*
 * Valida el pass de la cuenta
 * Autor: OT
 * Fecha: 01-07-2016
 * 
 */
function confirmarPass(){
    $("#divmensaje").html("");
    
    var pass1=$("#pass1").val();
    var pass2=$("#pass2").val();
    
    pass1=pass1.replace(/\s/g,"");
    pass2=pass2.replace(/\s/g,"");

    

    if(pass1==''){
      $("#divmensaje").html("<div class='alert alert-danger'>Escriba la contraseña.</div>");
      $("#pass1").focus();
      return false;
    }

    if(pass2==''){
      $("#divmensaje").html("<div class='alert alert-danger'>Escriba la confirmación de la contraseña.</div>");
      $("#pass2").focus();
      return false;
    }
    
    var longitudPass= pass1.length;
    
    if(parseFloat(longitudPass)<8){
      $("#divmensaje").html("<div class='alert alert-danger'>La contraseña debe tener al menos 8 caracteres sin espacios.</div>");
      $("#pass1").focus();
      return false;
    }
    
    var longitudPass= pass2.length;
    
    if(parseFloat(longitudPass)<8){
      $("#divmensaje").html("<div class='alert alert-danger'>La contraseña debe tener al menos 8 caracteres sin espacios.</div>");
      $("#pass2").focus();
      return false;
    }

    if(pass1!=pass2){
      $("#divmensaje").html("<div class='alert alert-danger'>Las contraseñas no coinciden.</div>");
      $("#pass2").focus();
      return false;
    }
    
    
    
    waitingDialog(); 
    return true;
}


  function enviarCorreoContrasena(){
      var correoVerificar=$("#correoVerificar").val();
      
      if(correoVerificar.replace(/\s/g,"")==''){
        $("#divmensaje").html("<div class='alert alert-danger'>Escriba el correo electrónico.</div>");
        $("#correoVerificar").focus();
        return false;
      }
      
      var esValido=validarEmail(correoVerificar);
      if(esValido==0){
          $("#divmensaje").html("<div class='alert alert-danger'>La dirección email ingresada es inválida.</div>");
          $("#correoVerificar").focus();
          return;
      }
      
      waitingDialog(); 
      $.ajax({
          type:"GET",
          url:"/crearcuenta/solicitarContrasena/"+correoVerificar,
          success:function(respuesta){
              setTimeout(function(){closeWaitingDialog();},100);
              if(respuesta=="ok"){
                  $("#formularioCaptura").html("<center><h4><b>Correo enviado correctamente.</b></h4><h5 class='text-muted'>Revise su bandeja de entrada.</h5><br><a href='/login/' class=''>Volver al inicio!</a></center>");
                  setTimeout(function(){location.href="/login";},10000);
              }else if(respuesta=="correonoencontrado"){
                  $("#divmensaje").html("<div class='alert alert-danger'>No existe una cuenta con el correo ingresado.</div>");
                  $("#correoVerificar").focus();
              }else{
                  $("#divmensaje").html("<div class='alert alert-danger'>Error al enviar el correo.</div>");
                  $("#correoVerificar").focus();
              }
          }
      });
    }


function validarNumero(e) {
    var key;
    if(window.event){ // IE
       key = e.keyCode;
    }
    else if(e.which){ // Netscape/Firefox/Opera
       key = e.which;
    }

    if (key < 48 || key > 57){
      if(key == 8){ // Detectar . (punto) y backspace (retroceso)
        return true; 
      }
        else { 
            return false; 
        }
    }
        
    return true;
}