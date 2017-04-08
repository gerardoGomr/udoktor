<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<br>
      <font style="color: #b9261e"><h3 class="title">{{trans("leng.Notificaciones")}}</h3></font>
      <p class="text-info">{{trans("leng.Seleccione los eventos de los cuales quiere recibir un correo eletrónico")}}.</p>
      <br>
      <div class="col-md-12" style="font-size: 0.9em">
         <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="checkbox-inline">
                              <input type="checkbox" id="nuevaCita" class="" <?php echo $datos["nuevaCita"] ?> > {{trans("leng.Nueva cita")}}
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="checkbox-inline">
                              <input type="checkbox" id="citaCancelada" class="" <?php echo $datos["cancelada"] ?> > {{trans("leng.Cita cancelada")}}
                            </label>
                        </div>
                      </div>
                </div>
        </div> 
          
        <br><br>
        <div class="row">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-success btn-sm" onclick="guardarNotificacionesPrestador()"><i class="fa fa-check-circle"></i>&nbsp;{{trans("leng.Guardar cambios")}}</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="$('#elementosPerfil').html('');$('#back-to-top').click();"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Cancelar")}}</button>
                </div>

                

        </div>
     </div>
              
        


{!!Html::script('Theme/js/demos/form-extended.js')!!}
  







