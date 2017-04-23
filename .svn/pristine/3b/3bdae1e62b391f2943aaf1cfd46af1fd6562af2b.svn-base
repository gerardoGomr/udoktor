/* 
 * Script para la agenda del usuario prestador de servicios
 * Fecha : 15-12-2016
 * Autor: OT
 */


$(function() {
   if($("#tipoOculto").val()==1){ // hora fija
        $("#texto1").removeAttr("style");
        $("#texto2").attr("style","display:none");
        $("#duracionServicio").attr("style","display:none");
    }else if($("#tipoOculto").val()==2){ // rango de hora
      $("#texto1").attr("style","display:none");
      $("#texto2").removeAttr("style");
      $("#duracionServicio").removeAttr("style");
    }else{
      $("#texto1").attr("style","display:none");
      $("#texto2").attr("style","display:none");
      $("#duracionServicio").attr("style","display:none");
    }
})


/* 
 * Valida el cambio de tipo de agenda
 * Fecha : 15-12-2016
 * Autor: OT
 */

function tipoAgenda(tipo) {
    
    if(tipo!="" && tipo!=$("#tipoOculto").val()){
        swal({
          title: "",   
          text: "Si cambia el tipo de agenda se borrará la configuración actual, ¿Quiere continuar?",
          type: "warning",   
          showCancelButton: true,   
          confirmButtonColor: "#47A447",
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar", 
          closeOnConfirm: true,   
          closeOnCancel: true
          }, 
          function(isConfirm){   
             if (isConfirm) {
                 waitingDialog();
                 $.ajax({
                     type:"GET",
                     url:"/prestadorServicios/eliminarHorarioTodo/",
                     success:function(respuesta){
                            setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                $("#tipoOculto").val("0");
                                if(tipo==1){ // hora fija
                                    $("#texto1").removeAttr("style");
                                    $("#texto2").attr("style","display:none");
                                    $("#duracionServicio").attr("style","display:none");

                                }else if(tipo==2){ // rango de hora
                                    $("#texto1").attr("style","display:none");
                                    $("#texto2").removeAttr("style");
                                    $("#duracionServicio").removeAttr("style");
                                }else{
                                    $("#texto1").attr("style","display:none");
                                    $("#texto2").attr("style","display:none");
                                    $("#duracionServicio").attr("style","display:none");
                                }
                                cargarHorario();
                            }else if(respuesta=="existecita"){
                                 swal("","El horario no puede eliminarse porque hay citas programadas.","error");
                                 $("#tipoAgenda").val($("#tipoOculto").val());
                            }else{
                                swal("","Ocurrio un error aliminar el horario,consulte al administrador del servicio.","error");
                            }
                      }
                });    
              }else{
                  $("#tipoAgenda").val($("#tipoOculto").val());
              }
       });
        
    }
    
    
 }

/*
 * Muestra el formulario para agregar horario
 * Fecha: 16-12-2016
 * Autor: OT
 */
function nuevoHorario(){
    if($("#tipoAgenda").val()=="0"){
        swal("","Seleccione el tipo de agenda.","warning");
        return;
    }
    
        $("#divModal").dialog({
            autoOpen: false,
            title: "Agregar horario",
            modal:true,
            width: 450,
            height: 350,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{"tipo":$("#tipoAgenda").val()},
                   url:"/prestadorServicios/agregarHorarioPrestador/",
                   success:function(respuesta){
                       $("#divModal").html(respuesta);
                          $('#hora1').timepicker();
                          $('#hora1').val("");
                          $('#hora2').timepicker();
                          $('#hora2').val("");

                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divModal").dialog('open');
}


/*
 * Guarda el horario
 * Fecha: 16-12-2016
 * Autor: OT
 */
function agregarHorarioTabla(){
    var hora1=$("#hora1").val();
    var hora2=$("#hora2").val();
    var limite=$("#limite").val();
    var tipoAgenda=$("#tipoAgenda").val();
    
    
    if(tipoAgenda=="1"){
        if(hora1.replace(/\s/g,"")==""){
            swal("","Ingrese la hora inicial.","warning");
            return;
        }
        
        if(limite.replace(/\s/g,"")==""){
            swal("","Ingrese el límite de clientes.","warning");
            return;
        }
    }else{
        if(hora1.replace(/\s/g,"")==""){
            swal("","Ingrese la hora inicial.","warning");
            return;
        }
        
        if(hora2.replace(/\s/g,"")==""){
            swal("","Ingrese la hora final.","warning");
            return;
        }
        
    }
        
        waitingDialog();
        $.ajax({
               type:"GET",
               data:{"hora1":hora1,'limite':limite,"hora2":hora2,'tipoOculto':tipoAgenda},
               url:"/prestadorServicios/guardarHorario/",
               success:function(respuesta){
                   setTimeout(function(){closeWaitingDialog();},100);
                   if(respuesta=="ok"){
                       setTimeout(function(){$('#divModal').dialog('close');},100);
                       swal("","Horario agregado correctamente.","success");
                       cargarHorario();
                   }else if(respuesta=="diferecia30min1" || respuesta=="diferecia30min2"){
                       swal("","Existe un rango con menos de 30 minutos de diferencia, favor de verificar.","error");
                   }else if(respuesta=="iniciomayorafin"){
                       swal("","La hora inicio debe ser menor a la hora final, favor de verificar.","error");
                   }else if(respuesta=="diferencia30min3"){
                       swal("","La diferencia entre la hora inicial y la hora final debe ser de 30 minutos.","error");
                   }else if(respuesta=="iniciodentroderango"){
                       swal("","La hora inicial esta dentro de otro horario, favor de verificar.","error");
                   }else if(respuesta=="findentroderango"){
                       swal("","La hora final esta dentro de otro horario, favor de verificar.","error");
                   }else if(respuesta=="rangodentrodeotrorango"){
                       swal("","Existe un rango en conflicto, verifique las datos que esta ingresando.","error");
                   }else{
                       swal("","Ocurrio un error al guardar los precios,consulte al administrador del servicio.","error");
                   }
               }
           });
    
    
    
}


/* 
  * Elimina el horario
  * Autor: OT
  * Fecha: 16-12-2016
*/
    
 function eliminarHorario(id) {
     swal({
          title: "",   
          text: "Seguro de eliminar el horario",
          type: "warning",   
          showCancelButton: true,   
          confirmButtonColor: "#47A447",
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar", 
          closeOnConfirm: true,   
          closeOnCancel: true
          }, 
          function(isConfirm){   
             if (isConfirm) {
                 waitingDialog();
                 $.ajax({
                     type:"GET",
                     data:{"idDiario":id},
                     url:"/prestadorServicios/eliminarHorario/",
                     success:function(respuesta){
                            setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                swal("","Horario eliminado correctamente.","success");
                                cargarHorario();
                            }else if(respuesta=="existecita"){
                                 swal("","El horario no puede eliminarse porque hay citas programadas.","error");
                            }else{
                                swal("","Ocurrio un error aliminar el horario,consulte al administrador del servicio.","error");
                            }
                      }
                });      
              }
       });
  }
    

/* 
  * Carga el horario en el div
  * Autor: OT
  * Fecha: 16-12-2016
*/
    
 function cargarHorario() {
     setTimeout(function(){waitingDialog();},100);
        $.ajax({
               type:"GET",
               url:"/prestadorServicios/cargarHorario/",
               success:function(respuesta){
                   setTimeout(function(){closeWaitingDialog();},100);
                   $("#tablaHorario").html(respuesta);
               }
           });
 }    
 
 /*
 * Muestra el formulario para cambiar el tiempo de servicio
 * Fecha: 19-12-2016
 * Autor: OT
 */
function cambiarTiempo(){
        $("#divModal").dialog({
            autoOpen: false,
            title: "Modificar tiempo del servicio",
            modal:true,
            width: 450,
            height: 250,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/prestadorServicios/formularioCambioTiempo/",
                   success:function(respuesta){
                       $("#divModal").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divModal").dialog('open');
}


/*
 * Guarda el tiempo del servicio
 * Fecha: 19-12-2016
 * Autor: OT
 */
function guardarTiempoServicio(){
    var tiemposervicio =$("#vtiempoServicio").val();
    if(tiemposervicio .replace(/\s/g,"")==""){
        swal("","Ingrese el tiempo del servicio.","warning");
        return;
    }
        
    if(tiemposervicio=="" ||  !/^([0-9])*.([0-9])*$/.test(tiemposervicio) || !parseFloat(tiemposervicio) || parseFloat(tiemposervicio)<0  || isNaN(tiemposervicio)){
       swal("","El tiempo del servicio no es válido.","warning");
       return;
    }
    
     if (tiemposervicio % 1 != 0) {
        swal("","El tiempo de duración debe ser minutos cerrados","warning");
        return;
      }
    
    if(parseFloat(tiemposervicio)<10){
       swal("","El tiempo debe ser mayor o igual a 10 minutos","warning");
       return;
    }
    
     waitingDialog();
    $.ajax({
        type:"GET",
        data:{'tiemposervicio':tiemposervicio},
        url:"/prestadorServicios/guardarTiempoServicio/",
        success:function(respuesta){
            setTimeout(function(){closeWaitingDialog();},100);
           if(respuesta=="ok"){
               setTimeout(function(){$('#divModal').dialog('close');},100);
               $('#tiemposervicio').val(tiemposervicio);
               setTimeout(function(){swal("","Proceso realizado correctamente.","success");},100);
           }else if(respuesta=="existecita"){
               swal("","El tiempo del servicio no puede modificarse porque hay citas programadas.","error");
           }else{
               swal("","Ocurrio un error en el proceso, consulte al administrador del servicio.","error");
           }
           
        }
   });
}