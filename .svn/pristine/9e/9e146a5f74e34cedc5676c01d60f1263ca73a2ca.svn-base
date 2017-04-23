    <div class="col-md-12" style="font-size: 0.9em">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                    <label for="text-input">{{trans("leng.Contacto")}}</label>
                    <input type="text" id="text-input" value="<?php echo $respuesta["contacto"]; ?>" class="form-control" disabled="">
                  </div>
                </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="text-input">{{trans("leng.DNI")}}</label>
                    <input type="text" id="text-input" value="<?php echo $respuesta["dni"]; ?>" class="form-control" disabled="">
                  </div>
              </div>    

            </div>
             <div class="row">
                 <div class="col-md-12">
                    <div class="form-group">
                    <label for="textarea-input">{{trans("leng.Comentarios")}}</label>
                    <textarea name="textarea-input" disabled= id="textarea-input" cols="10" rows="4" class="form-control"><?php echo $respuesta["comentarios"]; ?></textarea>
                  </div>
                 </div>
            </div>
                
            <div class="row">
                <div class="col-md-5">
                    <label for="textarea-input">{{trans("leng.Firma")}}</label>
                </div>
                <div class="col-md-5">
                    <?php if($respuesta["img1"]!=""){ ?>
                        <label for="textarea-input">{{trans("leng.Comentarios")}}</label>
                        <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <?php if($respuesta["firma"]!=""){ ?>
                    <a href="javascript:;" onclick="mostrarImagenGeneral('<?php echo url("/imagenesEnvio/$respuesta[id]/$respuesta[firma]") ?>');"><img src="<?php echo url("/imagenesEnvio/$respuesta[id]/$respuesta[firma]") ?>"  style="max-height: 100px; max-width: 100px;"></a>
                        <?php } ?>
                </div>
                <div class="col-md-5">
                    <?php if($respuesta["img1"]!=""){ ?>
                    <a href="javascript:;" onclick="mostrarImagenGeneral('<?php echo url("/imagenesEnvio/$respuesta[id]/$respuesta[img1]") ?>');" >    <img src="<?php echo url("/imagenesEnvio/$respuesta[id]/$respuesta[img1]") ?>"  style="max-height: 100px; max-width: 100px;"></a>
                        <?php } ?>
                </div>
            </div>
            <br>
            <div class="row">
                    <div class="col-md-6">
                        <button type="button"  class="btn btn-primary btn-sm" onclick="$('#divDetalleEnvioDatos').dialog('close');"><i class="fa fa-times-circle-o"></i>&nbsp;{{ trans('leng.Cerrar')}}</button>
                    </div>
            </div>
    </div>
    <div id="divElmentosListaLog"></div>
        