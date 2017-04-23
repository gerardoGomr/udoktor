@extends('layouts.prestadorServicios')
@section('titulo', 'Inicio')

@section('contenido')
	<div class="row" style="font-size: 0.9em">
            <h2 class="heading content-header-title">{{ trans('leng.Calendiario - Citas') }}.</h2>

            <div class="col-md-3"><br><br>
                <div class="form-group">

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="checkboxEstado" id="checkPendiente" class="" checked value="1">
                            <font style="font-size: 13px;" class="label label-tertiary">Pendiente&nbsp;&nbsp;&nbsp;</font>
                        </label>
                    </div>
                  
                    <div class="checkbox">
                      <label>
                          <input type="checkbox" name="checkboxEstado" id="checkConfirmado" class="" checked value="2">
                        <font style="font-size: 13px;" class="label label-secondary">Confirmado</font>
                      </label>
                    </div>
                  
                    <div class="checkbox">
                      <label>
                          <input type="checkbox" name="checkboxEstado" id="checkExpirado" class="" checked value="3">
                          <font style="font-size: 13px;" class="label label-primary">Expirado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
                      </label>
                    </div>
                </div> 
                <br><br><br><br>
                <center><button type="button" onclick="cargarCitas();" class="btn btn-warning btn-lg"><i class="fa fa-refresh"></i>&nbsp;Actualizar calendario</button></center>
            </div>
            <div class="col-md-8">
                <div id="calendarioPrestador"></div>
            </div>
            <div class="col-md-1"></div>
            
            <div id="divModal"></div>
            
      </div> 
        

@endsection

@section('piePagina')
        
{!!Html::script('js/prestadorServicio/index.js')!!}  
@endsection


