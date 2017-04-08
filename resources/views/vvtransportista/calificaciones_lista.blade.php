@if(!is_null($feedbacks))
    <table class="table table-striped table-bordered table-hover table-highlight table-checkable" id="tablaCalificaciones">
        <thead>
            <tr  class="bg-success">
                <th style="width: 35%">{{trans("leng.Envío")}}</th>
                <th style="width: 25%">{{trans("leng.Cliente")}}</th>
                <th style="width: 10%">{{trans("leng.Costo")}}</th>
                <th style="width: 5%">{{trans("leng.Rating")}}</th>
                <th style="width: 25%">{{trans("leng.Comentarios")}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feedbacks as $feedback)
                <tr>
                    <td>{{ $feedback->shipment->serviceOffer->shippingRequest->title }}</td>
                    <td>{{ $feedback->shipment->serviceOffer->shippingRequest->person->company }}</td>
                    <td>${{ number_format($feedback->shipment->shipmentcost, 2) }}</td>
                    <td>{{ $feedback->starrating }}</td>
                    <td>{{ $feedback->comment }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif