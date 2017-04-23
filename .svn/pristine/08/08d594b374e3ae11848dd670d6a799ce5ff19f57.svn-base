<html>
    <body class="flet-lab">
        <p>{{trans("leng.Hola")}}&nbsp;<b>{{$usuario}}</b>.</p>
        <?php if($tipo==1){ ?>
        
                <p><b>{{trans("leng.Bienvenido a Efletex")}}</b>&nbsp;{{trans("leng.su cuenta ha sido activada")}}.</p>
                 <?php if($estransportista==1)$mensaje="leng.Para ver los envios, ofertar o preguntar ingrese a";
                       else $mensaje="leng.Para publicar envios ingrese a";
                 ?>   
            
            <p><b>{{trans($mensaje)}}:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>
            
            
        <?php }else if($tipo==2){ ?>
            
            <p><b>{{trans("leng.Bienvenido a Efletex")}}</b>&nbsp;{{trans("leng.su cuenta ha sido verficada")}}.</p>
                 <?php if($estransportista==1)$mensaje="leng.Para ver los envios, ofertar o preguntar ingrese a";
                       else $mensaje="leng.Para publicar envios ingrese a";
                 ?>   
            
            <p><b>{{trans($mensaje)}}:</b>&nbsp;<a href="http://efletex.com">www.efletex.com</a>.</p>
            
         <?php }else if($tipo==3){ ?>
            
                <p>{{trans("leng.Su cuenta ha sido desactivada por al administrador")}}.</p>
            
         <?php }?>
    </body>
</html>
    


