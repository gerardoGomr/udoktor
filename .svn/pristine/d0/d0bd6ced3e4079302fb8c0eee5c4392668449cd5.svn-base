/* 
 * script para choferes del transportista
 */
var oTable;

$(function() {
    

   oTable= $('#listaTablaChoferesTransportista').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/transportista/listaChoferesTransportista',
            data: function (d) {
                
                var buscaTitulo=$("#buscaTitulo").val();
                var dniFiltro=$("#dniFiltro").val();
                var licenciaFiltro=$("#licenciaFiltro").val();
                d.buscaTitulo = buscaTitulo;
                d.dniFiltro=dniFiltro;
                d.licenciaFiltro=licenciaFiltro;
               
            }
        },
        columns: [
            {data: 'name', name: 'name',orderable: true, searchable: false},
            {data: 'dni', name: 'dni',orderable: true, searchable: false},
            {data: 'licencia', name: 'licencia',orderable: true, searchable: false},
            {data: 'usuario', name: 'usuario',orderable: true, searchable: false},
            {data: 'telefono', name: 'telefono',orderable: true, searchable: false},
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
     * Busca los choferes
     * 
   */
   function buscarChoferes(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosChoferes(){
         $("#buscaTitulo").val("");
         $("#dniFiltro").val("");
         $("#licenciaFiltro").val("");
         buscarChoferes();
     }
     
     
    /*
    * Muestra el formulario para agregar un chofer
    * Autor: OT
    * Fecha: 23-09-2016
    * 
    */
   function nuevoChofer(){
       $("#divElmentosListaChofer").dialog({
            autoOpen: false,
            title: "Nuevo chofer",
            modal:true,
            width: 650,
            height: 600,
            close: function(event,ui){
                 $("#divElmentosListaChofer").html('');
                 $("#divElmentosListaChofer").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/transportista/nuevoChofer/",
                   success:function(respuesta){
                       $("#divElmentosListaChofer").html(respuesta);
                       $("#divElmentosListaChofer").focus();
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#primerNombre").focus();
                   }
                });
            }
	});
	$("#divElmentosListaChofer").dialog('open');
   }
   
   /*
    * Genera el nombre de usuario del chofer apartir de la compania y DNI
    * Autor: OT
    * Fecha: 23-09-2016
    * 
    */
    function generarUsuario(){
        var clave=$("#claveCompany").val();
        var dni=$("#dniChofer").val();
        dni=dni.replace(/[^a-zA-Z 0-9]+/g,'');
        $("#usuarioChofer").val(clave+dni+"@efletex.com");
    }
    
    
    /*
    * Guardar chofer
    * Autor: OT
    * Fecha: 23-09-2016
    * 
    */
   function guardarChoferTransportista(){
       var token=$("#token").val();
       var primerNombre = $("#primerNombre").val();
       var segundoNombre=$("#segundoNombre").val();
       var primerApellido=$("#primerApellido").val();
       var segundoApellido=$("#segundoApellido").val();
       var dniChofer=$("#dniChofer").val();
       var telefonoChofer=$("#telefonoChofer").val();
       var usuarioChofer=$("#usuarioChofer").val();
       var passChofer=$("#passChofer").val();
       
       var licenciaChofer=$("#licenciaChofer").val();
    
       if(primerNombre.replace(/\s/g,"")==""){
           swal("","Escriba el primer nombre del chofer","warning");
           return;
       }
       
       if(primerApellido.replace(/\s/g,"")==""){
           swal("","Escriba el primer apellido del chofer","warning");
           return;
       }
       
       if(dniChofer.replace(/\s/g,"")==""){
           swal("","Escriba el DNI del chofer","warning");
           return;
       }
       
       if(licenciaChofer.replace(/\s/g,"")==""){
           swal("","Escriba la licencia de conducir del chofer","warning");
           return;
       }
       
       if(telefonoChofer.replace(/\s/g,"")!=""){
           if (!/^([0-9])*$/.test(telefonoChofer)){
                swal("","El teléfono solo debe contener números","warning");
                return;
           }
       }
       
       
       if(passChofer.replace(/\s/g,"")==""){
           swal("","Escriba la contraseña del chofer","warning");
           return;
       }
       
       if(usuarioChofer.replace(/\s/g,"")==""){
           swal("","Escriba el nombre de usuario del chofer","warning");
           return;
       }
       
    passChofer=passChofer.replace(/\s/g,"");
    
    var longitudPass= passChofer.length;
    
    if(parseFloat(longitudPass)<8){
      swal("","La contraseña debe tener al menos 8 caracteres sin espacios.","warning");
      return false;
    }
       
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'primerNombre':primerNombre,'segundoNombre':segundoNombre,'primerApellido':primerApellido,
            'segundoApellido':segundoApellido,'dniChofer':dniChofer,'telefonoChofer':telefonoChofer,
            'usuarioChofer':usuarioChofer,'passChofer':passChofer,'licenciaChofer':licenciaChofer},
            url:"/transportista/guardarChofer",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosListaChofer').dialog('close');
                   buscarChoferes();
                   setTimeout(function(){swal("","Chofer agregado correctamente","success");},100);
               }else if(respuesta=="dnirepetido"){
                   setTimeout(function(){swal("","El DNI ingresado ya existe, favor de verificar","error");},100);
               }else if(respuesta="licenciarepetida"){
                   setTimeout(function(){swal("","La licencia de conducir ingresada ya existe, favor de verificar","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
   
   /*
    * Muestra el formulario para editar al chofer seleccionado
    * Autor: OT
    * Fecha: 23-09-2016
    * 
    */
   function editarChofer(idChofer){
       $("#divElmentosListaChofer").dialog({
            autoOpen: false,
            title: "Editar chofer",
            modal:true,
            width: 650,
            height: 500,
            close: function(event,ui){
                 $("#divElmentosListaChofer").html('');
                 $("#divElmentosListaChofer").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'idChofer':idChofer},
                   url:"/transportista/editarChofer/",
                   success:function(respuesta){
                       $("#divElmentosListaChofer").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#primerNombre").focus();
                   }
                });
            }
	});
	$("#divElmentosListaChofer").dialog('open');
   }
   
   /*
    * Actualizar datos del chofer
    * Autor: OT
    * Fecha: 24-09-2016
    * 
    */
   function guardarCambiosChoferTransportista(){
       var token=$("#token").val();
       var primerNombre = $("#primerNombre").val();
       var segundoNombre=$("#segundoNombre").val();
       var primerApellido=$("#primerApellido").val();
       var segundoApellido=$("#segundoApellido").val();
       var telefonoChofer=$("#telefonoChofer").val();
       var licenciaChofer=$("#licenciaChofer").val();
       
       var idChofer=$("#idChofer").val();

    
       if(primerNombre.replace(/\s/g,"")==""){
           swal("","Escriba el primer nombre del chofer","warning");
           return;
       }
       
       if(primerApellido.replace(/\s/g,"")==""){
           swal("","Escriba el primer apellido del chofer","warning");
           return;
       }
       
       if(licenciaChofer.replace(/\s/g,"")==""){
           swal("","Escriba la licencia de conducir del chofer","warning");
           return;
       }
       
       if(telefonoChofer.replace(/\s/g,"")!=""){
           if (!/^([0-9])*$/.test(telefonoChofer)){
                swal("","El teléfono solo debe contener números","warning");
                return;
           }
       }
       
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'primerNombre':primerNombre,'segundoNombre':segundoNombre,'primerApellido':primerApellido,
            'segundoApellido':segundoApellido,'idChofer':idChofer,'telefonoChofer':telefonoChofer,'licenciaChofer':licenciaChofer},
            url:"/transportista/actualizarChofer",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosListaChofer').dialog('close');
                   buscarChoferes();
                   setTimeout(function(){swal("","Chofer actualizado correctamente","success");},100);
               }else if(respuesta=="licenciaRepetida"){
                   setTimeout(function(){swal("","La licencia de conducir ingresada ya existe, intente con otra.","error");},100);
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
    * Fecha: 26-09-2016
    * 
    */
   function eliminarChofer(idChofer){
       
       swal({
           title: "",   
           text: "¿Seguro de eliminar el chofer?",
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
                         data:{'idChofer':idChofer},
                         url:"/transportista/eliminarChofer",
                         success:function(respuesta){
                             setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                buscarChoferes();
                                setTimeout(function(){swal("","Chofer eliminado correctamente","success");},100);
                            }else if(respuesta=="errorvehiculo"){
                                setTimeout(function(){swal("","El chofer tiene un vehículo asignado.","error");},100);
                            }else{
                                setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
                            }
                         }
                    });   
                }
           });
   }
   
