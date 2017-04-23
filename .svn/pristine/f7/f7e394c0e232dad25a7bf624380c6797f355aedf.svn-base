/*
 * 
 * Carga las citas en el calendario principal
 */
$(function () {
    cargarCitas();
        $('input:checkbox, input:radio').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            inheritClass: true
        })
  
  $('input').on('ifChanged', function(event){
         cargarCitas();
   });
  

});

/*
 * Carga las citas en el calendario principal
 * Autor: OT
 * Fecha : 10-01-2017
 * 
 */
function cargarCitas(){
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
    var checkPendiente=false;
    var checkConfirmado=false;
    var checkExpirado=false;
    
    if ($("#checkPendiente").is(":checked")) {
         checkPendiente=true;
     }
     
     if ($("#checkConfirmado").is(":checked")) {
         checkConfirmado=true;
     }
     
     if ($("#checkExpirado").is(":checked")) {
         checkExpirado=true;
     }
     
    waitingDialog();
                $.ajax({
                   type:"GET",
                   dataType: 'json',
                   data:{'checkPendiente':checkPendiente,'checkConfirmado':checkConfirmado,'checkExpirado':checkExpirado},
                   url:"/prestadorServicios/obtenerCitasCalendario/",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#calendarioPrestador").html("");
                       $('#calendarioPrestador').fullCalendar({
                        header: {
                          left: 'prev,next',
                          center: 'title',
                          right: 'month,agendaWeek,agendaDay'
                        },
                        editable: false,
                        droppable: false,
                        eventLimit: true,
                        
                        drop: function(date, allDay) { // this function is called when something is dropped

                          // retrieve the dropped element's stored Event Object
                          var originalEventObject = $(this).data('eventObject');

                          // we need to copy it, so that multiple events don't have a reference to the same object
                          var copiedEventObject = $.extend({}, originalEventObject);

                          // assign it the date that was reported
                          copiedEventObject.start = date;
                          copiedEventObject.allDay = allDay;
                          copiedEventObject.className = $(this).attr("data-category");

                          // render the event on the calendar
                          // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                          $('#calendarioPrestador').fullCalendar('renderEvent', copiedEventObject, true);

                          // is the "remove after drop" checkbox checked?
                          //if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                          //}

                        },

                        events:respuesta.citas,

                        eventClick: function(calEvent, jsEvent, view) {
                          
                          
                          cargarDetalleCita(calEvent.id);
                          //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                          //alert('View: ' + view.name);

                          // change the border color just for fun
                          //$(this).css('border-color', 'red');

                      }
                      });
                   }
                });


  $('#external-events div.external-event').each(function() {
    
      // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
      // it doesn't need to have a start or end
      var eventObject = {
        title: $.trim($(this).text()) // use the element's text as the event title
      };
      
      // store the Event Object in the DOM element so we can get to it later
      $(this).data('eventObject', eventObject);
      
      // make the event draggable using jQuery UI
     /* $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });*/
      
    });
    
    


  var addEvent = function (title, category) {
        title = title.length == 0 ? "Untitled Event" : title;
        category = category.length == 0 ? 'fc-secondary' : category;
        var html = $('<div data-category="' + category + '" class="external-event ui-draggable label ' + category + '">' + title + '</div>');
        jQuery('#event_box').append(html);
        initDrag(html);
    }

    $('#event-form').unbind('submit').submit(function (e) {
    e.preventDefault ();
    var title = $('#event_title');
    var category = $('#event_category');
    addEvent(title.val(), category.val ());
    title.val ('');
  });

    var initDrag = function (el) {
        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim(el.text()) // use the element's text as the event title
        };
        // store the Event Object in the DOM element so we can get to it later
        el.data('eventObject', eventObject);
        // make the event draggable using jQuery UI
        /*el.draggable({
            zIndex: 999,
            revert: true, // will cause the event to go back to its
            revertDuration: 0 //  original position after the drag
        });*/
    }
}


/*
 * Muestra el detalle de la cita seleccionada
 * Autor: OT
 * Fecha : 10-01-2017
 * 
 */
function cargarDetalleCita(id){
    var pos= id.indexOf(",");
    if(pos<=0){
        swal("","Ocurrio un error al obtener los datos de la cita, consulte al administrador del servicio.","error");
        return;
    }
    var vid= id.substring(0,pos);
    var tipo=id.substring(pos+1,id.length);
    var alto=450;
    if(tipo==1){
        alto=550;
    }
        
        $("#divModal").dialog({
            autoOpen: false,
            title: "Datos de la cita",
            modal:true,
            width: 600,
            height: alto,
            close: function(event,ui){
                 $("#divModal").html('');
                 $("#divModal").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{"idCita":vid},
                   url:"/prestadorServicios/detalleCita",
                   success:function(respuesta){
                       setTimeout(function(){closeWaitingDialog();},100);
                       $("#divModal").html(respuesta);
                        $('input[type=radio]').change(function() {       
                            if(this.value==2){
                                $("#divRechazo").removeAttr("style");
                                $("#motivoid").focus();
                                $("#motivoid").val("");
                            }else{
                                $("#divRechazo").attr("style","display:none");
                                $("#motivoid").val("");
                            }
                        });
                   }
                });
            }
	});
	$("#divModal").dialog('open');
}

/*
 * Guarda los cambios de la cita
 * Autor: OT
 * Fecha : 10-01-2017
 * 
 */
function guardaCambioCita(){
    var token=$("#token").val();
    var idCita=$("#idCita").val();
    var motivoid=$("#motivoid").val();
    var opcion= $('input:radio[name=radioset1]:checked').val();
    if(opcion==undefined){
        swal("","Confirme o rechace la cita.","warning");
        return;
    }
    
    if(motivoid.replace(/\s/g,"")=="" && opcion==2){
       swal("","Ingrese el motivo de rechazo.","warning");
       return;
    }
    
    waitingDialog();
    $.ajax({
        headers:{'X-CSRF-TOKEN':token},
        type:"POST",
        data:{"idCita":idCita,'motivoid':motivoid,'opcion':opcion},
        url:"/prestadorServicios/guardaCambioCita",
        success:function(respuesta){
           setTimeout(function(){closeWaitingDialog();},100);
           if(respuesta=="ok"){
               $("#divModal").dialog('close');
               swal("","Proceso realizado correctamente.","success");
               cargarCitas();
           }else{
               swal("","Ocurrio un error al realizar el proceso, consulte al administrador del servicio.","error");
           }
                        
        }
    });
}

