/* 
 * script para configuracion de correos
 */
    
    
    /*
    * Vista previo del correo
    * Autor: OT
    * Fecha: 29-07-2016
    * 
    */
     function vistaPreviaCorreo(){
        var token=$("#token").val();
        var imagen1="";
        var imagen2="";
     
        $("#divImagen1 img").each(function(key, element){ 
           imagen1=$(element).attr('src');
        }); 

        $("#divImagen2 img").each(function(key, element){ 
            imagen2=$(element).attr('src');
        }); 
        
        //titulos
        var tituloNegrita="N";
        var tituloCursiva="N";
        var tituloColor=$("#tituloColor").val();
        var tituloTamanio=$("#tituloTamanio").val();
        

        if ($("#tituloNegrita").is(":checked")) {
            tituloNegrita="Y";
        }
        
        if ($("#tituloCursiva").is(":checked")) {
            tituloCursiva="Y";
        }
        
        //Etiquetas
        var etiquetaNegrita="N";
        var etiquetaCursiva="N";
        var etiquetaColor=$("#etiquetaColor").val();
        var etiquetaTamanio=$("#etiquetaTamanio").val();
        

        if ($("#etiquetaNegrita").is(":checked")) {
            etiquetaNegrita="Y";
        }
        
        if ($("#etiquetaCursiva").is(":checked")) {
            etiquetaCursiva="Y";
        }
        
        //Detalle
        var detalleNegrita="N";
        var detalleCursiva="N";
        var detalleColor=$("#detalleColor").val();
        var detalleTamanio=$("#detalleTamanio").val();
        

        if ($("#detalleNegrita").is(":checked")) {
            detalleNegrita="Y";
        }
        
        if ($("#detalleCursiva").is(":checked")) {
            detalleCursiva="Y";
        }
        
        var enviaImagen1=0
        var enviaImagen2=0
        
        if(imagen1!="")enviaImagen1=1;
        if(imagen2!="")enviaImagen2=1;
                
        waitingDialog(); 
           $("#divElmentosCorreo").dialog({
		autoOpen: false,
		title: "Vista previa correo",
		modal:true,
		width: 900,
		height: 650,
		close: function(event,ui){
                    $("#divElmentosCorreo").html('');
                    $("#divElmentosCorreo").dialog('destroy');
		},
		open:function(event,ui){
                    waitingDialog();
                    $.ajax({
                        type:"POST",
                        headers:{'X-CSRF-TOKEN':token},
                        data:{'tituloNegrita':tituloNegrita,'tituloCursiva':tituloCursiva,'tituloColor':tituloColor,'tituloTamanio':tituloTamanio,
                              'etiquetaNegrita':etiquetaNegrita,'etiquetaCursiva':etiquetaCursiva,'etiquetaColor':etiquetaColor,'etiquetaTamanio':etiquetaTamanio,
                              'detalleNegrita':detalleNegrita,'detalleCursiva':detalleCursiva,'detalleColor':detalleColor,'detalleTamanio':detalleTamanio,
                              'enviaImagen1':enviaImagen1,'enviaImagen2':enviaImagen2
                            },
			url:"/admin/vistaPreviaCorreo",
			success:function(respuesta){
                            $("#divElmentosCorreo").html(respuesta);
                            if(imagen1!="")$("#encabezado").attr('src',imagen1);
                            if(imagen2!="")$("#pie").attr('src',imagen2);
                            
                                                            
                            setTimeout(function(){closeWaitingDialog();},100);
                        }
                    });

		}
	});
	$("#divElmentosCorreo").dialog('open');
     }
     
     
     
     /*
     * Quita la imagen del div de edicion de correo
     * Autor: OT
     * Fecha: 22-08-2016
     */
    
    function quitarImagen(id){
        if(id=="1")$("#i1").attr('src','');
        else $("#i2").attr('src','');
    }
    
    
    
    /*
    * Guardar configuración
    * Autor: OT
    * Fecha: 29-07-2016
    * 
    */
     function guardarCambiosCorreo(){
        var token=$("#token").val();
        var imagen1="";
        var imagen2="";
     
        $("#divImagen1 img").each(function(key, element){ 
           imagen1=$(element).attr('src');
        }); 

        $("#divImagen2 img").each(function(key, element){ 
            imagen2=$(element).attr('src');
        }); 
        
        //titulos
        var tituloNegrita=false;
        var tituloCursiva=false;
        var tituloColor=$("#tituloColor").val();
        var tituloTamanio=$("#tituloTamanio").val();
        

        if ($("#tituloNegrita").is(":checked")) {
            tituloNegrita=true;
        }
        
        if ($("#tituloCursiva").is(":checked")) {
            tituloCursiva=true;
        }
        
        //Etiquetas
        var etiquetaNegrita=false;
        var etiquetaCursiva=false;
        var etiquetaColor=$("#etiquetaColor").val();
        var etiquetaTamanio=$("#etiquetaTamanio").val();
        

        if ($("#etiquetaNegrita").is(":checked")) {
            etiquetaNegrita=true;
        }
        
        if ($("#etiquetaCursiva").is(":checked")) {
            etiquetaCursiva=true;
        }
        
        //Detalle
        var detalleNegrita=false;
        var detalleCursiva=false;
        var detalleColor=$("#detalleColor").val();
        var detalleTamanio=$("#detalleTamanio").val();
        

        if ($("#detalleNegrita").is(":checked")) {
            detalleNegrita=true;
        }
        
        if ($("#detalleCursiva").is(":checked")) {
            detalleCursiva=true;
        }
        
        waitingDialog();
                    $.ajax({
                        type:"POST",
                        headers:{'X-CSRF-TOKEN':token},
                        data:{'tituloNegrita':tituloNegrita,'tituloCursiva':tituloCursiva,'tituloColor':tituloColor,'tituloTamanio':tituloTamanio,
                              'etiquetaNegrita':etiquetaNegrita,'etiquetaCursiva':etiquetaCursiva,'etiquetaColor':etiquetaColor,'etiquetaTamanio':etiquetaTamanio,
                              'detalleNegrita':detalleNegrita,'detalleCursiva':detalleCursiva,'detalleColor':detalleColor,'detalleTamanio':detalleTamanio,
                              'imagen1':imagen1,'imagen2':imagen2
                            },
			url:"/admin/guardarConfiguracionCorreo",
			success:function(respuesta){
                            $("#divElmentosCorreo").html(respuesta);
                            setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                swal({
                                        title: "",   
                                        text: "Configuración guardada correctamente",
                                        type: "success",   
                                        showCancelButton: false,   
                                        confirmButtonColor: "#47A447",
                                        confirmButtonText: "Aceptar",
                                        cancelButtonText: "Cancelar", 
                                        closeOnConfirm: false,   
                                        closeOnCancel: false
                                        }, 
                                        function(isConfirm){   
                                            if (isConfirm) {
                                                location.href="/admin";
                                            }else{
                                               location.href="/admin";
                                            }
                                        });
                                    }else if(respuesta=="errorimagen1"){
                                        setTimeout(function(){swal("","El encabezado debe ser imagen jpg o png.","error");},100);
                                    }else{
                                        setTimeout(function(){swal("","Ocurrio un error inesperado, contacte al administrado del sistema.","error");},100);
                                    }
                        }
                    });
        
                
        
     }