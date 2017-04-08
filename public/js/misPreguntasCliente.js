/* 
 * script para el listado de preguntas del usuario cliente
 */
var oTable;

$(function() {
    

    $('#cal1').datepicker();
    $('#cal2').datepicker();
   oTable= $('#listaPreguntasGeneral').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 3, 'desc' ]],
        ajax: {
            url: '/listaPreguntasRecibidasCliente',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fecha1=$("#fecha1").val();
                var fecha2=$("#fecha2").val();
                var respondidas=false;
                
                if($('#respondidas').is(':checked')){
                    respondidas=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.fecha1 = fecha1;
                d.fecha2 = fecha2;
                d.respodidas = respondidas;
               
            }
        },
        columns: [
            {data: 'transportista', name: 'transportista',orderable: true, searchable: false},
            {data: 'envio', name: 'envio',orderable: true, searchable: false},
            {data: 'pregunta', name: 'pregunta',orderable: true, searchable: false},
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
     
    $('#listaPreguntasGeneral tbody').on('dblclick', 'tr', function () {
        var data = table.row( this ).data();
        mostrarPeguntaTransportista(data["id"]);
    } );
    
    $('#listaPreguntasGeneral tbody').on('mouseover', 'tr', function () {
        $('td', this).css({ 'background-color' : '#e74c3c' });
        $('td', this).css({ 'color' : '#FFFFFF' });
    } );
    
    $('#listaPreguntasGeneral tbody').on('mouseout', 'tr', function () {
        $('td', this).css({ 'background-color' : '#FFFFF' });
        $('td', this).css({ 'color' : '#000000' });
    } );

 });
 

    /*
     * Hace la llamada a la funcion para buscar los envios del cliente.
     * 
   */
   function buscarPreguntasCliente(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosPreguntaCliente(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");

         $('#respondidas').iCheck('uncheck');
         buscarPreguntasCliente();
     }

    /*
    * Muestra la pregunta seleccionada
    * Autor: OT
    * Fecha: 22-07-2016
    * 
    */
   function mostrarPeguntaTransportista(idPregunta){
       $("#divMostrarPreguntaTransportista").dialog({
               autoOpen: false,
               title: "Pregunta del transportista",
               modal:true,
               width: 650,
               height: 400,
               close: function(event,ui){
                    $("#divMostrarPreguntaTransportista").html('');
                    $("#divMostrarPreguntaTransportista").dialog('destroy');
               },
               open:function(event,ui){
                   waitingDialog();
                   $.ajax({
                      type:"GET",
                      url:"/cliente/responderPreguntaListado/"+idPregunta,
                      success:function(respuesta){
                          $("#divMostrarPreguntaTransportista").html(respuesta);
                          $("#textoRespuesta").focus();
                          setTimeout(function(){closeWaitingDialog();},100);
                      }
                   });
               }
           });
           $("#divMostrarPreguntaTransportista").dialog('open');
   }
   
   /*
    * Responder pegunta del transportista desde el listado
    * Autor: OT
    * Fecha: 29-06-2016
    *
    */
   function responderPreguntaAccionListado(){
       var respuesta=$("#textoRespuesta").val();
       var idPregunta=$("#idPregunta").val();
       var token=$("#token").val();

       if(respuesta.replace(/\s/g,"")==""){
          swal("","Escriba la respuesta.","warning");
          return;
       }

       waitingDialog();
        $.ajax({
           datatype:JSON,
           headers:{'X-CSRF-TOKEN':token},
           type:"POST",
           data:{'idPregunta':idPregunta,'respuesta':respuesta,},
	   url:"/cliente/responderPregunta",
           success:function(respuesta){
                setTimeout(function(){closeWaitingDialog();},100);
                if(respuesta.error==0){
                    swal("","Respuesta enviada correctamente.","success");
                    setTimeout(function(){$('#divMostrarPreguntaTransportista').dialog('close');},100);
                    buscarPreguntasCliente();
                }else{
                    swal("","Ocurrio un error al guardar la respuesta.","error");
                }
           }
       });
   }
   
   