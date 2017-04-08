/* 
 * script para promociones
 */
var oTable;

$(function() {
    $('#cal1').datepicker();
    $('#cal2').datepicker();

   oTable= $('#listaTablaPromociones').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        order: [[ 0, 'asc' ]],
        ajax: {
            url: '/admin/listaPromociones',
            data: function (d) {
                var buscaTitulo=$("#buscaTitulo").val();
                var fecha1=$("#fecha1").val();
                var fecha2=$("#fecha2").val();
                var activo=false;
                var inactivo=false;
                var expirado=false;
                
                if($('#activo').is(':checked')){
                    activo=true;
                }

                if($('#inactivo').is(':checked')){
                    inactivo=true;
                }
                
                if($('#expirado').is(':checked')){
                    expirado=true;
                }
                
                d.buscaTitulo = buscaTitulo;
                d.fecha1 = fecha1;
                d.fecha2 = fecha2;
                d.activo = activo;
                d.inactivo = inactivo;
                d.expirado = expirado;
               
            }
        },
        columns: [
            {data: 'descripcion', name: 'descripcion',orderable: true, searchable: false},
            {data: 'vigencia', name: 'vigencia',orderable: true, searchable: false},
            {data: 'monto', name: 'monto',orderable: true, searchable: false},
            {data: 'grupo', name: 'grupo',orderable: true, searchable: false},
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
    


 });
 

    /*
     * Busca promociones
     * 
   */
   function buscarPromociones(){
        oTable.draw();
    }

    /*
     * Limpia los filtros de busqueda
     */
    function restablecerFiltroPromociones(){
         $("#buscaTitulo").val("");
         $("#fecha1").val("");
         $("#fecha2").val("");
         $('#activo, #inactivo,#expirado').iCheck('uncheck');
         buscarPromociones();
     }
     
    /*
     * Muestra los datos de la promoción para su edición
     */
    function editarPromocion(idPromocion){
         location.href="/admin/editarPromocion/"+idPromocion;
     }
     
     /*
     * Muestra el formulario para crear la promocion
     */
    function nuevaPromocion(){
         location.href="/admin/nuevaPromocion/";
     }
     
     
     /*
    * Guardar cambios de promocion
    * Autor: OT
    * Fecha: 19-09-2016
    *
    */
   function agregarPromocion(){
       var idPromocion= $("#idPromocion").val();
       var codigo=$("#codigo").val();
       var grupoid=$("#grupoid").val();
       var vigencia=$("#vigencia").val();
       var monto=$("#monto").val();
       var descripcion=$("#descripcion").val();
       var estado=0;
       
       if(codigo.replace(/\s/g,"")==""){
           swal("","Ingrese el código de promoción","warning");
           return;
       }
       
       /*if (!/^( [A-Za-z][0-9])*$/.test(codigo)){
            swal("","El código solo debe contener letras y números.","warning");
            return;
        }*/
       
       
       
       
       if(vigencia.replace(/\s/g,"")==""){
           swal("","Indique la fecha de expiración de la promoción","warning");
           return;
       }
       
       if(monto=="" ||  !/^([0-9])*.([0-9])*$/.test(monto)  || parseFloat(monto)<0 || isNaN(monto)){
           swal("","El monto no es válido.","warning");
           return;
       }
       
       if(descripcion.replace(/\s/g,"")==""){
           swal("","Escriba la descripción de la promoción","warning");
           return;
       }
       
       if ($("#estado").is(":checked")) {
         estado=1;
       }
       
       var token=$("#token").val();
       waitingDialog();
        $.ajax({
            headers:{'X-CSRF-TOKEN':token},
           type:"POST",
           data:{'idPromocion':idPromocion,'codigo':codigo,'grupoid':grupoid,'vigencia':vigencia,'monto':monto,
               'descripcion':descripcion,'estado':estado},
	   url:"/admin/agregarPromocion",
           success:function(respuesta){
               setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   swal({
                        title: "",   
                        text: "Promoción guardada correctamente",
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
                                location.href="/admin/promociones";
                            }
                        });
                }else if(respuesta=="errorfecha"){
                    setTimeout(function(){swal("","La fecha de vigencia debe ser mayor a la fecha actual.","error");},100);
                }else if(respuesta=="errorcodigo"){
                    setTimeout(function(){swal("","El código de promoción ya existe, intente con otro.","error");},100);
                }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
               
           }
       });
       
   }
     
     
   /*
    * Guardar cambios de promocion
    * Autor: OT
    * Fecha: 19-09-2016
    *
    */
   function guardarCambiosPromocion(){
       var idPromocion= $("#idPromocion").val();
       var codigo=$("#codigo").val();
       var grupoid=$("#grupoid").val();
       var vigencia=$("#vigencia").val();
       var monto=$("#monto").val();
       var descripcion=$("#descripcion").val();
       var estado=0;
       
       if(codigo.replace(/\s/g,"")==""){
           swal("","Ingrese el código de promoción","warning");
           return;
       }
       
       /*if (!/^( [A-Za-z][0-9])*$/.test(codigo)){
            swal("","El código solo debe contener letras y números.","warning");
            return;
        }*/
       
       
       
       if(vigencia.replace(/\s/g,"")==""){
           swal("","Indique la fecha de expiración de la promoción","warning");
           return;
       }
       
       
       if(monto=="" ||  !/^([0-9])*.([0-9])*$/.test(monto)  || parseFloat(monto)<0 || isNaN(monto)){
           swal("","El monto no es válido.","warning");
           return;
       }
       
       if(descripcion.replace(/\s/g,"")==""){
           swal("","Escriba la descripción de la promoción","warning");
           return;
       }
       
       if ($("#estado").is(":checked")) {
         estado=1;
       }
       var token=$("#token").val();
       waitingDialog();
        $.ajax({
            headers:{'X-CSRF-TOKEN':token},
           type:"POST",
           data:{'idPromocion':idPromocion,'codigo':codigo,'grupoid':grupoid,'vigencia':vigencia,'monto':monto,
               'descripcion':descripcion,'estado':estado},
	   url:"/admin/guardarCambiosPromocion",
           success:function(respuesta){
               setTimeout(function(){closeWaitingDialog();},100);
               if(respuesta=="ok"){
                   swal({
                        title: "",   
                        text: "Promoción actualizada correctamente",
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
                                location.href="/admin/promociones";
                            }
                        });
               }else if(respuesta=="errorfecha"){
                    setTimeout(function(){swal("","La fecha de vigencia debe ser mayor a la fecha actual.","error");},100);
               }else if(respuesta=="errorcodigo"){
                    setTimeout(function(){swal("","El código de promoción ya existe, intente con otro.","error");},100);
               }else{
                   setTimeout(function(){swal("","Ocurrio un error en el proceso.","error");},100);
               }
               
           }
       });
       
   }
    
    
    
    /*
    * Activar / Desactivar promocion
    * Autor: OT
    * Fecha: 19-09-2016
    *
    */
   function activarDesactivarPromocion(idPromocion,activo){
       var titulo="";
       var respuesta="";
       
       if(activo==0){
           titulo="¿Esta seguro de desactivar la promoción?";
           respuesta="Promocion desactivada correctamente";
       }else{
           titulo="¿Esta seguro de activar la promoción?";
           respuesta="Promocion activada correctamente";
       }
       
       swal({
           title: "",   
           text: titulo,
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
               data:{'idPromocion':idPromocion,'activo':activo},
               url:"/admin/activarDesactivarPromocion",
               success:function(data){
                   setTimeout(function(){closeWaitingDialog();},100);
                   if(data=="ok"){
                       setTimeout(function(){swal("",respuesta,"success");},100);
                       buscarPromociones();
                   }else{
                       setTimeout(function(){swal("","Error al realizar el proceso","error");},100);
                   }

               }
           });
        }
     });
   }
   