<div class="header">
    <div class="col-md-12" style="font-size: 0.9em;" >
        <div class="row">
            <div class="col-md-12">
               <?php 
                  /*   Tipo
                   * 1= Lo ve el prestador del servicio, nueva cita
                  */
               
               if($datosAlerta["tipo"]==1) { ?>
                  <p>Le han solicitado una cita.</p>
                  <p>{{trans("leng.Cliente")}}:&nbsp;{{$datosAlerta["cliente"]}}.</p>
                  <p>{{trans("leng.Fecha cita")}}:&nbsp;{{$datosAlerta["fechaCita"]}}.</p>
                  <p>{{trans("leng.Hora cita")}}:&nbsp;{{$datosAlerta["horaCita"]}}.</p>
                  <center><p><a href="/prestadorServicios/misCitas"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('Ir a la cita')}}</a></p></center>
                        
                        
                        
               <?php }else if($datosAlerta["tipo"]==2) { ?>
                  <p>Su cita ha sido confirmada por {{$datosAlerta["prestador"] }}.</p>
                  <p>{{trans("leng.Fecha cita")}}:&nbsp;{{$datosAlerta["fechaCita"]}}.</p>
                  <p>{{trans("leng.Hora cita")}}:&nbsp;{{$datosAlerta["horaCita"]}}.</p>
                  <center><p><a href="/cliente/misCitas"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('Ir a mis citas')}}</a></p></center>      
                  
                  
               <?php } else if($datosAlerta["tipo"]==3) { ?>
                  <p>Su cita ha sido rechazada por {{$datosAlerta["prestador"] }}.</p>
                  <p>{{trans("leng.Fecha cita")}}:&nbsp;{{$datosAlerta["fechaCita"]}}.</p>
                  <p>{{trans("leng.Hora cita")}}:&nbsp;{{$datosAlerta["horaCita"]}}.</p>
                  <p>{{trans("leng.Motivo")}}:&nbsp;{{$datosAlerta["texto"]}}.</p>
                  <center><p><a href="/cliente/misCitas"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('Ir a mis citas')}}</a></p></center>      
                        
               <?php } else if($datosAlerta["tipo"]==4) { ?>
                  <p>Cita cancelada por el cliente {{$datosAlerta["cliente"] }}.</p>
                  <p>{{trans("leng.Fecha cita")}}:&nbsp;{{$datosAlerta["fechaCita"]}}.</p>
                  <p>{{trans("leng.Hora cita")}}:&nbsp;{{$datosAlerta["horaCita"]}}.</p>
                  <center><p><a href="/prestadorServicios/misCitas"><i class="fa fa-external-link-square"></i>&nbsp;{{trans('Ir a mis citas')}}</a></p></center>      
                        
               <?php } else if($datosAlerta["tipo"]==5) { ?>
                        
               <?php } else if($datosAlerta["tipo"]==6) { ?>
                        
               <?php } else if($datosAlerta["tipo"]==7) { ?>

               <?php } else if($datosAlerta["tipo"]==8) { ?>
                        
               <?php } else if($datosAlerta["tipo"]==9) { ?>
                        
               <?php } else if($datosAlerta["tipo"]==10) { ?>
                        
               <?php } else if($datosAlerta["tipo"]==11) { ?>
                        
               <?php } else if($datosAlerta["tipo"]==12) { ?>
                        
                <?php } else if($datosAlerta["tipo"]==13) { ?>
                        
                <?php } else if($datosAlerta["tipo"]==14) { ?>
                        
                <?php } else if($datosAlerta["tipo"]==15) { ?>
                        
               <?php } ?>

            </div>
        </div>

        <br>
        <div class="row">
            <div class="col-md-10">
                <button type="button" class="btn btn-primary" onclick="$('#modalNotificacionesGeneral').dialog('close');"><i class="fa fa-times"></i>&nbsp;{{trans("leng.Cerrar")}}</button>
            </div>
        </div>

    </div>
 </div>
         