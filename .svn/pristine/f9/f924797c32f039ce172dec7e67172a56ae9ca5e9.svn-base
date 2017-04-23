/* 
 * script para la cuenta del transportista
 */
var oTable;
var oTable2;

$(function() {

});


    /*
    * Muestra la pantalla inicial de prociones
    * Autor: OT
    * Fecha: 20-09-2016
    * 
    */
   function cargaPromociones(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/transportista/misPromociones/",
            success:function(respuesta){
               $("#elementosCuenta").html(respuesta);
               $('#cal1').datepicker();
               $('#cal2').datepicker();
               listaPromociones();
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   /*
     * Genera los datos del datatable de promociones
     * Autor: OT
     * Fecha: 20-09-2016
     */
    
    function listaPromociones(){
            oTable= $('#listaTablaPromociones').DataTable({
            processing: true,
            serverSide: true,
            searching:false,
            order: [[ 1, 'desc' ]],
            ajax: {
                url: '/transportista/listaMisPromociones',
                data: function (d) {
                    var buscaTitulo=$("#buscaTitulo").val();
                    var fecha1=$("#fecha1").val();
                    var fecha2=$("#fecha2").val();
                    

                    d.buscaTitulo = buscaTitulo;
                    d.fecha1 = fecha1;
                    d.fecha2 = fecha2;

                }
            },
            columns: [
                {data: 'codigo', name: 'codigo',orderable: true, searchable: false},
                {data: 'vigencia', name: 'vigencia',orderable: true, searchable: false},
                {data: 'monto', name: 'monto',orderable: true, searchable: false},
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
        
    }
   
  
  /*
     * Busca promociones realizadas
     * Autor: OT
     * Fecha 20-09-2016
     * 
   */
   function buscarPromocionesTrans(){
        oTable.draw();
    }

    /*
     * Limpia filtros
     * Autor: OT
     * Fecha 20-09-2016
     * 
   */
    function restablecerFiltrosPromocionesTrans(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");
         buscarPromocionesTrans();
     }
  
  
  
   /*
    * Muestra el formulario para capturar el codigo
    * Autor: OT
    * Fecha: 20-09-2016
    * 
    */
   function capturarCodigo(){
       $("#divPromocionAux").dialog({
            autoOpen: false,
            title: "Capturar código de promoción",
            modal:true,
            width: 450,
            height: 250,
            close: function(event,ui){
                 $("#divPromocionAux").html('');
                 $("#divPromocionAux").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/transportista/capturarCodigoPromocion/",
                   success:function(respuesta){
                       $("#divPromocionAux").html(respuesta);
                       $("#codigoPromocion").focus();
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divPromocionAux").dialog('open');
   }
   
   /*
    * Guarda el código de la promocion
    * Autor: OT
    * Fecha: 20-09-2016
    * 
    */
   function guardarCodigoPromocion(){
       var codigoPromocion = $("#codigoPromocion").val();
       var token=$("#token").val();
       if(codigoPromocion.replace(/\s/g,"")==""){
           swal("","Escriba el código de la promoción","warning");
           return;
       }
       waitingDialog();
       $.ajax({
            datatype:JSON,
            type:"POST",
            headers:{'X-CSRF-TOKEN':token},
            data:{'codigoPromocion':codigoPromocion},
            url:"/transportista/guardarCodigoPromocion",
            success:function(data){
                setTimeout(function(){closeWaitingDialog();},100);
               if(data.respuesta=="ok"){
                   $('#divPromocionAux').dialog('close');
                   buscarPromocionesTrans();
                   consultaSaldoTransportistaPrincipal();
                   $("#saldoTransportista").html(data.saldo);
                   setTimeout(function(){swal("","Promoción procesada correctamente","success");},100);
               }else if(data.respuesta=="errorCodigo"){
                   setTimeout(function(){swal("","El código ingresado es incorrecto, favor de verificar","warning");},100);
               }else if(data.respuesta=="errorfecha"){
                   setTimeout(function(){swal("","El código ingresado ha expirado, favor de verificar","warning");},100);
               }else if(data.respuesta=="errorgrupo"){
                   setTimeout(function(){swal("","La promoción ingresada no aplica para su usuario, favor de verificar.","warning");},100);
               }else if(data.respuesta=="promocionrepetida"){
                   setTimeout(function(){swal("","El código fue ingresado anteriormente, favor de verificar.","warning");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
            }
       });
   }
   
   
   /*
    * Muestra la pantalla inicial de historial
    * Autor: OT
    * Fecha: 20-09-2016
    * 
    */
   function historialCuenta(){
       waitingDialog();
       $.ajax({
            type:"GET",
            url:"/transportista/historialCuenta/",
            success:function(respuesta){
               $("#elementosCuenta").html(respuesta);
               $('#cal1').datepicker();
               $('#cal2').datepicker();
               tablaHistorial();
               setTimeout(function(){closeWaitingDialog();},100);
            }
       });
   }
   
   
   /*
     * Genera los datos del datatable del historia
     * Autor: OT
     * Fecha: 20-09-2016
     */
    
    function tablaHistorial(){
            oTable2= $('#listaTablaHistorial').DataTable({
            processing: true,
            serverSide: true,
            searching:false,
            order: [[ 1, 'desc' ]],
            ajax: {
                url: '/transportista/listaMiHistorial',
                data: function (d) {
                    var tipoMovimiento=$("#tipoMovimiento").val();
                    var fecha1=$("#fecha1").val();
                    var fecha2=$("#fecha2").val();
                    

                    d.tipoMovimiento = tipoMovimiento;
                    d.fecha1 = fecha1;
                    d.fecha2 = fecha2;

                }
            },
            columns: [
                {data: 'movimiento', name: 'movimiento',orderable: true, searchable: false},
                {data: 'vigencia', name: 'vigencia',orderable: true, searchable: false},
                {data: 'monto', name: 'monto',orderable: true, searchable: false},
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
    }
    
    
    /*
     * Busca historial 
     * Autor: OT
     * Fecha 20-09-2016
     * 
   */
   function buscarHistorialTrans(){
        oTable2.draw();
    }

    /*
     * Limpia filtros de historial
     * Autor: OT
     * Fecha 20-09-2016
     * 
   */
    function restablecerFiltrosHistorialTrans(){
         $("#tipoMovimiento").val(0);
         $("#fecha1").val("");
         $("#fecha2").val("");
         buscarHistorialTrans();
     }
   
