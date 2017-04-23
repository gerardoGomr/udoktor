@extends('layouts.transportista')
@section('titulo', 'Buscar Cargas')

@section('contenido')
	<input type="hidden" id="green" value="{{ url('/img/dot-green.png') }}">
	<input type="hidden" id="red" value="{{ url('/img/dot-red.png') }}">
    <input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Buscar Cargas') }}</h2>
<div class="row">    
    <div class="col-md-12">
    	<div id="defaultCountdown"></div>
    	<div class="col-md-3">
			<div class="portlet">
				<div class="portlet-header">
					<label>{{ trans('leng.Filtros de Búsqueda')}}</label>
				</div>
				<div class="portlet-content">
					<form action="{{ route('buscar-ofertas') }}" id="formBusqueda">
						<h4>{{ trans('leng.Recoger')}}</h4>
                                                
						<div class="form-group">
							<label for="paisRecoleccion" class="control-label">{{ trans('leng.Pais') }}:</label>
							<select name="paisRecoleccion" id="paisRecoleccion" class="form-control" data-url="{{ route('buscar-estados') }}">
								<option value="">{{trans("leng.Todos")}}</option>
								@foreach($paises as $pais)
									<option value="{{ $pais->id }}">{{ $pais->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label for="estadoRecoleccion" class="control-label">{{ trans('leng.Departamento') }}:</label>
							<select name="estadoRecoleccion" id="estadoRecoleccion" class="form-control">
								<option value="">{{trans("leng.Todos")}}</option>
							</select>
						</div>

						<div class="form-group">
							<label for="ciudadRecoleccion" class="control-label">{{ trans('leng.Ciudad') }}:</label>
							<input type="text" name="ciudadRecoleccion" id="ciudadRecoleccion" class="form-control">
						</div>
						<div class="form-group">
								<label for="fechaOrigen" class="control-label">{{ trans('leng.Fecha') }}:</label>
									<div id="cal1" class="input-group date" data-auto-close="true"  data-date-format="dd-mm-yyyy" data-date-autoclose="true">
                                         <input class="form-control" type="text" id="fechaOrigen" name="fechaOrigen">
                                         <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
						</div>
						<hr>
                                                <h4>{{ trans('leng.Entregar')}}</h4>
						<div class="form-group">
							<label for="paisEntrega" class="control-label">{{ trans('leng.Pais') }}:</label>
							<select name="paisEntrega" id="paisEntrega" class="form-control">
								<option value="">{{trans("leng.Todos")}}</option>
								@foreach($paises as $pais)
									<option value="{{ $pais->id }}">{{ $pais->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label for="estadoEntrega" class="control-label">{{ trans('leng.Departamento') }}:</label>
							<select name="estadoEntrega" id="estadoEntrega" class="form-control">
								<option value="">{{trans("leng.Todos")}}</option>
							</select>
						</div>

						<div class="form-group">
							<label for="ciudadEntrega" class="control-label">{{ trans('leng.Ciudad') }}:</label>
							<input type="text" name="ciudadEntrega" id="ciudadEntrega" class="form-control">
						</div>
						<div class="form-group">
						
								<label for="fechaDestino" class="control-label">{{ trans('leng.Fecha') }}:</label>
									<div id="cal2" class="input-group date col-md-12" data-auto-close="true"  data-date-format="dd-mm-yyyy" data-date-autoclose="true">
                                         <input class="form-control" type="text" id="fechaDestino" name="fechaDestino">
                                         <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
							
						</div>
						<hr>
						<h4>{{trans('leng.Rango de precios')}}</h4>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<input type="text" name="precioDesde" class="form-control" placeholder="desde" value="0">
								</div>

								<div class="col-md-6">
									<input type="text" name="precioHasta" class="form-control" placeholder="hasta" value="0">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="open" > {{ trans('leng.Sin precio') }}
								</label>
							</div>
						</div>
						<br>
						
						
						<button type="button" id="buscar" class="btn btn-success"><i class="fa fa-search"></i> {{ trans('leng.Buscar Ofertas') }}</button>
						<input type="hidden" name="orderBy" id="orderBy">
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
		              </ul> <!-- /.portlet-tools -->

		            </div> <!-- /.portlet-header -->

		            <div class="portlet-content" id="rutaMapaDetalle" data-url="{{ url('transportista/ofertas/id/detalle') }}">
			    		<div id="contenedorMapa" style="height:500px">
				    		<div id="mapaOfertas" style="height:100%"></div>
			    		</div>
			    	</div>
			    </div>
		    </div>
	    
		    <div class="portlet">
	        	<div class="portlet-header">
	        		<h3>{{trans('leng.Resultado')}}: <label id="resultados"></label>  {{trans('leng.Envío(s)')}}</h3>
	            </div>
	            <div class="portlet-content">
	               	<div class="table-responsive">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="ordenar" class="control-label">{{trans("leng.Ordenar por")}}:</label>
									<select name="ordenar" id="ordenar" class="form-control">
										<option value="">{{trans("leng.Sin orden")}}</option>
										<option value="1">{{trans("leng.Precio ascendente")}}</option>
										<option value="2">{{trans("leng.Precio descendente")}}</option>
										<option value="3">{{trans("leng.Origen de A-Z")}}</option>
										<option value="4">{{trans("leng.Origen de Z-A")}}</option>
										<option value="5">{{trans("leng.Destino de A-Z")}}</option>
										<option value="6">{{trans("leng.Destino de Z-A")}}</option>
										<option value="7">{{trans("leng.Kilómetros ascendente")}}</option>
										<option value="8">{{trans("leng.Kilómetros descendente")}}</option>
										<option value="9">{{trans("leng.Reciéntemente publicado")}}</option>
										<option value="10">{{trans("leng.Reciéntemente actualizado")}}</option>
										<option value="11">{{trans("leng.Más próximos a vencer")}}</option>
									</select>
								</div>
							</div>
						</div>
	                   <table  id="listadoOfertas" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
	                   		<thead>
	                   			<tr>
	                   				<th>{{trans("leng.Envío")}}</th>
	                   				<th>{{trans("leng.Precio")}}</th>
	                   				<th>{{trans("leng.Origen")}}</th>
	                   				<th>{{trans("leng.Destino")}}</th>
							<th>{{trans("leng.Finaliza")}}</th>
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
  
  


    
    <!-- Datos origen -->
    {!! Form::hidden('latitudOrigen','',array('id'=>'latitudOrigen'))!!}
    {!! Form::hidden('longitudOrigen','',array('id'=>'longitudOrigen'))!!}
    {!! Form::hidden('numeroOrigen','',array('id'=>'numeroOrigen'))!!}
    {!! Form::hidden('calleOrigen','',array('id'=>'calleOrigen'))!!}
    {!! Form::hidden('coloniaOrigen','',array('id'=>'coloniaOrigen'))!!}
    {!! Form::hidden('municipioOrigen','',array('id'=>'municipioOrigen'))!!}
    {!! Form::hidden('estadoOrigen','',array('id'=>'estadoOrigen'))!!}
    {!! Form::hidden('paisOrigen','',array('id'=>'paisOrigen'))!!}
    {!! Form::hidden('cpOrigen','',array('id'=>'cpOrigen'))!!}
    
 
    
    <!-- Datos Destino -->
    {!! Form::hidden('latitudDestino','',array('id'=>'latitudDestino'))!!}
    {!! Form::hidden('longitudDestino','',array('id'=>'longitudDestino'))!!}
    {!! Form::hidden('numeroDestino','',array('id'=>'numeroDestino'))!!}
    {!! Form::hidden('calleDestino','',array('id'=>'calleDestino'))!!}
    {!! Form::hidden('coloniaDestino','',array('id'=>'coloniaDestino'))!!}
    {!! Form::hidden('municipioDestino','',array('id'=>'municipioDestino'))!!}
    {!! Form::hidden('estadoDestino','',array('id'=>'estadoDestino'))!!}
    {!! Form::hidden('paisDestino','',array('id'=>'paisDestino'))!!}
    {!! Form::hidden('cpDestino','',array('id'=>'cpDestino'))!!}
    

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
    {!!Html::script('js/ofertas.js')!!}  
@endsection