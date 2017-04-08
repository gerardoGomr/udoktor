@foreach ($serviceOffers as $serviceOffer)
    <?php
    $labelEstatus       = '';
    $estatus            = '';
    $accionRecoleccion  = '';
    $accionEntrega      = '';
    $accionCalificacion = '';

    switch ($serviceOffer->shippingRequest->status) {
        case 2:
            $labelEstatus = 'label-danger';
            $estatus      = trans('leng.Pendiente de asignación');
            $accionRecoleccion = '<button type="button" data-id="' . $serviceOffer->shipment->id . '" class="asignar btn btn-primary" title="Asignar vehículo"><i class="fa fa-exclamation"></i></button>';
            break;

        case 3:
            $labelEstatus = 'label-info';
            $estatus      = trans('leng.Pendiente de entrega');
            $accionEntrega = '<button type="button" data-id="' . $serviceOffer->shipment->id . '" class="entregar btn btn-primary" title="Entregar"><i class="fa fa-home"></i></button>';
            break;

        case 4:
            $labelEstatus = 'label-success';
            $estatus      = trans('leng.Entregado');

            if (!$serviceOffer->shipment->clienthasfeedback) {
                $accionCalificacion = '<button type="button" data-id="' . $serviceOffer->shipment->id . '" class="calificar btn btn-info" title="Calificar"><i class="fa fa-star"></i></button>';
            } else {
                $estatus .= ' // ' . trans("leng.Calificado");
            }
            break;
        case 7:
            $labelEstatus = 'label-success';
            $estatus      = trans('leng.Pendiente de recojo');

            
            $accionCalificacion = '<button type="button" data-id="' . $serviceOffer->shipment->id . '" class="recolectar btn btn-info" title="Recolectar"><i class="fa fa-truck"></i></button>';
            
            break;
    }
    ?>
    <tr>
        <td>{{ $serviceOffer->shippingRequest->title }}</td>
        <td>{{ !is_null($serviceOffer->shippingRequest->person->company) ? $serviceOffer->shippingRequest->person->company : $serviceOffer->shippingRequest->person->firstname . ' ' . $serviceOffer->shippingRequest->person->middlename . ' ' . $serviceOffer->shippingRequest->person->lastname }}</td>
        <td>{{ number_format($serviceOffer->shipmentcost, 2) }}</td>
        <td>{{ Udoktor\Funciones::fechaF1Hora($serviceOffer->createdat) }}</td>
        <td>{{ $serviceOffer->shipment->currency->name }}</td>
        <td>{{ $serviceOffer->shipment->vehicle->description or '-' }}</td>
        <td><span class="label {{ $labelEstatus }}">{!! $estatus !!}</span></td>
        <td>
            <a href="{{ url('transportista/mis-envios/' . $serviceOffer->shippingRequest->id . '/detalle') }}" class="btn btn-info" title="Ver envío" target="_blank"><i class="fa fa-eye"></i></a>
            {!! $accionRecoleccion !!}
            {!! $accionEntrega !!}
            {!! $accionCalificacion !!}
        </td>
    </tr>
@endforeach