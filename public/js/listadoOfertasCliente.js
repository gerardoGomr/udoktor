/* 
 * script para grupos
 */
var oTable;

$(function() {
    
  $('#cal1').datepicker();
  $('#cal2').datepicker();
    
   oTable= $('#listaTablaOfertasCliente').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 3, 'asc' ]],
        ajax: {
            url: '/listaOfertasCliente',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fecha1=$("#fecha1").val();
                var fecha2=$("#fecha2").val();
                var activa=false;
                var aceptada=false;
                var rechazada=false;
                var cancelada=false;
                
                if($('#activa').is(':checked')){
                    activa=true;
                }
                
                if($('#aceptada').is(':checked')){
                    aceptada=true;
                }
                
                if($('#rechazada').is(':checked')){
                    rechazada=true;
                }
                
                if($('#cancelada').is(':checked')){
                    cancelada=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.fecha1 = fecha1;
                d.fecha2 = fecha2;
                d.activa=activa;
                d.aceptada=aceptada;
                d.rechazada=rechazada;
                d.cancelada=cancelada;

               
            }
        },
        columns: [
            {data: 'envio', name: 'envio',orderable: true, searchable: false},
            {data: 'transportista', name: 'transportista',orderable: true, searchable: false},
            {data: 'oferta', name: 'oferta',orderable: true, searchable: false},
            {data: 'fecha', name: 'fecha',orderable: true, searchable: false},
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
    
    var table = oTable;
     
    
    
    $('#listaTablaOfertasCliente tbody').on('mouseover', 'tr', function () {
        $('td', this).css({ 'background-color' : '#e74c3c' });
        $('td', this).css({ 'color' : '#FFFFFF' });
    } );
    
    $('#listaTablaOfertasCliente tbody').on('mouseout', 'tr', function () {
        $('td', this).css({ 'background-color' : '#FFFFF' });
        $('td', this).css({ 'color' : '#000000' });
    } );

 });
 

    /*
     * Busca los grupos
     * 
   */
   function buscarOfertasCliente(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosOfertasCliente(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");
         $('#activa, #aceptada, #rechazada,#cancelada').iCheck('uncheck');
         buscarOfertasCliente();
     }
     