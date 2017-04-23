@extends('layouts.prestadorServicios')
@section('titulo', trans('leng.Mis servicios'))

@section('contenido')

<?php

 if($dataPerson->priceservice==true){
     $vtipo=0;
 }else {
     $vtipo=1;
 }
?>
<input type="hidden" value="<?php echo $vtipo; ?>" id="tipoOculto">
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">

    <div class="row" style="font-size: 0.9em">
            <h2 class="heading content-header-title">{{ trans('leng.Mis servicios') }}</h2>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <label for="text-input">{{ trans('leng.Usar precios') }}</label>
                        <select class="form-control" id="tipoCosto" onchange="cargarServicios(this.value)">
                            <option value="0" <?php echo ($dataPerson->priceservice==true)?"selected":""; ?> >{{trans("leng.Mis precios")}}</option>
                            <option value="1" <?php echo ($dataPerson->priceservice==false)?"selected":""; ?>>{{trans("leng.Sugeridos")}}</option>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4"></div>
                </div>
            </div>
           <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-5">
                        <button type="button" onclick="agregarServicio();"  class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i>&nbsp;{{trans("leng.Agregar servicio")}}</button>
                    </div>
                </div>
            </div>
            
            <br> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-11" id="tablaServicios">
                    </div>    
                    <div  class="col-sm-4"></div>
                </div>
            </div>
            <br> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <button type="button" onclick="guardarServicios();" style="width: 110px;" class="btn btn-success btn-sm"><i class="fa fa-check-circle-o"></i>&nbsp;{{trans("leng.Guardar")}}</button>
                        <a type="button" href="{{url("/prestadorServicios")}}" style="width: 110px;" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i>&nbsp;{{trans("leng.Salir")}}</a>
                    </div>
                    <div class="col-sm-8"></div>
                </div>
            </div>
        
            <div id="divModal"></div>
            <div id="divNuevoServicio"></div>
      </div> 
        

@endsection

@section('piePagina')
        
{!!Html::script('js/prestadorServicio/misServicios.js')!!}  
@endsection