<div class="row">
    <div class="col-md-4 col-sm-5">
        <div class="well">
            <h4>{{trans("leng.Seguimiento")}}</h4>
            <ul class="icons-list text-md">
                @foreach($shippingRequest->logs()->get() as $log)
                    <?php
                        $mensaje = '';
                        switch ($log->status) {
                            case 2:
                                $mensaje = trans('leng.Oferta por el envío fue aceptada');
                                break;

                            case 3:
                                $mensaje = trans('leng.Artículos a enviar recolectados');
                                break;

                            case 4:
                                $mensaje = trans('leng.Artículos entregados');
                                $shipment = $shippingRequest->serviceOffers()->where('status', 2)->first()->shipment;
                                $feedback = $shipment->feedbacks()->where('recipientid', Auth::user()->personid)->first();
                                !is_null($feedback) ? $calificacion = trans('leng.Calificación').': ' . $feedback->starrating .' ' .trans('leng.estrellas').'.<br>'.trans("leng.Comentarios") .': ' . substr($feedback->comment, 0, 150) : $calificacion = '';
                                break;

                            case 7:
                                $mensaje = trans('leng.Vehículo asignado');
                                break;
                        }
                    ?>
                    @if($log->status !== 1)
                        <li>
                            <i class="icon-li fa fa-location-arrow"></i>
                            <strong>{!! $mensaje !!}</strong>
                            <br />
                            <small>{{ Udoktor\Funciones::fechaF1Hora($log->createdat) }}</small>
                            @if(isset($calificacion) && strlen($calificacion))
                                <p><em class="text-success">{!! $calificacion !!}</em></p>
                            @endif
                            @if($log->status== 3)
                                <br>
                                <button type="button" onclick="verDetalleEnvioLog(<?php echo $log->shippingrequestid;?>,1)" class="btn btn-secondary btn-xs">{{trans("leng.Detalle")}}</button>
                                @if($log->longitude!=0)
                                <button type="button" onclick="vermapaEnvio(<?php echo $log->latitude;?>,<?php echo $log->longitude;?>,1)" class="btn btn-secondary btn-xs">{{trans("leng.Mapa")}}</button>
                                @endif
                            @endif
                            @if($log->status== 4)
                            <br>
                                <button type="button" onclick="verDetalleEnvioLog(<?php echo $log->shippingrequestid;?>,2)" class="btn btn-secondary btn-xs">{{trans("leng.Detalle")}}</button>
                                @if($log->longitude!=0)
                                    <button type="button" onclick="vermapaEnvio(<?php echo $log->latitude;?>,<?php echo $log->longitude;?>,2)" class="btn btn-secondary btn-xs">{{trans("leng.Mapa")}}</button>
                                @endif
                            @endif
                            
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div>
            <div class="portlet-content">
                <p>
                    <h4 class="heading">{{ trans('leng.Informacion del anuncio') }}.</h4>
                    <table>
                        <tr>
                            <td style="width: 150px;"><font class="flet-lab"><b>{{ trans('leng.Num de envio') }}:</b></font></td>
                            <td><font class="flet-lab">{{ $shippingRequest->id }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.cliente') }}:</b></font></td>
                            <td style="vertical-align:top"><font class="flet-lab">{{ !is_null($shippingRequest->person->company) ? $shippingRequest->person->company : $shippingRequest->person->firstname . ' ' .$shippingRequest->person->lastname }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.fecha de publicacion') }}:</b></font></td>
                            <td style="vertical-align:top"><font class="flet-lab">{{ Udoktor\Funciones::fechaF1Hora($shippingRequest->createdat) }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Tipo de costo') }}:</b></font></td>
                            <td style="vertical-align:top"><font class="flet-lab">{{ ($shippingRequest->costtype === 1) ? trans('leng.Fijo') : trans('leng.Sin precio') }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Costo') }}:</b></font></td>
                            <td style="vertical-align:top"><font class="flet-lab">{{ $shippingRequest->totalprice==""?"---":number_format($shippingRequest->totalprice, 2) }}</td>
                        </tr>
                    </table>
                </p>

                <p>
                    <h4 class="heading">{{ trans('leng.Informacion de recojo') }}.</h4>
                    <table>
                        <tr>
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Recoger el') }}:</b></font></td>
                            <td><font class="flet-lab">{{ \Udoktor\Funciones::fechaDMY($shippingRequest->serviceOffers()->where('status', 2)->first()->collectiondate)  }}</font></td>
                        </tr>
                        <tr>
                            <td style="width: 155px;"><font class="flet-lab"><b>{{trans("leng.Hasta")}}:</b></font></td>
                            <td><font class="flet-lab">{{ !empty($shippingRequest->serviceOffers()->where('status', 2)->first()->deliveryuntildate) ? \Udoktor\Funciones::fechaF1Hora($shippingRequest->serviceOffers()->where('status', 2)->first()->collectionuntildate) : '------' }}</font></td>
                        </tr>
                        <?php
                        $horarioRecoger="";
                        $horaRecoger=$shippingRequest->collectionAddress()->first()->collecttimefrom;
                        $horaRecogerHasta=$shippingRequest->collectionAddress()->first()->collecttimeuntil;
                        
                        if($horaRecoger!="" || $horaRecogerHasta!=""){
                            if($horaRecoger!="")$horarioRecoger.=trans("leng.Desde"). " " . $horaRecoger."<br>";
                            if($horaRecogerHasta!="")$horarioRecoger.=trans("leng.Hasta"). " " . $horaRecogerHasta;
                        }else{
                            $horarioRecoger='------';
                        }
                        ?>
                        
                        <tr>
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Horario') }}:</b></font></td>
                            <td><font class="flet-lab"><?php echo $horarioRecoger  ?></font></td>
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
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Contacto') }}:</b></font></td>
                            <td><font class="flet-lab"><?php echo $shippingRequest->collectionAddress()->first()->contactfullname  ?></font></td>
                        </tr>
                        
                        <tr>
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Telefono') }}:</b></font></td>
                            <td><font class="flet-lab"><?php echo $shippingRequest->collectionAddress()->first()->contactnumber1  ?></font></td>
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
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Entregar el') }}:</b></font></td>
                            <td><font class="flet-lab">{{ \Udoktor\Funciones::fechaDMY($shippingRequest->serviceOffers()->where('status', 2)->first()->deliverydate) }}</font></td>
                        </tr>
                        <tr>
                            <td style="width: 155px;"><font class="flet-lab"><b>{{trans("leng.Hasta")}}:</b></font></td>
                            <td><font class="flet-lab">{{ !empty($shippingRequest->serviceOffers()->where('status', 2)->first()->deliveryuntildate) ? \Udoktor\Funciones::fechaF1Hora($shippingRequest->serviceOffers()->where('status', 2)->first()->deliveryuntildate) : '------' }}</font></td>
                        </tr>
                        
                        <?php
                        $horarioEntregar="";
                        $horaEntregar=$shippingRequest->deliveryAddress()->first()->collecttimefrom;
                        $horaEntregarHasta=$shippingRequest->deliveryAddress()->first()->collecttimeuntil;
                        
                        if($horaEntregar!="" || $horaEntregarHasta!=""){
                            if($horaRecoger!="")$horarioEntregar.=trans("leng.Desde"). " " . $horaEntregar."<br>";
                            if($horaRecogerHasta!="")$horarioEntregar.=trans("leng.Hasta"). " " . $horaEntregarHasta;
                        }else{
                            $horarioEntregar="------";
                        }
                        ?>
                        
                        <tr>
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Horario') }}:</b></font></td>
                            <td><font class="flet-lab"><?php echo $horarioEntregar  ?></font></td>
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
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Contacto') }}:</b></font></td>
                            <td><font class="flet-lab"><?php echo $shippingRequest->deliveryAddress()->first()->recipientfullname  ?></font></td>
                        </tr>
                        
                        <tr>
                            <td style="width: 155px;vertical-align:top"><font class="flet-lab"><b>{{ trans('leng.Telefono') }}:</b></font></td>
                            <td><font class="flet-lab"><?php echo $shippingRequest->deliveryAddress()->first()->recipientcontactnumber1  ?></font></td>
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
                        <td><font class="flet-lab">{{  $shippingRequest->collectionAddress()->first()->streeUdoktor . ', ' . $shippingRequest->collectionAddress()->first()->estado()->first()->name .', '.$shippingRequest->collectionAddress()->first()->estado()->first()->country()->first()->name }}.</font></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;<img src="/img/dot-red.png" width='14px;'>&nbsp;<b><font class="flet-lab">{{ trans('leng.Destino') }}:&nbsp;</font></b></td>
                        <td><font class="flet-lab">{{ $shippingRequest->deliveryAddress()->first()->streeUdoktor . ', ' . $shippingRequest->collectionAddress()->first()->estado()->first()->name .', '.$shippingRequest->collectionAddress()->first()->estado()->first()->country()->first()->name }}.</font></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;<i class="fa fa-truck"></i>&nbsp;<b><font class="flet-lab">{{ trans('leng.Ruta') }}:&nbsp;</font></b></td>
                        <td><font class="flet-lab">{{ $shippingRequest->km ."&nbsp;". trans('leng.km'). "&nbsp;&nbsp;&nbsp;&nbsp;" . $shippingRequest->tiempo. "&nbsp;".trans('leng.hrs') }}.</font></td>
                    </tr>

                </table>
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
                                        <font class="flet-lab"><b>{{ trans('leng.Apilable') }}: </b>&nbsp;{{ $item->stackable ? 'Si' : 'No' }}</font>
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
                        $cadenaServicios="";
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
                        @if($cadenaServicios!="")
                            <?php $cadenaServicios=  substr($cadenaServicios,0,-2); ?>
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
<div id="divDetalleEnvioDatos">