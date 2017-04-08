/* 
 * script para el listado de envios de cliente
 */
var oTable;

$(function() {
    

    $('#cal1').datepicker();
    $('#cal2').datepicker();
   oTable= $('#listaMensajesGeneral').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 2, 'desc' ]],
        ajax: {
            url: '/listaMensajesRecibidos',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fecha1=$("#fecha1").val();
                var fecha2=$("#fecha2").val();
                var sinleer=false;
                
                if($('#sinleer').is(':checked')){
                    sinleer=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.fecha1 = fecha1;
                d.fecha2 = fecha2;
                d.sinleer = sinleer;
               
            }
        },
        columns: [
            {data: 'emisor', name: 'emisor',orderable: true, searchable: false},
            {data: 'mensaje', name: 'mensaje',orderable: true, searchable: false},
            {data: 'fecha', name: 'fecha',orderable: true, searchable: false},
            {data: 'ver', name: 'ver',orderable: false, searchable: false},
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
    
    
     var table = oTable;
     
    $('#listaMensajesGeneral tbody').on('dblclick', 'tr', function () {
        var data = table.row( this ).data();
        leerMensajeCliente(data["id"]);
    } );
    
    $('#listaMensajesGeneral tbody').on('mouseover', 'tr', function () {
        $('td', this).css({ 'background-color' : '#e74c3c' });
        $('td', this).css({ 'color' : '#FFFFFF' });
    } );
    
    $('#listaMensajesGeneral tbody').on('mouseout', 'tr', function () {
        $('td', this).css({ 'background-color' : '#FFFFF' });
        $('td', this).css({ 'color' : '#000000' });
    } );

 });
 

    /*
     * Hace la llamada a la funcion para buscar los envios del cliente.
     * 
   */
   function buscarMenajes(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosMensajes(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");

         $('#sinleer').iCheck('uncheck');
         buscarMenajes();
     }

    /*
    * Muestra el mensaje seleccionado
    * Autor: OT
    * Fecha: 04-07-2016
    * 
    */
   function leerMensajeCliente(idMensaje){
       $("#leerMensajeClientePrincipal").dialog({
               autoOpen: false,
               title: "Leer mensaje",
               modal:true,
               width: 650,
               height: 430,
               close: function(event,ui){
                    $("#leerMensajeClientePrincipal").html('');
                    $("#leerMensajeClientePrincipal").dialog('destroy');
               },
               open:function(event,ui){
                   waitingDialog();
                   $.ajax({
                      type:"GET",
                      url:"/general/leerMensajeFormulario/"+idMensaje,
                      success:function(respuesta){
                          $("#leerMensajeClientePrincipal").html(respuesta);
                          buscarMensajesNuevosPrincipal();
                          buscarMenajes();
                          setTimeout(function(){closeWaitingDialog();},100);
                      }
                   });
               }
           });
           $("#leerMensajeClientePrincipal").dialog('open');
   }
   
   