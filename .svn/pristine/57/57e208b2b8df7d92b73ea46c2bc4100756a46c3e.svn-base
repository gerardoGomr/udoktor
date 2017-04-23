@extends('layouts.prestadorServicios')
@section('titulo', 'Perfil')

@section('contenido')
<div class="content-header">
        <h2 class="content-header-title">{{trans("leng.Mi perfil")}}</h2>
      </div> <!-- /.content-header -->

      

      <div class="row">

        <div class="col-md-12">

          <div class="row">

            <div class="col-md-4 col-sm-5">

              <div class="thumbnail">
                <?php if(trim($datosUsuario["imagen"])==""){ ?>
                    <img src="/img/logousuario.png" alt="Profile Picture" />
                <?php }else{ ?>
                    <img src="<?php echo asset($datosUsuario["imagen"]); ?>" style="max-height: 200px;" alt="Profile Picture" />
                <?php } ?>
                
              </div> <!-- /.thumbnail -->

              <br />

              <div class="list-group">  

                  <a href="javascript:;" onclick="cargaInformacionUsuario();" class="list-group-item">
                  <i class="fa fa-pencil"></i> &nbsp;&nbsp;{{trans("leng.Información del usuario")}}

                  <i class="fa fa-chevron-right list-group-chevron"></i>
                </a> 

              
                <a href="javascript:;" onclick="formularioContrasenaUsuario();" class="list-group-item">
                  <i class="fa fa-lock"></i> &nbsp;&nbsp;{{trans("leng.Cambiar contraseña")}}

                  <i class="fa fa-chevron-right list-group-chevron"></i>
                </a> 
                  
                <a href="javascript:;" onclick="formularioNotificacionesPrestador();" class="list-group-item">
                  <i class="fa fa-bell"></i> &nbsp;&nbsp;{{trans("leng.Notificaciones")}}

                  <i class="fa fa-chevron-right list-group-chevron"></i>
                </a>
                  
              </div> <!-- /.list-group -->

            </div> <!-- /.col -->


            <div class="col-md-8 col-sm-7">
                    <h2>{{$datosUsuario["compania"]}}</h2>
                    <h4>{{$datosUsuario["nombre"]}}&nbsp;</h4>
                     <br>
                    <table>
                        <tr>
                            <td style="width: 20px;"><i class="icon-li fa fa-envelope"></i></td>
                            <td>{{$datosUsuario["correo"]}}</td>
                        </tr>
                        <tr>
                            <td><i class="icon-li fa fa-map-marker"></i> </td>
                            <td>{{$datosUsuario["ciudad"]}},&nbsp;{{$datosUsuario["nombreEstado"]}},&nbsp;{{$datosUsuario["nombrePais"]}}</td>
                        </tr>
                    </table>
                        <br>
                    <table>
                        <tr style="font-size: 0.9em;">
                            <td style="width: 200px;"><b>{{trans("leng.Fecha de última actualización")}}:</b></td>
                            <td>{{$datosUsuario["actualizacion"]}}</td>
                            
                        </tr>
                    </table>
                          
              <br>
              
              <div id="elementosPerfil">
                  
                  
                  
              </div>
              
              

            </div> <!-- /.col -->

          </div> <!-- /.row -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->        

@endsection

@section('otrosScripts')
    {!!Html::script('js/prestadorServicio/perfil.js')!!}  
@endsection

