/* 
 * script para configuracion de precios al ofertar grupo
 */
var oTable;

$(function() {
    
   oTable= $('#listaTablaPrecioGrupo').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 1, 'desc' ]],
        ajax: {
            url: '/admin/listaPrecioGrupo',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var preciofijo=false;
                var porcentaje=false;
                
                
                if($('#preciofijo').is(':checked')){
                    preciofijo=true;
                }

                if($('#porcentaje').is(':checked')){
                    porcentaje=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.preciofijo = preciofijo;
                d.porcentaje = porcentaje;
                d.filtroGrupo=$("#filtroGrupo").val();
               
            }
        },
        columns: [
            {data: 'grupo', name: 'grupo',orderable: true, searchable: false},
            {data: 'tipo', name: 'tipo',orderable: true, searchable: false},
            {data: 'desde', name: 'desde',orderable: true, searchable: false},
            {data: 'hasta', name: 'hasta',orderable: true, searchable: false},
            {data: 'cantidad', name: 'cantidad',orderable: true, searchable: false},
            {data: 'acciones', name: 'accioes',orderable: false, searchable: false},
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
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
    
    $("#idCliente").select2();

 });
 

    /*
     * Busca precio grupo
     * 
   */
   function buscarPrecioGrupo(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosPrecioGrupo(){
         $("#buscaTitulo").val("");
         $('#preciofijo, #porcentaje').iCheck('uncheck');
         $("#filtroGrupo").val("0");
         buscarPrecioGrupo();
     }
     
     
     /*
     * Muestra el formulario para capturar el precio por grupo
     * Fecha: 28-09-2016
     * Autor: OT
     */
    function agregarPrecioGrupo(){
        $("#divElmentosGrupoPrecio").dialog({
            autoOpen: false,
            title: "Agregar precio por grupo",
            modal:true,
            width: 550,
            height: 450,
            close: function(event,ui){
                 $("#divElmentosGrupoPrecio").html('');
                 $("#divElmentosGrupoPrecio").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/admin/formularioPrecioGrupo",
                   success:function(respuesta){
                       $("#divElmentosGrupoPrecio").html(respuesta);
                       $("#tipoPrecio").focus();
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divElmentosGrupoPrecio").dialog('open');
    }

     
    /*
     * Guarda nuevo precio con grupo
     * Fecha: 29-09-2016
     * Autor: OT
     */
    function guardarPrecioGrupo(){
        var token=$("#token").val();
        var tipoPrecio=$("#tipoPrecio").val();
        var precioporcentaje=$("#precioporcentaje").val();
        var vdesde=$("#vdesde").val();
        var vhasta=$("#vhasta").val();
        var idgrupo=$("#idgrupo").val();
        
        if(tipoPrecio==""){
            swal("","Seleccione el tipo de precio.","warning");
           return;
        }
        
        if(tipoPrecio=="1"){
           if(precioporcentaje=="" ||  !/^([0-9])*.([0-9])*$/.test(precioporcentaje)  || parseFloat(precioporcentaje)<0 || isNaN(precioporcentaje)){
                swal("","El porcentaje ingresado no es válido.","warning");
                return;
            }
            if(precioporcentaje>100){
                swal("","El porcentaje debe ser menor o igual al 100.","warning");
                return;
            }
        }else{
            if(precioporcentaje=="" ||  !/^([0-9])*.([0-9])*$/.test(precioporcentaje)  || parseFloat(precioporcentaje)<0 || isNaN(precioporcentaje)){
                swal("","El porcentaje ingresado no es válido.","warning");
                return;
            }
        }
        
        if(vdesde=="" ||  !/^([0-9])*.([0-9])*$/.test(vdesde)  || parseFloat(vdesde)<0 || isNaN(vdesde)){
                swal("","El monto de la oferta desde no es válido.","warning");
                return;
            }
            
        if(vhasta=="" ||  !/^([0-9])*.([0-9])*$/.test(vhasta)  || parseFloat(vhasta)<0 || isNaN(vhasta)){
                swal("","El monto de la oferta hasta no es válido.","warning");
                return;
            }
            
        if(parseFloat(vdesde)>=parseFloat(vhasta)){
                swal("","Verifique el rango de montos de la oferta sea correcto.","warning");
                return;
        }
        
        if(idgrupo=="0"){
            swal("","Seleccione un grupo.","warning");
                return;
        }
        
        waitingDialog();
                $.ajax({
                   headers:{'X-CSRF-TOKEN':token},
                   type:"POST",
                   data:{'tipoPrecio':tipoPrecio,'precioporcentaje':precioporcentaje,'vdesde':vdesde,'vhasta':vhasta,'idgrupo':idgrupo},
                   url:"/admin/guardarPrecioGrupo",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       if(respuesta=="ok"){
                           $("#divElmentosGrupoPrecio").dialog('close');
                           buscarPrecioGrupo();
                           setTimeout(function(){swal("","Precio guardado correctamente.","success");},100);
                       }else if(respuesta=="otroRango"){
                           setTimeout(function(){swal("","Ya existe un rango dentro del rango que quiere agregar, favor de verificar.","error");},100);
                       }else if(respuesta=="rangoigual"){
                           setTimeout(function(){swal("","Ya existe un rango de montos de oferta para el grupo con los mismos datos, favor de verificar.","error");},100);
                       }else if(respuesta=="errordesde"){
                           setTimeout(function(){swal("","El monto de la oferta desde se encuentra dentro de otro rango para el grupo seleccionado.","error");},100);
                       }else if(respuesta=="errorhasta"){
                           setTimeout(function(){swal("","El monto de la oferta hasta se encuentra dentro de otro rango para el grupo seleccionado.","error");},100);
                       }else{
                           setTimeout(function(){swal("","Ocurrio un error durante el proceso.","error");},100);
                       }
                   }
                });
    }


    
    
    /*
     * Elimina el precio 
     * Fecha: 23-09-2016
     * Autor: OT
     */
    function eliminarPrecioGeneral(idPrecio){
        swal({
             title: "",   
             text: "¿Seguro de eliminar el precio seleccionado?",
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
                            data:{'idPrecio':idPrecio},
                            url:"/admin/eliminarPrecioGeneral",
                            success:function(respuesta){
                                setTimeout(function(){closeWaitingDialog();},100);
                              if(respuesta=="ok"){
                                   buscarPrecioGrupo();
                                   setTimeout(function(){swal("","Precio eliminado correctamente.","success");},100);
                              }else{
                                   setTimeout(function(){swal("","Ocurrio un error durante el proceso.","error");},100);
                              }
                            }
                       });
                }
      });
    }
    
    
    