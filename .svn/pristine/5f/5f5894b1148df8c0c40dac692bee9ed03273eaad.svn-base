/* 
 * script para el listado de citas del cliente
 * 
 */
var oTable;

$(function() {
    

    $('#cal1').datepicker();
    $('#cal2').datepicker();
    $('#serviciosConsulta').multiselect({maxHeight: 200,
         includeSelectAllOption: true,
         selectAllJustVisible: false,
         buttonWidth: '250px'
    });
   oTable= $('#listaCitasCliente').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 2, 'desc' ]],
        ajax: {
            url: '/cliente/listaCitasCliente',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fecha1=$("#fecha1").val();
                var fecha2=$("#fecha2").val();
                var pendiente=false;
                var confirmada=false;
                var rechazada=false;
                
                if($('#pendiente').is(':checked')){
                    pendiente=true;
                }

                if($('#confirmada').is(':checked')){
                    confirmada=true;
                }
                
                if($('#rechazada').is(':checked')){
                    rechazada=true;
                }

                d.buscaTitulo = buscaTitulo;
                d.fecha1 = fecha1;
                d.fecha2 = fecha2;
                d.pendiente = pendiente;
                d.confirmada = confirmada;
                d.rechazada = rechazada;
                d.servicios=$("#serviciosConsulta").val();
                d.cita=$("#tipoCita").val();
               
            }
        },
        columns: [
            {data: 'compania', name: 'compania',orderable: true, searchable: false},
            {data: 'servicios', name: 'servicios',orderable: true, searchable: false},
            {data: 'fecha', name: 'fecha',orderable: true, searchable: false},
            {data: 'hora', name: 'hora',orderable: false, searchable: false},
            {data: 'estado', name: 'estado',orderable: false, searchable: false},
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
    

 });
 

    /*
     * Hace la llamada para buscar citas del cliente.
     * 
   */
   function buscarCitasCliente(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosCitaCliente(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");
         $('#pendiente, #confirmada, #rechazada').iCheck('uncheck');
         $('#serviciosConsulta').multiselect('deselectAll', false);
         $('#serviciosConsulta').multiselect('updateButtonText');
         $("#tipoCita").val(0);

         buscarCitasCliente();
     }

    /*
    * Cancela la cita del cliente
    * Autor: OT
    * Fecha: 26-12-2016
    * 
    */
   function cancelarCitaCliente(idCita){
       
       swal({
          title: "",   
          text: "¿Seguro de cancelar la cita?",
          type: "warning",   
          showCancelButton: true,   
          confirmButtonColor: "#47A447",
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar", 
          closeOnConfirm: true,   
          closeOnCancel: true
         }, 
          function(isConfirm){   
              if (isConfirm) {
                  waitingDialog();
                   $.ajax({
                      type:"GET",
                      data:{"idCita":idCita},
                      url:"/cliente/cancelarCita/",
                      success:function(respuesta){
                          setTimeout(function(){closeWaitingDialog();},100);
                          if(respuesta=="ok"){
                              swal("","Cita cancelada correctamente.","success");
                              buscarCitasCliente();
                          }else{
                              swal("","Ocurrio un error al cancelar la cita, consulte al administrador del servicio.","error");
                          }
                      }
                   });
              }
          });
   }
   
   /*
    * Muestra el motivo de rechazo
    * Autor: OT
    * Fecha: 28-12-2016
    * 
    */
   function mostrarMotivoRechazo(idCita){
       
       $("#divModal").dialog({
            autoOpen: false,
            title: "",
            modal:true,
            width: 400,
            height: 300,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{"idCita":idCita},
                   url:"/cliente/verMotivoRechazoCita/",
                   success:function(respuesta){
                       $("#divModal").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divModal").dialog('open');
   }
   
   
   /*
     * Muestra el mapa con la ubicacion del cliente
     * Fecha: 06-01-2017
     * Autor: OT
     */
    function mostrarUbicacionCliente(latitud,longitud){
        var mapaEnvio="";
        
        $("#divModal").dialog({
            autoOpen: false,
            title: "Ubicación",
            modal:true,
            width: 600,
            height: 550,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   url:"/cliente/verMapaUbicacion",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#divModal").html(respuesta);
                            var myLatlng = new google.maps.LatLng(latitud,longitud);
                            var mapOptions = {
                                  center: myLatlng,
                                  zoom: 9,
                                  mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                
                            var mapaEnvio = new google.maps.Map(document.getElementById("mapidUb"),mapOptions);
                            var marker = new google.maps.Marker({
                                   position: myLatlng,
                                   map: mapaEnvio,
                            });
                   }
                });
            }
	});
	$("#divModal").dialog('open');
    }


   
   