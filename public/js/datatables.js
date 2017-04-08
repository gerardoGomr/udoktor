function dataTable(idTable, url){

    $('#'+idTable).DataTable({
        "oLanguage": {
            "sLengthMenu": "Registros por pagina: _MENU_",
            "sZeroRecords": "No hay registros",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "sInfoEmpty": "No hay registros disponibles",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Inicio",
                "sPrevious": "Anterior",
                "sNext": "Siguiente",
                "sLast": "Fin"
            },
            "sProcessing": "Cargando dato ... espere>",
            "sInfoFiltered": "(filtrado desde _MAX_ registros)",
            "buttons": {
                "copyTitle": 'Copiado a portapapeles',
                "copySuccess": {
                    1: "Copiado una fila al portapapeles",
                    _: "Copiado %d filas al portapapeles"
                }
            }
        },
        "sPaginationType": "bootstrap",
        "sDom": "B<'row separator bottom'<'col-md-3'T><'col-md-3'l><'col-md-4'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "bDestroy": true,
        "bProcessing": true,
        "sAjaxSource":url,
        "sServerMethod": "POST",
        "bAutoWidth": false,
        "order": [[ 0, "desc" ]]
    });
}