<div class="col-md-12" style="font-size: 0.9em;" >
    <input type="hidden" value="<?php echo $idPregunta  ?>" id="idPregunta">
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <div class="row">
        <table>
            <tr>
                <td class="flet-lab" style="vertical-align:top; width: 90px;">{{trans("leng.Cliente")}}:</td>
                <td class="flet-lab" style="vertical-align:top">{{$cliente}}</td>
            </tr>
            <tr>
                <td class="flet-lab" style="vertical-align:top">{{trans("leng.Envío")}}:</td>
                <td class="flet-lab" style="vertical-align:top">{{$tituloEnvio}}</td>
            </tr>
            <tr>
                <td class="flet-lab" style="vertical-align:top"><b>{{trans('leng.Pregunta')}} : </b></td>
                <td class="flet-lab" style="vertical-align:top"><b>{{$textoPregunta}}?</b></td>
            </tr>
        </table>    
    </div>
    <br>
    <?php if($respuesta==""){ ?>
        <div class="row">
            <div class="form-group">
                <h4>{{trans("leng.La prengunta no ha sido respondida")}}.</h4>
            </div>
        </div>
    <br><br>
     <?php } else { ?>
    <div class="row">
            <div class="form-group">
                <label style="font-size: 0.9em;" for="textarea-input">{{trans("leng.Respuesta")}}&nbsp;&nbsp;&nbsp;{{$fechaRespuesta}}</label>
                <textarea style="font-size: 0.9em;" id="textoRespuesta" cols="7" rows="4" maxlength="200" class="form-control" <?php if($respuesta!="")echo "disabled";?> >{{$respuesta}}</textarea>
            </div>
    </div>
    <?php }?>
    <center><p><a href="/transportista/ofertas/{{$idEnvio}}/detalle"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('Ir al envío')}}</a></p></center>
    <br>
    <div class="row">
        <div class="col-md-10">
            <button type="button" class="btn btn-primary" onclick="$('#divMostrarPreguntaTransportista').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{trans("leng.Cerrar")}}</button>
        </div>
    </div>
    
</div>
    
         