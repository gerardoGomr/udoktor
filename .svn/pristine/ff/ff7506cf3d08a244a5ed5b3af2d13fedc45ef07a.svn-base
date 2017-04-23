<!DOCTYPE html>
<html class="no-js">
<head>
  <title>Verificación de cuenta</title>

  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
  
  {!!Html::style('Theme/css/font-awesome.min.css')!!}
  {!!Html::style('Theme/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.min.css')!!}
  {!!Html::style('Theme/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css')!!}
  {!!Html::style('Theme/css/bootstrap.min.css')!!}
  
  <!-- App CSS -->
  {!!Html::style('Theme/css/target-admin.css')!!}
  {!!Html::style('Theme/css/custom.css')!!}

  
  <script>
       function waitingDialog() { 
                $("#loadingScreen").dialog({
                    autoOpen: false,
                    title: "Espere un momento..",
                    modal:true,
                    width: 300,
                    height: 150,
                    buttons: {},
                    draggable: false,
                    resizable: false,
                    close: function(event,ui){
                        $("#loadingScreen").html('');
                        $("#loadingScreen").dialog('destroy');
                    },
                    open:function(event,ui){
                        $(this).parent().children().children('.ui-dialog-titlebar-close').hide();
                        $("#loadingScreen").html("<br><div class='progress progress-striped active'><div class='progress-bar progress-bar-primary' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%'><span class='sr-only'></span></div></div>");
                    }
                    });
                $("#loadingScreen").dialog('open');
            }
        function closeWaitingDialog() {
                $("#loadingScreen").dialog('close');
         }
  </script>
  
</head>
<div id="loadingScreen"></div>
<body class="account-bg fondoimg">

<div class="navbar">

  <div class="container">

    <div class="navbar-header">
      <a class="navbar-brand navbar-brand-image" href="#">
        
      </a>

    </div> <!-- /.navbar-header -->

    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">   
      </ul>
    </div> <!--/.navbar-collapse -->

  </div> <!-- /.container -->

</div> <!-- /.navbar -->



<br>
    
<div class="account-wrapper">
    <div class="account-body">
      <center><img src="/img/efletex_logo.png" height="100px;"></center>
      <br>
      <h3 class="account-body-title">Verificación de cuenta</h3>
      
      <b>Hola {{ $nombreCliente }}!!</b>  para poder iniciar sesión establece la contraseña de tu cuenta.

      <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
      
      
      <div class="form-group">
          <div id="divmensaje">
          </div>
      </div>
      
      
      {!! Form::open(array('url' => '/crearcuenta/confirmarPassword', 'method'=>'POST','id'=>'enviaPass', 'onsubmit'=>'return confirmarPass();')) !!}
       {!! Form::hidden('idUsuario',$idUsuariol,array('id'=>'idUsuario'))!!}
       {!! Form::hidden('nombreUsuario',$nombreCliente,array('id'=>'nombreUsuario'))!!}
       {!! Form::hidden('correoUsuario',$correoCliente,array('id'=>'correoUsuario'))!!}
       {!! Form::hidden('passTemp',$passCliente,array('id'=>'passTemp'))!!}
        <div class="form-group">
          {!! Form::label('lusuario','Contraseña',array('class'=>'placeholder-hidden','for'=>'login-username')) !!}
          {!! Form::password('pass1',array('id'=>'pass1','tabindex'=>'1','class'=>'form-control'))!!}
        </div> <!-- /.form-group -->

        <div class="form-group">
          {!! Form::label('lpass','Confirmar contraseña',array('class'=>'placeholder-hidden','for'=>'login-password')) !!}
          {!! Form::password('pass2',array('id'=>'pass2','tabindex'=>'2','class'=>'form-control'))!!}
        </div> <!-- /.form-group -->

        <div class="form-group">
          {!! Form::submit('Aceptar',array('class'=>'btn btn-primary btn-block btn-lg','tabindex'=>'4')) !!}
        </div> <!-- /.form-group -->

        {!! Form::close() !!}
        
    </div> <!-- /.account-body -->


  </div> <!-- /.account-wrapper -->


  
{!!Html::script('Theme/js/libs/jquery-1.10.1.min.js')!!}
{!!Html::script('Theme/js/libs/jquery-ui-1.9.2.custom.min.js')!!}
{!!Html::script('Theme/js/libs/bootstrap.min.js')!!}

    <!-- App JS -->
  
{!!Html::script('Theme/js/target-admin.js')!!}
  <!-- Plugin JS -->
{!!Html::script('Theme/js/target-account.js')!!}
  
{!!Html::script('js/login.js')!!}

  
</body>
</html>
