@if (count($shippingRequest->has('questions')->get()) > 0)
    @foreach ($shippingRequest->questions()->orderBy('id', 'desc')->get() as $question)
        <div class="thumbnail">
            <div class="caption">
                <h5 class="text-info">{{ $question->shipper->person->firstname . ' ' . $question->shipper->person->lastname }} :</h5>
                <h5 class="text-muted">{{ $question->body }}</h5>
                <p>{{ strlen($question->answer) > 0 ? $question->answer : '-' }}</p>
            </div>
            <div class="thumbnail-footer">
                <p class="text-muted" style="font-size: 8pt;"><strong>Registrada: </strong><i class="fa fa-clock-o"></i> {{ Udoktor\Funciones::fechaF1Hora($question->createdat) }}</p>
            </div>
        </div>
    @endforeach 
@else 
    <h4>Aún no se han publicado preguntas.</h4>
@endif