/* 
 * script para los tipos de vehiculos del transportista
 */
var oTable;

$(function() {
    

   oTable= $('#listaTablaTiposVehiculosTransportista').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/admin/listaTiposVehiculosTransportista',
            data: function (d) {
                
                var buscaTitulo=$("#buscaTitulo").val();
                var filtropeso=$("#filtropeso").val();
                var filtrocapacidad=$("#filtrocapacidad").val();
                var filtrovolumen=$("#filtrovolumen").val();
                var filtroancho=$("#filtroancho").val();
                var filtroalto=$("#filtroalto").val();
                var filtrolargo=$("#filtrolargo").val();
                
                var activo=false;
                var inactivo=false;
                
                if($('#activo').is(':checked')){
                    activo=true;
                }

                if($('#inactivo').is(':checked')){
                    inactivo=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.filtropeso = filtropeso;
                d.filtrocapacidad = filtrocapacidad;
                d.filtrovolumen = filtrovolumen;
                d.filtroancho = filtroancho;
                d.filtroalto = filtroalto;
                d.filtrolargo = filtrolargo;
                
                d.activo = activo;
                d.inactivo = inactivo;
               
            }
        },
        columns: [
            {data: 'name', name: 'name',orderable: true, searchable: false},
            {data: 'peso', name: 'peso',orderable: true, searchable: false},
            {data: 'capacidad', name: 'capacidad',orderable: true, searchable: false},
            {data: 'dimensiones', name: 'dimensiones',orderable: true, searchable: false},
            {data: 'volumen', name: 'volumen',orderable: true, searchable: false},
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
     * Busca los tipos de vehiculos de trasportista
     * 
   */
   function buscarTipoVehiculo(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosTipoVehiculos(){
         $("#buscaTitulo").val("");
         $("#filtropeso").val("");
         $("#filtrocapacidad").val("");
         $("#filtrovolumen").val("");
         $("#filtroancho").val("");
         $("#filtroalto").val("");
         $("#filtrolargo").val("");
         $('#activo,#inactivo').iCheck('uncheck');
         buscarTipoVehiculo();
     }
     
     
    /*
    * Muestra el formulario para agregar el tipo de vehiculo
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function nuevoTipoVehiculo(){
       $("#divElmentosListaTiposVehiculos").dialog({
            autoOpen: false,
            title: "Nuevo tipo de vehículo",
            modal:true,
            width: 750,
            height: 500,
            close: function(event,ui){
                 $("#divElmentosListaTiposVehiculos").html('');
                 $("#divElmentosListaTiposVehiculos").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/admin/nuevoTipoVehiculo/",
                   success:function(respuesta){
                       $("#divElmentosListaTiposVehiculos").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#tipoVehiculo").focus();
                   }
                });
            }
	});
	$("#divElmentosListaTiposVehiculos").dialog('open');
   }
   
    
    
    /*
    * Guardar tipo de vehiculo
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function guardarTipoVehiculoTransportista(){
       var token=$("#token").val();
       var tipoVehiculo = $("#tipoVehiculo").val();
       
       var pesomax = $("#pesomax").val();
       var capcarga = $("#capcarga").val();
       var ancho = $("#ancho").val();
       var alto = $("#alto").val();
       var largo = $("#largo").val();
       var volumen = $("#volumen").val();
       
           
       if(tipoVehiculo.replace(/\s/g,"")==""){
           swal("","Escriba el tipo de vehículo","warning");
           return;
       }
       
       if(pesomax=="" ||  !/^([0-9])*.([0-9])*$/.test(pesomax) || !parseFloat(pesomax) || parseFloat(pesomax)<0  || isNaN(pesomax)){
            swal("","El peso máximo no es válido.","warning");
            return;
        }
     
     
        if(capcarga=="" ||  !/^([0-9])*.([0-9])*$/.test(capcarga) || !parseFloat(capcarga) || parseFloat(capcarga)<0  || isNaN(capcarga)){
            swal("","La capacidad de carga no es válida.","warning");
            return;
        }
     
        if(ancho=="" ||  !/^([0-9])*.([0-9])*$/.test(ancho) || !parseFloat(ancho) || parseFloat(ancho)<0  || isNaN(ancho)){
            swal("","El ancho no es válido.","warning");
            return;
        }
     
        if(alto=="" ||  !/^([0-9])*.([0-9])*$/.test(alto) || !parseFloat(alto) || parseFloat(alto)<0  || isNaN(alto)){
            swal("","El alto no es válido.","warning");
            return;
        }
     
        if(largo=="" ||  !/^([0-9])*.([0-9])*$/.test(largo) || !parseFloat(largo) || parseFloat(largo)<0  || isNaN(largo)){
            swal("","El largo no es válido.","warning");
            return;
        }
     
        if(volumen=="" ||  !/^([0-9])*.([0-9])*$/.test(volumen) || !parseFloat(volumen) || parseFloat(volumen)<0  || isNaN(volumen)){
            swal("","El volúmen no es válido.","warning");
            return;
        }
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'tipoVehiculo':tipoVehiculo,'pesomax':pesomax,'capcarga':capcarga,'ancho':ancho,'alto':alto,
            'largo':largo,'volumen':volumen},
            url:"/admin/guardarTipoVehiculo",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosListaTiposVehiculos').dialog('close');
                   buscarTipoVehiculo();
                   setTimeout(function(){swal("","Tipo de vehículo agregado correctamente","success");},100);
               }else if(respuesta=="tiporepetido"){
                   setTimeout(function(){swal("","El tipo de vehículo ingresado ya existe, favor de verificar","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
   
   /*
    * Muestra el formulario para editar al tipo de vehiculo seleccionado
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function editarTipoVehiculo(idTipoVehiculo){
       $("#divElmentosListaTiposVehiculos").dialog({
            autoOpen: false,
            title: "Editar tipo de vehículo",
            modal:true,
            width: 750,
            height: 500,
            close: function(event,ui){
                 $("#divElmentosListaTiposVehiculos").html('');
                 $("#divElmentosListaTiposVehiculos").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'idTipoVehiculo':idTipoVehiculo},
                   url:"/admin/editarTipoVehiculo/",
                   success:function(respuesta){
                       $("#divElmentosListaTiposVehiculos").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#tipoVehiculo").focus();
                   }
                });
            }
	});
	$("#divElmentosListaTiposVehiculos").dialog('open');
   }
   
   /*
    * Actualizar datos del tipo de vehiculo
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function guardarCambiosTipoVehiculoTransportista(){
       var token=$("#token").val();
       var idTipo = $("#idTipo").val();
       var tipoVehiculo = $("#tipoVehiculo").val();
       
       var pesomax = $("#pesomax").val();
       var capcarga = $("#capcarga").val();
       var ancho = $("#ancho").val();
       var alto = $("#alto").val();
       var largo = $("#largo").val();
       var volumen = $("#volumen").val();
       
       if(tipoVehiculo.replace(/\s/g,"")==""){
           swal("","Escriba el tipo de vehículo","warning");
           return;
       }
           
       
       
       if(pesomax=="" ||  !/^([0-9])*.([0-9])*$/.test(pesomax) || !parseFloat(pesomax) || parseFloat(pesomax)<0  || isNaN(pesomax)){
            swal("","El peso máximo no es válido.","warning");
            return;
        }
     
     
        if(capcarga=="" ||  !/^([0-9])*.([0-9])*$/.test(capcarga) || !parseFloat(capcarga) || parseFloat(capcarga)<0  || isNaN(capcarga)){
            swal("","La capacidad de carga no es válida.","warning");
            return;
        }
     
        if(ancho=="" ||  !/^([0-9])*.([0-9])*$/.test(ancho) || !parseFloat(ancho) || parseFloat(ancho)<0  || isNaN(ancho)){
            swal("","El ancho no es válido.","warning");
            return;
        }
     
        if(alto=="" ||  !/^([0-9])*.([0-9])*$/.test(alto) || !parseFloat(alto) || parseFloat(alto)<0  || isNaN(alto)){
            swal("","El alto no es válido.","warning");
            return;
        }
     
        if(largo=="" ||  !/^([0-9])*.([0-9])*$/.test(largo) || !parseFloat(largo) || parseFloat(largo)<0  || isNaN(largo)){
            swal("","El largo no es válido.","warning");
            return;
        }
     
        if(volumen=="" ||  !/^([0-9])*.([0-9])*$/.test(volumen) || !parseFloat(volumen) || parseFloat(volumen)<0  || isNaN(volumen)){
            swal("","El volúmen no es válido.","warning");
            return;
        }
    
    
    
       
       waitingDialog();
       $.ajax({
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'idTipo':idTipo,'tipoVehiculo':tipoVehiculo,'pesomax':pesomax,'capcarga':capcarga,'ancho':ancho,'alto':alto,
            'largo':largo,'volumen':volumen},
            url:"/admin/actualizarTipoVehiculo",
            success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   $('#divElmentosListaTiposVehiculos').dialog('close');
                   buscarTipoVehiculo();
                   setTimeout(function(){swal("","Tipo de vehículo actualizado correctamente","success");},100);
               }else if(respuesta=="tiporepetido"){
                   setTimeout(function(){swal("","El tipo de vehículo ingresado ya existe, favor de verificar","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });   
   }
   
    
   

 /*
    * Activar o desactivar el tipo de vehiculo
    * Autor: OT
    * Fecha: 27-09-2016
    * 
    */
   function activarDesactivarTipoVehiculo(idTipoVehiculo,opcion){
       var titulo,mensaje="";
       
       if(opcion==1){
           titulo="¿Seguro de desactivar el tipo vehículo?";
           mensaje="Tipo de vehículo desactivado correctamente";
       }else{
           titulo="¿Seguro de activar el tipo vehículo?";
           mensaje="Tipo de vehículo activado correctamente";
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
                         data:{'idTipoVehiculo':idTipoVehiculo,'opcion':opcion},
                         url:"/admin/activarDesactivarTipoVehiculo",
                         success:function(respuesta){
                             setTimeout(function(){closeWaitingDialog();},100);
                            if(respuesta=="ok"){
                                buscarTipoVehiculo();
                                setTimeout(function(){swal("",mensaje,"success");},100);
                            }else{
                                setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
                            }
                         }
                    });   
                }
           });
   }
   
   /*
     * Limpia la caja de texto que pierde el foco
     * Autor: OT
     * 20-06-2016
     */
    function validarCajav(texto,id){
       var textoLimpio=limpiarTextov(texto);
       $("#"+id).val(textoLimpio);
    }
    
    /*
     * Limpia el texto recibido, para solo permitir numeros
     * Autor: OT
     * 20-06-2016
     */
    function limpiarTextov(texto){
       var vTexto="";
       vTexto=texto.split(',').join('');
       return vTexto; 
    }