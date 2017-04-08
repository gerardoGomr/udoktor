/* 
 * script para los servios
 */
var oTable;

$(function() {
    

   oTable= $('#listaTablaServicios').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/admin/listaServicios',
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
            {data: 'sugerido', name: 'sugerido',orderable: true, searchable: false},
            {data: 'min', name: 'min',orderable: true, searchable: false},
            {data: 'max', name: 'max',orderable: true, searchable: false},
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
     * Busca los servicios
     * 
   */
   function buscarSErvicios(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosServicios(){
         $("#buscaTitulo").val("");
         $('#activo,#inactivo').iCheck('uncheck');
         buscarSErvicios();
     }
     
     
    /*
    * Muestra el formulario para agregar servicios
    * Autor: OT
    * Fecha: 30-12-2016
    * 
    */
   function nuevoServicioAdmin(){
       $("#divModal").dialog({
            autoOpen: false,
            title: "Nuevo servicio",
            modal:true,
            width: 550,
            height: 480,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/admin/nuevoServicioAdmin/",
                   success:function(respuesta){
                       $("#divModal").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#nombreClasificacion").focus();
                   }
                });
            }
	});
	$("#divModal").dialog('open');
   }
   
    
    
    /*
    * Guardar servicio
    * Autor: OT
    * Fecha: 30-12-2016
    * 
    */
   function guardarServcioAdmin(){
       var token=$("#token").val();
       var idServicio=$("#idServicio").val();
       var nombreServicio = $("#nombreServicio").val();
       var descripcionServicio = $("#descripcionServicio").val();
       var sugerido = $("#sugerido").val();
       var minimo = $("#minimo").val();
       var maximo = $("#maximo").val();
       
       if(nombreServicio.replace(/\s/g,"")==""){
           swal("","Escriba el nombre del servicio","warning");
           return;
       }
       
       if(descripcionServicio.replace(/\s/g,"")==""){
           swal("","Escriba la descripción del servicio","warning");
           return;
       }
       
       if(sugerido=="" ||  !/^([0-9])*.([0-9])*$/.test(sugerido) || !parseFloat(sugerido) || parseFloat(sugerido)<0  || isNaN(sugerido)){
           swal("","El precio  sugerido no es válido.","warning");
           return;
       }
       
       if(minimo=="" ||  !/^([0-9])*.([0-9])*$/.test(minimo) || !parseFloat(minimo) || parseFloat(minimo)<0  || isNaN(minimo)){
           swal("","El precio  mímino no es válido.","warning");
           return;
       }
       
       if(maximo=="" ||  !/^([0-9])*.([0-9])*$/.test(maximo) || !parseFloat(maximo) || parseFloat(maximo)<0  || isNaN(maximo)){
           swal("","El precio máximo no es válido.","warning");
           return;
       }
       
       if(parseFloat(sugerido)<parseFloat(minimo)){
           swal("","El precio sugerido debe ser mayor o igual al precio mínimo.","warning");
           return;
       }
       
       if(parseFloat(sugerido)>parseFloat(maximo)){
           swal("","El precio sugerido debe ser menor o igual al precio máximo.","warning");
           return;
       }

       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'nombreServicio':nombreServicio,'descripcionServicio':descripcionServicio,'idServicio':idServicio,
            'sugerido':sugerido,'minimo':minimo,'maximo':maximo},
            url:"/admin/guardarServicioAdmin",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divModal').dialog('close');
                   buscarSErvicios();
                   setTimeout(function(){swal("","Proceso realizado correctamente","success");},100);
               }else if(respuesta=="existenombre"){
                   setTimeout(function(){swal("","El servicio ingresado ya existe, favor de verificar","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
   
   /*
    * Muestra el formulario para editar el servicio
    * Autor: OT
    * Fecha: 30-12-2016
    * 
    */
   function editarServicioAdmin(idServicio){
       $("#divModal").dialog({
            autoOpen: false,
            title: "Editar servicio",
            modal:true,
            width: 550,
            height: 480,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'idServicio':idServicio},
                   url:"/admin/editarServicioAdmin/",
                   success:function(respuesta){
                       $("#divModal").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#nombreServicio").focus();
                   }
                });
            }
	});
	$("#divModal").dialog('open');
   }
   
   
 /*
    * Activar o desactivar el servicio
    * Autor: OT
    * Fecha: 30-12-2016
    * 
    */
   function activarDesactivarServicio(idServicio,opcion){
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
                         data:{'idServicio':idServicio,'opcion':opcion},
                         url:"/admin/activarDesactivarServicio",
                         success:function(respuesta){
                             setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                buscarSErvicios();
                                setTimeout(function(){swal("",mensaje,"success");},100);
                            }else{
                                setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
                            }
                         }
                    });   
                }
           });
   }
 