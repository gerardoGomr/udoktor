@if (count($shippingRequests) > 0)
    @foreach($shippingRequests as $shippingRequest)
        <tr>
            <td>
                <a href="{{ url('transportista/ofertas/'.$shippingRequest->id.'/detalle') }}">{{ $shippingRequest->title }}</a>
                <br>
                <span style="font-size:12px"><i class="fa fa-dashboard"></i> {{ $shippingRequest->km }}{{trans("leng.km")}}.</span>
                <br>
                <span style="font-size:12px"><i class="fa fa-clock-o"></i> {{ $shippingRequest->tiempo }} {{trans("leng.hrs")}}.</span>
            </td>
            <td style="font-size:12px">{{ $shippingRequest->cost == "" ? trans("leng.Sin precio"): 'S/ ' . number_format($shippingRequest->cost, 2) }}</td>
            <td style="font-size:12px">{!! ucwords($shippingRequest->cityorigen).', '.$shippingRequest->stateorigen.', '.$shippingRequest->countryorigen.'<br>'.
                Udoktor\Funciones::fechaF3($shippingRequest->collectiondateorigen).($shippingRequest->collectionuntildateorigen==''?'':' '.trans('leng.al').' '.Udoktor\Funciones::fechaF3($shippingRequest->collectionuntildateorigen)).'<br>'.
                ($shippingRequest->collecttimefromorigen!=""?trans('leng.Desde').': ':'').Udoktor\Funciones::hora12($shippingRequest->collecttimefromorigen).($shippingRequest->collecttimeuntilorigen==''?'':'<br>'.trans('leng.Hasta').': '.Udoktor\Funciones::hora12($shippingRequest->collecttimeuntilorigen)) !!}</td>
            <td style="font-size:12px">{!! ucwords($shippingRequest->citydestino).', '.$shippingRequest->statedestino.', '.$shippingRequest->countrydestino.'<br>'.Udoktor\Funciones::fechaF3($shippingRequest->deliverydatedestino).($shippingRequest->deliveryuntildatedestino==''?'':' al '.Udoktor\Funciones::fechaF3($shippingRequest->deliveryuntildatedestino)).'<br>'.($shippingRequest->deliverytimedestino!=""?trans('leng.Desde').': ':'').Udoktor\Funciones::hora12($shippingRequest->deliverytimedestino).($shippingRequest->deliverytimeuntildestino==''?'':'<br>'.trans('leng.Hasta').': '.Udoktor\Funciones::hora12($shippingRequest->deliverytimeuntildestino)) !!}</td>
            <td style="font-size:12px"><div id="clock{!! $shippingRequest->id !!}"></div></td>
        </tr>
    @endforeach
@endif