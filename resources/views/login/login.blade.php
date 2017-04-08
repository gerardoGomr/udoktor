<!DOCTYPE html>
<html class="no-js">
<head>
  <title>Iniciar sesión</title>

  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">


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



  {!!Html::style('Theme/css/font-awesome.min.css')!!}
  {!!Html::style('Theme/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.min.css')!!}
  {!!Html::style('Theme/css/bootstrap.min.css')!!}
  {!!Html::style('Theme/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css')!!}
  <!-- App CSS -->
  {!!Html::style('Theme/css/target-admin.css')!!}
  {!!Html::style('Theme/css/custom.css')!!}

</head>
<div id="loadingScreen"></div>
<body class="account-bg fondoimg">

<div class="navbar" style="background: black">

  <div class="container">

    <div class="navbar-header" style="background: black; color: #fff">


    </div> <!-- /.navbar-header -->




    <div class="navbar-collapse collapse" style="background: black; color: #fff">
      <ul class="nav navbar-nav navbar-left">
        <li><a style="color:#fff;font-size: 20px !important"><i class="fa fa-envelope"></i> info@efletex.com</a></li>
        <li><a style="color:#fff;font-size: 20px !important"><i class="fa fa-mobile-phone"></i> +51-956-089-111</a></li>

      </ul>

      <ul class="nav navbar-nav navbar-right" >
        <li><a href="http://www.facebook.com/efletex" target="_blank" style="color:#fff;font-size: 20px !important"><i class="fa fa-facebook"></i></a></li>
        <li><a target="_blank" style="color:#fff;font-size: 20px !important"><i class="fa fa-twitter"></i></a></li>
        <li><a target="_blank" style="color:#fff;font-size: 20px !important"><i class="fa fa-pinterest"></i></a></li>
        <li><a target="_blank" style="color:#fff;font-size: 20px !important"><i class="fa fa-youtube"></i></a></li>
        <li><a target="_blank" style="color:#fff;font-size: 20px !important"><i class="fa fa-google-plus"></i></a></li>
        <li><a target="_blank" style="color:#fff;font-size: 20px !important"><i class="fa fa-instagram"></i></a></li>
      </ul>



    </div> <!--/.navbar-collapse -->

  </div> <!-- /.container -->

</div> <!-- /.navbar -->


<div class="account-wrapper">





    <div class="account-body">
      <center><img src="/img/efletex_logo.png" height="100px;"></center>
      <br>
      <h3 class="account-body-title">Bienvenido</h3>

      <h5 class="account-body-subtitle">Iniciar sesión</h5>

      <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">


      <div class="form-group">
          <div id="divmensaje">
              <?php if(isset($usuarioInvalido)){ ?>
                <div class='alert alert-danger'>Datos de sesión incorrectos, favor de verificar.</div>
              <?php }else if(isset($cuentainactiva)){ ?>
                <div class='alert alert-danger'>Su cuenta ha sido desactivada por el administrador.</div>
              <?php } else if (isset($verificado)&& $verificado=="1"){ ?>
                <div class='alert alert-success'><b>Cuenta verificada, ya puedes iniciar sesión.</b></div>
              <?php } ?>

          </div>
      </div>




      {!! Form::open(array('url' => 'login', 'method'=>'POST','id'=>'enviaUsuario', 'onsubmit'=>'return iniciarSesion();')) !!}
       {!! Form::hidden('origen','0',array('id'=>'origen'))!!}
        <div class="form-group">
          {!! Form::label('lusuario','Dirección de correo',array('class'=>'placeholder-hidden','for'=>'login-username')) !!}
          {!! Form::text('usuario','',array('id'=>'usuario','placeholder'=>'Email','tabindex'=>'1','class'=>'form-control','onkeypress'=>'validarEnter(event)'))!!}
        </div> <!-- /.form-group -->

        <div class="form-group">
          {!! Form::label('lpass','Contraseña',array('class'=>'placeholder-hidden','for'=>'login-password')) !!}
          {!! Form::password('password',array('id'=>'password','placeholder'=>'Contraseña','tabindex'=>'2','class'=>'form-control','onkeypress'=>'validarEnter(event)'))!!}
          <a href="/crearcuenta/olvidoCuenta">¿Olvidó su contraseña?</a>
        </div> <!-- /.form-group -->
        <div class="form-group">
          {!! Form::submit('Iniciar sesión',array('class'=>'btn btn-primary btn-block btn-lg','tabindex'=>'4')) !!}
        </div> <!-- /.form-group -->

        {!! Form::close() !!}
        <p>
            ¿No tienes una cuenta? &nbsp;
            <a href="/crearcuenta/" class="">Crea tu cuenta!</a>
      </p>

    </div> <!-- /.account-body -->

    <div class="account-footer">

    </div> <!-- /.account-footer -->

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
