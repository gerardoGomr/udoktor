/* 
 * script para solicitudes
 */
var oTable;

$(function() {
    $('#cal1').datepicker();
    $('#cal2').datepicker();
    $("#listaGruposTransportistas").multiselect({
        enableFiltering: true,
            includeSelectAllOption: true,
            maxHeight: 300,
    });
    
   oTable= $('#listaTablaGrupos').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 2, 'desc' ]],
        ajax: {
            url: '/listaSolicitudes',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fecha1=$("#fecha1").val();
                var fecha2=$("#fecha2").val();
                var pendiente=false;
                var aceptado=false;
                var rechazado=false;
                
                if($('#pendiente').is(':checked')){
                    pendiente=true;
                }

                if($('#aceptado').is(':checked')){
                    aceptado=true;
                }

                if($('#rechazado').is(':checked')){
                    rechazado=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.fecha1 = fecha1;
                d.fecha2 = fecha2;
                d.pendiente = pendiente;
                d.aceptado = aceptado;
                d.rechazado = rechazado;
               
            }
        },
        columns: [
            {data: 'transportista', name: 'transportista',orderable: true, searchable: false},
            {data: 'estado', name: 'estado',orderable: true, searchable: false},
            {data: 'fecha', name: 'fecha',orderable: true, searchable: false},
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
     * Busca los transportistas
     * 
   */
   function buscarTransportista(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosTransportista(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");

         $('#pendiente, #aceptado, #rechazado').iCheck('uncheck');
         buscarTransportista();
     }
     
     /*
     * Muestra los datos de la solictud pendiente
     */
    function asignarGrupo(idSolicitud){
         location.href="/admin/asginarGrupo/"+idSolicitud;
     }
     
    /*
     * Aceptar el transportista al grupo
     * Fecha: 31-07-2016
     * Autor:OT
     */
    function aceptarTransportista(){
        var token=$("#token").val();
        var tipo=$("#tipo").val();
        var idTransportista=$("#idTransportista").val();
        var idSolicitud=$("#idSolicitud").val();
        
        if(tipo==1){
            var grupoEnvio=$("#grupoEnvio").val();
          
            waitingDialog();
                $.ajax({
                   headers:{'X-CSRF-TOKEN':token},
                   type:"POST",
                   data:{'idTransportista':idTransportista,'grupoEnvio':grupoEnvio,'tipo':tipo,'idSolicitud':idSolicitud},
                   url:"/admin/aceptarTransportista",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       if(respuesta=="ok"){
                           swal({
                            title: "",   
                            text: "Proceso realizado correctamente",
                            type: "success",   
                            showCancelButton: false,   
                            confirmButtonColor: "#47A447",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancelar", 
                            closeOnConfirm: true,   
                            closeOnCancel: true }, 
                            function(isConfirm){   
                                if (isConfirm) {
                                    waitingDialog();
                                    location.href="/admin/solicitudes";
                                }
                            });
                       }else{
                           swal('', 'Ocurrio un error al realizar el proceso.', 'error');
                       }
                       
                   }
                });
        }else{
        
            var listaGruposTransportistas=$("#listaGruposTransportistas").val();
            if(listaGruposTransportistas==null){
                swal('', 'Seleccione al menos un grupo.', 'error');
                return;
            }
            waitingDialog();
                $.ajax({
                   headers:{'X-CSRF-TOKEN':token},
                   type:"POST",
                   data:{'idTransportista':idTransportista,'listaGruposTransportistas':listaGruposTransportistas,'tipo':tipo,'idSolicitud':idSolicitud},
                   url:"/admin/aceptarTransportista",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       if(respuesta=="ok"){
                           swal({
                            title: "",   
                            text: "Proceso realizado correctamente",
                            type: "success",   
                            showCancelButton: false,   
                            confirmButtonColor: "#47A447",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancelar", 
                            closeOnConfirm: true,   
                            closeOnCancel: true }, 
                            function(isConfirm){   
                                if (isConfirm) {
                                    waitingDialog();
                                    location.href="/admin/solicitudes";
                                }
                            });
                           
                       }else{
                           swal('', 'Ocurrio un error al realizar el proceso.', 'error');
                       }
                       
                   }
                });
        
        }
    }
    
    function rechazarTransportista(){
      var token=$("#token").val();
      var idSolicitud=$("#idSolicitud").val();
       waitingDialog();
                $.ajax({
                   headers:{'X-CSRF-TOKEN':token},
                   type:"POST",
                   data:{'idSolicitud':idSolicitud},
                   url:"/admin/rechazarTransportista",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       if(respuesta=="ok"){
                           swal({
                            title: "",   
                            text: "Proceso realizado correctamente",
                            type: "success",   
                            showCancelButton: false,   
                            confirmButtonColor: "#47A447",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancelar", 
                            closeOnConfirm: true,   
                            closeOnCancel: true }, 
                            function(isConfirm){   
                                if (isConfirm) {
                                    waitingDialog();
                                    location.href="/admin/solicitudes";
                                }
                            });
                           
                       }else{
                           swal('', 'Ocurrio un error al realizar el proceso.', 'error');
                       }
                       
                   }
                }); 
    }