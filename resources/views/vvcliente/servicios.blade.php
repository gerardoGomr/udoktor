@extends('layouts.cliente')
@section('titulo', 'Buscar servicios')

@section('contenido')
	<input type="hidden" id="green" value="{{ url('/img/dot-green.png') }}">
	<input type="hidden" id="red" value="{{ url('/img/dot-red.png') }}">
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Buscar servicios') }}</h2>
    <div class="row" style="font-size: 0.9em">    
    <div class="col-md-12">
    	<div id="defaultCountdown"></div>
    	<div class="col-md-3">
            <div class="portlet">
                <div class="portlet-header">
                    <label>{{ trans('leng.Filtros de Búsqueda')}}</label>
		</div>
		<div class="portlet-content">
                    <form action="{{ route('buscar-servicios') }}" id="formBusqueda">
                        <div class="form-group">
                            <label for="paisRecoleccion" class="control-label">{{ trans('leng.Pais') }}:</label>
                            <select name="paisRecoleccion" id="paisRecoleccion" class="form-control" data-url="{{ route('buscar-estados') }}">
				<option value="0">{{trans("leng.Todos")}}</option>
                                  <?php echo $vCombos["cadenaPaises"]; ?>
                            </select>
			</div>
			<div class="form-group">
                            <label for="estadoRecoleccion" class="control-label">{{ trans('leng.Estado') }}:</label>
                            <select name="estadoRecoleccion" id="estadoRecoleccion" class="form-control" data-url="{{ route('buscar-ciudades') }}">
                                <option value="0">{{trans("leng.Todos")}}</option>
                                    <?php echo $vCombos["cadenaEstados"]; ?>
                            </select>
			</div>
			<div class="form-group">
                            <label for="ciudadRecoleccion" class="control-label">{{ trans('leng.Municipios') }}:</label>
                            <select name="ciudadRecoleccion" id="ciudadRecoleccion" class="form-control">
                                <option value="0">{{trans("leng.Todos")}}</option>
                                    <?php echo $vCombos["cadenaCiudades"]; ?>
                            </select>
			</div>
                        
                        <div class="form-group">
                            <label for="vEspecialidad" class="control-label">{{ trans('leng.Especialidad') }}:</label>
                            <select name="vEspecialidad" id="vEspecialidad" class="form-control">
                                <option value="0">{{trans("leng.Todos")}}</option>
                                    <?php echo $vCombos["cadenaEspecialidad"]; ?>
                            </select>
			</div>
                        <div class="form-group">
                            <label for="vServicios" class="control-label">{{ trans('leng.Servicios') }}:</label>
                            <select name="vServicios" id="vServicios" class="form-control">
                                <option value="0">{{trans("leng.Todos")}}</option>
                                    <?php echo $vCombos["cadenaServicio"] ?>
                            </select>
			</div>
			<br>
			<button type="button" id="buscar" class="btn btn-success btn-sm"><i class="fa fa-search"></i> {{ trans('leng.Buscar servicios') }}</button>
                    </form>
                </div>
           </div>
    	</div>
    	<div class="col-md-9">
            <div class="row">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3>{{trans("leng.Mapa")}}</h3>
                        <ul class="portlet-tools pull-right">
                            <li>
                                <input type="checkbox" id="ocultarMapa" checked="cheked" onclick="mostrarMapa()" />{{ trans('leng.Mostrar Mapa') }}
		            </li>
                        </ul>
	            </div> 

		    <div class="portlet-content" id="rutaMapaDetalle" data-url="{{ url('transportista/ofertas/id/detalle') }}">
                        <div id="contenedorMapa" style="height:500px">
                            <div id="mapaServicios" style="height:100%"></div>
                        </div>
                    </div>
		</div>
            </div>
	    <div class="row">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3>{{trans('leng.Resultado')}}: <label id="resultados"></label>  {{trans('leng.Servicio(s)')}}</h3>
                    </div>
                    <div class="portlet-content">
                        <div class="table-responsive">
                            <table  id="listadoOfertas" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>{{trans("leng.Compania")}}</th>
                                        <th>{{trans("leng.Prestador del servicio")}}</th>
                                        <th>{{trans("leng.Ubicación")}}</th>
                                        <th>{{trans("leng.Teléfono")}}</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
             </div>
	</div>
    </div>
</div>
  
  
{!! Form::hidden('latitudUsuario',$person->latitude,array('id'=>'latitudUsuario'))!!}
{!! Form::hidden('longitudUsuario',$person->longitude,array('id'=>'longitudUsuario'))!!}

    
    <!-- Datos origen 
    {!! Form::hidden('latitudOrigen','',array('id'=>'latitudOrigen'))!!}
    {!! Form::hidden('longitudOrigen','',array('id'=>'longitudOrigen'))!!}
    {!! Form::hidden('numeroOrigen','',array('id'=>'numeroOrigen'))!!}
    {!! Form::hidden('calleOrigen','',array('id'=>'calleOrigen'))!!}
    {!! Form::hidden('coloniaOrigen','',array('id'=>'coloniaOrigen'))!!}
    {!! Form::hidden('municipioOrigen','',array('id'=>'municipioOrigen'))!!}
    {!! Form::hidden('estadoOrigen','',array('id'=>'estadoOrigen'))!!}
    {!! Form::hidden('paisOrigen','',array('id'=>'paisOrigen'))!!}
    {!! Form::hidden('cpOrigen','',array('id'=>'cpOrigen'))!!}
    -->
 
    
    <!-- Datos Destino 
    {!! Form::hidden('latitudDestino','',array('id'=>'latitudDestino'))!!}
    {!! Form::hidden('longitudDestino','',array('id'=>'longitudDestino'))!!}
    {!! Form::hidden('numeroDestino','',array('id'=>'numeroDestino'))!!}
    {!! Form::hidden('calleDestino','',array('id'=>'calleDestino'))!!}
    {!! Form::hidden('coloniaDestino','',array('id'=>'coloniaDestino'))!!}
    {!! Form::hidden('municipioDestino','',array('id'=>'municipioDestino'))!!}
    {!! Form::hidden('estadoDestino','',array('id'=>'estadoDestino'))!!}
    {!! Form::hidden('paisDestino','',array('id'=>'paisDestino'))!!}
    {!! Form::hidden('cpDestino','',array('id'=>'cpDestino'))!!}
    -->

@endsection


@section('piePagina')
		<div id="modalCargando" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<span><i class="fa fa-spinner fa-spin fa-5x"></i></span>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
@endsection


@section('otrosScripts')
	<script src="{{ asset('js/validator/jquery.validate.min.js') }}"></script>
     {!!Html::script('js/cliente/servicios.js')!!}   
@endsection