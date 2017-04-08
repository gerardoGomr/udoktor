/* 
 * script para grupos
 */
var oTable;

$(function() {
    

   oTable= $('#listaTablaGrupos').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'desc' ]],
        ajax: {
            url: '/listaGrupos',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fprioridad=$("#fprioridad").val();
                d.buscaTitulo = buscaTitulo;
                d.fprioridad=fprioridad;
            }
        },
        columns: [
            {data: 'name', name: 'name',orderable: true, searchable: false},
            {data: 'cliente', name: 'cliente',orderable: true, searchable: false},
            {data: 'prioridad', name: 'prioridad',orderable: true, searchable: false},
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
         $("#fprioridad").val("");
         buscarGrupos();
     }
     
     /*
     * Limpia los filtros de busqueda
     */
    function verGrupo(idGrupo){
         location.href="/admin/detalleGrupo/"+idGrupo;
     }
     
    