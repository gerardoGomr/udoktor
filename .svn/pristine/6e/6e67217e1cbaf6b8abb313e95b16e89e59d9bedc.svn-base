 $(function() {
    consultaSaldoTransportistaPrincipal();
 });

 
 
/* Muestra el saldo disponible del transportista
    * Autor: OT
    * Fecha: 04-07-2016
    * 
    */
   function consultaSaldoTransportistaPrincipal(){
       $.ajax({
           type:"GET",
           url:"/transportista/consultaSaldoTransportista",
           success:function(respuesta){
               $("#divSaldoTransportistaPrincipal").html(respuesta);
           }
       });
   }
   
   
   
   
   
    
    