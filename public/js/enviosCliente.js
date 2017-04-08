/* 
 * script para envios del cliente
 */

var mapaRecoger="",mapaEntregar="";
var geocoder,geocoder2;
var marcadorRecoger="";
var marcadorEntregar="";
var geocoder = new google.maps.Geocoder();

$(function() {
    $('#cal1').datepicker('setStartDate',new Date(new Date().getTime()-86400000));
    $('#cal2').datepicker({clearBtn: true});
    $('#cal1').datepicker().on('changeDate',function(e){
            $("#hora1,#hora2,#fechaHastaRecoger").removeAttr('disabled'); 
            $("#fechaRecoger").attr('readonly','readonly');

            var startDate=new Date(e.date.getTime());
            $("#cal2").datepicker('setStartDate',startDate);       
    });
    $('#cal2').datepicker().on('changeDate',function(e){
            $("#fechaHastaRecoger").attr('readonly','readonly');
            var startDate=new Date(e.date.getTime()-86400000);
            $("#cal1").datepicker('setEndDate',startDate);       
    });

    $('#cal3').datepicker('setStartDate',new Date(new Date().getTime()-86400000));
    $('#cal4').datepicker({clearBtn: true});
    $('#cal3').datepicker().on('changeDate',function(e){
            $("#hora3,#hora4,#fechaEntregarHasta").removeAttr('disabled'); 
            $("#fechaEntregar").attr('readonly','readonly');

            var startDate=new Date(e.date.getTime());
            $("#cal4").datepicker('setStartDate',startDate);       
    });
    $('#cal4').datepicker().on('changeDate',function(e){
            $("#fechaEntregarHasta").attr('readonly','readonly');
            var startDate=new Date(e.date.getTime()-86400000);
            $("#cal3").datepicker('setEndDate',startDate);       
    });
    $("#descuentoOferta").on('ifChanged',function(event){
        if($('#descuentoOferta').is(':checked')){
            $("#divDescuento").removeAttr("style");
            $("#porcentajeDescuento").focus();
        }else{
            $("#divDescuento").attr("style","display:none");
             $("#porcentajeDescuento").val("");
        }
    })
    
    $('#hora1').timepicker();
    $('#hora1').val("");
    $('#hora2').timepicker();
    $('#hora2').val("");
    $('#hora3').timepicker();
    $('#hora3').val("");
    $('#hora4').timepicker();
    $('#hora4').val("");

    $('#calExpiracion').datepicker();
    $('#horaExpiracion').timepicker();
    $('#gruposCliente').multiselect({
            maxHeight: 200
        });
     

     
    
 });
 
    $('#t1').on('shown.bs.tab', function (e) {
      $("#liTab11,#liTab1,#liTab2,#liTab3,#liTab4,#liTab5").attr("style","display:none");
      $("#liTab1,#liTab21").removeAttr("style");
    });
 
    $('#t2').on('shown.bs.tab', function (e) {
        
        $("#liTab1,#liTab21,#liTab3,#liTab4,#liTab5").attr("style","display:none");
        $("#liTab11,#liTab2,#liTab31").removeAttr("style");
        
        
        if(mapaRecoger==""){
           mapaRecoger = new google.maps.Map(document.getElementById("mapaOrigen"),
           {
             center: {lat: -12.0553419, lng: -77.0802054},
             zoom: 7
           });
        } 
        
    });

    $('#t3').on('shown.bs.tab', function (e) {
        $("#liTab1,#liTab2,#liTab31,#liTab4,#liTab5").attr("style","display:none");
        $("#liTab21,#liTab3,#liTab41,#liTab51").removeAttr("style");

        if(mapaEntregar==""){
            mapaEntregar = new google.maps.Map(document.getElementById("mapaDestino"),
            {
              center: {lat: -12.0553419, lng: -77.0802054},
              zoom: 7
            });
        }
    });
    
    $('#t4').on('shown.bs.tab', function (e) {
      $("#liTab31,#liTab4,#liTab5").removeAttr("style");
      $("#liTab1,#liTab2,#liTab3,#liTab41,#liTab51").attr("style","display:none");
    });
    
    $('#t5').on('shown.bs.tab', function (e) {
      $("#liTab1,#liTab2,#liTab3,#liTab4,#liTab51").attr("style","display:none");
        $("#liTab41").removeAttr("style");
    });
 


 
   

    /*
    * Obtiene la direccion del punto recibido
    * Autor: OT
    * Fecha: 01-06-2016
    */
    function detalleDireccion(latlng,tipo){
        var latitud = latlng.lat();
        var longitud = latlng.lng();
        
        var cadenaOrigen1="";
        var cadenaDestino2="";
        
        if(tipo==1){
           $("#dirOrigen").val("");
           $("#dirOrigen2").val("");
           $("#latitudOrigen").val("");
           $("#longitudOrigen").val("");
           cadenaOrigen1="";
        
        }else if(tipo==2){
           $("#dirDestino").val("");
           $("#dirDestino2").val("");
           $("#latitudDestino").val("");
           $("#longitudDestino").val("");
           cadenaDestino2="";
        }
        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
               if (results[1]) {
                   var addressComponent = results[0].address_components;
                    if(tipo==1){
                        $("#dirOrigen").val(results[0].formatted_address);
                        $("#latitudOrigen").val(latitud);
                        $("#longitudOrigen").val(longitud);
                        
                    }else if(tipo==2){
                        $("#dirDestino").val(results[0].formatted_address);
                        $("#latitudDestino").val(latitud);
                        $("#longitudDestino").val(longitud);
                    }
                    
                    var aCompareAdress = {
                            'locality' : 'municipio',
                            'administrative_area_level_1':'estado',
                            'administrative_area_level_2' : 'estado2',
                            'country' : 'pais'
                    };
                    for(var iAddress in addressComponent){
                            var type = aCompareAdress[addressComponent[iAddress].types[0]];
                            if(type != null){
                                if(tipo==1){
                                    if(type=="municipio") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                                    if(type=="estado") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                                    if(type=="estado2") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].short_name.toUpperCase()+",";
                                    if(type=="pais") cadenaOrigen1=cadenaOrigen1+addressComponent[iAddress].long_name.toUpperCase()+",";

                                }else if(tipo==2){
                                    if(type=="municipio") cadenaDestino2=cadenaDestino2+addressComponent[iAddress].short_name.toUpperCase()+",";
                                    if(type=="estado") cadenaDestino2=cadenaDestino2+addressComponent[iAddress].short_name.toUpperCase()+",";
                                    if(type=="estado2") cadenaDestino2=cadenaDestino2+addressComponent[iAddress].short_name.toUpperCase()+",";
                                    if(type=="pais") cadenaDestino2=cadenaDestino2+addressComponent[iAddress].long_name.toUpperCase()+",";
                                }
                            }
                    }
                    if(tipo==1){
                        $("#dirOrigen2").val(cadenaOrigen1);
                        geocoder.geocode( { 'address': cadenaOrigen1}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                               $("#puntoOrigenRef").val(results[0].geometry.location);
                            } 
                        });
                    }
                    if(tipo==2){
                        $("#dirDestino2").val(cadenaDestino2);
                        geocoder.geocode( { 'address': cadenaDestino2}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                               $("#puntoDestinoRef").val(results[0].geometry.location);
                            } 
                        });
                    }
                    
               }
            }
         });
         
    }
        
    
    function buscarDireccion(id) {
        var dir="";
        if(id==1){ // si es mapa Recoger
            if($("#idPaisOrigen").val()=="0"){
                swal("","Seleccione un país.","warning");
                return;
            }
            
            if($("#idEstadoOrigen").val()=="0"){
                swal("","Seleccione un departamento.","warning");
                return;
            }
            
            var ciudad=$("#ciudadOrigen").val();
            if(ciudad.replace(/\s/g,"")==""){
                swal("","Ingrese el nombre de la ciudad.","warning");
                return;
            }

            var direccion=$("#direccionOrigen").val();
            if(direccion.replace(/\s/g,"")==""){
                swal("","Ingrese la Calle o Lugar","warning");
                return;
            }
           if(marcadorRecoger!="")marcadorRecoger.setMap(null);

            dir= direccion+ciudad+","+$("#idEstadoOrigen :selected").text()+","+$("#idPaisOrigen :selected").text();
            geocoder.geocode( { 'address': dir}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                       mapaRecoger.setCenter(results[0].geometry.location);
                       mapaRecoger.setZoom(15);
                       marcadorRecoger = new google.maps.Marker({
                           map: mapaRecoger,
                           position: results[0].geometry.location,
                           draggable: true,
                       });
                       
                       google.maps.event.addListener(marcadorRecoger, 'dragend', function(event) {
                            detalleDireccion(event.latLng,1);
                        });
                       detalleDireccion(results[0].geometry.location,1);
                    } else {
                       swal("","No se obtuvieron resultados, verifique los datos.","warning");
                    }
                });
            
            
        }else{
            if($("#idPaisDestino").val()=="0"){
                swal("","Seleccione un país.","warning");
                return;
            }
            
            if($("#idEstadoDestino").val()=="0"){
                swal("","Seleccione un departamento.","warning");
                return;
            }
            
            var ciudad=$("#ciudadDestino").val();
            if(ciudad.replace(/\s/g,"")==""){
                swal("","Ingrese el nombre de la ciudad.","warning");
                return;
            }

            var direccion=$("#direccionDestino").val();
            if(direccion.replace(/\s/g,"")==""){
                swal("","Ingrese la Calle o Lugar","warning");
                return;
            }

           if(marcadorEntregar!="")marcadorEntregar.setMap(null);

            dir= direccion+ciudad+","+$("#idEstadoDestino :selected").text()+","+$("#idPaisDestino :selected").text();
            geocoder.geocode( { 'address': dir}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                       mapaEntregar.setCenter(results[0].geometry.location);
                       mapaEntregar.setZoom(15);
                       marcadorEntregar = new google.maps.Marker({
                           map: mapaEntregar,
                           position: results[0].geometry.location,
                           draggable: true,
                       });
                       
                       google.maps.event.addListener(marcadorEntregar, 'dragend', function(event) {
                            detalleDireccion(event.latLng,2);
                        });
                       detalleDireccion(results[0].geometry.location,2);
                    } else {
                       swal("","No se obtuvieron resultados, verifique los datos.","warning");
                    }
                });
            
        }
      
    }

   /*
   * Muestra la ventana para agregar un articulo
   * Auto: OT
   * Fecha : 31-05-2016
   */  
    function agregarArticulo(){
        
        $("#dialogoArticulos").dialog({
		autoOpen: false,
		title: "Agregar artículo",
		modal:true,
		width: 900,
		height: 550,
		close: function(event,ui){
                    $("#dialogoArticulos").html('');
                    $("#dialogoArticulos").dialog('destroy');
		},
		open:function(event,ui){
                    $.ajax({
                        type:"GET",
			url:"/cliente/agregarArticuloEnvio",
			success:function(respuesta){
                            $("#dialogoArticulos").html(respuesta);
                        }
                    });

		}
	});
	$("#dialogoArticulos").dialog('open');
    }
    
  /*
   * Agrega un articulo al envio del cliente
   * Auto: OT
   * Fecha : 31-05-2016
   */  
 function agregarArticuloEnvio(){
     
     waitingDialog(); 
     
     var tipoArticulo= $("#tipoArticulo").val();
     var textoAr=$("#tipoArticulo option:selected" ).text();
     var largo= $("#largo").val();
     var alto= $("#alto").val();
     var ancho= $("#ancho").val();
     var unidadDimension= $("#unidadDimension").val();
     var peso= $("#peso").val();
     var unidadPeso= $("#unidadPeso").val();
     var cantidad= $("#cantidad").val();
     var detalle=$("#articuloDetalle").val();
     var cadenaDimesion=largo + " X " + alto +  " X " + ancho + " (" + unidadDimension+")";
     var cadenaPesoUnitario= peso + " (" + unidadPeso + ")";
     var imagen1="";
     var imagen2="";
     var apilable="N";
     var peligroso="N";
     var perecedero="N";
     
     
     if ($("#apilable").is(":checked")) {
         apilable="Y";
     }
     
     if ($("#peligroso").is(":checked")) {
         peligroso="Y";
     }
     
     if ($("#perecedero").is(":checked")) {
         perecedero="Y";
     }
      
     $("#divImagen1 img").each(function(key, element){ 
        imagen1=$(element).attr('src');
     }); 
        
     $("#divImagen2 img").each(function(key, element){ 
         imagen2=$(element).attr('src');
     }); 
      
     var imagenMostrar=imagen1;
      
     if(imagenMostrar=="")imagenMostrar=imagen2;
          
     if(tipoArticulo=="0"){
        setTimeout(function(){closeWaitingDialog();},100);
        swal("","Indique la unidad de menejo","warning");
        return;
     }
      
     if(largo=="" ||  !/^([0-9])*.([0-9])*$/.test(largo) || !parseFloat(largo) || parseFloat(largo)<0  || isNaN(largo)){
        setTimeout(function(){closeWaitingDialog();},100);
        swal("","El Largo no es válido.","warning");
        return;
     }
       
     if(alto=="" ||  !/^([0-9])*.([0-9])*$/.test(alto) || !parseFloat(alto) || parseFloat(alto)<0 || isNaN(alto)){
        setTimeout(function(){closeWaitingDialog();},100);
        swal("","El Alto no es válido.","warning");
        return;
     }
       
     if(ancho=="" ||  !/^([0-9])*.([0-9])*$/.test(ancho) || !parseFloat(ancho) || parseFloat(ancho)<0 || isNaN(ancho)){
        setTimeout(function(){closeWaitingDialog();},100);
        swal("","El Ancho no es válido.","warning");
        return;
     }
       
     if(peso=="" ||  !/^([0-9])*.([0-9])*$/.test(peso) || !parseFloat(peso) || parseFloat(peso)<0 || isNaN(peso)){
       setTimeout(function(){closeWaitingDialog();},100);
       swal("","El Peso no es válido.","warning");
       return;
     }
       
     if(cantidad=="" ||  !/^([0-9])*.([0-9])*$/.test(cantidad) || !parseFloat(cantidad) || parseFloat(cantidad)<0 || isNaN(cantidad)){
        setTimeout(function(){closeWaitingDialog();},100);
        swal("","La Cantidad no es válida.","warning");
        return;
     }

      var mran=Math.floor((Math.random() * 10000) + 1);
      var pesoTotalArticulo = parseFloat(cantidad) * parseFloat(peso);
      pesoTotalArticulo = pesoTotalArticulo.toFixed(2);
      
      
        var cadena = "<tr>";
        cadena = cadena + "<td><img id='imgArticulo"+mran+"' name='imgArticulo' src='"+imagenMostrar+"' onclick='mostrarImagenGeneral(this.src);' style='cursor: pointer;' height='50' width='50'></td>";
        cadena = cadena + "<td><p id='nArticulo"+mran+"' name='nArticulo'>" + textoAr + "</p></td>";
        cadena = cadena + "<td><p id='cDimension"+mran+"' name='cDimension'>" + cadenaDimesion + "</p></td>";
        cadena = cadena + "<td><p id='cPeso"+mran+"' name='cPeso'>" + cadenaPesoUnitario + "</p></td>";
        cadena = cadena + "<td><p id='cCAntidad"+mran+"' name='cCAntidad'>" + cantidad + "</p></td>";
        cadena = cadena + "<td><p id='cPesoTotal"+mran+"' name='cPesoTotal'>" + pesoTotalArticulo + "</p></td>";
        cadena = cadena + "<td><a href='#' title='Editar artículo' onclick='editarArticulo("+mran+");'><img src='/img/icono_editar.png' width='25px;'></a>&nbsp;&nbsp;<a href='javascript:;' class='elimina' title='Eliminar artículo' onclick='deleteRow(this)'><img src='/img/icono_eliminar.png' width='25px;'></i></a>\n\
                            <input id='idLinea"+mran+"' name='idLinea' type='hidden' value="+mran+">\n\
                            <input id='tipoArticulo"+mran+"' name='tipoArticulo' type='hidden' value='"+tipoArticulo+"'>\n\
                            <input id='largo"+mran+"' name='largo' type='hidden' value="+largo+">\n\
                            <input id='alto"+mran+"' name='alto' type='hidden' value="+alto+">\n\
                            <input id='ancho"+mran+"' name='ancho' type='hidden' value="+ancho+">\n\
                            <input id='unidadDimension"+mran+"' name='unidadDimension' type='hidden' value='"+unidadDimension+"'>\n\
                            <input id='peso"+mran+"' name='peso' type='hidden' value="+peso+">\n\
                            <input id='unidadPeso"+mran+"' name='unidadPeso' type='hidden' value='"+unidadPeso+"'>\n\
                            <input id='cantidad"+mran+"' name='cantidad' type='hidden' value="+cantidad+">\n\
                            <input id='detalle"+mran+"' name='detalle' type='hidden' value='"+detalle+"'>\n\
                            <input id='imagen1"+mran+"' name='imagen1' type='hidden' value='"+imagen1+"'>\n\
                            <input id='imagen2"+mran+"' name='imagen2' type='hidden' value='"+imagen2+"'>\n\
                            <input id='apilable"+mran+"' name='apilable' type='hidden' value='"+apilable+"'>\n\
                            <input id='peligroso"+mran+"' name='peligroso' type='hidden' value='"+peligroso+"'>\n\
                            <input id='perecedero"+mran+"' name='perecedero' type='hidden' value='"+perecedero+"'>\n\
                        </td></tr>";
        $("#listaProductos tbody").append(cadena);
        setTimeout(function(){closeWaitingDialog();},100);
        $('#dialogoArticulos').dialog('close');
    }
    
    /*
     * Busca una imagen dentro del div para mostralo
     * Autor: OT
     * Fecha 17-07-2016
     * 
     */
    function buscaImagenDiv(idImagen){
        var imagen="";
      if(idImagen==1){
            var imagen="";
            $("#divImagen1 img").each(function(key, element){ 
               imagen=$(element).attr('src');
            }); 
            if(imagen!=""){
                mostrarImagenGeneral(imagen);
            }  
      }else{
          
            $("#divImagen2 img").each(function(key, element){ 
               imagen=$(element).attr('src');
            }); 
            if(imagen!=""){
                mostrarImagenGeneral(imagen);
            }
      }
        
    }
    
    
    /* 
     * Elimina el articulo seleccionado del envio
     * Autor: OT
     * Fecha: 31-05-2016
     */
    
    function deleteRow(r) {
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById("listaProductos").deleteRow(i);
    }
    
    
    /* 
     * Muestra el formulario de edición del articulo
     * Autor: OT
     * Fecha: 31-05-2016
     */
    
    function editarArticulo(idArticulo){
        var articulo=$("#tipoArticulo"+idArticulo).val();
        var largo=$("#largo"+idArticulo).val();
        var alto=$("#alto"+idArticulo).val();
        var ancho=$("#ancho"+idArticulo).val();
        var unidadDimension=$("#unidadDimension"+idArticulo).val();
        var peso=$("#peso"+idArticulo).val();
        var unidadPeso=$("#unidadPeso"+idArticulo).val();
        var cantidad=$("#cantidad"+idArticulo).val();
        var detalle=$("#detalle"+idArticulo).val();
        var imagen1=$("#imagen1"+idArticulo).val();
        var imagen2=$("#imagen2"+idArticulo).val();
        var apilable=$("#apilable"+idArticulo).val();
        var peligroso=$("#peligroso"+idArticulo).val();
        var perecedero=$("#perecedero"+idArticulo).val();
        var token=$("#token").val();
        var existeImagen1=0;
        var existeImagen2=0;
        if(imagen1!="")existeImagen1=1;
        if(imagen2!="")existeImagen2=1;
        
        $("#dialogoArticulos").dialog({
		autoOpen: false,
		title: "Editar artículo",
		modal:true,
		width: 900,
		height: 550,
		close: function(event,ui){
                    $("#dialogoArticulos").html('');
                    $("#dialogoArticulos").dialog('destroy');
		},
		open:function(event,ui){
                    waitingDialog();
                    $.ajax({
                        type:"POST",
                        headers:{'X-CSRF-TOKEN':token},
                        data:{'existeImagen1':existeImagen1,'existeImagen2':existeImagen2,'idArticulo':idArticulo},
			url:"/cliente/edtiarArticuloEnvio",
			success:function(respuesta){
                            $("#dialogoArticulos").html(respuesta);
                            
                            $("#tipoArticulo").val(articulo);
                            $("#unidadDimension").val(unidadDimension);
                            $("#unidadPeso").val(unidadPeso);
                            $("#largo").val(largo);
                            $("#alto").val(alto);
                            $("#ancho").val(ancho);
                            $("#peso").val(peso);
                            $("#cantidad").val(cantidad);
                            $("#articuloDetalle").val(detalle);
                            
                            if(apilable=="Y"){
                                $('#apilable').iCheck('check');
                            }
                            
                            if(peligroso=="Y"){
                                $('#peligroso').iCheck('check');
                            }

                            if(perecedero=="Y"){
                                $('#perecedero').iCheck('check');
                            }
                            
                            if(existeImagen1==1){
                                $("#i1").attr('src',imagen1);
                            }
                            
                            if(existeImagen2==1){
                                $("#i2").attr('src',imagen2);
                            }
                                                            
                            setTimeout(function(){closeWaitingDialog();},100);
                        }
                    });

		}
	});
	$("#dialogoArticulos").dialog('open');
    }
    
    /* 
     * Actualiza los datos del articulo en el envio
     * Autor: OT
     * Fecha: 31-05-2016
     */
    function actualizarArticuloEnvio(idArticulo){
        waitingDialog();
        var tipoArticulo= $("#tipoArticulo").val();
        var textoAr=$("#tipoArticulo option:selected" ).text();
        var largo= $("#largo").val();
        var alto= $("#alto").val();
        var ancho= $("#ancho").val();
        var unidadDimension= $("#unidadDimension").val();
        var peso= $("#peso").val();
        var unidadPeso= $("#unidadPeso").val();
        var cantidad= $("#cantidad").val();
        var detalle=$("#articuloDetalle").val();
        var cadenaDimesion=largo + " X " + alto +  " X " + ancho + " (" + unidadDimension+")";
        var cadenaPesoUnitario= peso + " (" + unidadPeso + ")";
        var imagen1="";
        var imagen2="";
        var apilable="N";
        var peligroso="N";
        var perecedero="N";


        if ($("#apilable").is(":checked")) {
            apilable="Y";
        }

        if ($("#peligroso").is(":checked")) {
            peligroso="Y";
        }
        
        if ($("#perecedero").is(":checked")) {
            perecedero="Y";
        }

        $("#divImagen1 img").each(function(key, element){ 
           imagen1=$(element).attr('src');
        }); 

        $("#divImagen2 img").each(function(key, element){ 
            imagen2=$(element).attr('src');
        }); 
        
        
        if(imagen1==""){
            imagen1=$("#i1").attr('src');
        }
        
        if(imagen2==""){
            imagen2=$("#i2").attr('src');
        }
        
        if(imagen1==undefined)imagen1="";
        if(imagen2==undefined)imagen2="";
        
        var imagenMostrar=imagen1;
        if(imagenMostrar=="")imagenMostrar=imagen2;

        if(tipoArticulo=="0"){
           setTimeout(function(){closeWaitingDialog();},100);
           swal("","Indique la unidad de menejo","warning");
           return;
        }

        if(largo=="" ||  !/^([0-9])*.([0-9])*$/.test(largo) || !parseFloat(largo) || parseFloat(largo)<0 || isNaN(largo)){
           setTimeout(function(){closeWaitingDialog();},100);
           swal("","El Largo no es válido.","warning");
           return;
        }

        if(alto=="" ||  !/^([0-9])*.([0-9])*$/.test(alto) || !parseFloat(alto) || parseFloat(alto)<0 ||  isNaN(alto)){
           setTimeout(function(){closeWaitingDialog();},100);
           swal("","El Alto no es válido.","warning");
           return;
        }

        if(ancho=="" ||  !/^([0-9])*.([0-9])*$/.test(ancho) || !parseFloat(ancho) || parseFloat(ancho)<0 || isNaN(ancho)){
           setTimeout(function(){closeWaitingDialog();},100);
           swal("","El Ancho no es válido.","warning");
           return;
        }

        if(peso=="" ||  !/^([0-9])*.([0-9])*$/.test(peso) || !parseFloat(peso) || parseFloat(peso)<0 || isNaN(peso)){
          setTimeout(function(){closeWaitingDialog();},100);
          swal("","El Peso no es válido.","warning");
          return;
        }

        if(cantidad=="" ||  !/^([0-9])*.([0-9])*$/.test(cantidad) || !parseFloat(cantidad) || parseFloat(cantidad)<0 || isNaN(cantidad)){
           setTimeout(function(){closeWaitingDialog();},100);
           swal("","La cantidad no es válida.","warning");
           return;
        }

         var pesoTotalArticulo = parseFloat(cantidad) * parseFloat(peso);
         pesoTotalArticulo = pesoTotalArticulo.toFixed(2);
         
         $("#imgArticulo"+idArticulo).attr("src",imagenMostrar);
         $("#nArticulo"+idArticulo).html(textoAr);
         $("#cDimension"+idArticulo).html(cadenaDimesion);
         $("#cPeso"+idArticulo).html(cadenaPesoUnitario);
         $("#cCAntidad"+idArticulo).html(cantidad);
         $("#cPesoTotal"+idArticulo).html(pesoTotalArticulo);
         $("#tipoArticulo"+idArticulo).val(tipoArticulo);
         $("#largo"+idArticulo).val(largo);
         $("#alto"+idArticulo).val(alto);
         $("#ancho"+idArticulo).val(ancho);
         $("#unidadDimension"+idArticulo).val(unidadDimension);
         $("#peso"+idArticulo).val(peso);
         $("#unidadPeso"+idArticulo).val(unidadPeso);
         $("#cantidad"+idArticulo).val(cantidad);
         $("#detalle"+idArticulo).val(detalle);
         $("#imagen1"+idArticulo).val(imagen1);
         $("#imagen2"+idArticulo).val(imagen2);
         $("#apilable"+idArticulo).val(apilable);
         $("#peligroso"+idArticulo).val(peligroso);
         $("#perecedero"+idArticulo).val(perecedero);
         
         setTimeout(function(){closeWaitingDialog();},100);
         $("#dialogoArticulos").dialog('close');
	
    }
    
    /*
     * Quita la imagen del div de edicion de articulo
     * Autor: OT
     * Fecha: 07-06-2016
     */
    
    function quitarImagen(id){
        if(id=="1")$("#i1").attr('src','');
        else $("#i2").attr('src','');
    }
    
    /*
     * Guarda el envio del cliente
     * Autor: OT
     * Fecha:31-05-2016
     */
    
    function guardarEnvioCliente(){
        var hoy=new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1;
        if(mm<10)mm="0"+mm;
        var yyyy = hoy.getFullYear();
        hoy=dd+"-"+mm+"-"+yyyy;

        var horahoy=new Date();
        var hh=horahoy.getHours();
        var mm=horahoy.getMinutes();
        var ss=horahoy.getSeconds();

        var hora = hh+":"+mm+":"+ss;
        
        
       var idLinea = document.getElementsByName("idLinea");
        var token=$("#token").val();
        if(idLinea.length==0){
            swal("","Agregue al menos un artículo en el envío.","warning");
            $("#t1").click();
           return;
        }
        var articulos=[];
        
        for (var i = 0; i < idLinea.length; i++) {
            articulos[i]=[];
            var idArticulo=(idLinea[i].value).replace(/\s/g,"");
            var idUnidad=$("#tipoArticulo"+idArticulo).val();
            var largo=$("#largo"+idArticulo).val();
            var alto=$("#alto"+idArticulo).val();
            var ancho=$("#ancho"+idArticulo).val();
            var unidadDimension=$("#unidadDimension"+idArticulo).val();
            var peso=$("#peso"+idArticulo).val();
            var unidadPeso=$("#unidadPeso"+idArticulo).val();
            var cantidad=$("#cantidad"+idArticulo).val();
            var detalle=$("#detalle"+idArticulo).val();
            var imagen1=$("#imagen1"+idArticulo).val();
            var imagen2=$("#imagen2"+idArticulo).val();
            var apilable=$("#apilable"+idArticulo).val();
            var peligroso=$("#peligroso"+idArticulo).val();
            var perecedero=$("#perecedero"+idArticulo).val();
            
            if(apilable=="Y")apilable=true;else apilable=false;
            if(peligroso=="Y")peligroso=true;else peligroso=false;
            if(perecedero=="Y")perecedero=true;else perecedero=false;
            
            articulos[i][0]=idUnidad;
            articulos[i][1]=largo;
            articulos[i][2]=alto;
            articulos[i][3]=ancho;
            articulos[i][4]=unidadDimension;
            articulos[i][5]=peso;
            articulos[i][6]=unidadPeso;
            articulos[i][7]=cantidad;
            articulos[i][8]=detalle;
            articulos[i][9]=imagen1;
            articulos[i][10]=imagen2;
            articulos[i][11]=apilable;
            articulos[i][12]=peligroso;
            articulos[i][13]=perecedero;
        }
        
        if($("#latitudOrigen").val()==""){
           swal("","Ubique en el mapa el punto donde se recogera el envío.","warning");
           $("#t2").click();
           return;
        }
        
        var idPaisOrigen=$("#idPaisOrigen").val();
        var idEstadoOrigen=$("#idEstadoOrigen").val();
        var ciudadOrigen=$("#ciudadOrigen").val();
        var direccionOrigen=$("#direccionOrigen").val();
        var horaRecoger=$("#hora1").val();
        var horaRecogerHasta=$("#hora2").val();
        
        var fechaRecoger=$("#fechaRecoger").val();
        var fechaHastaRecoger=$("#fechaHastaRecoger").val();
        var contactoOrigen=$("#contactoOrigen").val();
        var telefonoOrigen=$("#telefonoOrigen").val();
        var idLugarOrigen=$("#idLugarOrigen").val();
        
        var elevadorRecoger=false;
        var servicioRecogerInterior=false;
        
        if($('#elevadorRecoger').is(':checked')){
            elevadorRecoger=true;
        }
        
        if($('#servicioRecogerInterior').is(':checked')){
            servicioRecogerInterior=true;
        }
        
        if(idPaisOrigen=="0"){
            swal("","Seleccione un país.","warning");
            $("#t2").click();
            return;
        }
            
        if(idEstadoOrigen=="0"){
            swal("","Seleccione un departamento.","warning");
            $("#t2").click();
            return;
       }
            
       if(ciudadOrigen.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre de la ciudad.","warning");
           $("#t2").click();          
           return;
       }
       
       if(direccionOrigen.replace(/\s/g,"")==""){
          swal("","Ingrese la calle.","warning");
          $("#t2").click();          
          return;
       }
       
      
        if(fechaRecoger==""){
           swal("","Indique la fecha para recoger el envío.","warning");
           $("#t2").click();
           return;
        }
         if(tipoFecha(fechaRecoger) < tipoFecha(hoy)){
            swal("","La fecha 'Recoger el..' debe ser mayor o igual a la fecha actual.","warning");
            $("#t2").click();
           return;
        }
        
        if(fechaHastaRecoger!=""){
            if(tipoFecha(fechaRecoger)>tipoFecha(fechaHastaRecoger)){
                swal("","Verifique que las fechas para recoger el envío sean correctas.","warning");
                $("#t2").click();
                return;
            }
        }
        
        
        if(contactoOrigen.replace(/\s/g,"")==''){
           swal("","Escriba el nombre del contacto en el lugar de recolección.","warning");
           $("#t2").click();
           return;
       }
       
       if(telefonoOrigen.replace(/\s/g,"")==''){
           swal("","Escriba el telefono del contacto en el lugar de recolección.","warning");
           $("#t2").click();
           return;
       }
            
        if(idLugarOrigen=="0"){
           swal("","Seleccione el lugar donde se recogerá el envío.","warning");
           $("#t2").click();
           return;
        }
        
        
        if($("#latitudDestino").val()==""){
           swal("","Ubique en el mapa el punto de entrega.","warning");
           $("#t3").click();
           return;
        }
        
        var idPaisDestino=$("#idPaisDestino").val();
        var idEstadoDestino=$("#idEstadoDestino").val();
        var ciudadDestino=$("#ciudadDestino").val();
        var direccionDestino=$("#direccionDestino").val();
        var fechaEntregar=$("#fechaEntregar").val()
        var fechaEntregarHasta=$("#fechaEntregarHasta").val();
        var horaEntregar=$("#hora3").val();
        var horaEntregarHasta=$("#hora4").val();
        var contactoDestino=$("#contactoDestino").val();
        var telefonoDestino=$("#telefonoDestino").val();
        var idLugarDestino=$("#idLugarDestino").val();
        var llamarEntrega=false;
        var horarioEntrega=false;
        var elevadorEntrega=false;
        var servicioEntregarInterior=false;

        
        if($('#llamarEntrega').is(':checked')){
            llamarEntrega=true;
        }
        
        if($('#elevadorEntrega').is(':checked')){
            elevadorEntrega=true;
        }
        
        if($('#servicioEntregarInterior').is(':checked')){
            servicioEntregarInterior=true;
        }
        
        if(idPaisDestino=="0"){
            swal("","Seleccione un país.","warning");
            $("#t3").click();
            return;
        }
            
        if(idEstadoDestino=="0"){
            swal("","Seleccione un departamento.","warning");
            $("#t3").click();
            return;
       }
            
       if(ciudadDestino.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre de la ciudad.","warning");
           $("#t3").click();          
           return;
       }
       
       if(direccionDestino.replace(/\s/g,"")==""){
          swal("","Ingrese la calle.","warning");
          $("#t3").click();          
          return;
       }
        
        
        if($("#fechaEntregar").val()==""){
           swal("","Indique la fecha a entregar el envío.","warning");
           $("#t3").click();
           return;
        }
        
        if(tipoFecha(fechaEntregar)<tipoFecha(hoy)){
            swal("","La fecha 'Entregar el..' debe ser mayor o igual a la fecha actual.","warning");
            $("#t3").click();
           return;
        }
        
        
        if(fechaHastaRecoger!=""){
            if(tipoFecha(fechaEntregar)<tipoFecha(fechaHastaRecoger)){
                swal("","La fecha de entrega debe ser mayor a la fecha de recolección.","warning");
                $("#t3").click();
                return;
            }
        }else{
            if(tipoFecha(fechaEntregar)<tipoFecha(fechaRecoger)){
                swal("","La fecha de entrega debe ser mayor a la fecha de recolección.","warning");
                $("#t3").click();
                return;
            }
        }
        
        if(fechaEntregarHasta!=""){
            if(tipoFecha(fechaEntregar)>tipoFecha(fechaEntregarHasta)){
                swal("","Verifique que las fechas de entrega sean correctas.","warning");
                $("#t3").click();
                return;
            }
        }
        
        if(contactoDestino.replace(/\s/g,"")==''){
           swal("","Escriba el nombre del contacto en el lugar de entrega.","warning");
           $("#t3").click();
           return;
        }
       
       if(telefonoDestino.replace(/\s/g,"")==''){
           swal("","Escriba el telefono del contacto en el lugar de entrega.","warning");
           $("#t3").click();
           return;
       }
        
        
        if(idLugarDestino=="0"){
           swal("","Seleccione el lugar donde se recogerá el envío.","warning");
           $("#t3").click();
           return;
        }
        
        var tituloEnvio=$("#tituloEnvio").val();
        var tipoCosto=$("#tipoCosto").val();
        var costoEnvioM=$("#costoEnvioM").val();
        var porcentajeDescuento=$("#porcentajeDescuento").val();
        var servicioFrio=false;
        var descuentoOferta=false;
        var servicioClasificar=false;
        var servicioCoordinar=false;
        var fechaExpiracion=$("#fechaExpiracion").val();
        var horaExpiracion=$("#horaExpiracion").val();
        
        if(tituloEnvio.replace(/\s/g,"")==''){
           setTimeout(function(){closeWaitingDialog();},100);
           swal("","Escriba el título del envio.","warning");
           return;
        }

        
        if($('#servicioFrio').is(':checked')){
            servicioFrio=true;
        }
        
        if($('#servicioClasificar').is(':checked')){
            servicioClasificar=true;
        }
        
        if($('#servicioCoordinar').is(':checked')){
            servicioCoordinar=true;
        }
        
        
        if(tipoCosto=="0"){
           swal("","Seleccione el tipo de costo.","warning");
           return;
        }
        
        if(tipoCosto=="1"){ // si el costo es fijo
           if(costoEnvioM=="" ||  !/^([0-9])*.([0-9])*$/.test(costoEnvioM)  || parseFloat(costoEnvioM)<0 || isNaN(costoEnvioM)){
                swal("","El costo de envío no es válido.","warning");
                return;
            }
            
            if($('#descuentoOferta').is(':checked')){
                descuentoOferta=true;
                if(porcentajeDescuento=="" ||  !/^([0-9])*.([0-9])*$/.test(porcentajeDescuento)  || parseFloat(porcentajeDescuento)<0 || isNaN(porcentajeDescuento)){
                    swal("","El porcentaje de descuento no es válido.","warning");
                    return;
                }
                
                if(parseFloat(porcentajeDescuento)<3){
                    swal("","El porcentaje de descuento no debe ser menor al 3%.","warning");
                    return;
                }
                
                if(parseFloat(porcentajeDescuento)>=100){
                    swal("","El porcentaje de descuento debe ser menor al 100%.","warning");
                    return;
                }
            }
        }

        
        if(fechaExpiracion==""){
            swal("","Debe seleccionar una Fecha de Expiración");
            return;
        }

        if(horaExpiracion==""){
            swal("","Debe seleccionar una Hora de Expiración");
            return;
        }


        var hoy=new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1;
        if( dd<10)dd="0"+dd;
        if(mm<10)mm="0"+mm;
        var yyyy = hoy.getFullYear();
        var hh = hoy.getHours();
        var ii = hoy.getMinutes();
        if(hh<10)hh='0'+hh;
        if(ii<10)ii='0'+ii;
        horahoy= hh+':'+ii+':'+'00';
        hoy=dd+"-"+mm+"-"+yyyy;

        act=hoy.split("-");
        actH=act[2]+'-'+act[1]+'-'+act[0];
        actual=actH+' '+horahoy;
        horaex=parseHora(horaExpiracion);
        ex=fechaExpiracion.split("-");
        ex=ex[2]+'-'+ex[1]+'-'+ex[0];
        exp=ex+' '+horaex;

        if(actual>exp){
            swal("","La hora de expiración debe ser mayor a "+horahoy);
            return;
        }
        
        if(fechaRecoger!=hoy){
            fechaR=fechaRecoger.split("-");
            fechaR=fechaR[2]+'-'+fechaR[1]+'-'+fechaR[0];
                
            if(horaRecoger!=""){
                
                horaR=parseHora(horaRecoger);
                fechaR=fechaR+' '+horaR;
                if(exp>fechaR){
                    swal("","La fecha de expiración debe ser menor a "+fechaR);
                    return;
                }
            }
            else{
                if(ex>fechaR){
                    swal("","La fecha de expiración debe ser menor a "+fechaR);
                    return;
                }
            }
        }else{
            horaR=parseHora(horaRecoger);
            if(horaex>horaR){
                swal("","La hora de expiración debe ser menor a "+horaR);
                return;
            }
        }
        

        var latitudOrigen=$("#latitudOrigen").val();
        var longitudOrigen=$("#longitudOrigen").val();
        var dirOrigen=$("#dirOrigen").val();
        var dirOrigen2=$("#dirOrigen2").val();
        var puntoOrigenRef=$("#puntoOrigenRef").val();

        
        var latitudDestino=$("#latitudDestino").val();
        var longitudDestino=$("#longitudDestino").val();
        var dirDestino=$("#dirDestino").val();
        var dirDestino2=$("#dirDestino2").val();
        var puntoDestinoRef=$("#puntoDestinoRef").val();
        
        var otroLugarRecoger=$("#otroLugarRecoger").val();
        var otroLugarEntregar=$("#otroLugarEntregar").val();
        
        var tipoEnvio=$("#tipoEnvio").val();
        var gruposCliente=$("#gruposCliente").val();
        
        if(tipoEnvio=="0"){
            swal("","Indique el tipo de envío.","warning");
            return;
        }
        
        if(tipoEnvio=="2" && gruposCliente==null ){
            swal("","Seleccione al menos un grupo.","warning");
            return;
        }
        
        
        var metodoPago=$("#metodoPago").val();
        var informacionPago=$("#informacionPago").val();
        
        
        waitingDialog();
        $.ajax({
             type:"POST",
             headers:{'X-CSRF-TOKEN':token},
             data:{'articulos':articulos,
                 //Recoger
                 'dirOrigen':dirOrigen,'fechaRecoger':fechaRecoger,'fechaHastaRecoger':fechaHastaRecoger,'idLugarOrigen':idLugarOrigen,
                 'contactoOrigen':contactoOrigen,'telefonoOrigen':telefonoOrigen,'latitudOrigen':latitudOrigen,'longitudOrigen':longitudOrigen,
                 'idPaisOrigen':idPaisOrigen,'idEstadoOrigen':idEstadoOrigen,'ciudadOrigen':ciudadOrigen,'direccionOrigen':direccionOrigen,
                 'horaRecoger':horaRecoger,'horaRecogerHasta':horaRecogerHasta,'dirOrigen2':dirOrigen2,'puntoOrigenRef':puntoOrigenRef,
                 'otroLugarRecoger':otroLugarRecoger,
                 
                 //Entregar
                 'dirDestino':dirDestino,'fechaEntregar':fechaEntregar,'fechaEntregarHasta':fechaEntregarHasta,
                 'idLugarDestino':idLugarDestino,'contactoDestino':contactoDestino,'telefonoDestino':telefonoDestino,'latitudDestino':latitudDestino,
                 'longitudDestino':longitudDestino,'idPaisDestino':idPaisDestino,'idEstadoDestino':idEstadoDestino,'ciudadDestino':ciudadDestino,
                 'direccionDestino':direccionDestino,'horaEntregar':horaEntregar,'horaEntregarHasta':horaEntregarHasta,'dirDestino2':dirDestino2,
                 'puntoDestinoRef':puntoDestinoRef,'otroLugarEntregar':otroLugarEntregar,
                 
                 //servicios
                 'elevadorRecoger':elevadorRecoger,'servicioRecogerInterior':servicioRecogerInterior,'llamarEntrega':llamarEntrega,'horarioEntrega':horarioEntrega,
                 'elevadorEntrega':elevadorEntrega,'servicioEntregarInterior':servicioEntregarInterior,'servicioFrio':servicioFrio,'servicioClasificar':servicioClasificar,
                 'servicioCoordinar':servicioCoordinar,
                 //envio
                 'tipoCosto':tipoCosto,'costoEnvioM':costoEnvioM,'descuentoOferta':descuentoOferta,'porcentajeDescuento':porcentajeDescuento,'tituloEnvio':tituloEnvio,'expiracion':exp,
                 'tipoEnvio':tipoEnvio,'gruposCliente':gruposCliente,'metodoPago':metodoPago,'informacionPago':informacionPago
         },
         

             url:"/cliente/guardarEnvio",
	     success:function(respuesta){
                 if(respuesta.indexOf("ok=>")!=-1){
                    var id = respuesta.substring(respuesta.indexOf("ok=>")+4,respuesta.length);
                    mostrarDetalle(id);
                 }else if(respuesta=="errorpaisrecoger"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","El país seleccionado en la pestaña Recoger en.. no esta registrado.","error");},100);
                 }else if(respuesta=="errorestadorecoger"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","El estado seleccionado en la pestaña Recoger en.. no esta registrado.","error");},100);
                 }else if(respuesta=="errormunicipiorecoger"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","El municipio seleccionado en la pestaña Recoger en.. no esta registrado.","error");},100);
                 }else if(respuesta=="errorpaisentrega"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","El país seleccionado en la pestaña Entregar en.. no esta registrado.","error");},100);
                 }else if(respuesta=="errorestadoentrega"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","El estado seleccionado en la pestaña Entregar en.. no esta registrado.","error");},100);
                 }else if(respuesta=="errormunicipioentrega"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","El municipio seleccionado en la pestaña Entregar en.. no esta registrado.","error");},100);
                 }else if(respuesta=="errorimagen"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","Verifique que las imagenes de los articulos sean válidas (.png, .jpg, .gif) ","error");},100);
                 }else if(respuesta=="errorsesion"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","Ocurrio un error inesperado, inicie sesión nuevamente.","error");},100);
                 }else if(respuesta=="errorexpiracion"){
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","La Fecha y/o Hora de expiración son menores a las actual","error");},100);
                 }else{
                     setTimeout(function(){closeWaitingDialog();},300);
                     setTimeout(function(){swal("","Ocurrio un error inesperado, contacte al administrado del sistema.","error");},100);
                 }
             }
        });
    }
    
    
    /*
     * Muestra / Oculta el formulario de costos
     * Autor: OT
     * Fecha 04-06-2016
     * 
     */
    
    function tipoCosto(tipo){
        if(tipo==1){
            $("#costoEnvio").removeAttr("style");
            $("#costoEnvioM").focus();
        }
        else{
            $("#costoEnvio").attr("style","display:none");
            $("#divDescuento").attr("style","display:none");
            $("#porcentajeDescuento").val("");
            $("#costoEnvioM").val("");
            
            if($('#descuentoOferta').is(':checked')){
                $('#boxDesciento').click();   
            }
        }
    }
    
    
    
    /*
     * Muestra el detalle del envio
     * Autor: OT
     * Fecha 04-06-2016
     * 
     */
    function mostrarDetalle(idEnvio){
       /* var token=$("#token").val();
        $.ajax({
           type:"POST",
           headers:{'X-CSRF-TOKEN':token},
           data:{'idEnvio':idEnvio},
	   url:"/cliente/detalleEnvio",
           success:function(respuesta){
                $("#contenidoPaginaLay").html(respuesta);
                //cargarMapaDetalle();
           }
       });*/
        location.href="/cliente/detalleEnvio/"+idEnvio;
        
    }
    
    
    /*
     * Muestra el mapa con el detalle del envio
     * Autor: OT
     * Fecha 04-06-2016
     * 
     */
    
    var mapaDetalle;
    
    function cargarMapaDetalle(){
         var directionsDisplay = new google.maps.DirectionsRenderer;
         var directionsService = new google.maps.DirectionsService;
         mapaDetalle = new google.maps.Map(document.getElementById("mapid"),
            {
             center: {lat: 19.432608, lng: -99.133208},
             zoom: 8
            });
         var puntoRecoger = new google.maps.LatLng($("#latitudRecoger").val(),$("#longitudRecoger").val());
         var puntoEntregar = new google.maps.LatLng($("#latitudEntregar").val(),$("#longitudEntregar").val());
            
         directionsDisplay.setMap(mapaDetalle);
         calculateAndDisplayRoute(directionsService, directionsDisplay,puntoRecoger,puntoEntregar);
            
          setTimeout(function(){closeWaitingDialog();},300);  
     }
     
     
        function calculateAndDisplayRoute(directionsService, directionsDisplay,puntoRecoger,puntoEntregar) {
          directionsService.route({
            origin: puntoRecoger,
            destination: puntoEntregar, 
            travelMode: google.maps.TravelMode["DRIVING"]
          }, 
          function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
              calcularDistancia(puntoRecoger,puntoEntregar);
            } else {
                verMapaNormalCl(puntoRecoger,puntoEntregar);
                setTimeout(function(){swal("","No existe una ruta entre los puntos de recolección y entrega, no fue posible calcular el tiempo y la distancia.","error");},100);
            }
          });
        }
     
    
        /*
         * Muestra los puntos de recolección y entrega en un mapa cuando no existe ruta entre los puntos
         * Autor: OT
         * Fecha: 02-07-2016
         */
	function verMapaNormalCl(puntoRecoger,puntoEntregar){
           var bounds  = null;
           bounds  = new google.maps.LatLngBounds();
           marcadorRecoger = new google.maps.Marker({
               map: mapaDetalle,
               position: puntoRecoger,
               icon:'/img/dot-green.png'
            });
            
            marcadorEntregar = new google.maps.Marker({
               map: mapaDetalle,
               position: puntoEntregar,
               icon:'/img/dot-red.png'
            });
                       
            bounds.extend(marcadorRecoger.position);
            bounds.extend(marcadorEntregar.position);
            mapaDetalle.panToBounds(bounds); 
            mapaDetalle.fitBounds(bounds); 
        }
        
        
        function calcularDistancia(puntoRecoger,puntoEntregar) {
            var service = new google.maps.DistanceMatrixService;
               service.getDistanceMatrix({
               origins: [puntoRecoger],
               destinations: [puntoEntregar],
               travelMode: google.maps.TravelMode.DRIVING,
               unitSystem: google.maps.UnitSystem.METRIC,
               avoidHighways: false,
               avoidTolls: false
               }, 
               function(response, status) {
                   if (status !== google.maps.DistanceMatrixStatus.OK) {
                       //setTimeout(function(){swal("","Ocurrio un error al generar el mapa, consulte al adminitrador del sistema.","error");},100);
                   } else {
                           var originList = response.originAddresses;
                           var destinationList = response.destinationAddresses;
                           var tiempoEstimado=0;
                           var metrosEstimados=0;
                           for (var i = 0; i < originList.length; i++) {
                             var results = response.rows[i].elements;
                                 for (var j = 0; j < results.length; j++) {
                                     tiempoEstimado=results[j].duration.value;
                                     metrosEstimados=results[j].distance.value;
                             }
                           }

                           var cdistancia=$("#distancia").val();
                           cdistancia=cdistancia.split(' ').join('');
                           var idEnvio=$("#idEnvioC").val();

                           var distancia = metrosEstimados/1000;
                           distancia=parseFloat(distancia).toFixed(2);

                           var tiempo =(tiempoEstimado/60)/60;
                           tiempo=(parseFloat(tiempo).toFixed(2))+'';
                           var punto = tiempo.indexOf(".");
                           if(punto>0){
                               minutos = (parseFloat(tiempo.substring(punto))*60);
                               minutos=parseFloat(minutos).toFixed(0);
                               hora=tiempo.substring(0, punto);
                               if(parseFloat(hora)<10){
                                   hora="0"+hora;
                               }
                               tiempoRe=hora + ":" + minutos;
                           }else{
                               tiempoRe=tiempo+":00";
                           }


                           $("#divkm").html("<font class='flet-lab'>"+distancia + " KM." + "&nbsp;&nbsp;" + tiempoRe + " Hrs."+"</font>");

                           if(cdistancia=="" || parseFloat(cdistancia)==0){
                               $.ajax({
                                    type:"GET",
                                    url:"/cliente/actualizarTiempoEnvio/"+idEnvio+"/"+distancia+"/"+tiempo,
                                    success:function(respuesta){
                                    }
                                });
                           }
                 }
               });
        }
    
    
    
    /*
     * Retorna fecha 
     * Autor: OT
     * Fecha 01-06-2016
     * 
     */
    function tipoFecha(cadena){
        var parts = cadena.split("-");
        var f1=new Date(parts[2], parts[1] - 1, parts[0]);
        return f1;
    }
    
    /*
     * Recibe cadena dd-mm-yyyy
     * Retorna yyyy-mm-dd
     * Autor: OT
     * Fecha 01-06-2016
     * 
     */
    function cadenaFormatoFecha(cadena){
        var parts = cadena.split("-");
        var cadenaFecha=parts[2]+"-"+parts[1]+"-"+parts[0];
        return cadenaFecha;
    }
    
    
    /*
     * Mostrar fecha formado dd-mm-yyyy
     * Autor: OT
     * Fecha 20-07-2016
     * 
     */
    function tipoFechaCadena(fecha){
        fecha=fecha+"-";
        var parts = fecha.split("-");
        var cadena= parts[0] +"-"+ parts[1]+"-"+ parts[2];

        return cadena;
    }
    
    
    function presionarEnter(e,caja) {
        if(event.keyCode == 13) {
           buscarDireccion(caja);
        }
    }
    
    /*
     * Limpia la caja de texto que pierde el foco
     * Autor: OT
     * 20-06-2016
     */
    function validarCaja(texto,id){
       var textoLimpio=limpiarTexto(texto);
       $("#"+id).val(textoLimpio);
    }
    
    /*
     * Limpia el texto recibido, para solo permitir numeros
     * Autor: OT
     * 20-06-2016
     */
    function limpiarTexto(texto){
       var vTexto="";
       vTexto=texto.split(',').join('');
       return vTexto; 
    }
    
    /*
    * Obtiene la lista de estados
    * Autor: OT
    * Fecha: 20-06-2016
    * 
    */
   function buscarEstadoEnvio(idPais,caja){
       var token=$("#token").val();
       waitingDialog(); 
        $.ajax({
             type:"GET",
             headers:{'X-CSRF-TOKEN':token},
             data:{'idPais':idPais},
             url:"../general/estados",
             success:function(respuesta){
                 setTimeout(function(){closeWaitingDialog();},100);
                 if(caja==1){
                     $("#idEstadoOrigen").html(respuesta);
                     limpiarMapa(1);
                 }
                 if(caja==2){
                     $("#idEstadoDestino").html(respuesta);
                     limpiarMapa(2);
                 }
             }
           });   
   }
   
   /*
    * Limpia el mapa, ciudad y direccion
    * Autor: OT
    * Fecha: 24-06-2016
    * 
    */
   function limpiarMapa(idMapa){
       if(idMapa==1){
           if(marcadorRecoger!="")marcadorRecoger.setMap(null);
           $("#ciudadOrigen").val("");
           $("#dirOrigen").val("");
           $("#dirOrigen2").val("");
           $("#latitudOrigen").val("");
           $("#longitudOrigen").val("");
       }else{
           if(marcadorEntregar!="")marcadorEntregar.setMap(null);
           $("#ciudadDestino").val("");
           $("#dirDestino").val("");
           $("#dirDestino2").val("");
           $("#latitudDestino").val("");
           $("#longitudDestino").val("");
       }
   }
   
   /*
    * Se mueve del tab articulos a recoger
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function irTabRecoger(){
        
        var idLinea = document.getElementsByName("idLinea");
        if(idLinea.length==0){
            swal("","Agregue al menos un artículo en el envío.","warning");
           return;
        }
        $("#liTab2").removeAttr("style");
        $("#pasoEnvio").html("&nbsp;2 / 5");
        $('#t2').click();
        $("#back-to-top").click();
    }
    
    /*
    * Se mueve del tab recoger al tab entregar
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function irTabEntregar(){
        
        var hoy=new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1;
        if( dd<10)dd="0"+dd;
        if(mm<10)mm="0"+mm;
        var yyyy = hoy.getFullYear();
        var hh = hoy.getHours();
        var ii = hoy.getMinutes();
        if(hh<10)hh='0'+hh;
        if(ii<10)ii='0'+ii;
        horahoy= hh+':'+ii+':'+'00';
        hoy=dd+"-"+mm+"-"+yyyy;
        
        
        var idPaisOrigen=$("#idPaisOrigen").val();
        var idEstadoOrigen=$("#idEstadoOrigen").val();
        var ciudadOrigen=$("#ciudadOrigen").val();
        var direccionOrigen=$("#direccionOrigen").val();

        
        var fechaRecoger=$("#fechaRecoger").val();
        var fechaHastaRecoger=$("#fechaHastaRecoger").val();
        var contactoOrigen=$("#contactoOrigen").val();
        var telefonoOrigen=$("#telefonoOrigen").val();
        var idLugarOrigen=$("#idLugarOrigen").val();
        var otroLugarRecoger=$("#otroLugarRecoger").val();
        var hora1=$("#hora1").val();
        var hora2=$("#hora2").val();
        
        
       if(idPaisOrigen=="0"){
            swal("","Seleccione un país.","warning");
            return;
        }
            
        if(idEstadoOrigen=="0"){
            swal("","Seleccione un departamento.","warning");
            return;
       }
            
       if(ciudadOrigen.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre de la ciudad.","warning");
           return;
       }
       
       if(direccionOrigen.replace(/\s/g,"")==""){
          swal("","Ingrese la calle.","warning");
          return;
       }

       if($("#latitudOrigen").val()==""){
           swal("","Ubique en el mapa el punto donde se recogera el envío.","warning");
           return;
        }
       
      
        if(fechaRecoger==""){
           swal("","Indique la fecha 'Recoger el..'","warning");
           return;
        }
         if(tipoFecha(fechaRecoger) < tipoFecha(hoy)){
            swal("","La fecha 'Recoger el..' debe ser mayor o igual a "+tipoFechaCadena(hoy),"warning");
           return;
        }
        
        if(fechaHastaRecoger!=""){
            if(tipoFecha(fechaHastaRecoger)<=tipoFecha(fechaRecoger)){
                swal("","La fecha 'Hasta el..' debe ser mayor a " + tipoFechaCadena(fechaRecoger) ,"warning");
                return;
            }
        }

        var fechaRecoleccion=fechaRecoger.split("-");
        if(fechaHastaRecoger==""){
            if(hora1!=""){
                act=hoy.split("-");
                actH=act[2]+'-'+act[1]+'-'+act[0];
                actual=actH+' '+horahoy;
                horaR1=parseHora(hora1);
                fr=fechaRecoleccion[2]+'-'+fechaRecoleccion[1]+'-'+fechaRecoleccion[0];
                recoger=fr+' '+horaR1;

                if(actual>recoger){
                    swal("","La hora 'Recoger desde' debe ser mayor a "+horahoy);
                    return;
                }
            }
            
            if(hora2!=""){
                if(hora1==""){
                    actual=hoy+' '+horahoy;
                    horaR2=parseHora(hora2);
                    recoger=fechaRecoleccion+' '+horaR2;

                    if(actual>recoger){
                        swal("","La hora 'Recoger desde' debe ser mayor a "+horahoy);
                        return;
                    }
                }
                else{
                    horaR=parseHora($("#hora1").val());
                    horaH=parseHora($("#hora2").val());
                    if(horaH<=horaR){
                        swal("","La hora 'Recoger hasta' debe ser mayor a "+horaR);
                        return;
                    }
                }
                
            }
        }else{
            if(hora2!=""){
                if(hora1==""){
                    actual=hoy+' '+horahoy;
                    horaR2=parseHora(hora2);
                    recoger=fechaRecoleccion+' '+horaR2;

                    if(actual>recoger){
                        swal("","La hora 'Recoger desde' debe ser mayor a "+horahoy);
                        return;
                    }
                }
                else{
                    horaR=parseHora($("#hora1").val());
                    horaH=parseHora($("#hora2").val());
                    if(horaH<=horaR){
                        swal("","La hora 'Recoger hasta' debe ser mayor a "+horaR);
                        return;
                    }
                }
                
            }
        }
        
        if((fechaRecoger == hoy) && hora1=="" && hora2==""){
            swal("","Selecciono la fecha de hoy, por lo que debe especificar un horario","warning");
           return;
        }
        
        
        if(contactoOrigen.replace(/\s/g,"")==''){
           swal("","Escriba el nombre del contacto en el lugar de recolección.","warning");
           return;
       }
       
       if(telefonoOrigen.replace(/\s/g,"")==''){
           swal("","Escriba el telefono del contacto en el lugar de recolección.","warning");
           return;
       }
       
       if (!/^([0-9])*$/.test(telefonoOrigen)){
            swal("","El teléfono solo debe contener números.","warning");
            return;
        }
            
        if(idLugarOrigen=="0"){
           swal("","Seleccione el lugar donde se recogerá el envío.","warning");
           return;
        }
        
        if(idLugarOrigen=="6" && otroLugarRecoger.replace(/\s/g,"")==''){
           swal("","Especifique el lugar de recolección.","warning");
           return;
        }
        
        
        
        
        $("#liTab3").removeAttr("style");
        $('#t3').click();
        $("#pasoEnvio").html("&nbsp;3 / 5");
        $("#back-to-top").click();
    }
    
    /*
    * Se mueve del tab entragar a otros servicios
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function irTabOtrosServicios(){
        
        var hoy=new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1;
        if( dd<10)dd="0"+dd;
        if(mm<10)mm="0"+mm;
        var yyyy = hoy.getFullYear();
        var hh = hoy.getHours();
        var ii = hoy.getMinutes();
        if(hh<10)hh='0'+hh;
        if(ii<10)ii='0'+ii;
        horahoy= hh+':'+ii+':'+'00';
        hoy=dd+"-"+mm+"-"+yyyy;
        
        var fechaRecoger=$("#fechaRecoger").val();
        var fechaHastaRecoger=$("#fechaHastaRecoger").val();

	   var horaRecoger=$("#hora1").val();
        var horaRecogerHasta=$("#hora2").val();

        
        
        var idPaisDestino=$("#idPaisDestino").val();
        var idEstadoDestino=$("#idEstadoDestino").val();
        var ciudadDestino=$("#ciudadDestino").val();
        var direccionDestino=$("#direccionDestino").val();
        var fechaEntregar=$("#fechaEntregar").val()
        var fechaEntregarHasta=$("#fechaEntregarHasta").val();
        var contactoDestino=$("#contactoDestino").val();
        var telefonoDestino=$("#telefonoDestino").val();
        var idLugarDestino=$("#idLugarDestino").val();
        var otroLugarEntregar=$("#otroLugarEntregar").val();

        
        
        var horaEntregar=$("#hora3").val();
        var horaEntregarHasta=$("#hora4").val();

        if(idPaisDestino=="0"){
            swal("","Seleccione un país.","warning");
            return;
        }
            
        if(idEstadoDestino=="0"){
            swal("","Seleccione un departamento.","warning");
            return;
       }
            
       if(ciudadDestino.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre de la ciudad.","warning");
           return;
       }
       
       if(direccionDestino.replace(/\s/g,"")==""){
          swal("","Ingrese la calle.","warning");
          return;
       }
    
        if($("#latitudDestino").val()==""){
           swal("","Ubique en el mapa el punto de entrega.","warning");
           return;
        }
       
       
        if(fechaEntregar==fechaRecoger){
            if(horaRecoger==""){
                swal("","Ingrese el campo 'Recoger desde' en la pestaña anterior","warning");
                return;
            }
            
            if(horaEntregar==""){
                swal("","Ingrese la hora de entrega en 'Entregar desde..'","warning");
                return;
            }
        }
        
        
        if($("#fechaEntregar").val()==""){
           swal("","Indique la fecha a entregar el envío.","warning");
           return;
        }
        

        //if(tipoFecha(fechaEntregar)<tipoFecha(hoy)){
        //    swal("","La fecha 'Entregar el..' debe ser mayor o igual a la fecha actual.","warning");
        //   return;
        //}
        
      
            if(tipoFecha(fechaEntregar)<tipoFecha(fechaRecoger)){
                swal("","La fecha de entrega debe ser mayor o igual a "+ tipoFechaCadena(fechaRecoger),"warning");
                return;
            }
        
        
        if(fechaEntregarHasta!=""){
            if(tipoFecha(fechaEntregar)>tipoFecha(fechaEntregarHasta)){
                swal("","La 'fecha hasta el..' debe ser mayor "+ tipoFechaCadena(fechaEntregar),"warning");
                return;
            }
        }

        
            
        if(horaEntregarHasta!=""){
            if(horaEntregar!=""){
                horaR=parseHora(horaEntregar);
                horaH=parseHora(horaEntregarHasta);
                if(horaH<=horaR){
                    swal("","La hora 'Entregar hasta' debe ser mayor a "+horaR);
                    return;
                }
            }
        }
                
            

        
        if((fechaEntregar == hoy) && horaEntregar=="" && horaEntregarHasta==""){
            swal("","Selecciono la fecha de hoy, por lo que debe especificar un horario","warning");
           return;
        }
        
        if(contactoDestino.replace(/\s/g,"")==''){
           swal("","Escriba el nombre del contacto en el lugar de entrega.","warning");
           return;
        }
       
       if(telefonoDestino.replace(/\s/g,"")==''){
           swal("","Escriba el telefono del contacto en el lugar de entrega.","warning");
           return;
       }
       
        if (!/^([0-9])*$/.test(telefonoDestino)){
            swal("","El teléfono solo debe contener números.","warning");
            return;
        }

        
        if(idLugarDestino=="0"){
           swal("","Seleccione el lugar donde se entregará el envío.","warning");
           return;
        }
        
        if(idLugarDestino=="6" && otroLugarEntregar.replace(/\s/g,"")==''){
           swal("","Especifique el lugar de entrega.","warning");
           return;
        }
        
        $("#liTab4").removeAttr("style");
        $('#t4').click();
        $("#pasoEnvio").html("&nbsp;4 / 5");
        $("#back-to-top").click();
    }
    
    /*
    * Se mueve del tab otros servicios a guardar envio
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function irTabGuardarEnvio(){
        $("#pasoEnvio").html("&nbsp;5 / 5");
        $('#t5').click();
        //$("#liTab5").removeAttr("style");
    }
    
    
    /*
    * Se mueve del tab recoger a articulos
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function volveraArticulos(){
        $('#t1').click();
        $("#pasoEnvio").html("&nbsp;1 / 5");
        $("#back-to-top").click();
    }
    
    
    /*
    * Se mueve del tab entregar a recoger
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function volveraRecoger(){
        $('#t2').click();
        $("#pasoEnvio").html("&nbsp;2 / 5");
        $("#back-to-top").click();
    }
    
    
    /*
    * Se mueve del tab otros servicio a entregar
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function volveraEntregar(){
        $('#t3').click();
        $("#pasoEnvio").html("&nbsp;3 / 5");
        $("#back-to-top").click();
    }
    
    /*
    * Se mueve del tab guardar envio a otros servicios
    * Autor: OT
    * Fecha: 08-07-2016
    * 
    */
    function volveraOtrosServicios(){
       //$("#liTab5").attr("style","display:none");
        $('#t4').click();
        $("#pasoEnvio").html("&nbsp;4 / 5");
        $("#back-to-top").click();
    }
    
    
    /*
    * Manda el mail a los transportistas
    * Autor: OT
    * Fecha: 16-07-2016
    * 
    */
    function enviarMailTransportistas(idEnvio){
        $.ajax({
             type:"GET",
             url:"/cliente/enviarMailTransportista/"+idEnvio,
	     success:function(respuesta){
                
             }
        });
       
    }
    
    /*
    * Borra la hora de los elementos datepicker
    * Autor: OT
    * Fecha: 20-07-2016
    * 
    */
    function quitarHora(id){
        if(id==1){
            $("#hora1").val("");
            return;
        }
        
        if(id==2){
            $("#hora2").val("");
            return;
        }
        
        if(id==3){
            $("#hora3").val("");
            return;
        }
        
        if(id==4){
            $("#hora4").val("");
            return;
        }
        
        if(id==5){
            $("#horaExpiracion").val("");
            return;
        }
    }
    
    /*
    * Validar el lugar seleccionado en los tabs de recoger y entregar
    * Autor: OT
    * Fecha: 23-07-2016
    * 
    */
    function validarLugar(lugarSeleccionado,tab){
        if(tab==1){
            if(lugarSeleccionado==6){
                $("#divOtroLugarRecoger").removeAttr("style");
                $("#otroLugarRecoger").val("");
                $("#otroLugarRecoger").focus();
            }else{
                $("#divOtroLugarRecoger").attr("style","display: none");
                $("#otroLugarRecoger").val("");
            }
        }else{
            if(lugarSeleccionado==6){
                $("#divOtroLugarEntregar").removeAttr("style");
                $("#otroLugarEntregar").val("");
                $("#otroLugarEntregar").focus();
            }else{
                $("#divOtroLugarEntregar").attr("style","display: none");
                $("#otroLugarEntregar").val("");
            }
            
        }
    }
    
    
    /*
    * Sale de la ventana de envio y redirecciona al resumen del cliente
    * Autor: OT
    * Fecha: 23-07-2016
    * 
    */
    function salirDelEnvio(){
        swal({
                title: "",   
                text: "¿Desea salir y perder los datos del envío?",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#47A447",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar", 
                closeOnConfirm: true,   
                closeOnCancel: true }, 
                function(isConfirm){   
                    if (isConfirm) {
                        waitingDialog();
                        location.href="/cliente";
                    }
                });
     }
     
     /*
     * Muestra / Oculta el formulario de costos
     * Autor: OT
     * Fecha 04-06-2016
     * 
     */
    
    function tipoEnvio(tipo){
        if(tipo==2){
            waitingDialog(); 
            $.ajax({
                type:"GET",
                url:"/cliente/buscarGrupoCliente/",
                success:function(respuesta){
                    setTimeout(function(){closeWaitingDialog();},100);
                    if(respuesta==""){
                        swal("","Actualmente no se encuentra dentro de ningun grupo, su envío no puede ser privado.","warning");
                    }else{
                       $("#divTipoEnvio").removeAttr("style");
                       $("#gruposCliente").html(respuesta);
		       $('#gruposCliente').multiselect('rebuild');
                    }
                }
            });
        }else{
            $("#divTipoEnvio").attr("style","display:none");
            $("#gruposCliente").html("");
            $('#gruposCliente').multiselect('rebuild');
        }
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


function parseHora(hora){

    //hora.toString();
    var horaParse=hora.replace(":"," ");
    var res = horaParse.split(" ");

    var hh;
    var mm;
    if(res[2]=="PM"){
        if(res[0]!=12){
            hh=parseInt(res[0])+12;
        }
        else{
            hh="12";
        }
    }
    else{
        if(res[0]=="12"){
            hh="00";
        }
        else{
            hh=res[0];
        }
    }
    mm=res[1];
    var newhour=hh+":"+mm+":00";
    return newhour;
}