<div class="col-md-12" style="font-size: 0.9em;" >
    <input type="hidden" value="<?php echo $idPregunta  ?>" id="idPregunta">
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <div class="row">
        <table>
            <tr>
                <td class="flet-lab" style="vertical-align:top; width: 90px;">{{trans("leng.Transportista")}}:</td>
                <td class="flet-lab" style="vertical-align:top">{{$transportista}}</td>
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
    <div class="row">
            <div class="form-group">
                <label style="font-size: 0.9em;" for="textarea-input">{{trans("leng.Respuesta")}}&nbsp;&nbsp;&nbsp;{{$fechaRespuesta}}</label>
                <textarea style="font-size: 0.9em;" id="textoRespuesta" cols="7" rows="4" maxlength="200" class="form-control" <?php if($respuesta!="")echo "disabled";?> >{{$respuesta}}</textarea>
            </div>
    </div>
    <center><p><a href="/cliente/verOfertasEnvio/{{$idEnvio}}"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('Ir al envío')}}</a></p></center>
    
    <div class="row">
        <div class="col-md-10">
            <?php if($respuesta==""){ ?>
                 <button type="button" class="btn btn-success" onclick="responderPreguntaAccionListado()"><i class='fa fa-edit'></i>&nbsp;{{trans("leng.Responder")}}</button>&nbsp;
            <?php } ?>
            <button type="button" class="btn btn-primary" onclick="$('#divMostrarPreguntaTransportista').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{trans("leng.Cancelar")}}</button>
        </div>
    </div>
    
</div>
    
         