<option value="" selected>Seleccione</option>
<option value="-1">Otro</option>
@if(count($aUnits) > 0)
    @foreach($aUnits as $aUnit)
        <option value="{{ $aUnit->getId() }}">{{ $aUnit->getName() }}</option>
    @endforeach
@endif