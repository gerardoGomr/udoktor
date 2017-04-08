<html>
    <body>
        <?php 
         $ruta="/login/inicio";
        ?>
        <b>Bienvenido a la comunidad</b><br><br>
        Gracias <?php echo ucwords($clienteNombre); ?> por utilizar este servicio.
        Para iniciar sesión ingrese <a href="{{url($ruta)}}">aquí</a>.
        
    </body>
</html>
    


