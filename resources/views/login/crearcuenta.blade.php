<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <title>{{ trans("leng.Crea tu cuenta")}}</title>

  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5UyM5feL_oL7pwodFUKGagZQieNXy3Ps"> type="text/javascript"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
  
  <script src="https://www.google.com/recaptcha/api.js" async defer></script> 
  
  {!!Html::style('Theme/css/font-awesome.min.css')!!}
  {!!Html::style('Theme/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.min.css')!!}
  {!!Html::style('Theme/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css')!!}
  {!!Html::style('Theme/css/bootstrap.min.css')!!}
  {!!Html::style('css/multiselect/bootstrap-multiselect.css')!!}
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

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<div id="loadingScreen"></div>
<?php





$servicios=$servicios; // recibimos del controlador
$clasificaciones=$clasificaciones; // recibimos del controlador
$paises=$paises; // recibimos del controlador

$cadenaServicios="";
foreach($servicios as $rowServicio){
      $cadenaServicios.="<option value='$rowServicio->id'>$rowServicio->name</option>";
  }
  
$cadenaClasificacion="";
foreach($clasificaciones as $rowClasificaciones){
      $cadenaClasificacion.="<option value='$rowClasificaciones->id'>$rowClasificaciones->name</option>";
  }

$cadenaPaises="";
foreach($paises as $rowpaises){
      $cadenaPaises.="<option value='$rowpaises->id'>$rowpaises->name</option>";
  }


?>


<body class="account-bg fondoimg">

    <div class="navbar">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <i class="fa fa-cogs"></i>
          </button>

          

        </div> <!-- /.navbar-header -->

        <div class="navbar-collapse collapse">

          <ul class="nav navbar-nav navbar-right">   

            <li>
              <a href="../login/">
                <i class="fa fa-angle-double-left"></i> 
                &nbsp;Volver al inicio
              </a>
            </li> 

          </ul>
        </div> <!--/.navbar-collapse -->
      </div> <!-- /.container -->
    </div> <!-- /.navbar -->
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
   <br><br>
      <center>
        <div style="width: 47%">
        
        <div id="divPrincipal" class="account-body">
                <center><img src="../img/efletex_logo.png" height="100px;"></center>
                <div id="divmensaje"></div>
                
                <div id="divGeneral">
                <br>
                  <h4 class="heading">{{trans("leng.Información general")}}</h4>
                  <div class="row">
                       <div class="col-sm-6">
                           <input class="form-control" id="nombreCompleto" name="nombreCompleto" placeholder="{{trans('leng.Nombre completo (obligatorio)')}}" tabindex="1" />
                       </div>
                       <div class="col-sm-6">
                          <input class="form-control" id="emailCuenta" name="emailCuenta" placeholder="{{trans('leng.Email (obligatorio)')}}" tabindex="2" />
                      </div>
                  </div>
                  <br>
                  <div class="row">
                       <div class="col-sm-6">
                           <input class="form-control" id="companiaCuenta" name="companiaCuenta" placeholder="{{trans('leng.Compania (opcional)')}}" tabindex="3" />
                       </div>
                      <div class="col-sm-6">
                           <select class="form-control" id="paisCuenta" onchange='buscarEstado(this.value);' tabindex="4">
                            <option value="0" selected="">{{trans("leng.Seleccione el pais")}}</option>
                            <?php echo $cadenaPaises; ?>
                        </select>
                       </div>
                  </div>
                  <br>
                  <div class="row">
                       <div class="col-sm-6">
                          <select class="form-control" id="estadoCuenta" onchange='buscarCiudad(this.value);' tabindex="5">
                            <option value="0" selected="">{{trans("leng.Seleccione el estado")}}</option>
                            <?php  ?>
                        </select>
                      </div>
                      <div class="col-sm-6">
                           <select class="form-control" id="ciudadCuenta" tabindex="6">
                            <option value="0" selected="">{{trans("leng.Seleccione el municipio")}}</option>
                            <?php  ?>
                        </select>
                       </div>
                  </div>
                  <br>
                  <div class="row">
                       <div class="col-sm-6">
                          <input class="form-control" id="telefonoCuenta" name="telefonoCuenta" placeholder="{{trans('leng.Teléfono (obligatorio)')}}" tabindex="7" />
                      </div>
                      <div class="col-sm-6">
                           <input  type="password" class="form-control" id="passCuenta" name="passCuenta" placeholder="{{trans('leng.Contraseña (obligatorio)')}}" tabindex="8" />
                       </div>
                  </div>


                  <br>
                  <div class="row">
                       <div class="col-sm-6">
                          <div class="col-md-12" style="text-align:left">
                              <input type="checkbox" value="1" id="aceptaTerminos">&nbsp;{{trans('leng.Acepto términos y condiciones')}}
                         </div>
                      </div>
                  </div>

                  <br>
                  <div class="row">
                      <div class="btn-group">
                          <button id='continuar1' style="width: 300px;" onclick="mostrarTipoCuenta();" type="button" class="btn btn-warning btn-lg">{{trans("leng.Continuar")}}&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                  </div>
            </div>
                
             <div id="cargaTipoCuenta" style="display: none">
                <br>
                  <h4 class="heading">{{trans("leng.Tipo de cuenta")}}</h4>
                <div class="row">
                      <div class="btn-group">
                          <button  style="width: 300px;" onclick="crearCuentaClienteDiv();" type="button" class="btn btn-default btn-lg">{{trans("leng.Cliente")}}</button>
                        </div>
                    <div class="btn-group">
                          <button style="width: 300px;" onclick="masDatosPrestador();" type="button" class="btn btn-default btn-lg">{{trans("leng.Prestador de servicios")}}</button>
                        </div>
                  </div>
            </div>
          <br>
         <div id="divDatosPrestador" style="display: none">
            <h4 class="heading">{{trans("leng.Prestador de servicios")}}</h4>
            <div class="row">
               <div class="col-sm-6">
                   <select class="form-control" id="idClasificacion">
                       <option value="0" selected="">{{trans("leng.Seleccione clasificación")}}</option>
                       <?php echo $cadenaClasificacion; ?>
                   </select>
               </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" id="idUbicacion" type="text" readonly placeholder="{{trans("leng.Ubicaciónl")}}">
                        <span class="input-group-btn">
                          <button class="btn btn-secondary" type="button" onclick="mostrarMapaLogin();"><i class="fa fa-map-marker"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <br>
                <div class="row">
                   <div class="col-sm-6">
                       {{trans("leng.Servicios")}}
                       <select id="serviciosid" class="form-control" multiple="multiple">
                           <?php echo $cadenaServicios; ?>
                       </select>
                   </div>
                    <div class="col-sm-6">
                       
                   </div>
                </div>
           </div> 
            
            <div class="row" id="divCaptcha" style="display: none">
                <br>
                <div class="g-recaptcha" data-sitekey="6Lc9piUTAAAAAFBNrYcFr0-Tukw2GWBcr88sHxSy"></div>
            </div>
            
            
            <div class="form-group" id="divCrearCuentaCliente" style="display: none">
                <br>
                <button style="width: 300px;" onclick="crearCuentaCliente();" type="button" class="btn btn-success btn-lg">{{trans("leng.Crear cuenta")}}</button>
                <button style="width: 100px;" onclick="cancelarCreacionCuenta();" type="button" class="btn btn-primary btn-lg">{{trans("leng.Cancelar")}}</button>
            </div>
            
            <div class="form-group" id="divCrearCuentaPrestador" style="display: none">
                <br>
                <button style="width: 300px;" onclick="crearCuentaPrestador();" type="button" class="btn btn-success btn-lg">{{trans("leng.Crear cuenta")}}</button>
                <button style="width: 100px;" onclick="cancelarCreacionCuenta2();" type="button" class="btn btn-primary btn-lg">{{trans("leng.Cancelar")}}</button>
            </div>

            
            <p>
            {{trans("leng.¿Tienes una cuenta?")}} &nbsp;
            <a href="../login/" class="">{{trans("leng.Ingresa con tu cuenta!")}}</a>
            </p>
        </div>
        
            
         
            
        <div id="divRespuesta" class="account-body" style="display:none">
          <h4 class="heading" id="labelRespuesta"></h4>
          <p>
            <a href="../login/" class="">{{trans("leng.Ingresa con tu cuenta!")}}</a>
            </p>
        </div>
            
         
        
    <div id="divMapaLogin"></div>

          <div class="account-footer">
            
          </div> <!-- /.account-footer -->

    </div> <!-- /.account-wrapper -->
    
    <input type="hidden" id="latitudUbicacion" value="">
    <input type="hidden" id="longitudUbicacion" value="">
    
    
  </center>
    {!!Html::script('Theme/js/libs/jquery-1.10.1.min.js')!!}
    {!!Html::script('Theme/js/libs/jquery-ui-1.9.2.custom.min.js')!!}
    {!!Html::script('Theme/js/libs/bootstrap.min.js')!!}

    {!!Html::script('Theme/js/target-admin.js')!!}
    {!!Html::script('Theme/js/target-account.js')!!}

    {!!Html::script('js/login.js')!!}  
    {!!Html::script('js/general.js')!!}  
{!! Html::script('js/multiselect/bootstrap-multiselect.js') !!}

</body>
</html>
