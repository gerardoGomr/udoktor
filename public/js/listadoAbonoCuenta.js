/* 
 * script para abonos a cuenta
 */
var oTable;

$(function() {
    $('#cal1').datepicker();
    $('#cal2').datepicker();
    $("#listaTransportistas").multiselect({
        enableFiltering: true,
            includeSelectAllOption: true,
            maxHeight: 300,
            maxWidth: 300,
            numberDisplayed: 0

    });
    
   oTable= $('#listaTablaAbonoCuenta').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 2, 'desc' ]],
        ajax: {
            url: '/admin/listaAbonoCuenta',
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
            {data: 'transportista', name: 'transportista',orderable: true, searchable: false},
            {data: 'descripcion', name: 'descripcion',orderable: true, searchable: false},
            {data: 'fecha', name: 'fecha',orderable: true, searchable: false},
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
    


 });
 

    /*
     * Buscar abonos a transportistas
     * 
   */
   function buscarAbonos(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltroAbonos(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");
         buscarAbonos();
     }
     
     
     /*
     * Muestra el formulario para crear el abono
     * Autor: OT
     * 
     */
    function nuevoAbono(){
         location.href="/admin/nuevoAbono/";
     }
     
     
     /*
    * Guardar el aboono de los transportistas
    * Autor: OT
    * Fecha: 19-09-2016
    *
    */
   function guardarAbonoTransportista(){
       var idPromocion= $("#idPromocion").val();
       var monto=$("#monto").val();
       var descripcion=$("#descripcion").val();
       var listaTransportistas=$("#listaTransportistas").val();
       
       if(listaTransportistas==null){
           swal("","Seleccione al menos un transportista.","warning");
           return;
       }
       
       if(monto=="" ||  !/^([0-9])*.([0-9])*$/.test(monto)  || parseFloat(monto)<0 || isNaN(monto)){
           swal("","La cantidad a abonar no es válida.","warning");
           return;
       }
       
       if(descripcion.replace(/\s/g,"")==""){
           swal("","Escriba la descripción del abono","warning");
           return;
       }
       
       
       var token=$("#token").val();
       waitingDialog();
        $.ajax({
            headers:{'X-CSRF-TOKEN':token},
           type:"POST",
           data:{'idPromocion':idPromocion,'monto':monto,'descripcion':descripcion,'listaTransportistas':listaTransportistas},
	   url:"/admin/guardarAbono",
           success:function(respuesta){
               setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   swal({
                        title: "",   
                        text: "Abono guardado correctamente",
                        type: "success",   
                        showCancelButton: false,   
                        confirmButtonColor: "#47A447",
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar", 
                        closeOnConfirm: false,   
                        closeOnCancel: false
                        }, 
                        function(isConfirm){   
                            if (isConfirm) {
                                location.href="/admin/abonarCuenta";
                            }
                        });
                }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
               
           }
       });
       
   }
     