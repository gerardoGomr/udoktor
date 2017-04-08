@extends('layouts.transportista')
@section('titulo', 'Estado de cuenta')

@section('contenido')
<div class="content-header">
        <h2 class="content-header-title">{{trans("leng.Estado de cuenta")}}</h2>
      </div> <!-- /.content-header -->

      

      <div class="row">

        <div class="col-md-12">

          <div class="row">

            <div class="col-md-4 col-sm-5">
                  <div class="pricing-plan pricing-plan-price">
                      <div class="pricing-plan-header" id="saldoTransportista">
                        <center>
                              <h3 class="pricing-plan-title">{{trans("leng.Saldo actual")}}</h3>
                              <span class="pricing-plan-price">S/ <?php echo $datosUsuario["saldo"]; ?></span>
                          <span class="pricing-plan-price-term">{{trans("leng.Disponible")}}:&nbsp;S/ <?php echo $datosUsuario["disponible"]; ?></span>
                      </center>
                    </div> <!-- /.pricing-header -->
                  </div> <!-- /.pricing -->

               

              

              <div class="list-group">  

                  <a href="javascript:;" onclick="cargaPromociones();" class="list-group-item">
                  <i class="fa fa-bookmark"></i> &nbsp;&nbsp;{{trans("leng.Promociones")}}

                  <i class="fa fa-chevron-right list-group-chevron"></i>
                </a> 

              
                <a href="javascript:;" onclick="historialCuenta();" class="list-group-item">
                  <i class="fa fa-book"></i> &nbsp;&nbsp;{{trans("leng.Historial")}}

                  <i class="fa fa-chevron-right list-group-chevron"></i>
                </a> 
              </div> <!-- /.list-group -->

            </div> <!-- /.col -->


            <div class="col-md-8 col-sm-7">
                    <h2>{{$datosUsuario["compania"]}}</h2>
                    <h5>{{$datosUsuario["nombre1"]}}&nbsp;{{$datosUsuario["nombre2"]}}&nbsp;{{$datosUsuario["apellido1"]}}&nbsp;{{$datosUsuario["apellido2"]}}&nbsp;</h5>
                    <p>
                    <?php if($datosUsuario["ruc"]!="") { ?>
                        <b>{{trans("leng.RUC")}}:&nbsp;</b>{{$datosUsuario["ruc"]}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php } ?>
                        
                    <?php if($datosUsuario["dni"]!="") { ?>
                    <b>{{trans("leng.DNI")}}:&nbsp;</b>{{$datosUsuario["dni"]}}
                    <?php } ?>
                    </p>
              
              
              <div id="elementosCuenta">
                  
                  
                  
              </div>
              
              

            </div> <!-- /.col -->

          </div> <!-- /.row -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->        

@endsection

@section('otrosScripts')
    {!!Html::script('js/estadoCuentaTransportista.js')!!}  
@endsection

