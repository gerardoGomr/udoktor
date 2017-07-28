@php
    use Udoktor\Domain\Users\User;
@endphp
@if(Auth::user()->hasServices())
    <div class="row clearfix">
        <div class="col-sm-4 col-xs-12 col-md-3 form-control-label">
            <label for="priceType" class="control-label">Tipo de precios para el prestador:</label>
        </div>
        <div class="col-sm-8 col-xs-12 col-md-5">
            <div class="form-group">
                <input type="radio" name="priceType" id="fixed" value="1" class="filled-in" {{ Auth::user()->getPriceType() === User::FIXED_PRICE ? 'checked' : '' }}>
                <label for="fixed">Mis Precios</label>
                &nbsp;&nbsp;
                <input type="radio" name="priceType" id="recommended" value="2" class="filled-in" {{ Auth::user()->getPriceType() === User::RECOMMENDED_PRICE ? 'checked' : '' }}>
                <label for="recommended">Sugeridos</label>
                &nbsp;&nbsp;
                <button type="button" class="btn btn-sm bg-teal waves-effect align-center btn-change-service-type"><i class="material-icons">cached</i> Asignar tipo</button>
            </div>
        </div>
    </div>
    <br>
    <table class="table table-condensed table-striped">
        <thead class="bg-red">
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Precios Establecidos</th>
                <th>Mis Precios</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach (Auth::user()->getServices() as $service)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $service->getService()->getName() }}</td>
                    <td>{{ $service->getService()->getDescription() }}</td>
                    <td>
                        <b>Precio sugerido:</b> ${{ number_format($service->getService()->getPrice() ?: '-', 2) }} <br>
                        <b>Min:</b> ${{ number_format($service->getService()->getMinPrice() ?: '-', 2) }}
                        <b>Max:</b> ${{ number_format($service->getService()->getMaxPrice() ?: '-', 2) }}
                    </td>
                    <td>
                        @if (Auth::user()->offersFixedPrices())
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">attach_money</i>
                                </span>
                                <div class="form-line">
                                    <input type="number" name="prices[]" class="price form-control" value="{{ $service->getPrice() }}" min="{{ $service->getService()->getMinPrice() }}" max="{{ $service->getService()->getMaxPrice() }}" data-id="{{ $service->getId() }}">
                                </div>
                            </div>
                        @elseif (Auth::user()->offersRecommendedPrices())
                            ${{ number_format($service->getPrice(), 2) }}
                        @endif
                    </td>
                    <td><button type="button" class="btn btn-sm bg-pink waves-effect remove-service" data-toggle="tooltip" title="Remover servicio" data-id="{{ $service->getId() }}"><i class="material-icons">delete</i></button></td>
                </tr>
            @endforeach
        </tbody>
        @if (Auth::user()->offersFixedPrices())
            <tfoot>
                <tr>
                    <td colspan="4">&nbsp;</td>
                    <td class="align-center"><button class="btn bg-light-green waves-effect btn-save-prices"><i class="material-icons">save</i> Guardar precios</button></td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        @endif
    </table>
@else
    <h4>No se ha agregado algún servicio.</h4>
@endif