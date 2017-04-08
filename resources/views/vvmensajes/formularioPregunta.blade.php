<div class="header">
    <div class="col-md-12" style="font-size: 0.9em;" >
        <input type="hidden" id="idPersonaEnvia" value="{{$idPersonaEnvia}}">
        <input type="hidden" id="idPersonaRecibe" value="{{$idPersonaRecibe}}">
        <input type="hidden" id="idEnvio" value="{{$idEnvio}}">
        <input type="hidden" id="idOferta" value="{{$idOferta}}">
        <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
        <div class="row">
            <div class="col-md-12">
                <table>
                    <tr>
                        <td class="flet-lab" style="width: 80px;"><b>{{trans('leng.Para')}} : </b></td>
                        <td class="flet-lab">{{$nombreRecibe}}</td>
                    </tr>
                    <tr>
                        <td class="flet-lab" style="vertical-align:top"><b>{{trans('leng.Envío')}} : </b></td>
                        <td class="flet-lab" style="vertical-align:top">{{$tituloEnvio}}</td>
                    </tr>
                    <tr>
                        <td class="flet-lab"><b>{{trans('leng.No.Oferta')}} : </b></td>
                        <td class="flet-lab">{{$idOferta}}</td>
                    </tr>
                </table>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="textarea-input" class="flet-lab">{{trans("leng.Pregunta")}}</label>
                    <textarea  id="textoMensaje" cols="7" rows="4" maxlength="200" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-md-10">
                <button type="button" class="btn btn-success" onclick="enviarMensajeAccion()"><i class='fa fa-envelope-o'></i>&nbsp;{{trans("leng.Enviar")}}</button>&nbsp;
                <button type="button" class="btn btn-secondary" onclick="$('#divEnviarMensaje').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{trans("leng.Cancelar")}}</button>
            </div>
        </div>

    </div>
</div>
         