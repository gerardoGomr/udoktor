<div class="row">
    <div class="col-md-4 col-sm-5">
        <div>
            <div class="portlet-content">
                <p>
                    <h4 class="heading">{{ trans('leng.Informacion del anuncio') }}.</h4>
                    <table>
                        <tr>
                            <td style="width: 150px;"><font class="flet-lab"><b>{{ trans('leng.Num de envio') }}:</b></font></td>
                            <td><font class="flet-lab">{{ $shippingRequest->id }}</td>
                        </tr>
                        @if($inGroup)
                            <tr>
                                <td><font class="flet-lab"><b>{{ trans('leng.cliente') }}:</b></font></td>
                                <td><font class="flet-lab">{{ !is_null($shippingRequest->person->company) ? $shippingRequest->person->company : $shippingRequest->person->firstname . ' ' .$shippingRequest->person->lastname }}</td>
                            </tr>
                         @endif
                        <tr>
                            <td><font class="flet-lab"><b>{{ trans('leng.fecha de publicacion') }}:</b></font></td>
                            <td><font class="flet-lab">{{ Udoktor\Funciones::fechaF1Hora($shippingRequest->createdat) }}</td>
                        </tr>
                        <tr>
                            <td><font class="flet-lab"><b>{{ trans('leng.fecha de expiracion') }}:</b></font></td>
                            <td><font class="flet-lab">{{ Udoktor\Funciones::fechaF1Hora($shippingRequest->expirationdate) }}</td>
                        </tr>
                        <tr>
                            <td><font class="flet-lab"><b>{{ trans('leng.Tipo de costo') }}:</b></font></td>
                            <td><font class="flet-lab">{{ ($shippingRequest->costtype === 1) ? trans('leng.Fijo') : trans('leng.Sin precio') }}</td>
                        </tr>
                        <tr>
                            <td><font class="flet-lab"><b>{{ trans('leng.Costo') }}:</b></font></td>
                            <td><font class="flet-lab">{{ $shippingRequest->cost==""?"---":"S/ ".number_format($shippingRequest->cost, 2) }}</td>
                        </tr>
                    </table>
                </p>

                <p>
                    <h4 class="heading">{{ trans('leng.Informacion de recojo') }}.</h4>
                    <table>
                        <tr>
                            <td style="width: 155px;"><font class="flet-lab"><b>{{ trans('leng.Recoger el') }}:</b></font></td>
                            <td><font class="flet-lab">{{ \Udoktor\Funciones::fechaF1Hora($shippingRequest->collectionaddress->collectiondate)  }}</font></td>
                        </tr>

                        <tr>
                            <td style="width: 155px;"><font class="flet-lab"><b>{{ trans('leng.Hasta') }}:</b></font></td>
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
                            <td style="vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Lugar') }}:</b></font></td>
                            <?php
                            $mostarLugarRecoger = "";
                            switch ( $shippingRequest->collectionAddress()->first()->place ) {
                                case "1":
                                    $mostarLugarRecoger = trans('leng.Casa');
                                    break;
                                case "2":
                                    $mostarLugarRecoger = trans('leng.Empresa');
                                    break;
                                case "3":
                                    $mostarLugarRecoger = trans('leng.Puerto');
                                    break;
                                case "4":
                                    $mostarLugarRecoger = trans('leng.Area de construccion');
                                    break;
                                case "5":
                                    $mostarLugarRecoger = trans('leng.Aeropuerto');
                                    break;
                                case "6":
                                    $mostarLugarRecoger = ucfirst($shippingRequest->collectionAddress()->first()->anotherplace);
                                    break;
                            }


                            ?>
                            <td style="vertical-align:top"><font class="flet-lab">{{ $mostarLugarRecoger }}</font></td>
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
                </p>

                <p>
                    <h4 class="heading">{{ trans('leng.Informacion de entrega') }}.</h4>
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
                            <td style="vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Lugar') }}:</b></font></td>
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
                                    $mostarLugarEntregar = trans('leng.Area de construccion');
                                    break;
                                case "5":
                                    $mostarLugarEntregar = trans('leng.Aeropuerto');
                                    break;
                                case "6":
                                    $mostarLugarEntregar = ucfirst($shippingRequest->deliveryAddress()->first()->anotherplace);
                                    break;
                            }
                            ?>
                            <td style="vertical-align:top"><font class="flet-lab">{{ $mostarLugarEntregar }}</font></td>
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
                </p>
            </div> <!-- /.portlet-content -->
        </div>
    </div>

    <div class="col-md-8 col-sm-7">
        <div>
            <div class="portlet-content">
                <h4 class="heading">{{ trans('leng.Origen, destino e información de la ruta') }}.</h4>
                <div class="col-sm-12">
                    <div id="mapaEnvio" style="width: 650px; height: 410px;"></div>
                </div>
            </div> <!-- /.portlet-content -->
        </div>
        <div class="col-sm-12">
            <br>
            <div class="col-sm-12">
                <table>
                    <tr>
                        <td colspan="2">&nbsp;<img src="/img/dot-green.png" width='14px;'>&nbsp;<b><font class="flet-lab">{{ trans('leng.Origen') }}:&nbsp;</font></b></td>
                        <td><font class="flet-lab">{{  $shippingRequest->collectionAddress()->first()->generalubication }}.</font></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;<img src="/img/dot-red.png" width='14px;'>&nbsp;<b><font class="flet-lab">{{ trans('leng.Destino') }}:&nbsp;</font></b></td>
                        <td><font class="flet-lab">{{  $shippingRequest->deliveryAddress()->first()->generalubication }}.</font></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;<i class="fa fa-truck"></i>&nbsp;<b><font class="flet-lab">{{ trans('leng.Ruta') }}:&nbsp;</font></b></td>
                        <td><font class="flet-lab">{{ $shippingRequest->km ."&nbsp;". trans('leng.km'). "&nbsp;&nbsp;&nbsp;&nbsp;" . $shippingRequest->tiempo. "&nbsp;".trans('leng.hrs') }}.</font></td>
                    </tr>

                </table>
            </div>
        </div>
        <br><br>
            <div class="col-md-12">
            <div>
                 <div class="portlet-content">
                    <h4 class="heading">{{ trans('leng.Información de pago') }}.</h4>
                        <table style="width: 100%">
                               <tr>
                                   <td style="width: 90px;vertical-align:top">&nbsp;<b><font class="flet-lab">{{ trans('leng.Método') }}:&nbsp;</font></b></td>
                                   <td style="vertical-align:top"><font class="flet-lab">{{ $shippingRequest->paymentmethodid!=""?$shippingRequest->pago()->first()->method:trans('leng.No especificado') }}</font></td>
                               </tr>
                               <tr>
                                   <td style="width: 90px;vertical-align:top">&nbsp;<b><font class="flet-lab">{{ trans('leng.Condiciones') }}:&nbsp;</font></b></td>
                                   <td style="vertical-align:top"><font class="flet-lab">{{ $shippingRequest->paymentconditions!=""?$shippingRequest->paymentconditions:trans('leng.No especificado') }}</font></td>
                               </tr>

                        </table>
                </div> 
              </div>
            </div> 
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div>
            <div class="portlet-content">
                <h4 class="heading">{{ trans('leng.Articulos a enviar') }}.</h4>
                <font class="flet-lab"><b>{{ trans('leng.Numero total de articulos') }}:</b></font> <font class="flet-lab">&nbsp; {{ count($shippingRequest->items()->get()) }} </font><br>
                <?php
                $cadenaTonelada = "";
                if($shippingRequest->peso_total > 1000) {
                    $pesoTonelada   = round(($shippingRequest->peso_total / 1000), 3);
                    $cadenaTonelada = "(".$pesoTonelada ." ". trans("leng.tn").")";
                }
                ?>

                <font class="flet-lab"><b>{{ trans('leng.Peso total') }}:</b></font> <font class="flet-lab">&nbsp;{{ Udoktor\Funciones::formato_numeros($shippingRequest->peso_total, ',', '.') }}&nbsp;{{trans("leng.kg")}} &nbsp; {{ $cadenaTonelada }}</font>
                <br><br>

                <div class="table-responsive">
                    <table>
                        @if( count($shippingRequest->items()->get()) > 0)
                            <?php $items = $shippingRequest->items()->get();
                            ?>
                            @foreach ($items as $item)
                                <tr>
                                    @if ( count($item->image()->get()) === 0 )
                                        <td style="width: 250px;vertical-align:top">
                                            &nbsp;
                                        </td>
                                    @else
                                        @foreach($item->image()->get() as $image)
                                            <td style="width: 250px;vertical-align:top">
                                                <div class="form-group">
                                                    <img src="{{ '/imagenesEnvio/' . $shippingRequest->id . '/' . $image->filename }}" width="110" height="110">
                                                </div>
                                            </td>
                                        @endforeach
                                    @endif
                                    <td style="width: 300px; vertical-align:top">
                                        <font class="flet-lab"><b>{{ trans('leng.Número de unidades') }}: </b>&nbsp;{{ $item->quantity }}</font>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Empaquetado') }}: </b>&nbsp;{{ trans('leng.'.$item->description) }}</font>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Peso total del artículo') }}:</b>&nbsp;{{ ($item->weight * $item->quantity) . " " . trans('leng.'.$item->unitweight) }}</font>
                                    </td>
                                    <td style="width: 250px; vertical-align:top">
                                        <b><font class="flet-lab">{{ trans('leng.Dimensiones unitarias') }}</font></b>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Largo') }}: </b>&nbsp;{{ $item->long . " " . trans('leng.'.$item->unitdimensions) }}</font>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Alto') }}: </b>&nbsp;{{ $item->high . " " . trans('leng.'.$item->unitdimensions) }}</font>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Ancho') }}: </b>&nbsp;{{ $item->width . " " . trans('leng.'.$item->unitdimensions) }}</font>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Peso') }}: </b>&nbsp;{{ $item->weight . " " . trans('leng.'.$item->unitweight) }}</font>
                                    </td>
                                    <td style="width: 250px; vertical-align:top">
                                        <b><font class="flet-lab">{{ trans('leng.Detalles') }}</font></b>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Apilable') }}: </b>&nbsp;{{ $item->stackble ? 'Si' : 'No' }}</font>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Material peligroso') }}: </b>&nbsp;{{ $item->dangerous ? 'Si' : 'No' }}</font>
                                        <br>
                                        <font class="flet-lab"><b>{{ trans('leng.Perecedero') }}: </b>&nbsp;{{ $item->perishable ? 'Si' : 'No' }}</font>
                                    </td>
                                </tr>
                                @if($item->comments != "")
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="3" style="vertical-align:top"><font class="flet-lab"><b> {{ $item->comments }}</b></font><br></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4"><h4 class="heading"></h4></td>
                                </tr>

                            @endforeach
                        @endif

                        <?php
                        $cadenaServicios = "";
                        if($shippingRequest->deliveryaddress->callbefore) {
                            $cadenaServicios.=trans('leng.Llamar antes de la entrega').", ";
                        }
                        if($shippingRequest->deliveryaddress->deliverytime) {
                            $cadenaServicios.=trans('leng.Es necesario acordar un horario para la entrega').", ";
                        }
                        if($shippingRequest->coldprotection === true) {
                            $cadenaServicios.=trans('leng.Los articulos deben protegerse contra el frio').", ";
                        }
                        if($shippingRequest->sortout === true) {
                            $cadenaServicios.=trans('leng.Ordenar y separar').", ";
                        }
                        if($shippingRequest->blindperson === true) {
                            $cadenaServicios.=trans('leng.Es necesario coordinarse con una persona invidente').", ";
                        }
                        ?>
                        @if($cadenaServicios != "")
                            <?php //$cadenaServicios = substr($cadenaServicios,0,-2); ?>
                            <tr>
                                <td style="vertical-align:top"><font class="flet-lab"><b>{{trans('Servicios adicionales')}}:</b></font></td>
                                <td colspan="3" style="vertical-align:top"><font class="flet-lab text-primary">{{ $cadenaServicios }}.</font></td>
                            </tr>
                        @endif
                    </table><br>
                </div>
            </div> <!-- /.portlet-content -->
        </div>
    </div>
</div>