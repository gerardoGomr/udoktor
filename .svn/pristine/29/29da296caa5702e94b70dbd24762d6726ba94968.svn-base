@extends('layouts.transportista')

@section('titulo', 'Detalles del envío')

@section('contenido')
	<h2 class="heading content-header-title">{{ $shippingRequest->title }}</h2>

	@include('vvtransportista.envio_detalle_datos')

	<input type="hidden" id="green" value="{{ url('/img/dot-green.png') }}">
	<input type="hidden" id="red" value="{{ url('/img/dot-red.png') }}">
	<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
	<input type="hidden" id="latitudRecoger" value="{{ $coordsRecoger['latitud'] }}">
	<input type="hidden" id="longitudRecoger" value="{{ $coordsRecoger['longitud'] }}">
	<input type="hidden" id="latitudEntregar" value="{{ $coordsEntregar['latitud'] }}">
	<input type="hidden" id="longitudEntregar" value="{{ $coordsEntregar['longitud'] }}">
@endsection

@section('otrosScripts')
    <script src="{{ asset('js/transportista/envio_detalle.js') }}"></script>
@endsection