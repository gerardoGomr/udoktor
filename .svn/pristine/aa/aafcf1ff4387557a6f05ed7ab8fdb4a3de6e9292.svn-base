/* 
 * Script para los servicios del usuario prestador de servicios
 * Fecha : 08-12-2016
 * Autor: OT
 */

$(function() {
   $("#mensajeAlertaServicio").attr("style","display:none");
   var tipoOculto= $("#tipoOculto").val();
   cargarServicios(tipoOculto);
})


/* 
 * Carga la tabla de servicios
 * Fecha : 04-01-2017
 * Autor: OT
 */

function cargarServicios(tipo) {
    var token=$("#token").val();
       waitingDialog(); 
           $.ajax({
               type:"POST",
               headers:{'X-CSRF-TOKEN':token},
               data:{"tipoCosto":tipo},
               url:"/prestadorServicios/cargarTablaServicios/",
               success:function(respuesta){
                   $("#tablaServicios").html(respuesta);
                   setTimeout(function(){closeWaitingDialog();},100);
               }
           });
 }


/* 
 * Guarda el precio de los servicios
 * Fecha : 09-12-2016
 * Autor: OT
 */

function guardarServicios() {
    var token=$("#token").val();
    var servicios = "";
    var tipoCosto=$("#tipoCosto").val();
    var error=0;
    var reg=1;
    
        $('input[name=costoServicio]').each(function(){
            var id=$(this).attr("id");
            var valor=$(this).val();
            if(valor=="" ||  !/^([0-9])*.([0-9])*$/.test(valor) || !parseFloat(valor) || parseFloat(valor)<0  || isNaN(valor)){
                swal("","El precio del servicio " + reg +  " no es válido.","warning");
                error=1;
                return;
             }else{
                 servicios=servicios+id+"="+valor+",";
             }
             reg=reg+1;
             
        })
    
    if(error==0){
        waitingDialog(); 
           $.ajax({
               type:"POST",
               headers:{'X-CSRF-TOKEN':token},
               data:{"tipoCosto":tipoCosto,"servicios":servicios},
               url:"/prestadorServicios/guardarPrecioServicios/",
               success:function(respuesta){
                   setTimeout(function(){closeWaitingDialog();},100);
                   if(respuesta=="ok"){
                       swal({
                        title: "",   
                        text: "Proceso realizado correctamente correctamente",
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
                                location.href="/prestadorServicios";
                            }else{
                               location.href="/prestadorServicios";
                            }
                        });
                    }else if(respuesta.indexOf("=")>0){
                        var pos=respuesta.indexOf("=");
                        var x=respuesta.substring(pos+1, respuesta.length);
                        swal("","Verifique el precio del servicio # "+ x ,"error");

                    }else{
                       swal("","Ocurrio un error al guardar los precios,consulte al administrador de servicios.","error");
                   }
               }
           });
    }
 }
 
    /*
     * Muestra el formulario para agregar un servicio al usuario
     * Fecha: 10-12-2016
     * Autor: OT
     */
function agregarServicio(){
        $("#divModal").dialog({
            autoOpen: false,
            title: "Agregar servicio",
            modal:true,
            width: 350,
            height: 320,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/prestadorServicios/agregarServicioPrestador/",
                   success:function(respuesta){
                       $("#divModal").html(respuesta);
                       $('#serviciosid').multiselect({
                            maxHeight: 200,
                            includeSelectAllOption: true,
                            selectAllJustVisible: false
                       });
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divModal").dialog('open');
}

/* 
 * Agregar servicio al usuario
 * Fecha : 12-12-2016
 * Autor: OT
 */

function agregarServicioUsuario() {
    var serviciosid=$("#serviciosid").val();
    var tipoCosto= $("#tipoCosto").val();
    if(serviciosid==null){
           swal("","Seleccione al menos un servicio.","warning");
           return;
    }
        waitingDialog(); 
           $.ajax({
               type:"GET",
               data:{"serviciosid":serviciosid,'tipoCosto':tipoCosto},
               url:"/prestadorServicios/guardarServicioUsuario/",
               success:function(respuesta){
                   setTimeout(function(){closeWaitingDialog();},100);
                   if(respuesta=="ok"){
                       cargarServicios();
                       $('#divModal').dialog('close');
                       /*swal({
                        title: "",   
                        text: "Servicios agregados correctamente",
                        type: "success",   
                        showCancelButton: false,   
                        confirmButtonColor: "#47A447",
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar", 
                        closeOnConfirm: true,   
                        closeOnCancel: false
                        }, 
                        function(isConfirm){   
                            if (isConfirm) {
                                
                            }
                        });*/
                   }else{
                       swal("","Ocurrio un error al guardar los precios,consulte al administrador de servicios.","error");
                   }
               }
           });
 }


    /*
     * Muestra el formulario para agregar servicio nuevo
     * Fecha: 12-12-2016
     * Autor: OT
     */
function nuevoServicio(){
    setTimeout(function(){$('#divModal').dialog('close')},100);
   
                       
        $("#divNuevoServicio").dialog({
            autoOpen: false,
            title: "Nuevo servicio",
            modal:true,
            width: 450,
            height: 340,
            close: function(event,ui){
                 $("#divNuevoServicio").html('');
                 $("#divNuevoServicio").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/prestadorServicios/agregarNuevoServicioPrestador/",
                   success:function(respuesta){
                       $("#divNuevoServicio").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divNuevoServicio").dialog('open');
}


/* 
 * Agregar nuevo servicio al sistema y  al usuario
 * Fecha : 12-12-2016
 * Autor: OT
 */

function guardarNuevoServicioUsuario() {
    var nombreServicio=$("#nombreServicio").val();
    var descripcionservicio=$("#descripcionservicio").val();
    var token=$("#token").val();
    
    if(nombreServicio.replace(/\s/g,"")==""){
           swal("","Ingrese el nombre del servicio.","warning");
           return;
    }
    
    if(descripcionservicio.replace(/\s/g,"")==""){
           swal("","Ingrese la descripción del servicio.","warning");
           return;
    }
    
        waitingDialog(); 
           $.ajax({
               headers:{'X-CSRF-TOKEN':token},
               type:"POST",
               data:{"nombreServicio":nombreServicio,'descripcionservicio':descripcionservicio},
               url:"/prestadorServicios/guardarNuevoServicioUsuario/",
               success:function(respuesta){
                   setTimeout(function(){closeWaitingDialog();},100);
                   if(respuesta=="ok"){
                       $('#divNuevoServicio').dialog('close');
                       swal({
                        title: "",   
                        text: "Servicios agregados correctamente",
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
                                location.href="/prestadorServicios/misServicios";
                            }else{
                               location.href="/prestadorServicios/misServicios";
                            }
                        });
                   }else if(respuesta=="nombreRepetido"){
                       swal("","El servicio ya existe, intente con otro nombre.","error");
                   }else{
                       swal("","Ocurrio un error al guardar los precios,consulte al administrador de servicios.","error");
                   }
               }
           });
 }

/* 
     * Elimina el servicio de la lista
     * Autor: OT
     * Fecha: 12-12-2016
     */
    
    function eliminarRegistro(r) {
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById("listaServicios").deleteRow(i);
    }
    
    