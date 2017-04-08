/* 
 * script para los tipos de vehiculos del transportista
 */
var oTable;

$(function() {
    

   oTable= $('#listaTablaClasificacion').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/admin/listaClasificacion',
            data: function (d) {
                
                var buscaTitulo=$("#buscaTitulo").val();
                
                var activo=false;
                var inactivo=false;
                
                if($('#activo').is(':checked')){
                    activo=true;
                }

                if($('#inactivo').is(':checked')){
                    inactivo=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.activo = activo;
                d.inactivo = inactivo;
               
            }
        },
        columns: [
            {data: 'name', name: 'name',orderable: true, searchable: false},
            {data: 'descripcion', name: 'descripcion',orderable: true, searchable: false},
            {data: 'estado', name: 'estado',orderable: true, searchable: false},
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
     * Busca las clasificaciones
     * 
   */
   function buscarClasificacion(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosClasificacion(){
         $("#buscaTitulo").val("");
         $('#activo,#inactivo').iCheck('uncheck');
         buscarClasificacion();
     }
     
     
    /*
    * Muestra el formulario para agregar el tipo de vehiculo
    * Autor: OT
    * Fecha: 14-12-2016
    * 
    */
   function nuevoClasificacion(){
       $("#divElmentosClasificacion").dialog({
            autoOpen: false,
            title: "Nueva clasificación",
            modal:true,
            width: 500,
            height: 350,
            close: function(event,ui){
                 $("#divElmentosClasificacion").html('');
                 $("#divElmentosClasificacion").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/admin/nuevaClasificacion/",
                   success:function(respuesta){
                       $("#divElmentosClasificacion").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#nombreClasificacion").focus();
                   }
                });
            }
	});
	$("#divElmentosClasificacion").dialog('open');
   }
   
    
    
    /*
    * Guardar clasificacion
    * Autor: OT
    * Fecha: 14-12-2016
    * 
    */
   function guardarClasificacion(){
       var token=$("#token").val();
       var idClasificacion=$("#idClasificacion").val();
       var nombreClasificacion = $("#nombreClasificacion").val();
       var descripcionClasificacion = $("#descripcionClasificacion").val();
       
       if(nombreClasificacion.replace(/\s/g,"")==""){
           swal("","Escriba el nombre de la clasificación","warning");
           return;
       }
       
       if(descripcionClasificacion.replace(/\s/g,"")==""){
           swal("","Escriba la descripción de la clasificación","warning");
           return;
       }

       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'nombreClasificacion':nombreClasificacion,'descripcionClasificacion':descripcionClasificacion,'idClasificacion':idClasificacion},
            url:"/admin/guardarClasificacion",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosClasificacion').dialog('close');
                   buscarClasificacion();
                   setTimeout(function(){swal("","Proceso realizado correctamente","success");},100);
               }else if(respuesta=="existenombre"){
                   setTimeout(function(){swal("","La clasificación ingresada ya existe, favor de verificar","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
   
   /*
    * Muestra el formulario para editar la clasificacion selecccionada
    * Autor: OT
    * Fecha: 14-12-2016
    * 
    */
   function editarClasificacion(idClasificacion){
       $("#divElmentosClasificacion").dialog({
            autoOpen: false,
            title: "Editar clasifiación",
            modal:true,
            width: 500,
            height: 350,
            close: function(event,ui){
                 $("#divElmentosClasificacion").html('');
                 $("#divElmentosClasificacion").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'idClasificacion':idClasificacion},
                   url:"/admin/editarClasificacion/",
                   success:function(respuesta){
                       $("#divElmentosClasificacion").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#nombreClasificacion").focus();
                   }
                });
            }
	});
	$("#divElmentosClasificacion").dialog('open');
   }
   
   
 /*
    * Activar o desactivar el tipo de vehiculo
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function activarDesactivarClasificacion(idClasificacion,opcion){
       var titulo,mensaje="";
       
       if(opcion==1){
           titulo="¿Seguro de desactivar la clasificación?";
           mensaje="Clasificación desactivada correctamente";
       }else{
           titulo="¿Seguro de activar la clasifiación?";
           mensaje="Clasificación activada correctamente";
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
                         data:{'idClasificacion':idClasificacion,'opcion':opcion},
                         url:"/admin/activarDesactivarClasificacion",
                         success:function(respuesta){
                             setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                buscarClasificacion();
                                setTimeout(function(){swal("",mensaje,"success");},100);
                            }else{
                                setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
                            }
                         }
                    });   
                }
           });
   }
 