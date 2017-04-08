@if (count($shippingRequest->has('serviceOffers')->get()) > 0)
	<table class="table table-striped text-small">
		<thead class="bg-success">
			<tr>
				<th role="column">{{trans("leng.Transportista")}}</th>
				<th role="column">{{trans("leng.Oferta")}}</th>
				<th role="column">{{trans("leng.Fecha")}}</th>
				<th role="column">{{trans("leng.Recoger")}}</th>
				<th role="column">{{trans("leng.Entregar")}}</th>
				<th role="column">{{trans("leng.Estatus")}}</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($shippingRequest->serviceOffers()->where('status', '!=', 4)->orderBy('id', 'desc')->get() as $serviceOffer)
				<?php
				$fechaRecoleccion = Udoktor\Funciones::fechaF1Hora($serviceOffer->collectiondate);
				$fechaEntrega	  = Udoktor\Funciones::fechaF1Hora($serviceOffer->deliverydate);
				$labelEstatus	  = '';
				$estatus 		  = '';
				$linkEditar       = '';

				if (strlen($serviceOffer->collectionuntildate)) {
					$fechaRecoleccion .= ' :: ' . Udoktor\Funciones::fechaF1Hora($serviceOffer->collectionuntildate);
				}

				if (strlen($serviceOffer->deliveryuntildate)) {
					$fechaEntrega .= ' :: ' . Udoktor\Funciones::fechaF1Hora($serviceOffer->deliveryuntildate);
				}
				// =====================================================================

				switch ($serviceOffer->status) {
					case 1:
			            $labelEstatus = 'label-info';
			            $estatus      = trans('leng.Activa');
			            break;

			        case 2:
			            $labelEstatus = 'label-success';
			            $estatus      = trans('leng.Aceptada');
			            break;

			        case 3:
			            $labelEstatus = 'label-danger';
			            $estatus      = trans('leng.Rechazada');
			            break;

			        case 4:
			            $labelEstatus = 'label-warning';
			            $estatus      = trans('leng.Cancelada');
			            break;
				}

				?>
				<tr>
					<td>{{ $serviceOffer->shipper->person->firstname . ' ' . $serviceOffer->shipper->person->middlename . ' ' . $serviceOffer->shipper->person->lastname }}</td>
					<td>S/ {{ number_format($serviceOffer->shipmentcost, 2) }}</td>
					<td>{{ Udoktor\Funciones::fechaF1Hora($serviceOffer->createdat) }}</td>
					<td>{{ $fechaRecoleccion }}</td>
					<td>{{ $fechaEntrega }}</td>
					<td><span class="label {{ $labelEstatus }}">{{ $estatus }}</span></td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
    <h4>Aún no se han publicado ofertas.</h4>
@endif