@if(count($shipper->vehicles()->get()) > 0 )
    <input type="hidden" id="url-eliminar" value="{{ route('mis-vehiculos-eliminar') }}">
    <input type="hidden" id="url-activar" value="{{ route('mis-vehiculos-activar') }}">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th role="columnheader">{{trans("leng.No")}}.</th>
            <th role="columnheader">{{trans("leng.Descripción")}}</th>
            <th role="columnheader">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shipper->vehicles()->get() as $vehicle)
            <tr>
                <td class="id">{{ $vehicle->id }}</td>
                <td class="description">{{ $vehicle->description }}</td>
                <td>
                    <button data-id="{{ $vehicle->id }}" class="editar btn btn-default" type="button" title="Editar"><i class="fa fa-edit"></i></button>
                    @if($vehicle->active)
                        <button data-id="{{ $vehicle->id }}" class="eliminar btn btn-default" type="button" title="Desactivar"><i class="fa fa-times-circle"></i></button>
                    @else
                        <button data-id="{{ $vehicle->id }}" class="activar btn btn-default" type="button" title="Activar"><i class="fa fa-reply"></i></button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <h3>{{trans("leng.Aún no se han agregado vehículos")}}.</h3>
@endif