<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Promociones') }}</h2>
    <div class="col-md-12 flet-lab">
            <div class="row">
                <div class="col-md-4">
                    <label>{{ trans('leng.Código, monto')}}</label>
                    <div class="row">
                        <div class="col-md-12">
                                  <input class="form-control" id="buscaTitulo" type="text">
                        </div>
                      </div>
                </div>
                <div class="col-sm-8">
                   <label>{{ trans('leng.Fecha')}}</label>
                    <div class="row">
                        <div class="col-md-5">
                            <div id="cal1" class="input-group date" data-auto-close="true" data-date-format="yyyy-mm-dd" data-date-autoclose="true">
                                <input class="form-control" type="text" id="fecha1">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div> 
                        <div class="col-md-1"><b>{{ trans('leng.A')}}</b></div>
                        <div class="col-md-5">
                            <div id="cal2" class="input-group date" data-auto-close="true"  data-date-format="yyyy-mm-dd" data-date-autoclose="true">
                                <input class="form-control" type="text" id="fecha2">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                   </div>
               </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarPromocionesTrans();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosPromocionesTrans();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
            </div>
            <div class="col-md-4">
                        <button type="button"  class="btn btn-success btn-sm" onclick="capturarCodigo();"><i class="fa fa-plus"></i>&nbsp;{{ trans('leng.Capturar código')}}</button>
            </div>
        </div>
        
        
    </div>
    
    <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="listaTablaPromociones" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 40%">{{ trans("leng.Código")}}</th>
                                         <th style="width: 30%">{{ trans("leng.Fecha")}}</th>
                                         <th style="width: 30%">{{ trans("leng.Monto")}}</th>
                                     </tr>
                                 </thead>
                                 <tbody>

                                 </tbody>
                             </table>
                        </div>
                       </div>
               </div> 
    
    <div class="row">
        <br><br><br><br><br><br><br>
    </div>
    
       
    </div>

    
    <div id="divPromocionAux"></div>
    
    {!!Html::script('js/estadoCuentaTransportista.js')!!}  
    