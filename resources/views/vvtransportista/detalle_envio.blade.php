@extends('layouts.transportista')

@section('titulo', 'Detalles de la oferta')

@section('contenido')
	<h2 class="heading content-header-title">{{ $shippingRequest->title }}</h2>
        
        <div class="row">
            <div class="col-md-8">
                <a href="{{ url('transportista/ofertas') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> <strong>{{ trans('leng.Ver solicitudes de envío') }}</strong></a>
                @if($shippingRequest->status === 1)
                        @if(!$shippingRequest->hasExpired())
                                @if(!$shippingRequest->ispublic)
                                        @if($inGroup)
                                                <a href="{{ url('transportista/ofertas/nueva/' . $shippingRequest->id) }}" class="btn btn-success"><i class="fa fa-plus-square"></i> <strong>{{trans("leng.Publicar una oferta")}}</strong></a>
                                        @else
                                                <button type="button" class="btn btn-success" onclick="enviarSolicitud('{{ url('transportista/enviarSolicitud') }}','{{ $shippingRequest->id }}')"><i class="fa fa-group"></i> {{ trans('leng.Publicar una oferta') }}</button>
                                        @endif
                                @else
                                        <a href="{{ url('transportista/ofertas/nueva/' . $shippingRequest->id) }}" class="btn btn-success"><i class="fa fa-plus-square"></i> <strong>{{trans("leng.Publicar una oferta")}}</strong></a>
                                @endif
                        @endif
                @endif
            </div>
      </div>
        
        
    @include('vvtransportista.detalle_envio_datos')
    <!-- ============================================================================ -->
    @if(!$shippingRequest->ispublic)
		@if($inGroup)
			<h3 class="heading">{{trans("leng.Ofertass")}}</h3>
			@if($shippingRequest->status === 1)
				@if(!$shippingRequest->hasExpired())
		    		<a href="{{ url('transportista/ofertas/nueva/' . $shippingRequest->id) }}" class="btn btn-success"><i class="fa fa-plus-square"></i> <strong>{{trans("leng.Publicar una oferta")}}</strong></a>
				@endif
			@endif
		    <div id="seccionOfertas" style="max-height: 600px; overflow-y: scroll;">
		    	@include('vvtransportista.detalle_envio_ofertas')
		    </div>
		    <hr>
		    <br>
		    <!-- ============================================================================ -->
		    <h3 class="heading">{{trans("leng.Preguntas y respuestas")}}</h3>
		    <a href="#modalPregunta" data-toggle="modal" class="btn btn-danger"><i class="fa fa-edit"></i> <strong>{{trans("leng.Preguntar al cliente")}}</strong></a>
		    <div id="seccionPreguntas" style="max-height: 600px; overflow-y: scroll;">
		    	@include('vvtransportista.detalle_envio_preguntas')
			</div>

			
		@else
			<h3 class="heading">{{trans("leng.Ofertass")}}</h3>
			@if($shippingRequest->status === 1)
				@if(!$shippingRequest->hasExpired())

		    		<a onclick="enviarSolicitud('{{ url('transportista/enviarSolicitud') }}','{{ $shippingRequest->id }}')" class="btn btn-success"><i class="fa fa-plus-square"></i> <strong>{{trans("leng.Publicar una oferta")}}</strong></a>
				@endif
			@endif
		    <div id="seccionOfertas" style="max-height: 600px; overflow-y: scroll;">
		    	@include('vvtransportista.detalle_envio_ofertas')
		    </div>
		    <hr>
		    <br>
		    <!-- ============================================================================ -->
		    <h3 class="heading">{{trans("leng.Preguntas y respuestas")}}</h3>
		    <a onclick="enviarSolicitud('{{ url('transportista/enviarSolicitud') }}','{{ $shippingRequest->id }}')" data-toggle="modal" class="btn btn-danger"><i class="fa fa-edit"></i> <strong>{{trans("leng.Preguntar al cliente")}}</strong></a>
		    <div id="seccionPreguntas" style="max-height: 600px; overflow-y: scroll;">
		    	@include('vvtransportista.detalle_envio_preguntas')
			</div>
		@endif
	@else
            <h3 class="heading">{{trans("leng.Ofertass")}}</h3>
		@if($shippingRequest->status === 1)
			@if(!$shippingRequest->hasExpired())
	    		<a href="{{ url('transportista/ofertas/nueva/' . $shippingRequest->id) }}" class="btn btn-success"><i class="fa fa-plus-square"></i> <strong>{{trans("leng.Publicar una oferta")}}</strong></a>
			@endif
		@endif
	    <div id="seccionOfertas" style="max-height: 600px; overflow-y: scroll;">
	    	@include('vvtransportista.detalle_envio_ofertas')
	    </div>
	    <hr>
	    <br>
	    <!-- ============================================================================ -->
	    <h3 class="heading">{{trans("leng.Preguntas y respuestas")}}</h3>
	    <a href="#modalPregunta" data-toggle="modal" class="btn btn-danger"><i class="fa fa-edit"></i> <strong>{{trans("leng.Preguntar al cliente")}}</strong></a>
	    <div id="seccionPreguntas" style="max-height: 600px; overflow-y: scroll;">
	    	@include('vvtransportista.detalle_envio_preguntas')
		</div>
	@endif

	<input type="hidden" id="green" value="{{ url('/img/dot-green.png') }}">
	<input type="hidden" id="red" value="{{ url('/img/dot-red.png') }}">
	<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
	<input type="hidden" id="latitudRecoger" value="{{ $coordsRecoger['latitud'] }}">
	<input type="hidden" id="longitudRecoger" value="{{ $coordsRecoger['longitud'] }}">
	<input type="hidden" id="latitudEntregar" value="{{ $coordsEntregar['latitud'] }}">
	<input type="hidden" id="longitudEntregar" value="{{ $coordsEntregar['longitud'] }}">
@endsection

@section('otrosScripts')
    <script src="{{ asset('js/validator/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/transportista/detalle_envio.js') }}"></script>
@endsection

@section('piePagina')
	<div id="modalPregunta" class="modal fade">
	    <div class="modal-dialog">
	        <div class="modal-content">
	        	<form id="formPregunta" action="{{ route('envio-detalle-pregunta') }}">
	        		{{ csrf_field() }}
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                <h3 class="modal-title">{{trans("leng.Escriba su pregunta")}}</h3>
		            </div>
		            <div class="modal-body">
		                <div class="form-group">
		                    <label class="control-label" for="pregunta">{{trans("leng.Por favor, no escriba teléfonos, correos electrónicos o similares")}}:</label>
		                    <textarea name="pregunta" id="pregunta" class="form-control" rows="6" onkeypress="return soloLetras(event)"></textarea>
		                </div>
		            </div>
		            <div class="modal-footer">
		            	<input type="hidden" name="id" value="{{ $shippingRequest->id }}">
		                <button type="button" id="guardarPregunta" class="btn btn-primary">{{trans("leng.Guardar pregunta")}}</button>
		            </div>
	            </form>
	        </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@stop