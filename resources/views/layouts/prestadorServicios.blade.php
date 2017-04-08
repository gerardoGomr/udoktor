<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <title> - @yield('titulo')</title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width">
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5UyM5feL_oL7pwodFUKGagZQieNXy3Ps"> type="text/javascript"></script>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <style>
      .loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('/img/loading.gif') 50% 50% no-repeat #313131;

        }
  </style>

    <script type="text/javascript">
      $(window).load(function() {
              $(".loader").fadeOut("slow");
      })
  </script>

  <script>


       function waitingDialog() { // I choose to allow my loading screen dialog to be customizable, you don't have to
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

  <!-- Plugin CSS -->
  {!!Html::style('Theme/js/plugins/morris/morris.css')!!}
  {!!Html::style('Theme/js/plugins/icheck/skins/minimal/blue.css')!!}
  {!!Html::style('Theme/js/plugins/select2/select2.css')!!}
  {!!Html::style('Theme/js/plugins/fullcalendar/fullcalendar.css')!!}
  {!!Html::style('Theme/js/plugins/magnific/magnific-popup.css')!!}
  {!!Html::style('Theme/js/plugins/fileupload/bootstrap-fileupload.css')!!}
  {!!Html::style('Theme/js/plugins/datepicker/datepicker.css')!!}
  {!!Html::style('Theme/js/plugins/simplecolorpicker/jquery.simplecolorpicker.css')!!}
  {!!Html::style('Theme/js/plugins/timepicker/bootstrap-timepicker.css')!!}
  {!!Html::style('Theme/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css')!!}
  {!!Html::style('Theme/css/demos/ui-notifications.css')!!}
  {!!Html::style('css/multiselect/bootstrap-multiselect.css')!!}


   <!-- App CSS -->
  {!!Html::style('Theme/css/target-admin.css')!!}
  {!!Html::style('Theme/css/custom.css')!!}
  {!!Html::style('Theme/css/demos/sliders.css')!!}

  {!!Html::style('Theme/js/plugins/sweetalert/dist/sweetalert.css')!!}


    <!-- Page CSS -->
  {!!Html::style('Theme/css/demos/ui-buttons.css')!!}
  {!!Html::style('Theme/css/efletex.css')!!}

  {!! Html::style('css/star-ratings/star-rating.min.css') !!}
  {!! Html::style('js/countdown/jquery.countdown.css') !!}

  @yield('otrosStyles')



  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<?php
$nombreLogueado="";
$correoLogueado="";
$costoPendiente=0;
   if (Auth::check()) {
          $idPerson = Auth::user()->personid;
           $dataPerson=Udoktor\V_person::find($idPerson);
           $dataUsers=Udoktor\User::where("personid","=",$idPerson)->get();

           if($dataPerson->company==""){
              $nombreLogueado= ucwords($dataPerson->fullname);
           }else{
              $nombreLogueado= strlen($dataPerson->company)>30?substr(ucwords($dataPerson->company),0,27)."...":$dataPerson->company;
           }

           foreach($dataUsers as $rowUsuario){
               $correoLogueado=$rowUsuario->email;
           }
           
           $imagenUsuario="";
           if(trim($dataPerson->img)==""){ 
               $imagenUsuario=asset("img/logousuario.png");
           }else{ 
               $imagenUsuario=asset($dataPerson->img);
           }
           
           
           $dataServices=Udoktor\V_personservice::where("personid","=",$idPerson)->where("cost","=",null)->count();
           if($dataServices>0)$costoPendiente=1;
           


   }else{
       Auth::logout();
       redirect('login/inicio');
   }
?>

<div class="loader"></div>
<div id="loadingScreen"></div>
<div id="modalNotificacionesGeneral"></div>
<body>
        <div class="navbar">
            <div class="container">

              <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                  <i class="fa fa-cogs"></i>
                </button>

                <a class="navbar-brand navbar-brand-image" href="/prestadorServicios">
                  <img src="{{ asset('img/efletex_logo.png') }}" alt="Efletex" height="50">
                </a>

              </div> <!-- /.navbar-header -->

              <div class="navbar-collapse collapse">
                  <!-- Menu izquierda alertas -->
                  <ul class="nav navbar-nav noticebar navbar-left" id="menuNotifiacionesPrestador">
                      <li class='dropdown'>
                        <a href='./page-notifications.html' class='dropdown-toggle' data-toggle='dropdown'>
                            <i class='fa fa-bell'></i>
                            <span class='navbar-visible-collapsed'>&nbsp;{{trans('leng.Notificaciones')}}&nbsp;</span>
                            <span class='badge'></span>
                        </a>
                        <ul class='dropdown-menu noticebar-menu' role='menu'>
                            <li class='nav-header'>
                                <div class='pull-left'>{{trans('leng.Notificaciones')}}</div>
                            </li>
                        </ul>
                    </li>
                  </ul>

               <!-- Menu izquierda mensajes -->
               <ul class="nav navbar-nav noticebar navbar-left" id="menuMensajesCabera"></ul>
               
               <!-- Menu izaquierda warnings -->
               <ul class="nav navbar-nav noticebar navbar-left" id="menuWarningsCabecera"></ul>
               

               
               
                <ul class="nav navbar-nav navbar-right">
                   
                  <!--
                  <li>
                    <a href="javascript:;">About</a>
                  </li>

                  <li>
                    <a href="javascript:;">Resources</a>
                  </li>
                  -->
                  <!--li class="dropdown navbar-profile">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                      Lenguaje
                      <i class="fa fa-caret-down"></i>
                    </a>

                     <ul class="dropdown-menu" role="menu">
                         <li><a href="{{ url('lang', ['en']) }}">Ingles</a></li>
                         <li><a href="{{ url('lang', ['es']) }}">Español</a></li>
                     </ul>
                  </li-->

                  <li>
                    <a href="javascript:;"><b>{{$nombreLogueado}}</b></a>
                  </li>
                  <li class="dropdown navbar-profile">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                      <img src="{{$imagenUsuario}}" class="navbar-profile-avatar" alt="">
                      <i class="fa fa-caret-down"></i>
                    </a>

                      <ul class="dropdown-menu" role="menu">
                      <!--<li>
                          <div class="col-md-12">
                              <div class="col-md-3">
                                  <img src="/img/logousuario.png" width="50px">
                              </div>
                              <div class="col-md-9 flet-lab">
                                  <b>{{$nombreLogueado}}</b><br>
                                  <font class="flet-lab">{{$correoLogueado}}</font>
                              </div>
                          </div>
                      </li>-->
                      <li>
                        <a href="{!! url('/miPerfil') !!}">
                          <i class="fa fa-user-md"></i>
                          &nbsp;&nbsp;<font class="flet-lab">{{trans("leng.Mi perfil")}}</font>
                        </a>
                      </li>
                      <li>
                        <a href="{!! url('/logout') !!}">
                          <i class="fa fa-sign-out"></i>
                          &nbsp;&nbsp;<font class="flet-lab">{{trans("leng.Cerrar sesión")}}</font>
                        </a>
                      </li>
                    </ul>
                  </li>

                </ul>
               
               <ul class="nav navbar-nav navbar-right" id="divSaldoTransportistaPrincipal">
                   
               </ul>

              </div> <!--/.navbar-collapse -->

            </div> <!-- /.container -->

        </div> <!-- /.navbar -->






    <div class="mainbar">

        <div class="container">

          <button type="button" class="btn mainbar-toggle" data-toggle="collapse" data-target=".mainbar-collapse">
            <i class="fa fa-bars"></i>
          </button>

          <div class="mainbar-collapse collapse">

            <ul class="nav navbar-nav mainbar-nav">
         <?php  if (Auth::check()) {
                    $idPerson = Auth::user()->personid;
                    $dataPerson=Udoktor\V_person::find($idPerson);

            ?>
                              <li class="">
                                <a href="/prestadorServicios">
                                  <i class="fa fa-calendar"></i>
                                  {{ trans('leng.Calendario') }}
                                </a>
                              </li>
                              <li class="">
                                <a href="{{url("prestadorServicios/misServicios")}}">
                                  <i class="fa fa-stethoscope"></i>
                                  {{ trans('leng.Mis servicios') }}
                                </a>
                              </li>
                              
                              <li class="dropdown">
                                <a href="#about" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
                                  <i class="fa fa-book"></i>
                                  {{ trans('leng.Mi agenda') }}
                                  <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                  <li><a href="{{url("prestadorServicios/agendaConfiguracion")}}">{{ trans('leng.Configuración') }}</a></li>
                                  <li><a href="{{url("prestadorServicios/misCitas")}}">{{ trans('leng.Citas') }}</a></li>
                                </ul>
                              </li>
         <?php

              }
         ?>
            </ul>

          </div> <!-- /.navbar-collapse -->

        </div> <!-- /.container -->

    </div> <!-- /.mainbar -->


    <div class="container">

        <div class="content">

          <div id="contenidoPaginaLay" class="content-container">
              <?php if($costoPendiente==1){?>
              <div class="alert alert-danger" id="mensajeAlertaServicio">
                  <strong><i class="fa fa-exclamation-triangle"></i>&nbsp;{{trans("leng.Advertencia")}}!</strong>&nbsp;{{trans("leng.No ha configurado el precio de su(s) servicio(s), para hacerlo presione")}}&nbsp;<a href="{{url("prestadorServicios/misServicios")}}">{{trans("leng.aquí")}}</a>.
              </div>
             <?php } ?>
              @section('contenido')
              @show
          </div> <!-- /.content-container -->

        </div> <!-- /.content -->

    </div> <!-- /.container -->


    <footer class="footer">

      <div class="container">

         @section('piePagina')
         @show

      </div> <!-- /.container -->

    </footer>

{!!Html::script('Theme/js/libs/jquery-1.10.1.min.js')!!}
{!!Html::script('Theme/js/libs/jquery-ui-1.9.2.custom.min.js')!!}
{!!Html::script('Theme/js/libs/bootstrap.min.js')!!}

  <!--[if lt IE 9]>
  <script src="./js/libs/excanvas.compiled.js"></script>
  <![endif]-->

  <!-- Plugin JS -->
  {!!Html::script('Theme/js/plugins/icheck/jquery.icheck.js')!!}
  {!!Html::script('Theme/js/plugins/select2/select2.js')!!}
  {!!Html::script('Theme/js/libs/raphael-2.1.2.min.js')!!}
  {!!Html::script('Theme/js/plugins/morris/morris.min.js')!!}
  {!!Html::script('Theme/js/plugins/sparkline/jquery.sparkline.min.js')!!}
  {!!Html::script('Theme/js/plugins/nicescroll/jquery.nicescroll.min.js')!!}
  {!!Html::script('Theme/js/plugins/fullcalendar/fullcalendar.min.js')!!}
  {!!Html::script('Theme/js/plugins/flot/jquery.flot.js')!!}
  {!!Html::script('Theme/js/plugins/flot/jquery.flot.orderBars.js')!!}
  {!!Html::script('Theme/js/plugins/flot/jquery.flot.pie.js')!!}
  {!!Html::script('Theme/js/plugins/flot/jquery.flot.stack.js')!!}
  {!!Html::script('Theme/js/plugins/flot/jquery.flot.tooltip.min.js')!!}
  {!!Html::script('Theme/js/plugins/flot/jquery.flot.resize.js')!!}
  {!!Html::script('Theme/js/demos/dashboard.js')!!}
  {!!Html::script('Theme/js/demos/calendar.js')!!}
  {!!Html::script('Theme/js/demos/charts/morris/area.js')!!}
  {!!Html::script('Theme/js/demos/charts/morris/donut.js')!!}
  {!!Html::script('Theme/js/target-account.js')!!}
  {!!Html::script('Theme/js/plugins/magnific/jquery.magnific-popup.min.js')!!}
  {!!Html::script('Theme/js/plugins/fileupload/bootstrap-fileupload.js')!!}
  {!!Html::script('Theme/js/demos/buttons.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/line.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/area.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/stacked-area.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/vertical.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/horizontal.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/stacked-vertical.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/stacked-horizontal.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/pie.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/donut.js')!!}
  {!!Html::script('Theme/js/demos/charts/flot/scatter.js')!!}
  {!!Html::script('Theme/js/libs/raphael-2.1.2.min.js')!!}
  {!!Html::script('Theme/js/plugins/morris/morris.min.js')!!}
  {!!Html::script('Theme/js/demos/charts/morris/bar.js')!!}
  {!!Html::script('Theme/js/demos/charts/morris/line.js')!!}
  {!!Html::script('Theme/js/plugins/parsley/parsley.js')!!}
  {!!Html::script('Theme/js/plugins/datepicker/bootstrap-datepicker.js')!!}
  {!!Html::script('Theme/js/plugins/timepicker/bootstrap-timepicker.js')!!}
  {!!Html::script('Theme/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js')!!}
  {!!Html::script('Theme/js/plugins/autosize/jquery.autosize.min.js')!!}
  {!!Html::script('Theme/js/plugins/textarea-counter/jquery.textarea-counter.js')!!}

  {!!Html::script('Theme/js/plugins/parsley/parsley.js')!!}
  {!!Html::script('Theme/js/plugins/howl/howl.js')!!}
  {!!Html::script('Theme/js/demos/ui-notifications.js')!!}
  {!!Html::script('Theme/js/demos/sliders.js')!!}

  {!!Html::script('Theme/js/plugins/datatables/jquery.dataTables.min.js')!!}
  {!!Html::script('Theme/js/plugins/datatables/DT_bootstrap.js')!!}
  {!!Html::script('Theme/js/plugins/tableCheckable/jquery.tableCheckable.js')!!}
  {!!Html::script('Theme/js/plugins/icheck/jquery.icheck.min.js')!!}


   <!-- App JS -->
  {!!Html::script('Theme/js/target-admin.js')!!}
  {!!Html::script('Theme/js/plugins/sweetalert/dist/sweetalert.min.js')!!}
  {!!Html::script('Theme/js/plugins/sweetalert/dist/sweetalert-dev.js')!!}
  {!! Html::script('js/multiselect/bootstrap-multiselect.js') !!}

  {!!Html::script('js/general.js')!!}
  {!!Html::script('js/prestadorServicio/notificacionesPrestador.js')!!}
  

 {!!Html::script('js/star-ratings/star-rating.js')!!}
  {!! Html::script('js/validator/jquery.validate.min.js') !!}

  {!! Html::script('js/marker-clusterer/src/markerclusterer.js') !!}
  {!! Html::script('js/countdown/jquery.plugin.js') !!}
  {!! Html::script('js/countdown/jquery.countdown.js') !!}
  @section('otrosScripts')
  @show







</body>
</html>
