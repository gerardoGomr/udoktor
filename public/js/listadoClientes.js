/* 
 * script para solicitudes
 */
var oTable;

$(function() {
 });
 
 function cargaListado(){
     
   oTable= $('#listaTablaClente').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 3, 'desc' ]],
        ajax: {
            url: '/admin/listaClientes',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var activo=false;
                var inactivo=false;
                var personal=false;
                var negocio=false;
                var sinverificar=false;
                
                if($('#activo').is(':checked')){
                    activo=true;
                }

                if($('#inactivo').is(':checked')){
                    inactivo=true;
                }
                
                if($('#sinverificar').is(':checked')){
                    sinverificar=true;
                }

                if($('#personal').is(':checked')){
                    personal=true;
                }

                if($('#negocio').is(':checked')){
                    negocio=true;
                }

                d.buscaTitulo = buscaTitulo;
                d.activo = activo;
                d.inactivo = inactivo;
                d.sinverificar=sinverificar;
                d.personal = personal;
                d.negocio = negocio;
               
            }
        },
        columns: [
            {data: 'dni', name: 'dni',orderable: true, searchable: false},
            {data: 'nombre', name: 'nombre',orderable: true, searchable: false},
            {data: 'compania', name: 'compania',orderable: true, searchable: false},
            {data: 'estado', name: 'estado',orderable: true, searchable: false},
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
     
 }
 

    /*
     * Busca los clientes
     * 
   */
   function buscarCliente(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosCliente(){
         $("#buscaTitulo").val("");

         $('#activo, #inactivo, #personal,#negocio,#sinverificar').iCheck('uncheck');
         buscarCliente();
     }
         