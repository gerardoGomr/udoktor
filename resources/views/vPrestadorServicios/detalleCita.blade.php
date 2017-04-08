<?php

?>
<br>
<input type="hidden" value="{{$datosCita["idCita"]}}" id="idCita">
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<div class="row" style="font-size: 0.9em">
            <div class="col-sm-12" style="font-size: 0.9em">
                <div class="col-md-11">
                    <table class="table">
                        <tr>
                            <td style="width: 90px;vertical-align:top"><b>Cliente:</b></td>
                            <td colspan="3" style="vertical-align:top">{{$datosCita["compania"]}} / {{$datosCita["nombre"]}}</td>
                        </tr>
                        <tr>
                            <td style="width: 90px;vertical-align:top"><b>Fecha:</b></td>
                            <td style="vertical-align:top">{{$datosCita["fecha"]}}</td>
                            <td style="width: 90px;vertical-align:top"><b>Hora:</b></td>
                            <td style="vertical-align:top">{{$datosCita["hora"]}}</td>
                        </tr>
                        <tr>
                            <td style="width: 90px;vertical-align:top"><b>Cita:</b></td>
                            <td colspan="3" style="vertical-align:top">{{$datosCita["citaEn"]}}</td>
                        </tr>
                        <?php if($datosCita["citaEn"]=="A domicilio"){?>
                            <tr>
                                <td style="width: 90px;vertical-align:top"><b>Dirección:</b></td>
                                <td colspan="3" style="vertical-align:top">{{$datosCita["direccion"]}}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="width: 90px;vertical-align:top"><b>Informacion adicional:&nbsp;</b>
                                    {{$datosCita["info"]}}
                                </td>
                            </tr>
                        <?php } ?>
                        </table>
                    
                    <?php if($datosCita["expirado"]==0){ ?>
                        <table class="table">
                                <tr>
                                    <td colspan="2" style="vertical-align:top">
                                        <?php if($datosCita["aceptada"]==0){ ?>
                                            <div class="radio">
                                                <label>
                                                  <input type="radio" name="radioseUdoktor" value="1">
                                                  <b>Confirmar cita</b>
                                                </label>
                                            </div>
                                        <?php }  ?>
                                        <div class="radio">
                                            <label>
                                              <input type="radio" name="radioseUdoktor" value="2">
                                              <b>Rechazar cita</b>
                                            </label>
                                          </div>
                                    </td>
                                    <td colspan="2" style="vertical-align:top">
                                        <div class="form-group" style="display: none" id="divRechazo">
                                            <label for="textarea-input">Motivo de rechazo de la cita</label>
                                            <textarea maxlength="200" name="motivoid" id="motivoid" cols="10" rows="3" class="form-control"></textarea>
                                        </div>
                                    </td>
                                </tr>
                        </table>
                    <?php }else{ ?> 
                        <br><br><br><br><br><br><br><br>
                    <?php } ?> 
                </div>
            </div>
 </div> 

<div class="row" style="font-size: 0.9em">
   <div class="col-sm-12">
       <div class="col-sm-12">
           <?php if($datosCita["expirado"]==0){ ?>
                <button type="button" onclick="guardaCambioCita();" style="width: 110px;" class="btn btn-success btn-sm"><i class="fa fa-check-circle"></i>&nbsp;{{trans("leng.Guardar")}}</button>
           <?php }  ?>
        <button type="button" onclick="$('#divModal').dialog('close');" style="width: 110px;" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Cerrar")}}</button>
      </div>
   </div>
</div>


