/* 
 * script para los vehiculos del transportista
 */
var oTable;

$(function() {
    

   oTable= $('#listaTablaVehiculosTransportista').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/transportista/listaVehiculosTransportista',
            data: function (d) {
                
                var buscaTitulo=$("#buscaTitulo").val();
                var asignado=false;
                var sinAsignar=false;
                var activo=false;
                var inactivo=false;
                
                if($('#asignado').is(':checked')){
                    asignado=true;
                }

                if($('#sinAsignar').is(':checked')){
                    sinAsignar=true;
                }
                
                if($('#activo').is(':checked')){
                    activo=true;
                }

                if($('#inactivo').is(':checked')){
                    inactivo=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.asignado = asignado;
                d.sinAsignar = sinAsignar;
                d.activo = activo;
                d.inactivo = inactivo;
               
            }
        },
        columns: [
            {data: 'placa', name: 'placa',orderable: true, searchable: false},
            {data: 'tipo', name: 'tipo',orderable: true, searchable: false},
            {data: 'chofer', name: 'chofer',orderable: true, searchable: false},
            {data: 'name', name: 'name',orderable: true, searchable: false},
            {data: 'acciones', name: 'acciones',orderable: false, searchable: false},
        ],
        
       language:{
            "decimal":        "",
            "emptyTable":     "<center><h4><b>Ningún resultado encontrado.</b></h4><h5 class='text-muted'>Revisa tu búsqueda e inténtalo de nuevo.</h5></center>",
            "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "_MENU_",
            "loadingRecords": "Cargando...",
            "processing":     "<h4>Procesando...</h4>",
            "search":         "",
            "zeroRecords":    "No se encontraron resultados",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "",
                "previous":   ""
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
    
    
 

 });
 

    /*
     * Busca los vehiculos de trasportista
     * 
   */
   function buscarVehiculo(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosVehiculos(){
         $("#buscaTitulo").val("");
         $('#asignado, #sinAsignar, #activo,#inactivo').iCheck('uncheck');
         buscarVehiculo();
     }
     
     
    /*
    * Muestra el formulario para agregar un vehiculo
    * Autor: OT
    * Fecha: 26-09-2016
    * 
    */
   function nuevoVehiculo(){
       $("#divElmentosListaVehiculos").dialog({
            autoOpen: false,
            title: "Nuevo vehículo",
            modal:true,
            width: 650,
            height: 380,
            close: function(event,ui){
                 $("#divElmentosListaVehiculos").html('');
                 $("#divElmentosListaVehiculos").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/transportista/nuevoVehiculo/",
                   success:function(respuesta){
                       $("#divElmentosListaVehiculos").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#idTipo").focus();
                   }
                });
            }
	});
	$("#divElmentosListaVehiculos").dialog('open');
   }
   
    
    
    /*
    * Guardar datos del vehiculo
    * Autor: OT
    * Fecha: 26-09-2016
    * 
    */
   function guardarVehiculoTransportista(){
       var token=$("#token").val();
       var idTipo = $("#idTipo").val();
       var idChofer=$("#idChofer").val();
       var descripcion=$("#descripcion").val();
       var placa=$("#placa").val();
    
       if(idTipo=="0"){
           swal("","Seleccione el tipo de vehículo","warning");
           return;
       }
       
       if(placa.replace(/\s/g,"")==""){
           swal("","Escriba la placa del vehículo","warning");
           return;
       }
       
       if(descripcion.replace(/\s/g,"")==""){
           swal("","Escriba la descripcion del vehículo","warning");
           return;
       }
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'idTipo':idTipo,'idChofer':idChofer,'descripcion':descripcion,'placa':placa},
            url:"/transportista/guardarVehiculo",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosListaVehiculos').dialog('close');
                   buscarVehiculo();
                   setTimeout(function(){swal("","Vehículo agregado correctamente","success");},100);
               }else if(respuesta=="placarepetida"){
                   setTimeout(function(){swal("","La placa ingresada ya existe, favor de verificar","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
   
   /*
    * Muestra el formulario para editar al vehiculo seleccionado
    * Autor: OT
    * Fecha: 26-09-2016
    * 
    */
   function editarVehiculo(idVehiculo){
       $("#divElmentosListaVehiculos").dialog({
            autoOpen: false,
            title: "Editar vehículo",
            modal:true,
            width: 650,
            height: 380,
            close: function(event,ui){
                 $("#divElmentosListaVehiculos").html('');
                 $("#divElmentosListaVehiculos").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'idVehiculo':idVehiculo},
                   url:"/transportista/editarVehiculo/",
                   success:function(respuesta){
                       $("#divElmentosListaVehiculos").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#idTipo").focus();
                   }
                });
            }
	});
	$("#divElmentosListaVehiculos").dialog('open');
   }
   
   /*
    * Actualizar datos del vehiculo
    * Autor: OT
    * Fecha: 26-09-2016
    * 
    */
   function guardarCambiosVehiculoTransportista(){
       var token=$("#token").val();
       var idVehiculo = $("#idVehiculo").val();
       var idTipo = $("#idTipo").val();
       var idChofer=$("#idChofer").val();
       var descripcion=$("#descripcion").val();
       var placa=$("#placa").val();
    
       if(idTipo=="0"){
           swal("","Seleccione el tipo de vehículo","warning");
           return;
       }
       
       if(placa.replace(/\s/g,"")==""){
           swal("","Escriba la placa del vehículo","warning");
           return;
       }
       
       if(descripcion.replace(/\s/g,"")==""){
           swal("","Escriba la descripcion del vehículo","warning");
           return;
       }
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'idVehiculo':idVehiculo,'idTipo':idTipo,'idChofer':idChofer,'descripcion':descripcion,'placa':placa},
            url:"/transportista/actualizarVehiculo",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosListaVehiculos').dialog('close');
                   buscarVehiculo();
                   setTimeout(function(){swal("","Vehículo actualizado correctamente","success");},100);
               }else if(respuesta=="placarepetida"){
                   setTimeout(function(){swal("","La placa ingresada ya existe, favor de verificar","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
    
    /*
    * Muestra el formulario para cambiar la contraseña del chofer
    * Autor: OT
    * Fecha: 26-09-2016
    * 
    */
   function cambiarPassChofer(idChofer){
       $("#divElmentosListaChofer").dialog({
            autoOpen: false,
            title: "Cambiar contraseña del chofer",
            modal:true,
            width: 400,
            height: 250,
            close: function(event,ui){
                 $("#divElmentosListaChofer").html('');
                 $("#divElmentosListaChofer").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   data:{'idChofer':idChofer},
                   type:"GET",
                   url:"/transportista/cambiarPassChofer/",
                   success:function(respuesta){
                       $("#divElmentosListaChofer").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#nuevoPass").focus();
                   }
                });
            }
	});
	$("#divElmentosListaChofer").dialog('open');
   }
   
   
   /*
    * Guardar nuevo pass del chofer
    * Autor: OT
    * Fecha: 26-09-2016
    * 
    */
   function guardarNuevoPassChofer(){
       var token=$("#token").val();
       var idChofer = $("#idChofer").val();
       var nuevoPass=$("#nuevoPass").val();

       nuevoPass=nuevoPass.replace(/\s/g,"");
       var longitudPass= nuevoPass.length;
       if(parseFloat(longitudPass)<8){
          swal("","La contraseña debe tener al menos 8 caracteres sin espacios.","warning");
          return false;
        }
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'idChofer':idChofer,'nuevoPass':nuevoPass},
            url:"/transportista/actualizarPassChofer",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosListaChofer').dialog('close');
                   setTimeout(function(){swal("","Contraseña actualizada correctamente","success");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
   
   /*
    * Eliminar chofer
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function eliminarVehiculo(idVehiculo){
       swal({
           title: "",   
           text: "¿Seguro de eliminar el vehículo?",
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
                    $.ajax({
                         type:"GET",
                         data:{'idVehiculo':idVehiculo},
                         url:"/transportista/eliminarVehiculo2",
                         success:function(respuesta){
                             setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                buscarVehiculo();
                                setTimeout(function(){swal("","Vehículo eliminado correctamente","success");},100);
                            }else{
                                setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
                            }
                         }
                    });   
                }
           });
   }
   

/*
    * Activar o desactivar el vehiculo
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function activarDesactivarVehiculo(idVehiculo,opcion){
       var titulo,mensaje="";
       
       if(opcion==1){
           titulo="¿Seguro de desactivar el vehículo?";
           mensaje="Vehículo desactivado correctamente";
       }else{
           titulo="¿Seguro de activar el vehículo?";
           mensaje="Vehículo activado correctamente";
       }
       swal({
           title: "",   
           text: titulo,
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
                    $.ajax({
                         type:"GET",
                         data:{'idVehiculo':idVehiculo,'opcion':opcion},
                         url:"/transportista/activarDesactivarVehiculo",
                         success:function(respuesta){
                             setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                buscarVehiculo();
                                setTimeout(function(){swal("",mensaje,"success");},100);
                            }else{
                                setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
                            }
                         }
                    });   
                }
           });
   }
   