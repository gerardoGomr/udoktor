<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
<input type="hidden" name="idServicio" id="idServicio" value="{{$datosServicio["id"]}}">

    <div class="col-md-12 flet-lab">
            <div class="row flet-lab">
                <div class="col-sm-10">
                    <div class="form-group">
                        <label>{{ trans('leng.Nombre')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                        <input type="text" id="nombreServicio" maxlength="100" class="form-control" value="{{$datosServicio["name"]}}">
                    </div>

                    <div class="form-group">
                        <label>{{ trans('leng.Descripción')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                        <textarea name="descripcionServicio" id="descripcionServicio" maxlength="200" cols="10" rows="3" class="form-control">{{$datosServicio["description"]}}</textarea>
                    </div>
                </div>
            </div>
        <div class="row flet-lab">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label>{{ trans('leng.Precio mínimo')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                            <input type="text" id="minimo" maxlength="100" class="form-control" value="{{$datosServicio["minimo"]}}" onkeypress="return soloNumerosConDecimal(event);">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label>{{ trans('leng.Precio máximo')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                            <input type="text" id="maximo" maxlength="100" class="form-control" value="{{$datosServicio["maximo"]}}" onkeypress="return soloNumerosConDecimal(event);">
                        </div>
                    </div>
            </div>
        <div class="row flet-lab">
                <div class="col-sm-5">
                        <div class="form-group">
                            <label>{{ trans('leng.Precio sugerido')}}&nbsp;<font style="size: 0.7em;color: red">*</font></label>
                            <input type="text" id="sugerido" maxlength="100" class="form-control" value="{{$datosServicio["sugerido"]}}" onkeypress="return soloNumerosConDecimal(event);">
                        </div>
                    </div>
            </div>
        <br>
            <div class="row flet-lab">
                <div class="col-md-9">
                   <button type="button"  class="btn btn-success btn-sm" onclick="guardarServcioAdmin();"><i class="fa fa-check"></i>&nbsp;{{ trans('leng.Aceptar')}}</button>
                   <button type="button" class="btn btn-danger btn-sm" onclick="$('#divModal').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{ trans('leng.Cancelar')}}</button>
                </div>
            </div>
        
    </div>    

    
    
   
    