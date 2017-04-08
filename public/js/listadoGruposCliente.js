/* 
 * script para grupos
 */
var oTable;

$(function() {
    

   oTable= $('#listaTablaGruposCliente').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/listaGruposCliente',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                d.buscaTitulo = buscaTitulo;
               
            }
        },
        columns: [
            {data: 'name', name: 'name',orderable: true, searchable: false},
            {data: 'acciones', name: 'acciones',orderable: true, searchable: false},
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
     
    $('#listaTablaGruposCliente tbody').on('dblclick', 'tr', function () {
        var data = table.row( this ).data();
        verGrupo(data["id"]);
    } );
    
    $('#listaTablaGruposCliente tbody').on('mouseover', 'tr', function () {
        $('td', this).css({ 'background-color' : '#e74c3c' });
        $('td', this).css({ 'color' : '#FFFFFF' });
    } );
    
    $('#listaTablaGruposCliente tbody').on('mouseout', 'tr', function () {
        $('td', this).css({ 'background-color' : '#FFFFF' });
        $('td', this).css({ 'color' : '#000000' });
    } );

 
 

 });
 

    /*
     * Busca los grupos
     * 
   */
   function buscarGrupos(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosGrupos(){
         $("#buscaTitulo").val("");
         buscarGrupos();
     }
     
     /*
     * Limpia los filtros de busqueda
     */
    function verGrupo(idGrupo){
         location.href="/cliente/detalleGrupo/"+idGrupo;
     }
     
    