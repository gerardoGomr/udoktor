/*
 * script la configuracion de paises y estados del adminitrador
 */


$(function() {
    obtenerListaPaises();
});


/*
 * Obtener Paises
 * Autor: OT
 * Fecha 25-07-2016
 * 
 */
function obtenerListaPaises(){
     waitingDialog();
       $.ajax({
            dataType:"JSON",
            type:"GET",
            url:"/admin/obtenerPaisesAdmin",
            success:function(respuesta){
                $("#listaPaises").html(respuesta.divPaises);
                $("#cadenaEstados").val(respuesta.cadEstados);
                $("#divBotonesEstado").attr("style","display: none");
                $("#listaEstados").attr("style","display: none");

                $("#divBotonesPais").removeAttr("style");
                $("#listaPaises").removeAttr("style");
                setTimeout(function(){closeWaitingDialog();},100);
            }
       });
}

/*
 * Reedirige a resumen admin
 * Autor: OT
 * Fecha 25-07-2016
 * 
 */
function irAresumenAdmin(){
    waitingDialog();
    location.href="/admin";
}

/*
 * Muestra los estodos de los paises seleccionados
 * Autor: OT
 * Fecha 25-07-2016
 * 
 */
function irTabEstados(){
    
    var cadenap="";
    var cp=0;
    var chPais = document.getElementsByName("chPais");
    var i;
    for (i = 0; i < chPais.length; i++) {
      if(chPais[i].checked==true){
         cadenap=cadenap+chPais[i].id+',';
         cp=1;
      }
    }
    
    if(cp==0){
         swal("","Seleccione por lo menos un país.","warning");
    }else{
         $("#back-to-top").click();
         $("#cadenaPaises").val(cadenap);
         $("#divBotonesPais").attr("style","display: none");
         $("#listaPaises").attr("style","display: none");

         $("#divBotonesEstado").removeAttr("style");
         $("#listaEstados").removeAttr("style");
         obtenerListaEstados();
    }
    
    
    //waitingDialog();
    //setTimeout(function(){closeWaitingDialog();},100);
}

/*
 * Muestra los paises, volviendo de estados
 * Autor: OT
 * Fecha 25-07-2016
 * 
 */
function irTabPaises(){
    $("#divBotonesEstado").attr("style","display: none");
    $("#listaEstados").attr("style","display: none");
    
    $("#divBotonesPais").removeAttr("style");
    $("#listaPaises").removeAttr("style");
    
    $("#back-to-top").click();
    
    var cadenae="";
    var chEstado = document.getElementsByName("chEstado");
    var i;
    for (i = 0; i < chEstado.length; i++) {
       if(chEstado[i].checked==true){
           cadenae=cadenae+chEstado[i].id+',';
       }
    }
    $("#cadenaEstados").val(cadenae);
    
    
    
}
   
    
/*
    * Carga los estados de los paises seleccionados
    * Autor: OT
    * Fecha: 25-07-2016
    * 
    */
   function obtenerListaEstados(){
       var token=$("#token").val();
       var cadenaPaises=$("#cadenaPaises").val();
       var cadenaEstados=$("#cadenaEstados").val();
       
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'cadenaPaises':cadenaPaises,'cadenaEstados':cadenaEstados},
            url:"/admin/obtenerEstadosAdmin",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="error"){
                    swal("","Ocurrio un error al obtener los estados.","error");
                }else{
                    $("#listaEstados").html(respuesta);
                }
                
            }
       });
   }
  
  /*
    * Seleccionar todos los estado del pais recibido
    * Autor: OT
    * Fecha: 25-07-2016
    * 
    */
   function seleccionarTodo(idPais){
       var chEstado = document.getElementsByName("chEstado");
       var paisSeleccionado=document.getElementById(idPais);
            var i;
            for (i = 0; i < chEstado.length; i++) {
                if(chEstado[i].value==paisSeleccionado.value){
                   
                   chEstado[i].checked=paisSeleccionado.checked;
                }
            }
   }
   
   /*
    * Activar / desactivar estados y paises
    * Autor: OT
    * Fecha: 15-07-2016
    * 
    */
   function actualizarPaisesEstados(){
       var token=$("#token").val();
       var contadorEstados=0;
       
       var cadenaEstados="";
       var chEstado = document.getElementsByName("chEstado");
       for (var i = 0; i < chEstado.length; i++) {
           if(chEstado[i].checked==true){
               cadenaEstados=cadenaEstados+chEstado[i].id+',';
               contadorEstados++;
            }
        }
        
        if(contadorEstados==0){
            swal("","Seleccione al menos un Estado.","warning");
            return;
        }
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'cadenaEstados':cadenaEstados},
            url:"/admin/activarDesactivarPaisesEstados",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    setTimeout(function(){swal("","Proceso realizado correctamente.","success");},100);
                    setTimeout(function(){obtenerListaPaises();},100);

                    //$('#back-to-top').click();
                }else{
                    swal("","Error al realizar el proceso.","error");
                }
                
            }
       });
   }
   
/*
   * Muestra el formulario para crear un pais
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function nuevoPais(){
        
        $("#nuevoElemento").dialog({
		autoOpen: false,
		title: "Agregar país",
		modal:true,
		width: 400,
		height: 340,
		close: function(event,ui){
                    $("#nuevoElemento").html('');
                    $("#nuevoElemento").dialog('destroy');
		},
		open:function(event,ui){
                    $.ajax({
                        type:"GET",
			url:"/admin/nuevoPais",
			success:function(respuesta){
                            $("#nuevoElemento").html(respuesta);
                            $("#nombreCorto").focus();
                        }
                    });

		}
	});
	$("#nuevoElemento").dialog('open');
    }
    
   /*
   * Guardar Pais
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function guardarPais(){
        var token=$("#token").val();
        var nombreCorto= $("#nombreCorto").val();
        var nombreLargo= $("#nombreLargo").val();
       
       if(nombreCorto.replace(/\s/g,"")==""){
                swal("","Ingrese el nombre corto.","warning");
                return;
        }
       if(nombreLargo.replace(/\s/g,"")==""){
                swal("","Ingrese el nombre.","warning");
                return;
            }
            
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'nombreCorto':nombreCorto,'nombreLargo':nombreLargo},
            url:"/admin/guardarPais",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    setTimeout(function(){swal("","País guardado correctamente.","success");},100);
                    $('#nuevoElemento').dialog('close');
                    setTimeout(function(){obtenerListaPaises();},100);
                    $('#back-to-top').click();
                }else if(respuesta=="1"){
                    swal("","El nombre ya existe, intente con otro.","error");
                }else if(respuesta=="2"){
                    swal("","El nombre corto ya existe, intente con otro.","error");
                }else{
                    swal("","Error al realizar el proceso.","error");
                }
            }
       });
        
    }
    
    /*
   * Muestra el formulario para crear un Estado
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function nuevoEstado(){
        
        $("#nuevoElemento").dialog({
		autoOpen: false,
		title: "Agregar Departamento",
		modal:true,
		width: 400,
		height: 340,
		close: function(event,ui){
                    $("#nuevoElemento").html('');
                    $("#nuevoElemento").dialog('destroy');
		},
		open:function(event,ui){
                    $.ajax({
                        type:"GET",
			url:"/admin/nuevoEstado",
			success:function(respuesta){
                            $("#nuevoElemento").html(respuesta);
                            $("#idPais").select2();
                            
                        }
                    });

		}
	});
	$("#nuevoElemento").dialog('open');
    }

   /*
   * Guardar Estado
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function guardarEstado(){
        var token=$("#token").val();
        var idPais= $("#idPais").val();
        var nombreEstado= $("#nombreEstado").val();
        var activo=0;
       if(idPais=="0"){
           swal("","Seleccione un país.","warning");
           return;
        }
        
       if(nombreEstado.replace(/\s/g,"")==""){
           swal("","Ingrese el nombre del Departamento.","warning");
           return;
        }
        
        if ($("#estadoActivo").is(":checked")) {
         activo=1;
     }
            
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'idPais':idPais,'nombreEstado':nombreEstado,'activo':activo},
            url:"/admin/guardarEstado",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    setTimeout(function(){swal("","Departamento guardado correctamente.","success");},100);
                    $('#nuevoElemento').dialog('close');
                    setTimeout(function(){obtenerListaPaises();},100);
                    $('#back-to-top').click();
                }else if(respuesta=="1"){
                    swal("","El Departamento ya existe en el país seleccionado, intente con otro.","error");
                }else{
                    swal("","Error al realizar el proceso.","error");
                }
            }
       });
        
    }
   
   /*
   * Muestra el formulario para editar el nombre de un pais
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function editarPais(){
        
        $("#nuevoElemento").dialog({
		autoOpen: false,
		title: "Editar país",
		modal:true,
		width: 400,
		height: 400,
		close: function(event,ui){
                    $("#nuevoElemento").html('');
                    $("#nuevoElemento").dialog('destroy');
		},
		open:function(event,ui){
                    $.ajax({
                        type:"GET",
			url:"/admin/editarPais",
			success:function(respuesta){
                            $("#nuevoElemento").html(respuesta);
                            $("#idPais").select2();
                        }
                    });

		}
	});
	$("#nuevoElemento").dialog('open');
    }
    
    /*
    * Obtiene los datos del pais seleccioado
    * Autor: OT
    * Fecha: 20-05-2016
    * 
    */
   function obtenerDatosPais(idPais){
       if(idPais==0){
           
           $("#nombreCorto").val("");
           $("#nombreLargo").val("");
           $("#nombreCorto").attr("disabled","disabled");
           $("#nombreLargo").attr("disabled","disabled");
       }else{
            waitingDialog(); 
             $.ajax({
                  dataType:"JSON",
                  type:"GET",
                  data:{'idPais':idPais},
                  url:"/admin/obtenerDatosPais",
                  success:function(respuesta){
                      setTimeout(function(){closeWaitingDialog();},100);
                      $("#nombreCorto").val(respuesta.nombreCorto);
                      $("#nombreLargo").val(respuesta.nombre);

                      $("#nombreCorto").removeAttr("disabled");
                      $("#nombreLargo").removeAttr("disabled");
                      $("#nombreCorto").focus();
                  }
                });   
            }
   }

/*
   * Guarda cambios pais
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function guardarCambiosPais(){
        var token=$("#token").val();
        var idPais= $("#idPais").val();        
        var nombreCorto= $("#nombreCorto").val();
        var nombreLargo= $("#nombreLargo").val();
       
       if(idPais==0){
           swal("","Seleccione un pais.","warning");
           return;
        }
       
       if(nombreCorto.replace(/\s/g,"")==""){
           swal("","Ingrese el nombre corto.","warning");
           return;
        }
        
       if(nombreLargo.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre.","warning");
          return;
        }
            
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'idPais':idPais,'nombreCorto':nombreCorto,'nombreLargo':nombreLargo},
            url:"/admin/actualizarPais",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    setTimeout(function(){swal("","País actualizado correctamente.","success");},100);
                    $('#nuevoElemento').dialog('close');
                    setTimeout(function(){obtenerListaPaises();},100);
                    $('#back-to-top').click();
                }else if(respuesta=="1"){
                    swal("","El nombre ya existe, intente con otro.","error");
                }else if(respuesta=="2"){
                    swal("","El nombre corto ya existe, intente con otro.","error");
                }else{
                    swal("","Error al realizar el proceso.","error");
                }
            }
       });
           
    }
    
   /*
   * Muestra el formulario para editar los datos del estado
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function editarEstado(){
        
        $("#nuevoElemento").dialog({
		autoOpen: false,
		title: "Editar Departamento",
		modal:true,
		width: 400,
		height: 420,
		close: function(event,ui){
                    $("#nuevoElemento").html('');
                    $("#nuevoElemento").dialog('destroy');
		},
		open:function(event,ui){
                    $.ajax({
                        type:"GET",
			url:"/admin/editarEstado",
			success:function(respuesta){
                            $("#nuevoElemento").html(respuesta);
                            $("#idPais").select2();
                        }
                    });

		}
	});
	$("#nuevoElemento").dialog('open');
    }
    
    /*
    * Obtiene la lista de estados
    * Autor: OT
    * Fecha: 26-07-2016
    * 
    */
   function obtenerEstadosPais(idPais){
       if(idPais==0){
           $("#idEstado").html("<option value='0'>Seleccione</option>");
           $("#nombreEstado").val("");
           $("#nombreEstado").attr("disabled","disabled");
           $('#estadoActivo').iCheck('uncheck');
       }else{
           waitingDialog(); 
            $.ajax({
                 type:"GET",
                 data:{'idPais':idPais},
                 url:"/admin/buscarEstadosPais",
                 success:function(respuesta){
                     setTimeout(function(){closeWaitingDialog();},100);
                     $("#idEstado").html(respuesta);
                     $("#idEstado").select2();
                     $("#nombreEstado").val("");
                     $("#nombreEstado").attr("disabled","disabled");
                     $('#estadoActivo').iCheck('uncheck');
                 }
               });   
       }   
   }
   
   /*
    * Obtiene los datos del estado seleccionado
    * Autor: OT
    * Fecha: 26-07-2016
    * 
    */
   function obtenerDatosEstado(idEstado){
       if(idEstado==0){
           
           $("#nombreEstado").val("");
           $("#nombreEstado").attr("disabled","disabled");
           $('#estadoActivo').iCheck('uncheck');
       }else{
            waitingDialog(); 
             $.ajax({
                  dataType:"JSON",
                  type:"GET",
                  data:{'idEstado':idEstado},
                  url:"/admin/obtenerDatosEstado",
                  success:function(respuesta){
                      setTimeout(function(){closeWaitingDialog();},100);
                      $("#nombreEstado").val(respuesta.nombre);
                      $("#nombreEstado").removeAttr("disabled");
                      $("#nombreEstado").focus();
                      if(respuesta.activo==1){
                          $('#estadoActivo').iCheck('check');
                      }else{
                          $('#estadoActivo').iCheck('uncheck');
                      }
                  }
                });   
            }
   }


   /*
   * Actualizar Estado
   * Auto: OT
   * Fecha : 26-07-2016
   */  
    function actualizarEstado(){
        var token=$("#token").val();
        var idPais= $("#idPais").val();
        var idEstado= $("#idEstado").val();
        var nombreEstado= $("#nombreEstado").val();
        var activo=0;
        
       if(idPais=="0"){
           swal("","Seleccione un País.","warning");
           return;
        }
        
        if(idEstado=="0"){
           swal("","Seleccione un Departamento.","warning");
           return;
        }
        
       if(nombreEstado.replace(/\s/g,"")==""){
           swal("","Ingrese el nombre del Departamento.","warning");
           return;
        }
        
        if ($("#estadoActivo").is(":checked")) {
         activo=1;
     }
            
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
             data:{'idPais':idPais,'idEstado':idEstado,'nombreEstado':nombreEstado,'activo':activo},
            url:"/admin/actualizarEstado",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta=="ok"){
                    setTimeout(function(){swal("","Departamento actualizado correctamente.","success");},100);
                    $('#nuevoElemento').dialog('close');
                    setTimeout(function(){obtenerListaPaises();},100);
                    $('#back-to-top').click();
                }else if(respuesta=="1"){
                    swal("","El Departamento ya existe en el país seleccionado, intente con otro.","error");
                }else{
                    swal("","Error al realizar el proceso.","error");
                }
            }
       });
        
    }