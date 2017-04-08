@extends('layouts.transportista')

@section('titulo', 'Publicar oferta')

@section('contenido')
    <script>
        var fechaR1="{{ $shippingRequest->collectionaddress->collectiondate }}"
        var fechaR2="{{ $shippingRequest->collectionaddress->collectionuntildate }}"
        var fechaE1="{{ $shippingRequest->deliveryaddress->deliverydate }}"
        var fechaE2="{{ $shippingRequest->deliveryaddress->deliveryuntildate }}"
    </script>
    <h2 class="heading content-header-title">{{ trans('leng.Generar nueva oferta') }}</h2>

    {{-- presentar datos de la oferta --}}
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <thead class="bg-danger">
                    <tr>
                       <th colspan="2">{{trans("leng.Datos del envío")}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>{{ trans('leng.cliente') }}:</strong></td>
                        <td>{{ !is_null($shippingRequest->person->company) ? $shippingRequest->person->company : $shippingRequest->person->firstname . ' ' .$shippingRequest->person->lastname }}</td>
                    </tr>

                    <tr>
                        <td><strong>{{ trans('leng.fecha de publicacion') }}:</strong></td>
                        <td>{{ Udoktor\Funciones::fechaF1Hora($shippingRequest->createdat) }}</td>
                    </tr>

                    <tr>
                        <td><strong>{{ trans('leng.Costo') }}:</strong></td>
                        <td>{{ $shippingRequest->cost==""?"---":'S/ '.number_format($shippingRequest->cost, 2) }}</td>
                    </tr>

                    <tr>
                        <td><strong>{{ trans('leng.Método de pago') }}:</strong></td>
                        <td>{{ $shippingRequest->paymentmethodid==""?trans('leng.No especificado'):$shippingRequest->pago()->first()->method }}</td>
                    </tr>

                    <tr>
                        <td><strong>{{ trans('leng.Condiciones de pago') }}:</strong></td>
                        <td>{{ $shippingRequest->paymentconditions==""?trans('leng.No especificado'):$shippingRequest->paymentconditions }}</td>
                    </tr>
                    
                    <tr>
                        <td><strong>{{ trans('leng.Finaliza') }}:</strong></td>
                        <td><div id="clock_oferta"></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <form action="{{ route('oferta-publicar') }}" id="formOferta" class="form-horizontal">
            <input type="hidden" id="precioPorOfertarOculto" name="precioPorOfertarOculto" value="-1">
            <input type="hidden" id="precioPorOfertarOcultoFormato" name="precioPorOfertarOcultoFormato" value="-1">
            <input type="hidden" id="idEnvioOculto" name="idEnvioOculto" value="<?php echo $shippingRequest->id  ?>">
        
        <p class="text-danger">{{trans("leng.Los campos marcados con * son obligatorios")}}.</p>

        <div class="portlet">
            <div class="portlet-header">
                <h3><i class="fa fa-info-circle"></i>{{trans("leng.Datos generales")}}</h3>
            </div>
            <div class="portlet-content">
                <div class="form-group">
                    <label class="control-label col-md-3" for="costoOferta">* {{trans("leng.Precio a ofertar")}}:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i id="faCurrency" class="fa fa-money"></i></span>
                            <input name="costoOferta" id="costoOferta" class="form-control" placeholder="0.00" autofocus onchange="obtenerPrecioOferta();">
                        </div>
                        @if ($shippingRequest->costtype === 1)
                            <input type="hidden" id="costType" value="1">
                            <input type="hidden" id="costoAOfertar" value="{{ $shippingRequest->getCostAvailableToOffer() }}">
                            <span class="text-muted">{{trans("leng.Precio menor o igual a")}} S/ {{ $shippingRequest->getCostAvailableToOffer() }}</span>
                        @else
                            <input type="hidden" id="costType" value="2">
                        @endif
                    </div>

                    {{-- <div class="col-md-2">
                        <select name="currency" id="currency" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="1">Pesos</option>
                        </select>
                    </div> --}}
                </div>

                

                <div class="form-group">
                    <label for="condiciones" class="control-label col-md-3">{{trans("leng.Notas al cliente")}}:</label>
                    <div class="col-md-8">
                        <textarea name="condiciones" id="condiciones" rows="7" class="form-control" placeholder="{{trans("leng.Descripción de las condiciones, restricciones, etcétera")}}" maxlength="200"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="condiciones" class="control-label col-md-3">{{trans("leng.Saldo mínimo en cuenta para ofertar")}}:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i id="faCurrency" class="fa fa-money"></i></span>
                            <input name="preioPorOfertar" id="preioPorOfertar" disabled class="form-control" placeholder="0.00" autofocus>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="portlet">
            <div class="portlet-header">
                <h3><i class="fa fa-info-circle"></i> {{trans("leng.Recolección de paquete")}}</h3>
            </div>
            <div class="portlet-content">
                <table>
                    <tr>
                            <td style="width: 155px;"><font class="flet-lab"><b>{{ trans('leng.Recoger el') }}:</b></font></td>
                            <td><font class="flet-lab">{{ \Udoktor\Funciones::fechaF1Hora($shippingRequest->collectionaddress->collectiondate)  }}</font></td>
                        </tr>

                        <tr>
                            <td style="width: 155px;"><font class="flet-lab"><b>{{trans("leng.Hasta")}}:</b></font></td>
                            <td><font class="flet-lab">{{ !empty($shippingRequest->collectionaddress->collectionuntildate) ? \Udoktor\Funciones::fechaF1Hora($shippingRequest->collectionaddress->collectionuntildate) : '-----'  }}</font></td>
                        </tr>

                    <tr>
                        <td style="width: 155px;"><font class="flet-lab"><b>{{ trans('leng.Horario') }}:</b></font></td>
                        <?php
                        $mostrarHorario1 = $shippingRequest->collectionAddress()->first()->collecttimefrom;
                        if( $mostrarHorario1 != "") {
                            $mostrarHorario1 = trans('leng.Desde').': '.$mostrarHorario1;
                        }
                        $mostrarHorario2 = $shippingRequest->collectionAddress()->first()->collecttimeuntil;
                        if( $mostrarHorario2 != "") {
                            $mostrarHorario2 = trans('leng.Hasta').': '.$mostrarHorario2;
                        }
                        ?>
                        <td><font class="flet-lab">{{ $mostrarHorario1 }}<br>{{ $mostrarHorario2 }}</font></td>
                    </tr>

                    <tr>
                        <td><font class="flet-lab"><b>{{ trans('leng.Lugar') }}:</b></font></td>
                        <?php
                        $mostarLugarRecoger = "";
                        switch ( $shippingRequest->collectionAddress()->first()->place ) {
                            case "1":
                                $mostarLugarRecoger = trans("leng.Casa");
                                break;
                            case "2":
                                $mostarLugarRecoger = trans("leng.Empresa");
                                break;
                            case "3":
                                $mostarLugarRecoger = trans("leng.Puerto");
                                break;
                            case "4":
                                $mostarLugarRecoger = trans("leng.Area de construccion");
                                break;
                            case "5":
                                $mostarLugarRecoger = trans("leng.Aeropuerto");
                                break;
                            case "6":
                                $mostarLugarRecoger = trans("leng.Otro");
                                break;
                        }


                        ?>
                        <td><font class="flet-lab">{{ $mostarLugarRecoger }}</font></td>
                    </tr>
                    <tr>
                        <td><font class="flet-lab"><b>{{ trans('leng.recoger en interior') }}:</b></font></td>
                        <?php
                        $recogerDentro = trans('leng.No');
                        if ($shippingRequest->collectionAddress()->first()->collectinside === true){
                            $recogerDentro= trans('leng.Si');
                        }
                        ?>
                        <td><font class="flet-lab">{{ $recogerDentro }}</font></td>
                    </tr>


                    <tr>
                        <td><font class="flet-lab"><b>{{ trans('leng.Requiere elevador') }}:</b></font></td>
                        <?php
                        $requiereMontacarga = trans('leng.No');
                        if ($shippingRequest->collectionAddress()->first()->elevator === true){
                            $requiereMontacarga = trans('leng.Si');
                        }
                        ?>
                        <td><font class="flet-lab">{{ $requiereMontacarga }}</font></td>
                    </tr>
                </table>
                <br>
                <div class="form-group">
                    <label class="control-label col-md-3" for="formaRecoleccion">{{trans("leng.Forma de recojo")}}:</label>
                    <div class="col-md-8">
                        <div class="radio">
                            <label>
                                <input type="radio" name="formaRecoleccion" class="formaRecoleccion" value="1"> {{trans("leng.Dentro de una fecha determinada")}}
                            </label>
                        </div>

                        <div class="radio">
                            <label>
                                <input type="radio" name="formaRecoleccion" class="formaRecoleccion" value="2"> {{trans("leng.En un periodo de fechas")}}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="contenedorFechasRecoleccion" style="display: none;">
                    <label class="col-md-3 control-label">*{{trans("leng.Especifique")}}:</label>
                    <div class="col-md-2">
                        <input type="text" name="entreFechaRecoleccion" id="entreFechaRecoleccion" class="form-control fecha" placeholder="" readonly="readonly">
                    </div>

                    <div class="col-md-2">
                        <input type="text" name="yFechaRecoleccion" id="yFechaRecoleccion" class="form-control fecha" placeholder="" readonly="readonly"  style="display: none;">
                    </div>
                    <div class="col-md-2" id="semaforo"></div>
                </div>
            </div>
        </div>

        <div class="portlet">
            <div class="portlet-header">
                <h3><i class="fa fa-info-circle"></i> {{trans("leng.Entrega de paquete")}}</h3>
            </div>
            <div class="portlet-content">
                <table>
                    <tr>
                        <td style="width: 155px;"><font class="flet-lab"><b>{{ trans('leng.Entregar el') }}:</b></font></td>
                        <td><font class="flet-lab">{{ \Udoktor\Funciones::fechaF1Hora($shippingRequest->deliveryaddress->deliverydate) }}</font></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><font class="flet-lab"><b>{{trans("leng.Hasta")}}:</b></font></td>
                        <td><font class="flet-lab">{{ !empty($shippingRequest->deliveryaddress->deliveryuntildate) ? \Udoktor\Funciones::fechaF1Hora($shippingRequest->deliveryaddress->deliveryuntildate) : '------' }}</font></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><font class="flet-lab"><b>{{ trans('leng.Horario') }}:</b></font></td>
                        <?php
                        $mostrarHorario = $shippingRequest->deliveryAddress()->first()->deliverytimefrom;
                        if($mostrarHorario != ""){
                            $mostrarHorario = trans('leng.Desde').': '.$mostrarHorario;
                        }
                        $mostrarHorario2 = $shippingRequest->deliveryAddress()->first()->deliverytimeuntil;
                        if($mostrarHorario2 != ""){
                            $mostrarHorario2 = trans('leng.Hasta').': '.$mostrarHorario2;
                        }
                        ?>
                        <td><font class="flet-lab">{{ $mostrarHorario }}<br>{{ $mostrarHorario2 }}</font></td>
                    </tr>
                    <tr>
                        <td><font class="flet-lab"><b>{{ trans('leng.Lugar') }}:</b></font></td>
                        <?php
                        $mostarLugarEntregar = "";
                        switch($shippingRequest->deliveryAddress()->first()->place){
                            case "1":
                                $mostarLugarEntregar = trans('leng.Casa');
                                break;
                            case "2":
                                $mostarLugarEntregar = trans('leng.Empresa');
                                break;
                            case "3":
                                $mostarLugarEntregar = trans('leng.Puerto');
                                break;
                            case "4":
                                $mostarLugarEntregar = trans('leng.Area de construccion');break;
                            case "5":
                                $mostarLugarEntregar = trans('leng.Aeropuerto');
                                break;
                            case "6":
                                $mostarLugarEntregar = trans('leng.Otro');
                                break;
                        }
                        ?>
                        <td><font class="flet-lab">{{ $mostarLugarEntregar }}</font></td>
                    </tr>

                    <tr>
                        <td><font class="flet-lab"><b>{{ trans('leng.entregar en interior') }}:</b></font></td>
                        <?php
                        $entregarDentro = trans('leng.No');
                        if($shippingRequest->deliveryAddress()->first()->deliverywithin === true){
                            $entregarDentro = trans('leng.Si');
                        }
                        ?>
                        <td><font class="flet-lab">{{ $entregarDentro }}</font></td>
                    </tr>

                    <tr>
                        <td><font class="flet-lab"><b>{{ trans('leng.Requiere elevador') }}:</b></font></td>
                        <?php
                        $requiereMontacarga = trans('leng.No');
                        if($shippingRequest->deliveryAddress()->first()->elevator === true){
                            $requiereMontacarga = trans('leng.Si');
                        }

                        ?>
                        <td><font class="flet-lab">{{ $requiereMontacarga }}</font></td>
                    </tr>
                </table>
                <br>
                <div class="form-group">
                    <label class="control-label col-md-3" for="formaEntrega">{{trans("leng.Forma de entrega")}}:</label>
                    <div class="col-md-8">
                        <div class="radio">
                            <label>
                                <input type="radio" name="formaEntrega" class="formaEntrega" value="1"> {{trans("leng.Dentro de una fecha determinada")}}
                            </label>
                        </div>

                        <div class="radio">
                            <label>
                                <input type="radio" name="formaEntrega" class="formaEntrega" value="2"> {{trans("leng.En un periodo de fechas")}}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="contenedorFechasEntrega" style="display: none;">
                    <label class="control-label col-md-3">*{{trans("leng.Especifique")}}:</label>
                    <div class="col-md-2">
                        <input type="text" name="entreFechaEntrega" id="entreFechaEntrega" class="form-control fecha2" placeholder="" readonly="readonly">
                    </div>

                    <div class="col-md-2">
                        <input type="text" name="yFechaEntrega" id="yFechaEntrega" class="form-control fecha2" placeholder="" style="display: none;" readonly="readonly">
                    </div>
                    <div class="col-md-2" id="semaforo2"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <input type="hidden" name="id" id="id" value="{{ $shippingRequest->id }}">
            <div class="col-md-offset-3">
                {{ csrf_field() }}
                <button class="btn btn-primary" id="publicarOferta"><i class="fa fa-plus-square"></i> {{trans("leng.Publicar una oferta")}}</button>
                <a href="{{ url('transportista/ofertas/' . $shippingRequest->id . '/detalle') }}" class="btn btn-default" id="cancelar"><i class="fa fa-times"></i> {{trans("leng.Cancelar publicación")}}</a>
            </div>
        </div>
    </form>
@stop

@section('otrosScripts')
    <script src="{{ asset('js/validator/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/transportista/envio_ofertas_nueva.js') }}"></script>
    <script>
        var austDay=new Date('{!! $shippingRequest->expirationdate !!}');
        $('#clock_oferta').countdown({until: austDay,compact: true, description: ''});
    </script>
@stop