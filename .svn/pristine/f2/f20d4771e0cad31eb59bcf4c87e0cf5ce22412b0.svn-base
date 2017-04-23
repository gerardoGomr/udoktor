<div class="header">
    <div class="col-md-12" style="font-size: 0.9em;" >
        <input type="hidden" id="idPersonaEnvia" value="<?php echo $idPersonaEnvia ?>">
        <input type="hidden" id="idPersonaRecibe" value="<?php echo $idPersonaRecibe ?>">
        <input type="hidden" id="idEnvio" value="<?php echo $idEnvio ?>">
        <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
        <div class="row">
            <div class="col-md-12">
                <table>
                    <tr>
                        <?php if(isset($enviado)){ ?> <td class="flet-lab" style="width: 80px;"><b>{{trans('leng.Para')}}</b>:</td>   
                        <?php } else{ ?>
                                  <td class="flet-lab" style="width: 80px;"><b>{{trans('leng.mensajede')}}</b>:</td> 
                                <?php } ?>

                        <td class="flet-lab">{{$nombreEnvia}}</td>
                    </tr>
                    <tr>
                        <td class="flet-lab" style="vertical-align:top"><b>{{trans('leng.Envío')}} : </b></td>
                        <td class="flet-lab" style="vertical-align:top">{{$tituloEnvio}}</td>
                    </tr>
                    <tr>
                        <td class="flet-lab"><b>{{trans('leng.Fecha')}}</b>:</td>
                        <td class="flet-lab">{{$fechaEnvia}}</td>
                    </tr>
                    <tr>
                        <td class="flet-lab" style="vertical-align:top"><b>{{trans('leng.Mensaje')}}</b>:</td>
                        <td class="flet-lab" style="text-align: justify;">{{$mensaje}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div class="row" id="divBotonesResponder">
            <div class="col-md-12">
             <?php if(!isset($enviado)){ ?>   <button type="button" class="btn btn-info btn-xs" onclick="mostrarCamposCaptura();"><i class='fa fa-mail-reply'></i><font class="flet-lab">&nbsp;{{trans("leng.Responder")}}</font></button> <?php } ?>
                <button type="button" class="btn btn-primary btn-xs" onclick="$('#leerMensajeClientePrincipal').dialog('close');"><i class="fa fa-times"></i><font class="flet-lab">&nbsp;{{trans("leng.Cerrar")}}</font></button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="divCajaRespuesta" style="display: none">
                    <label for="textarea-input" class="flet-lab">{{trans("leng.Respuesta")}}</label>
                    <textarea  id="textoMensaje" cols="7" rows="4" maxlength="200" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <br>
        <div class="row" id="divBotonesEnviarRespuesta" style="display: none">
            <div class="col-md-10">
                <button type="button" class="btn btn-success" onclick="responderMensajePrincipal()"><i class='fa fa-envelope-o'></i>&nbsp;{{trans("leng.Enviar")}}</button>&nbsp;
                <button type="button" class="btn btn-primary" onclick="$('#leerMensajeClientePrincipal').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{trans("leng.Cerrar")}}</button>
            </div>
        </div>

    </div>
</div>
         