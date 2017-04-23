<div class="header">
    <div class="col-md-12" style="font-size: 0.9em;" >
        <?php if($datosAlerta["tipo"]==1){ ?>
            <div class="row">
                <div class="col-md-12">
                   <p>{{trans("leng.El transportista")}}&nbsp;"{{$datosAlerta["solicitante"]}}" &nbsp;{{trans("leng.ha solicitado su ingreso a un grupo")}}.</p>
                   <p>{{trans("leng.Fecha")}}:&nbsp;{{$datosAlerta["fechaAccion"]}}.</p>
                    <center><p><a href="/admin/asginarGrupo/{{$datosAlerta["idsolicitud"]}}"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('leng.Ir a la solicitud')}}</a></p></center>
                </div>
            </div>
        <?php } else if($datosAlerta["tipo"]==2){ ?>
            <div class="row">
                <div class="col-md-12">
                   <p>{{trans("leng.El usuario")}}&nbsp;"{{$datosAlerta["solicitante"]}}" &nbsp;{{trans("leng.tiene pendiente su activación de cuenta")}}.</p>
                   <p>{{trans("leng.Cuenta tipo")}}:&nbsp;{{$datosAlerta["tipoCuenta"]}}.</p>
                   <p>{{trans("leng.Fecha")}}:&nbsp;{{$datosAlerta["fechaAccion"]}}.</p>
                   <center><p><a href="/admin/verificarCuenta/{{$datosAlerta["idsolicitud"]}}"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('leng.Ir a la cuenta')}}</a></p></center>
                </div>
            </div>
        <?php } ?>

        <br>
        <div class="row">
            <div class="col-md-10">
                <button type="button" class="btn btn-primary" onclick="$('#leerMensajeClientePrincipal').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{trans("leng.Cerrar")}}</button>
            </div>
        </div>

    </div>
 </div>
         