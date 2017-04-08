<br>
<input type="hidden" name="_token" value="{{ csrf_token()}}" id="token">
    <h2 class="heading content-header-title">{{ trans('leng.Historial') }}</h2>
    <div class="col-md-12 flet-lab">
            <div class="row">
                <div class="col-md-4">
                    <label>{{ trans('leng.Movimiento')}}</label>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="tipoMovimiento" class="form-control">
                                <option value="0">{{trans("leng.Seleccione")}}</option>
                                <option value="1">{{trans("leng.Abono a cuenta")}}</option>
                                <option value="2">{{trans("leng.Promocíon")}}</option>
                                <option value="3">{{trans("leng.Envío")}}</option>
                            </select>
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
                        <button type="button"  class="btn btn-secondary btn-sm" onclick="buscarHistorialTrans();"><i class="fa fa-search"></i>&nbsp;{{ trans('leng.Buscar')}}</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="restablecerFiltrosHistorialTrans();"><i class="fa fa-refresh"></i>&nbsp;{{ trans('leng.Reestablecer')}}</button>
            </div>
        </div>
        
    </div>
    
    <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="listaTablaHistorial" class="table table-striped table-bordered table-hover table-highlight table-checkable" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 40%">{{ trans("leng.Movimiento")}}</th>
                                         <th style="width: 25%">{{ trans("leng.Fecha")}}</th>
                                         <th style="width: 25%">{{ trans("leng.Monto")}}</th>
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
    
    