@foreach ($serviceOffers as $serviceOffer)
    <?php
    $labelEstatus = '';
    $estatus      = '';

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
            $estatus      = trans("leng.Cancelada");
            break;
    }
    
    $recogerAtiempo=  \Udoktor\Funciones::validadFechasOferta($serviceOffer->id,1);
    $entregarAtiempo=  \Udoktor\Funciones::validadFechasOferta($serviceOffer->id,2);
                                                               
    if($recogerAtiempo==0){
        $celendarioRecoger=url("img/calendario_verde.png");
    }else{
        $celendarioRecoger=url("img/calendario_rojo.png");
    }
                                                               
    if($entregarAtiempo==0){
       $celendarioEntregar=url("img/calendario_verde.png");
    }else{
       $celendarioEntregar=url("img/calendario_rojo.png");
   }
                                                               
    ?>
    <tr>
        <td>{{ $serviceOffer->shippingRequest->title }}</td>
        <td>{{ !is_null($serviceOffer->shippingRequest->person->company) ? $serviceOffer->shippingRequest->person->company : $serviceOffer->shippingRequest->person->firstname . ' ' . $serviceOffer->shippingRequest->person->middlename . ' ' . $serviceOffer->shippingRequest->person->lastname }}</td>
        <td>S/ {{ number_format($serviceOffer->shipmentcost, 2) }}</td>
        <td>{{ Udoktor\Funciones::fechaF1Hora($serviceOffer->createdat) }}</td>
        <td>
            <img style="width: 22px;" src="<?php echo $celendarioRecoger; ?>">
            {{ Udoktor\Funciones::fechaF1Hora($serviceOffer->collectiondate) }} {{ strlen($serviceOffer->collectionuntildate) > 0 ? ' :: ' . Udoktor\Funciones::fechaF1Hora($serviceOffer->collectionuntildate) : '' }}
        </td>
        <td>
            <img style="width: 22px;" src="<?php echo $celendarioEntregar; ?>">
            {{ Udoktor\Funciones::fechaF1Hora($serviceOffer->deliverydate) }} {{ strlen($serviceOffer->deliveryuntildate) > 0 ? ' :: ' . Udoktor\Funciones::fechaF1Hora($serviceOffer->deliveryuntildate) : '' }}
        </td>
        <td><span class="label {{ $labelEstatus }} ui-popover" {!! $serviceOffer->status === 3 ? 'data-toggle="tooltip" data-placement="right" data-trigger="hover" data-content="' . $serviceOffer->reasonrejection . '" title="Motivo de rechazo"' : '' !!}>{{ $estatus }}</span></td>
        <td>
            <a href="{{ $serviceOffer->status === 2?url('transportista/mis-envios/' . $serviceOffer->shippingRequest->id. '/detalle'):url('transportista/ofertas/' . $serviceOffer->shippingRequest->id . '/detalle') }}" class="btn btn-info" title="Ver envío" target="_blank"><i class="fa fa-eye"></i></a>
            @if ($serviceOffer->status === 1)
                <button data-id="{{ $serviceOffer->id }}" type="button" class="cancelarOferta ul-tooltip btn btn-primary" title="Cancelar oferta" data-toggle="tooltip" data-original-title="Cancelar oferta" data-placement="top"><i class="fa fa-trash-o"></i></button>
            @endif
        </td>
    </tr>
@endforeach