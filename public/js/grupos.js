/* 
 * script para grupos
 */
var oTable;

$(function() {
    

    $('#cal1').datepicker();
    $('#cal2').datepicker();
   oTable= $('#listaTransportistas').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/listaTransportistas',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var cadenaIds="";
                var idsTRansportista = document.getElementsByName("idsTRansportista");
                for (var i = 0; i < idsTRansportista.length; i++) {
                    cadenaIds=cadenaIds+idsTRansportista[i].value+",";
                }
                
                
                d.buscaTitulo = buscaTitulo;
                d.idsTRansportistasEnGrupo =cadenaIds;
               
            }
        },
        columns: [
            {data: 'ruc', name: 'ruc',orderable: true, searchable: false},
            {data: 'compania', name: 'compania',orderable: true, searchable: false},
            {data: 'agregar', name: 'agregar',orderable: true, searchable: false},
        ],
        
       language:{
            "decimal":        "",
            "emptyTable":     "<center><h4><b>Ningún resultado encontrado.</b></h4><h5 class='text-muted'>Revisa tu búsqueda e inténtalo de nuevo.</h5></center>",
            "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "",
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
    
    $("#idCliente").select2();

 });
 

    /*
     * Busca los transportistas
     * 
   */
   function buscarTransportistas(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltrosTransportistas(){
         $("#buscaTitulo").val("");
         buscarTransportistas();
     }
     
    /*
    * Agrega el transportista al listado del grupo
    * Autor: OT
    * Fecha: 29-07-2016
    * 
    */
     function agregarTransportista(idTransportista){
       if(idTransportista>0){
            waitingDialog(); 
           $.ajax({
               type:"GET",
               dataType:"JSON",
               data:{"idTransportista":idTransportista},
               url:"/admin/obtenerdatosTransportista/",
               success:function(respuesta){
                   var nombre=respuesta.ruc + " " + respuesta.nombre;
                    var cadena = "<tr>";
                    cadena = cadena + "<td style='width: 380px;'>" + nombre + "</td>";
                    cadena = cadena + "<td><a href='javascript:;' class='elimina' title='Eliminar artículo' onclick='deleteRow(this)'>Eliminar</a>\n\
                                        <input id='idTRansportista"+idTransportista+"' name='idsTRansportista' type='hidden' value="+idTransportista+">\n\
                                    </td></tr>";
                    $("#tablaListaTransportista tbody").append(cadena);
                    buscarTransportistas();
                     setTimeout(function(){closeWaitingDialog();},300);
               }
           });
       }else{
           swal("","Ocurrio un error al agregar el transportista.","warning");
       }
     }

    /* 
     * Quita el transportista de la lista del grupo
     * Autor: OT
     * Fecha: 29-07-2016
     */
    
    function deleteRow(r) {
        waitingDialog(); 
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById("tablaListaTransportista").deleteRow(i);
        buscarTransportistas();
        setTimeout(function(){closeWaitingDialog();},300);
    }
    
    
    /*
    * Guardar el grupo
    * Autor: OT
    * Fecha: 29-07-2016
    * 
    */
     function guardarGrupo(){
        var token=$("#token").val();
        var nombreGrupo = $("#nombreGrupo").val();
        var idCliente = $("#idCliente").val();
        var cadenaIds="";
        var idsTRansportista = document.getElementsByName("idsTRansportista");
        
        if(nombreGrupo.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre del grupo.","warning");
          return;
        }
        
        if(idCliente=="0"){
          swal("","Seleccione el cliente.","warning");
          return;
        }
        
        
        for (var i = 0; i < idsTRansportista.length; i++) {
           cadenaIds=cadenaIds+idsTRansportista[i].value+",";
        }
        
        if(cadenaIds==""){
            swal("","Agregue por lo menos un transportista.","warning");
            return;
        }
                
        waitingDialog(); 
           $.ajax({
               headers:{'X-CSRF-TOKEN':token},
               type:"POST",
               data:{'idsTRansportista':cadenaIds,'idCliente':idCliente,'nombreGrupo':nombreGrupo},
               url:"/admin/crearGrupo",
               success:function(respuesta){
                   setTimeout(function(){closeWaitingDialog();},100);
                   if(respuesta=="ok"){
                       swal({
                        title: "",   
                        text: "Grupo creado correctamente,¿Desea crear otro?",
                        type: "success",   
                        showCancelButton: true,   
                        confirmButtonColor: "#47A447",
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar", 
                        closeOnConfirm: false,   
                        closeOnCancel: false
                        }, 
                        function(isConfirm){   
                            if (isConfirm) {
                                location.href="/admin/nuevoGrupo";
                            }else{
                               location.href="/admin/misGrupos";
                            }
                        });
                    }else if(respuesta=="prioridadrepetida"){
                        swal("","Existe otro grupo con la misma prioridad, favor de verificar.","error");
                   }else if(respuesta="sintransportistas"){
                       swal("","Ocurrio un error al crear el grupo.","error");
                   }
               }
           });
     }
     
     
     
     /*
     * Muestra el perfil del transportista
     * Fecha: 29-07-2016
     * Autor: OT
     */
    function verPerfilTransportista(idTransportista){
        $("#divElmentosGrupo").dialog({
            autoOpen: false,
            title: "Perfil",
            modal:true,
            width: 750,
            height: 340,
            close: function(event,ui){
                 $("#divElmentosGrupo").html('');
                 $("#divElmentosGrupo").dialog('destroy');
            },
            open:function(event,ui){
                waitingDialog();
                $.ajax({
                   type:"GET",
                   data:{'idTransportista':idTransportista},
                   url:"/admin/verPerfilTransportista",
                   success:function(respuesta){
                       $("#divElmentosGrupo").html(respuesta);
                       setTimeout(function(){closeWaitingDialog();},100);
                   }
                });
            }
	});
	$("#divElmentosGrupo").dialog('open');
    }

     
     /*
    * Guardar el grupo
    * Autor: OT
    * Fecha: 29-07-2016
    * 
    */
     function actualizarGrupo(){
        var token=$("#token").val();
        var idGrupo = $("#idGrupo").val();
        var nombreGrupo = $("#nombreGrupo").val();
        var idCliente = $("#idCliente").val();
        var prioridad = $("#prioridad").val();
        
        var cadenaIds="";
        var idsTRansportista = document.getElementsByName("idsTRansportista");
        
        if(nombreGrupo.replace(/\s/g,"")==""){
          swal("","Ingrese el nombre del grupo.","warning");
          return;
        }
        
        if(idCliente=="0"){
          swal("","Seleccione el cliente.","warning");
          return;
        }
        
        if(prioridad.replace(/\s/g,"")==""){
          swal("","Ingrese la prioridad del grupo.","warning");
          return;
        }
        
        if(prioridad=="" ||  !/^([0-9])*$/.test(prioridad)  || parseFloat(prioridad)<0 || isNaN(prioridad)){
                swal("","La prioridad no es valida, debe ser un número entero.","warning");
                return;
            }
        
        
        
        for (var i = 0; i < idsTRansportista.length; i++) {
           cadenaIds=cadenaIds+idsTRansportista[i].value+",";
        }
        
        if(cadenaIds==""){
            swal("","Agregue por lo menos un transportista.","warning");
            return;
        }
                
        waitingDialog(); 
           $.ajax({
               headers:{'X-CSRF-TOKEN':token},
               type:"POST",
               data:{'idsTRansportista':cadenaIds,'idCliente':idCliente,'nombreGrupo':nombreGrupo,'idGrupo':idGrupo,'prioridad':prioridad},
               url:"/admin/actualizarGrupo",
               success:function(respuesta){
                   setTimeout(function(){closeWaitingDialog();},100);
                   if(respuesta=="ok"){
                       swal({
                        title: "",   
                        text: "Grupo actualizado correctamente,¿Desea crear otro grupo?",
                        type: "success",   
                        showCancelButton: true,   
                        confirmButtonColor: "#47A447",
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar", 
                        closeOnConfirm: false,   
                        closeOnCancel: false
                        }, 
                        function(isConfirm){   
                            if (isConfirm) {
                                location.href="/admin/nuevoGrupo";
                            }else{
                               location.href="/admin/misGrupos";
                            }
                        });
                    }else if(respuesta="prioridadrepetida"){
                        swal("","Existe otro grupo con la misma prioridad, favor de verificar.","error");
                   }else if(respuesta="sintransportistas"){
                       swal("","Ocurrio un error al crear el grupo.","error");
                   }
               }
           });
     }

    
   
   /*
    * Sale de la ventana de nuevo grupo
    * Autor: OT
    * Fecha: 29-07-2016
    * 
    */
    function irPrincipalAdmin(){
        swal({
                title: "",   
                text: "¿Desea salir y perder los datos?",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#47A447",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar", 
                closeOnConfirm: true,   
                closeOnCancel: true }, 
                function(isConfirm){   
                    if (isConfirm) {
                        waitingDialog();
                        location.href="/admin/misGrupos";
                    }
                });
     }   