<?php
/*Devuelve todas las consultas del transportista
	Autor: Andrés - 16-06-16
*/
namespace Udoktor\Http\Controllers;

use Illuminate\Http\Request;

use Udoktor\AdministrativeUnit;
use Udoktor\Http\Requests;
use Udoktor\EnvioCliente;
use Auth;
use Udoktor\Http\Controllers\Controller;
use DB;
use Udoktor\ArticuloEnvioCliente;
use Udoktor\ImagenArticuloEnvio;
use Udoktor\Funciones;
use Udoktor\Pais;
use Udoktor\PaymentMethod;
use Udoktor\Person;
use Udoktor\Shipment;
use Udoktor\ShipmentLog;
use Udoktor\ShippingRequest;
use Udoktor\Question;
use Udoktor\Vehicle;
use Yajra\Datatables\Datatables;
use Udoktor\ServiceOffer;
use Udoktor\Currency;
use Udoktor\Feedback;
use Illuminate\Support\Collection;
use Udoktor\Preference;
use Udoktor\GrupoEnvio;
use Udoktor\GroupsPerson;
use Udoktor\Groups;
use Udoktor\GroupRequest;
use Udoktor\Tracking;
use Udoktor\ShippingRequestCheck;
use Mail;
use Udoktor\Alert;
use Udoktor\Promotions;
use Udoktor\ShipperPromotion;
use Udoktor\Shipper;
use Udoktor\ShipperAccount;
use Udoktor\ShipperAccountDetail;
use Udoktor\User;
use Udoktor\RolUsuario;
use Udoktor\Driver;
use Udoktor\Vehiculo;
use Udoktor\TipoVehiculo;

class TransportistaController extends Controller
{
    /**
     * mostrar la vista de ofertas en general junto con la lista de países disponibles
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofertas()
    {
        if (Auth::check()) {
            // obtener paises
            $paises = DB::select('select c.id, c.name from preferences p inner join administrativeunit au on p.administrativeunitid = au.id inner join country c on au.countryid = c.id where p.personid = '.Auth::user()->personid.' group by c.id, c.name');

            return view('vvtransportista.ofertas', compact('paises'));

        } else{
            return view('login.login');
        }
    }

    /**
     * buscar los estados correspondientes al país seleccionado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarEstados(Request $request)
    {
        $paisId    = (int)$request->get('paisId');

        $preference = Preference::where([['personid','=',Auth::user()->personid]])->get();
        $ids=array();
        foreach ($preference as $row) {
            $ids[]=$row->administrativeunitid;
        }
        $ids=implode(',',$ids);


        $estados   = AdministrativeUnit::where([['countryid', $paisId],['active','=',1]])->whereRaw('id in ('.$ids.')')->orderBy('name')->get();

        $respuesta = [];
        $html      = '<option value="">Todos</option>';

        if (count($estados) > 0) {
            foreach ($estados as $estado) {
                $html .= '<option value="' .$estado->id. '">' .$estado->name. '</option>';
            }
        }

        $respuesta['estatus'] = 'OK';
        $respuesta['html']    = $html;

        return response()->json($respuesta);
    }

    /**
     * buscar ofertas en base a los filtros de búsqueda.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function buscarOfertasMapa(Request $request)
    {
        $respuesta = [];
        $collectionQuery = $deliveryQuery = $orderBy = '';

        // filtros referentes a la entrega y recepción
        if ($request->has('paisRecoleccion')){
            if ($request->has('estadoRecoleccion')) {
                if ($request->has('ciudadRecoleccion')) {
                    $collectionQuery = 'AND collectionState.countryid = ' . $request->get('paisRecoleccion') . ' AND collection.stateid = ' . $request->get('estadoRecoleccion') . " AND collection.city LIKE '%%" . $request->get('ciudadRecoleccion') . "%%'";

                } else {

                    $collectionQuery = 'AND collectionState.countryid = ' . $request->get('paisRecoleccion') . ' AND collection.stateid = ' . $request->get('estadoRecoleccion');
                }
            } else {
                $collectionQuery = 'AND collectionState.countryid = ' . $request->get('paisRecoleccion');
            }
        }
        if($request->get('fechaOrigen')!=""){
            $fecha=$request->get('fechaOrigen');
            $fecha=date("Y-m-d",strtotime($fecha));
            $collectionQuery.=" AND collection.collectiondate='".$fecha."'";
        }

        if ($request->has('paisEntrega')){
            if ($request->has('estadoEntrega')) {
                if ($request->has('ciudadEntrega')) {
                    $deliveryQuery = 'AND deliveryState.countryid = ' . $request->get('paisEntrega') . ' AND delivery.stateid = ' . $request->get('estadoEntrega') . " AND delivery.city LIKE '%%" . $request->get('ciudadEntrega') . "%%'";

                } else {
                    $deliveryQuery = 'AND deliveryState.countryid = ' . $request->get('paisEntrega') . ' AND delivery.stateid = ' . $request->get('estadoEntrega');
                }
            } else {
                $deliveryQuery = 'AND deliveryState.countryid = ' . $request->get('paisEntrega');
            }
        }
        if($request->has('fechaDestino')){
                $fecha=$request->get('fechaDestino');
                $fecha=date("Y-m-d",strtotime($fecha));
                $deliveryQuery.=" AND delivery.deliverydate='".$fecha."'";
            }
        // ====================================================================================
        // filtro de los rangos de costo
        $costQuery = '';
        $precioDesde = (double)$request->get('precioDesde');
        $precioHasta = (double)$request->get('precioHasta');

        if ($precioHasta !== 0.0) {
            $costQuery = '(totalprice BETWEEN ' . $precioDesde . ' AND ' . $precioHasta . ')';

        }elseif($precioHasta == 0.0 && $precioDesde==0.0){
            $costQuery = 'costtype=1';
        }
        else{
            if($precioDesde > 0.0)$costQuery = 'totalprice >= ' . $precioDesde;
        }

        // =================================================================================
        // filtro de precio fijo o variable
        if ($request->has('open')) {
            $costQuery = $costQuery === '' ? $costQuery.' AND costtype = 2' : ' AND ('.$costQuery.' OR costtype = 2)';
        }else{
            $costQuery = ' AND '.$costQuery;
        } 

        // =================================================================================
        // filtro orderBy
        if ($request->has('orderBy')) {
            $orderBy = ShippingRequest::getOrderByToObtainShippingRequests($request->get('orderBy'));
        }
        
        $shippingRequests           = ShippingRequest::getByCollectionOrDeliveryQuery($collectionQuery, $deliveryQuery, $costQuery, $orderBy);
        //dd($shippingRequests);
        $respuesta['estatus']       = 'OK';
        $respuesta['registros']     = count($shippingRequests);
        $respuesta['html']          = view('vvtransportista.ofertas_lista', compact('shippingRequests'))->render();
        $respuesta['listadoEnvios'] = $shippingRequests;

        return response()->json($respuesta);
    }

    /**
     * visualizar el detalle de una oferta
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofertaDetalle($id)
    {
        $shippingRequest = ShippingRequest::find((int)$id);
        $coordsRecoger   = $coordsEntregar = [];

        list($coordsRecoger['latitud'], $coordsRecoger['longitud']) = explode(',', $shippingRequest->collectionAddress()->first()->collectionrandomubication);
        list($coordsEntregar['latitud'], $coordsEntregar['longitud']) = explode(',', $shippingRequest->deliveryAddress()->first()->deliveryrandomubication);

        $inGroup=0;
        if(!$shippingRequest->ispublic){
            $grupos=GrupoEnvio::where('shippingrequestid','=',$id)->get();

            $ids=array();
            foreach ($grupos as $row) {
                $ids[]=$row->groupid;
            }
            $ids=implode(',',$ids);

            $personid=Auth::user()->personid;
            $grupoT=GroupsPerson::where('personid','=',Auth::user()->personid)->whereRaw('groupid in ('.$ids.')')->count();

            if($grupoT>0) $inGroup=1;
        }
        
        return view('vvtransportista.detalle_envio', compact('shippingRequest', 'coordsRecoger', 'coordsEntregar','inGroup'));
    }

    /**
     * guardar pregunta generada
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function guardarPregunta(Request $request)
    {
        // parámetros
        $id        = (int)$request->get('id');
        $cuerpo    = $request->get('pregunta');
        $personId  = Auth::user()->personid;
        $respuesta = [];

        // creación de pregunta
        $shippingRequest     = ShippingRequest::find($id);
        $pregunta            = new Question;
        $pregunta->body      = $cuerpo;
        $pregunta->createdat = date('Y-m-d H:i:s');
        $pregunta->updated   = date('Y-m-d H:i:s');
        $person              = Person::find($personId);//dd($pregunta->person);
        $pregunta->shipper()->associate($person->shipper);

        try {
            $shippingRequest->questions()->save($pregunta);

            // envío de alertas
            Funciones::enviarAlerta($shippingRequest->person->id, 5, $id, $pregunta->id);

            $respuesta['estatus'] = 'ok';
            $respuesta['html']    = view('vvtransportista.detalle_envio_preguntas', compact('shippingRequest'))->render();

            return response()->json($respuesta);
        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);
        }
    }

    /**
     * mostrar la vista de publicación de una nueva oferta
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function nuevaOferta($id)
    {
        $idPerson = Auth::user()->personid;
        
        $shippingRequest = ShippingRequest::find((int)$id);
        
        
        return view('vvtransportista.envio_ofertas_nueva', compact('shippingRequest'));
    }

    /**
     * guardar la nueva oferta
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \PDOException
     * @throws \Throwable
     */
    public function publicarOferta(Request $request)
    {
        $id              = (int)$request->get('id');
        $costoOferta     = (double)$request->get('costoOferta');
        $condiciones     = $request->get('condiciones');
        $informacionPago = $request->get('informacionPago');
        $paymentMethodId = (int)$request->get('metodoPago');
        $fechaactual = date('Y-m-d H:i:s');
        
        $precioPorOfertar     = (double)$request->get('precioPorOfertarOculto');
        
        if($precioPorOfertar==-1){
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   =trans("leng.No hay precio establecido para realizar la oferta, consulte al administrador") .'.';
            return response()->json($respuesta);
        }
        

        // retrieving the current shipping request
        $shippingRequest = ShippingRequest::find($id);

        // verificar que esté todavía en status 1, activo
        if ($shippingRequest->status !== 1) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = 'El envío no se encuentra en estatus disponible para realizar ofertas.';

            return response()->json($respuesta);
        }

        // verificar que no haya expirado
        if ($shippingRequest->hasExpired()) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = 'La fecha límite para publicar ofertas ha concluído.';

            return response()->json($respuesta);
        }

        if($shippingRequest->costtype === 1){//que sea fijo
            // verificar que el costo a ofertar efectivamente sea el más bajo
            if ($shippingRequest->isTheLowerCost($costoOferta)!="ok") {
                $respuesta['estatus'] = 'fail';
                $respuesta['error']   = $shippingRequest->isTheLowerCost($costoOferta);

                return response()->json($respuesta);
            }
        }

        // retrieving the current shipper
        $person = Person::find(Auth::user()->personid);
        // ver si hay ofertas del transportista actual, si las hay, modificarles el estatus a 4 = cancelado
        if (count($shippingRequest->has('serviceOffers')->get())) {
            //$serviceOffers = ServiceOffer::
            foreach ($shippingRequest->serviceOffers()->where('shipperid', $person->shipper->id)->get() as $serviceOffer) {
                if ($serviceOffer->status === 1) {
                    $serviceOffer->status = 4;
                    $serviceOffer->updated = date('Y-m-d H:i:s');
                    $serviceOffer->save();
                    Funciones::sumarSaldoDisponibleTransportista(Auth::user()->personid,$serviceOffer->offerprice);
                }
            }
        }

        // retrieving currency by id
        $currency      = Currency::find(1);
        $paymentMethod = PaymentMethod::find($paymentMethodId);

        // creating the new service offer
        $serviceOffer                     = new ServiceOffer;
        $serviceOffer->shipmentcost       = $costoOferta;
        $serviceOffer->conditions         = $condiciones;
        $serviceOffer->paymentinformation = $informacionPago;

        if ($request->get('formaRecoleccion') === '1') {
            // a una fecha determinada
            $fechaRecoleccion             = $request->get('entreFechaRecoleccion');
            $serviceOffer->collectiondate = date('Y-m-d',strtotime($fechaRecoleccion.' 00:00:00'));
            $serviceOffer->collectiontype = 1;
        }

        if ($request->get('formaRecoleccion') === '2') {
            // a un periodo de fechas
            $entreFechaRecoleccion = $request->get('entreFechaRecoleccion');
            $yFechaRecoleccion     = $request->get('yFechaRecoleccion');

            $serviceOffer->collectiondate      = date('Y-m-d',strtotime($entreFechaRecoleccion.' 00:00:00'));
            $serviceOffer->collectionuntildate = date('Y-m-d',strtotime($yFechaRecoleccion.' 00:00:00'));
            $serviceOffer->collectiontype      = 3;
        }
        // ======================================================================
        if ($request->get('formaEntrega') === '1') {
            // a una fecha determinada
            $fechaEntrega               = $request->get('entreFechaEntrega');
            $serviceOffer->deliverydate = date('Y-m-d',strtotime($fechaEntrega.' 00:00:00'));
            $serviceOffer->deliverytype = 1;
        }

        if ($request->get('formaEntrega') === '2') {
            // a un periodo de fechas
            $entreFechaEntrega = $request->get('entreFechaEntrega');
            $yFechaEntrega     = $request->get('yFechaEntrega');

            $serviceOffer->deliverydate      = date('Y-m-d',strtotime($entreFechaEntrega.' 00:00:00'));
            $serviceOffer->deliveryuntildate = date('Y-m-d',strtotime($yFechaEntrega.' 00:00:00'));
            $serviceOffer->deliverytype      = 3;
        }

        // binding the relationship
        $serviceOffer->shippingRequest()->associate($shippingRequest);
        $serviceOffer->shipper()->associate($person->shipper);
        $serviceOffer->currency()->associate($currency);
        $serviceOffer->paymentMethod()->associate($paymentMethod);

        $serviceOffer->createdat = date('Y-m-d H:i:s');
        $serviceOffer->updated   = date('Y-m-d H:i:s');
        $serviceOffer->status    = 1;

        // saving the new service offer
        try {
            
            $conSaldo=  Funciones::validarSaldoDisponibleTransportista(Auth::user()->personid,$precioPorOfertar);
            if($conSaldo["error"]==1){
                $error=trans("leng.Su saldo disponible no es suficiente para realizar la oferta").".";
                $respuesta['estatus'] = 'fail';
                $respuesta['error']   = $error;
                return response()->json($respuesta);
            }
            
            $serviceOffer->offerprice    = $precioPorOfertar;
            
            $shippingRequest->serviceOffers()->save($serviceOffer);

            // envio de alerta
            Funciones::enviarAlerta($serviceOffer->shippingRequest->person->id, 1, $id, $serviceOffer->id);

            $competencia = DB::table('serviceoffer')->select('users.email','person.id')
            ->leftJoin('shipper','shipper.id','=','serviceoffer.shipperid')
            ->leftJoin('person','person.id','=','shipper.personid')
            ->leftjoin('users','users.personid','=','person.id')
            ->where('serviceoffer.shippingrequestid','=',$id)
            ->whereNotIn('person.id',[$person->id])
            ->distinct()->get();

            $datos=array();
            $datos["titulo"]=$serviceOffer->shippingRequest()->first()->title;
            $datos["precio"]=$serviceOffer->shipmentcost;
            $datos["idenvio"]=$serviceOffer->shippingrequestid;

            foreach ($competencia as $row) {
                $correo=$row->email;
                Mail::send('correos.nuevaOfertaCompetencia',$datos,function($msj) use ($correo){
                  $msj->subject("Notificaciones Efletex - Nueva oferta de competencia");
                  $msj->to($correo);
                });

                $alerta=Alert::create([
                     'recipientid'=>$row->id,
                     'createdat'=>$fechaactual,
                     'updated'=>$fechaactual,
                     'type'=>15,
                     'relationid'=>$shippingRequest->id,
                     'relationid2'=>$serviceOffer->id
                ]);
            }
            
            Funciones::descontarSaldoDisponibleTransportista(Auth::user()->personid,$precioPorOfertar);
            

            $respuesta['estatus'] = 'ok';
            $respuesta["comp"] = $serviceOffer;

            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);
        }
    }

    /**
     * mostrar la vista del listado de ofertas del transportista logueado
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofertasTransportista()
    {
        $person        = Person::find(Auth::user()->personid);
        $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->orderBy('id', 'desc')->orderBy('shippingrequestid', 'desc')->get();

        return view('vvtransportista.ofertas_listado', compact('serviceOffers'));
    }

    /**
     * cancelar una oferta
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelarOferta(Request $request)
    {
        $idPerson = Auth::user()->personid;
        
        $serviceOfferId = (int)$request->get('serviceOfferId');

        $serviceOffer         = ServiceOffer::find($serviceOfferId);
        $serviceOffer->status = 4;
        
        $precioOferta=$serviceOffer->offerprice;

        try {
            // envio de alerta
            Funciones::enviarAlerta($serviceOffer->shippingRequest->person->id, 2, $serviceOffer->shippingRequest->id, $serviceOfferId);

            $serviceOffer->save();
            $respuesta['estatus'] = 'ok';
            
            Funciones::sumarSaldoDisponibleTransportista($idPerson,$precioOferta);

            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);

        }
    }

    /**
     * buscar ofertas en base al estatus y al shipper logueado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarOfertas(Request $request)
    {
        $respuesta = [];
        $estatus   = $request->get('estatus');
        $person    = Person::find(Auth::user()->personid);

        if (is_null($estatus)) {
            // todas
            $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->orderBy('id', 'desc')->orderBy('shippingrequestid', 'desc')->get();
        } else {
            $estatusABuscar = implode(',', $estatus);

            $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->whereRaw('status IN (' . $estatusABuscar . ')')->orderBy('id','desc')->orderBy('shippingrequestid', 'desc')->get();
        }

        $respuesta['estatus'] = 'ok';
        $respuesta['html']    = view('vvtransportista.ofertas_listado_resultados', compact('serviceOffers'))->render();

        return response()->json($respuesta);
    }

    public function dataTableOfertas()
    {
        $envios = DB::table('shippingrequest')
            ->select(["shippingrequest.id as ref",
                "shippingrequest.createdat as creacion",
                "shippingrequest.title as titulo",
                "collectionaddress.city as ciudadrecoger",
                "estado.name as estadorecoger",
                "paisrecoger.shortname as paisrecoger",
                "deliveryaddress.city as ciudadentregar",
                "estadoentrega.name as estadoentregar",
                "paisentrega.shortname as paisentregar",
                "shippingrequest.status as estadoenvio",
                "collectionaddress.latitude as latitudOrigen",
                "collectionaddress.longitude as longitudOrigen",
                "deliveryaddress.latitude as latitudDestino",
                "deliveryaddress.longitude as longitudDestino",
                "collectionaddress.collecttimefrom as hora1Origen",
                "collectionaddress.collecttimeuntil as hora2Origen",
                "collectionaddress.collectiondate as fecha1Origen",
                "collectionaddress.collectionuntildate as fecha2Origen",
                "deliveryaddress.deliverytimefrom as hora1Destino",
                "deliveryaddress.deliverytimeuntil as hora2Destino",
                "deliveryaddress.deliverydate as fecha1Destino",
                "deliveryaddress.deliveryuntildate as fecha2Destino",
                "shippingrequest.cost",
                "shippingrequest.km",
                "shippingrequest.tiempo",
                "shippingrequest.peso_total"
            ])
            ->leftJoin('collectionaddress', 'collectionaddress.shippingrequestid', '=', 'shippingrequest.id')
            ->leftJoin('administrativeunit as estado', 'estado.id', '=', 'collectionaddress.stateid')
            ->leftJoin('country as paisrecoger', 'paisrecoger.id', '=', 'estado.countryid')
            ->leftJoin('deliveryaddress', 'deliveryaddress.shippingrequestid', '=', 'shippingrequest.id')
            ->leftJoin('administrativeunit as estadoentrega', 'estadoentrega.id', '=', 'deliveryaddress.stateid')
            ->leftJoin('country as paisentrega', 'paisentrega.id', '=', 'estadoentrega.countryid')
            ->whereNotNull('collectionaddress.latitude')
            ->whereNull('shippingrequest.deleted')
            ->where('shippingrequest.status','=',1)
            ->orderBy("shippingrequest.createdat");


        return Datatables::of($envios)
            ->addColumn('desc',function($envio){
                return '<a href="'.url('transportista/ofertas/'.$envio->ref.'/detalle').'">'.$envio->titulo.'</a><br><i class="fa fa-dashboard"></i> '.$envio->km.'km. &nbsp;&nbsp;<i class="fa fa-clock-o"></i> '.$envio->tiempo.'hrs.';
            })
        	->addColumn('costo',function($envio){
        		return $envio->cost==""?"Libre":$envio->cost;
        	})
        	->addColumn('origen',function($envio){
        		return $envio->ciudadrecoger.', '.$envio->estadorecoger.', '.$envio->paisrecoger.'<br>'.$envio->fecha1Origen.($envio->fecha2Origen==''?'':' - '.$envio->fecha2Origen).'<br>'.$envio->hora1Origen.($envio->hora2Origen==''?'':' - '.$envio->hora2Origen);
        	})
        	->addColumn('entrega',function($envio){
        		return $envio->ciudadentregar.','.$envio->estadoentregar.','.$envio->paisentregar.'<br>'.$envio->fecha1Destino.($envio->fecha2Destino==''?'':' - '.$envio->fecha2Destino).'<br>'.$envio->hora1Destino.($envio->hora2Destino==''?'':' - '.$envio->hora2Destino);
        	})
        	->make(true);
    }

    /**
     * obtener las ofertas del shipper que están aceptadas (estatus 2)
     * y construir la vista de envios
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mostrarEnvios()
    {
        $person        = Person::find(Auth::user()->personid);
        $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->where('status', 2)->get();
        $vehicles      = Vehicle::where('shipperid', $person->shipper->id)->orderBy('plate')->get();

        return view('vvtransportista.envios', compact('serviceOffers', 'vehicles'));
    }

    /**
     * Asignar un vehículo al envío y setear estatus 7 a shipping request
     * además de que inserta en la tabla logs de envío para ver
     * en qué momento se guardó el estatus.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function asignarVehiculo(Request $request)
    {
        $fechaactual = date('Y-m-d H:i:s');
        $shipmentId = (int) $request->get('shipmentId');
        $vehiculo   = $request->get('vehiculo');
        $shipment   = Shipment::find($shipmentId);
        $respuesta  = [];
        $person     = Person::find(Auth::user()->personid);

        if ($vehiculo === '-1') {
            // se crea vehiculo
            $vehicle = new Vehicle();
            $vehicle->description = $request->get('otroVehiculo');
            $vehicle->active = true;
            $vehicle->shipper()->associate($person->shipper);
            $vehicle->save();

        } else {
            $vehicle = Vehicle::find((int) $vehiculo);
        }

        $shipment->serviceOffer->shippingRequest->status = 7;
        $shipment->updated = $fechaactual;
        $shipment->serviceOffer->shippingRequest->updated = $fechaactual;
        $shipment->vehicle()->associate($vehicle);
        $shipment->trackingtype = $request->get('tracking');
        
        // log
        $shipmentLog = new ShipmentLog();
        $shipmentLog->status    = 7;
        $shipmentLog->createdat = $fechaactual;
        $shipmentLog->updated   = $fechaactual;
        $shipmentLog->longitude   = 0;
        $shipmentLog->latitude   = 0;
        $shipmentLog->shippingRequest()->associate($shipment->serviceOffer->shippingRequest);

        DB::beginTransaction();
        try {
            $shipment->save();
            $shipment->serviceOffer->shippingRequest->save();
            $shipment->serviceOffer->shippingRequest->logs()->save($shipmentLog);

            $person        = Person::find(Auth::user()->personid);
            $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->where('status', 2)->get();
            // envio de alerta
            Funciones::enviarAlerta($shipment->serviceOffer->shippingRequest->person->id, 11, $shipment->serviceOffer->shippingRequest->id);

            $respuesta['estatus'] = 'ok';
            $respuesta['html']    = view('vvtransportista.envios_resultados', compact('serviceOffers'))->render();

            DB::commit();
            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            DB::rollback();
            return response()->json($respuesta);

        }
    }

    /**
     * Setear estatus 3 a shipping request
     * además de que inserta en la tabla logs de envío para ver
     * en qué momento se guardó el estatus.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function recolectarEnvio(Request $request)
    {
        $fechaactual = date('Y-m-d H:i:s');
        $shipmentId = (int) $request->get('shipmentId');
        $shipment   = Shipment::find($shipmentId);
        $respuesta  = [];
        $person     = Person::find(Auth::user()->personid);

        $shipment->serviceOffer->shippingRequest->status = 3;
        $shipment->updated = $fechaactual;
        $shipment->serviceOffer->shippingRequest->updated = $fechaactual;
        
        //Se validan las imagenes
        $imgFirma=$request->get('firma');
             if($imgFirma!=""){
                 if(substr($imgFirma,0,10)=="data:image"){
                    list($tipo, $imgFirma) = explode(';', $imgFirma);
                    if($tipo!="data:image/jpeg" && $tipo!="data:image/png"){
                        $respuesta['estatus'] = 'fail';
                        $respuesta['error']   = "errorimagenFirma";
                        return response()->json($respuesta);
                    }
                }else{
                     $respuesta['estatus'] = 'fail';
                     $respuesta['error']   = "errorimagenFirma";
                     return response()->json($respuesta);
                }
             }
             
         $imgImagen=$request->get('imagen');
             if($imgImagen!=""){
                 if(substr($imgImagen,0,10)=="data:image"){
                    list($tipo, $imgImagen) = explode(';', $imgImagen);
                    if($tipo!="data:image/jpeg" && $tipo!="data:image/png"){
                        $respuesta['estatus'] = 'fail';
                        $respuesta['error']   = "errorimagen";
                        return response()->json($respuesta);
                    }
                }else{
                     $respuesta['estatus'] = 'fail';
                     $respuesta['error']   = "errorimagen";
                     return response()->json($respuesta);
                }
             }
        
         $nombreArchivoFirma="";
         $imgFirma=$request->get('firma');
         if($imgFirma!=""){
              $dirEnvio="imagenesEnvio/".$shipment->shippingrequestid;
              if(!file_exists($dirEnvio)){
                    mkdir($dirEnvio, 0777);
              }

             list($tipo, $imgFirma) = explode(';', $imgFirma);
             list(, $imgFirma) = explode(',', $imgFirma);
             $imgFirma = base64_decode($imgFirma);
             if($tipo=="data:image/jpeg"){
                  $nombreArchivoFirma="firmaimg.jpg";
                  $tipoA=1;
             }else if($tipo=="data:image/png"){
                 $nombreArchivoFirma="firmaimg.png";
                 $tipoA=2;
             }else if($tipo=="data:image/gif"){
                 $nombreArchivoFirma="firmaimg.gif";
                 $tipoA=3;
             }
             file_put_contents($dirEnvio."/".$nombreArchivoFirma, $imgFirma);
        }
        
        $nombreArchivoImagen="";
        $imgImagen=$request->get('imagen');
         if($imgImagen!=""){
              $dirEnvio="imagenesEnvio/".$shipment->shippingrequestid;
              if(!file_exists($dirEnvio)){
                    mkdir($dirEnvio, 0777);
              }

             list($tipo, $imgImagen) = explode(';', $imgImagen);
             list(, $imgImagen) = explode(',', $imgImagen);
             $imgImagen = base64_decode($imgImagen);
             if($tipo=="data:image/jpeg"){
                  $nombreArchivoImagen="imgenRecoleccion.jpg";
                  $tipoA=1;
             }else if($tipo=="data:image/png"){
                 $nombreArchivoImagen="imgenRecoleccion.png";
                 $tipoA=2;
             }else if($tipo=="data:image/gif"){
                 $nombreArchivoImagen="imgenRecoleccion.gif";
                 $tipoA=3;
             }
             file_put_contents($dirEnvio."/".$nombreArchivoImagen, $imgImagen);
        }
             
             

        // log
        $shipmentLog = new ShipmentLog();
        $shipmentLog->status    = 3;
        $shipmentLog->createdat = $fechaactual;
        $shipmentLog->updated   = $fechaactual;
        $shipmentLog->contact   = ucfirst($request->get('nombreContacto'));
        $shipmentLog->comment   = ucfirst($request->get('comentarios'));
        if($imgFirma!="")$shipmentLog->sing = $nombreArchivoFirma;
        if($imgImagen!="")$shipmentLog->img1 = $nombreArchivoImagen;
        $shipmentLog->longitude   = 0;
        $shipmentLog->latitude   = 0;
        $shipmentLog->shippingRequest()->associate($shipment->serviceOffer->shippingRequest);
        $shipmentLog->dni = $request->get('dni');

        DB::beginTransaction();
        try {
            $shipment->save();
            $shipment->serviceOffer->shippingRequest->save();
            $shipment->serviceOffer->shippingRequest->logs()->save($shipmentLog);

            $person        = Person::find(Auth::user()->personid);
            $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->where('status', 2)->get();
            // envio de alerta
            Funciones::enviarAlerta($shipment->serviceOffer->shippingRequest->person->id, 7, $shipment->serviceOffer->shippingRequest->id, $shipment->serviceOffer->id);

            $respuesta['estatus'] = 'ok';
            $respuesta['html']    = view('vvtransportista.envios_resultados', compact('serviceOffers'))->render();

            DB::commit();
            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();
            DB::rollback();
            return response()->json($respuesta);

        }
    }

    /**
     * Asignar un vehículo al envío y setear estatus 4 a shipping request
     * además de que inserta en la tabla logs de envío para ver
     * en qué momento se guardó el estatus.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function marcarEntrega(Request $request)
    {
        $fechaactual = date('Y-m-d H:i:s');
        $shipmentId = (int) $request->get('shipmentId');
        $shipment   = Shipment::find($shipmentId);
        $respuesta  = [];

        $shipment->serviceOffer->shippingRequest->status = 4;
        $shipment->updated = $fechaactual;
        $shipment->serviceOffer->shippingRequest->updated = $fechaactual;
        
        //Se validan las imagenes
        $imgFirma=$request->get('firma');
             if($imgFirma!=""){
                 if(substr($imgFirma,0,10)=="data:image"){
                    list($tipo, $imgFirma) = explode(';', $imgFirma);
                    if($tipo!="data:image/jpeg" && $tipo!="data:image/png"){
                        $respuesta['estatus'] = 'fail';
                        $respuesta['error']   = "errorimagenFirma";
                        return response()->json($respuesta);
                    }
                }else{
                     $respuesta['estatus'] = 'fail';
                     $respuesta['error']   = "errorimagenFirma";
                     return response()->json($respuesta);
                }
             }
             
         $imgImagen=$request->get('imagen');
             if($imgImagen!=""){
                 if(substr($imgImagen,0,10)=="data:image"){
                    list($tipo, $imgImagen) = explode(';', $imgImagen);
                    if($tipo!="data:image/jpeg" && $tipo!="data:image/png"){
                        $respuesta['estatus'] = 'fail';
                        $respuesta['error']   = "errorimagen";
                        return response()->json($respuesta);
                    }
                }else{
                     $respuesta['estatus'] = 'fail';
                     $respuesta['error']   = "errorimagen";
                     return response()->json($respuesta);
                }
             }
        
         $nombreArchivoFirma="";
         $imgFirma=$request->get('firma');
         if($imgFirma!=""){
              $dirEnvio="imagenesEnvio/".$shipment->shippingrequestid;
              if(!file_exists($dirEnvio)){
                    mkdir($dirEnvio, 0777);
              }

             list($tipo, $imgFirma) = explode(';', $imgFirma);
             list(, $imgFirma) = explode(',', $imgFirma);
             $imgFirma = base64_decode($imgFirma);
             if($tipo=="data:image/jpeg"){
                  $nombreArchivoFirma="firmaEntregaimg.jpg";
                  $tipoA=1;
             }else if($tipo=="data:image/png"){
                 $nombreArchivoFirma="firmaEntregaimg.png";
                 $tipoA=2;
             }else if($tipo=="data:image/gif"){
                 $nombreArchivoFirma="firmaEntregaimg.gif";
                 $tipoA=3;
             }
             file_put_contents($dirEnvio."/".$nombreArchivoFirma, $imgFirma);
        }
        
        $nombreArchivoImagen="";
        $imgImagen=$request->get('imagen');
         if($imgImagen!=""){
              $dirEnvio="imagenesEnvio/".$shipment->shippingrequestid;
              if(!file_exists($dirEnvio)){
                    mkdir($dirEnvio, 0777);
              }

             list($tipo, $imgImagen) = explode(';', $imgImagen);
             list(, $imgImagen) = explode(',', $imgImagen);
             $imgImagen = base64_decode($imgImagen);
             if($tipo=="data:image/jpeg"){
                  $nombreArchivoImagen="imgenEntrega.jpg";
                  $tipoA=1;
             }else if($tipo=="data:image/png"){
                 $nombreArchivoImagen="imgenEntrega.png";
                 $tipoA=2;
             }else if($tipo=="data:image/gif"){
                 $nombreArchivoImagen="imgenEntrega.gif";
                 $tipoA=3;
             }
             file_put_contents($dirEnvio."/".$nombreArchivoImagen, $imgImagen);
        }
        
        
        
        

        // log
        $shipmentLog = new ShipmentLog();
        $shipmentLog->status    = 4;
        $shipmentLog->createdat = $fechaactual;
        $shipmentLog->updated   = $fechaactual;
        $shipmentLog->contact   = ucfirst($request->get('nombreContacto'));
        $shipmentLog->comment   = ucfirst($request->get('comentarios'));
        if($imgFirma!="")$shipmentLog->sing = $nombreArchivoFirma;
        if($imgImagen!="")$shipmentLog->img1 = $nombreArchivoImagen;
        $shipmentLog->longitude   = 0;
        $shipmentLog->latitude   = 0;
        $shipmentLog->shippingRequest()->associate($shipment->serviceOffer->shippingRequest);
        $shipmentLog->dni = $request->get('dni');

        try {
            $shipment->save();
            $shipment->serviceOffer->shippingRequest->save();
            $shipment->serviceOffer->shippingRequest->logs()->save($shipmentLog);

            $person        = Person::find(Auth::user()->personid);
            $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->where('status', 2)->get();
            // envio de alerta
            Funciones::enviarAlerta($shipment->serviceOffer->shippingRequest->person->id, 8, $shipment->serviceOffer->shippingRequest->id, $shipment->serviceOffer->id);

            $respuesta['estatus'] = 'ok';
            $respuesta['html']    = view('vvtransportista.envios_resultados', compact('serviceOffers'))->render();

            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);

        }
    }

    /**
     * mostrar el detalle del envío autorizado
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function envioDetalle($id)
    {
        $id = (int) $id;

        $shippingRequest = ShippingRequest::find((int)$id);
        $coordsRecoger   = $coordsEntregar = [];

        $coordsRecoger['latitud']   = $shippingRequest->collectionAddress()->first()->latitude;
        $coordsRecoger['longitud']  = $shippingRequest->collectionAddress()->first()->longitude;
        $coordsEntregar['latitud']  = $shippingRequest->deliveryAddress()->first()->latitude;
        $coordsEntregar['longitud'] = $shippingRequest->deliveryAddress()->first()->longitude;

        return view('vvtransportista.envio_detalle', compact('shippingRequest', 'coordsRecoger', 'coordsEntregar'));
    }

    /**
     * mostrar la lista de vehículos del transportista
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verVehiculos()
    {
        $person  = Person::find(Auth::user()->personid);
        $shipper = $person->shipper;

        return view('vvtransportista.vehiculos', compact('shipper'));
    }

    /**
     * guardar o editar un vehículo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function registroVehiculo(Request $request)
    {
        $person      = Person::find(Auth::user()->personid);
        $shipper     = $person->shipper;
        $descripcion = $request->get('descripcion');
        $respuesta   = [];

        try {
            if ($request->get('tipoAccion') === 'agregar') {
                // se crea vehiculo
                $vehicle = new Vehicle();
                $vehicle->description = $descripcion;
                $vehicle->active      = true;
                $vehicle->shipper()->associate($shipper);
                $vehicle->save();
            }

            if ($request->get('tipoAccion') === 'editar') {
                $vehicle = Vehicle::find((int) $request->get('vehicleId'));
                $vehicle->description = $descripcion;

                $vehicle->save();
            }

            $respuesta['estatus'] = 'ok';
            $respuesta['html']    = view('vvtransportista.vehiculos_lista', compact('shipper'))->render();
            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);

        }
    }

    /**
     * eliminar a un vehículo
     *
     * se verifica que el vehículo estipulado no esté asociado a un envío
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function eliminarVehiculo(Request $request)
    {
        $vehicleId   = (int)$request->get('vehicleId');
        $person      = Person::find(Auth::user()->personid);
        $shipper     = $person->shipper;
        $respuesta   = [];

        try {
            $vehicle = Vehicle::find($vehicleId);
            $vehicle->active = false;
            $vehicle->save();
            /*if (count($vehicle->shipments()->get()) > 0) {
                $respuesta['estatus'] = 'fail';
                $respuesta['error']   = 'Este vehículo ya está asociado a un envío y no es posible eliminarlo.';
                return response()->json($respuesta);
            }*/

            $respuesta['estatus'] = 'ok';
            $respuesta['html']    = view('vvtransportista.vehiculos_lista', compact('shipper'))->render();

            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);

        }
    }

    /**
     * activar a un vehículo
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function activarVehiculo(Request $request)
    {
        $vehicleId   = (int)$request->get('vehicleId');
        $person      = Person::find(Auth::user()->personid);
        $shipper     = $person->shipper;
        $respuesta   = [];

        try {
            $vehicle = Vehicle::find($vehicleId);
            $vehicle->active = true;
            $vehicle->save();

            $respuesta['estatus'] = 'ok';
            $respuesta['html']    = view('vvtransportista.vehiculos_lista', compact('shipper'))->render();

            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);

        }
    }

    /**
     * calificar el envío del lado del transportista
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calificarEnvio(Request $request)
    {
        // variables
        $shipmentId   = (int)$request->get('shipmentId');
        $comentario   = $request->get('comentario');
        $calificacion = $request->get('calificacion');
        $respuesta    = [];

        // objects
        $person   = Person::find(Auth::user()->personid);
        $shipment = Shipment::find($shipmentId);
        $client   = $shipment->serviceOffer->shippingRequest->person;

        // new feedback
        $feedback                    = new Feedback();
        $feedback->comment           = $comentario;
        $feedback->starrating        = $calificacion;
        $feedback->updated           = date('Y-m-d H:i:s');
        $shipment->clienthasfeedback = true;

        // relationships
        $feedback->shipment()->associate($shipment);
        $feedback->author()->associate($person);
        $feedback->recipient()->associate($client);

        try {
            $feedback->save();
            $shipment->save();

            // service offers
            $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->where('status', 2)->get();

            // enviar alerta
            Funciones::enviarAlerta($shipment->serviceOffer->shippingRequest->person->id, 9, $shipment->serviceOffer->shippingRequest->id, $feedback->id);
            // promedio de  calificaciones
            $this->promedioCalificaciones($client);

            $respuesta['estatus'] = 'OK';
            $respuesta['html']    = view('vvtransportista.envios_resultados', compact('serviceOffers'))->render();
            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);

        }
    }

    /**
     * obtener la vista de calificaciones del transportista
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function calificaciones()
    {
        $person      = Person::find(Auth::user()->personid);
        $shipper     = $person->shipper;
        $shipmentRatings = Shipment::findRatings($person);
        return view('vvtransportista.calificaciones', compact('shipmentRatings', 'shipper'));
    }

    /**
     * obtener una lista de calificaciones del transportista
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerCalificaciones(Request $request)
    {
        $calificacion = (int)$request->get('calificacion');
        $respuesta = [];

        $person      = Person::find(Auth::user()->personid);
        $shipper     = $person->shipper;
        $serviceOffers   = ServiceOffer::where('shipperid', $shipper->id)->get();
        $feedbacks   = [];

        foreach ($serviceOffers as $serviceOffer) {
            if (!is_null($serviceOffer->shipment)) {
                foreach ($serviceOffer->shipment->feedbacks()->where('recipientid', $shipper->person->id)->where('starrating', $calificacion)->get() as $feedback) {
                    $feedbacks[] = $feedback;
                }
            }
        }

        $respuesta['estatus'] = 'OK';
        $respuesta['html'] = view('vvtransportista.calificaciones_lista', compact('feedbacks'))->render();

        return response()->json($respuesta);
    }

    /**
     * obtener y guardar el promedio de rating al cliente
     * @param Person $client
     */
    public function promedioCalificaciones(Person $client)
    {
        // calcular promedio
        $feedbacks = Feedback::where('recipientid', $client->id)->get();

        $totalRating    = 0;
        $totalFeedbacks = count($feedbacks);

        if ($totalFeedbacks === 0) {
            // update 0
            $client->starrating = 0;
            $client->updated    = date('Y-m-d H:i:s');
            $client->save();

        } else {
            foreach ($feedbacks as $feedback) {
                $totalRating += $feedback->starrating;
            }

            $personStarRating   = $totalRating / $totalFeedbacks;
            $client->starrating = round($personStarRating,1);
            $client->updated    = date('Y-m-d H:i:s');
            $client->save();
        }
    }

    public function buscarEnvios(Request $request) 
    {
        $respuesta = [];
        $person    = Person::find(Auth::user()->personid);
        $serviceOffers = new Collection();
        //$serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->where('status', 2)->get();

        $estatus   = $request->get('estatus');
        if (is_null($estatus)) {
            // todas
            $serviceOffers = ServiceOffer::where('shipperid', $person->shipper->id)->where('status', 2)->orderBy('id', 'desc')->orderBy('shippingrequestid', 'desc')->get();
        } else {
            $estatusABuscar = implode(',', $estatus);
            $shippingRequests = ShippingRequest::whereRaw('status IN (' . $estatusABuscar . ')')->get();
            foreach ($shippingRequests as $shippingRequest) {
                $offers = $shippingRequest->serviceOffers()->where('shipperid', $person->shipper->id)->where('status', 2)->get();

                foreach ($offers as $serviceOffer) {
                    $serviceOffers->push($serviceOffer);
                }
            }
        }

        $respuesta['html']    = view('vvtransportista.envios_resultados', compact('serviceOffers'))->render();
        $respuesta['estatus'] = 'ok';

        return response()->json($respuesta);
    }


    public function Dashboard(){
        $person      = Person::find(Auth::user()->personid);
        $shipper     = $person->shipper;

        $Dashboard = array();
        
        $Dashboard["activos"]  = ServiceOffer::where('shipperid', $shipper->id)->where('status','=',1)->count(); 

        $Dashboard["recolectar"]  = DB::table('shipment')
            ->leftjoin('serviceoffer','serviceoffer.id','=','shipment.acceptedserviceofferid')
            ->leftjoin('shippingrequest','shippingrequest.id','=','shipment.shippingrequestid')
            ->where('shippingrequest.status','=',2)->where('serviceoffer.shipperid','=',$shipper->id)
            ->count();

        $Dashboard["entregar"]  = DB::table('shipment')
            ->leftjoin('serviceoffer','serviceoffer.id','=','shipment.acceptedserviceofferid')
            ->leftjoin('shippingrequest','shippingrequest.id','=','shipment.shippingrequestid')
            ->where('shippingrequest.status','=',3)->where('serviceoffer.shipperid','=',$shipper->id)
            ->count();

        $Dashboard["calf"] = $person->starrating==""?"Sin Calificación":$person->starrating;
        
        return view('vvtransportista.index')->with('Dashboard',$Dashboard);
    }
    
    
    /* Muestra la pregunta hecha al cliente
     * Autor: OT
     * Fecha: 22-07-2016
    */
    public function mosrtrarPreguntaListado($idPregunta=0){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }

        if($idPregunta==0){
            return redirect('/misPreguntas');
        }

        $textoPregunta="";
        $cliente="";
        $tituloEnvio="";
        $respuesta="";
        $fechaRespuesta="";
        $idEnvio="";
        
        $datoPregunta = Question::select(["shippingrequest.id as idenvio","person.company","shippingrequest.id as idenvio","question.body","question.id","shippingrequest.title","question.createdat","question.answer","question.respondedat"])
            ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'question.shippingrequestid')
            ->leftJoin('person', 'person.id', '=', 'shippingrequest.requesterid')
            ->where("question.id","=",$idPregunta)
            ->get();
        
        foreach($datoPregunta as $rowPregunta){
            $textoPregunta=$rowPregunta->body;
            $cliente=  ucfirst($rowPregunta->company);
            $tituloEnvio=ucfirst($rowPregunta->title);
            $respuesta=ucfirst($rowPregunta->answer);
            $fechaRespuesta=($rowPregunta->respondedat=="")?"":Funciones::fechaF1Hora($rowPregunta->respondedat);
            $idEnvio=$rowPregunta->idenvio;
        }
        


        return view('vvpreguntas.mostrarPreguntaTransportista')
                ->with("idPregunta",$idPregunta)
                ->with("cliente",$cliente)
                ->with("idEnvio",$idEnvio)
                ->with("tituloEnvio",$tituloEnvio)
                ->with("respuesta",$respuesta)
                ->with("fechaRespuesta",$fechaRespuesta)
                ->with("textoPregunta",$textoPregunta);
    }
    
    /* Muestra el listado de grupos del transportista
     * Autor: OT
     * Fecha: 30-07-2016
    */
    public function misGrupos(){
        return view('vvtransportista.misGrupos');
    }
    
    /* Listado de grupos
     * Autor: OT
     * Fecha: 30-07-2016
    */
    public function listaGruposTransportista(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $buscatitulo=trim($request["buscaTitulo"]);
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (person.firstname ilike'%$buscatitulo%' or person.lastname ilike'%$buscatitulo%' or
                person.company ilike'%$buscatitulo%' or groups.name ilike'%$buscatitulo%')";
        }
        
        $sWhere="groupsperson.personid=$idPerson" . $filtroTitulo;
        
                
        $datosGrupos = GroupsPerson::select(["groups.name","person.company","person.firstname","person.lastname" ])
            ->leftJoin('groups', 'groups.id', '=', 'groupsperson.groupid')
            ->leftJoin('person', 'person.id', '=', 'groups.personid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("groups.name")
            ->get();
        
        
        return Datatables::of($datosGrupos)
        ->addColumn('name', function ($datosGrupos) {
            $ca="<font class='flet-lab'>".ucwords($datosGrupos->name)."</font>";
            
            return $ca;
         })
         ->addColumn('cliente', function ($datosGrupos) {
             $nombreCliente="";
             if($datosGrupos->company==""){
                 $nombreCliente=  ucfirst($datosGrupos->firstname) . " "  . ucfirst($datosGrupos->lastname);
             }else{
                 $nombreCliente= ucfirst($datosGrupos->company);
             }
            $ca="<font class='flet-lab'>$nombreCliente</font>";
            return $ca;
         })
         
         
        ->make(true);
    }
    
    
    /**
     * Muestra los datos del log del envio
     * Autor: OT
     * Fecha: 30-07-2016
     */
    public function verDetalleLog(Request $request){
        $envio=trim($request["idEnvio"]);
        $tipo=trim($request["tipo"]);
        
        $contacto="";
        $comentarios="";
        $dni="";
        $firma="";
        $img1="";
        $estado=0;
        if($tipo==1)$estado=3;
        else $estado=4;
            
            $datosLog=DB::table('shippingrequestlog')->where('shippingrequestid','=',$envio)
                    ->where('status','=',$estado)
                    ->get();
            foreach($datosLog as $datoLog){
                $contacto=  ucfirst($datoLog->contact);
                $comentarios=ucfirst($datoLog->comment);
                $dni=ucfirst($datoLog->dni);
                $firma=$datoLog->sing;
                $img1=$datoLog->img1;
            }

        
        $respuesta=array();
        $respuesta["id"]=$envio;
        $respuesta["contacto"]=$contacto;
        $respuesta["comentarios"]=$comentarios;
        $respuesta["dni"]=$dni;
        $respuesta["firma"]=$firma;
        $respuesta["img1"]=$img1;
        return view("vvtransportista.detalleLog")->with("respuesta",$respuesta);
        
    }

    /* Muestra el mapa
     * Autor OT
     * Fecha 30-07-2016
     */
    public function verMapaEnvio(){
        return view('vvtransportista.mapaEnvio');
    }
    

    public function solicitudGrupo(Request $request){
        $fechaactual=date('Y-m-d H:i:s');
        $solicitud                    = new GroupRequest();
        $solicitud->shippingrequestid = $request->id;
        $solicitud->personid          = Auth::user()->personid;
        $solicitud->updated           = $fechaactual;
        $solicitud->createdat         = $fechaactual;

        try {
            $solicitud->save();

            // enviar alerta
            /*Funciones::enviarAlerta($shipment->serviceOffer->shippingRequest->person->id, 9, $shipment->serviceOffer->shippingRequest->id, $feedback->id);
            // promedio de  calificaciones
            $this->promedioCalificaciones($client);*/

            $respuesta['estatus'] = 'OK';

            return response()->json($respuesta);

        } catch (\PDOException $e) {
            $respuesta['estatus'] = 'fail';
            $respuesta['error']   = $e->getMessage();

            return response()->json($respuesta);

        }
    }

    /**
     * visualizar el detalle de una oferta
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tracking()
    {  
        return view('vvtransportista.tracking');
    }


    /**
     * busca el tracking del transportista.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function buscarTracking(Request $request)
    {
        $respuesta = [];

        $tracking = Tracking::where('personid','=',Auth::user()->personid)->get();

        $person      = Person::find(Auth::user()->personid);
        $shipper     = $person->shipper;

        $shippings = DB::table('shipment')->select(['shippingrequestlog.status','shippingrequestlog.latitude',
                    'shippingrequestlog.longitude',DB::raw("to_char(shippingrequestlog.createdat,'DD/MM/YYYY HH24:MI:SS') as createdat")])
                    ->leftJoin('serviceoffer','serviceoffer.id','=','shipment.acceptedserviceofferid')
                    ->leftJoin('shippingrequestlog','shippingrequestlog.shippingrequestid','=','shipment.shippingrequestid')
                    ->where('serviceoffer.shipperid','=',$shipper->id)->whereNotNull('latitude')->where('latitude','>',0)
                    ->get();

        $checks = ShippingRequestCheck::select('latitude','longitude',DB::raw("to_char(datecheck,'DD/MM/YYYY HH24:MI:SS') as fecha"))->where('shipperid','=',$shipper->id)->get();
        
        $respuesta['estatus']       = 'OK';
        $respuesta['tracking'] = $tracking;
        $respuesta['shippings'] = $shippings;
        $respuesta['checks'] = $checks;

        return response()->json($respuesta);
    }

    /* Muestra el estado de cuenta del transportista
     * Autor OT
     * Fecha 20-09-2016
     */
    public function estadoCuenta(){
            $idPerson = Auth::user()->personid;
            $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                            $compania="";
                            $nombre1="";
                            $nombre2="";
                            $apellido1="";
                            $apellido2="";
                            $estrellas="";
                            $correo="";
                            $ubicacion="";
                            $idEstado="";
                            $idPais="";
                            $dni="";
                            $ruc="";
                            $arregloDatos=array();
                            $datosUsuario=DB::table('person')->select("person.firstname","person.middlename","person.lastname","person.secondlastname","person.company",
                            "person.starrating","person.img","person.updated","person.ruc","person.dni","shipperaccount.balance",
                            "shipperaccount.available")
                            ->leftJoin('shipper', 'shipper.personid', '=', 'person.id')
                            ->leftJoin('shipperaccount', 'shipperaccount.shipperid', '=', 'shipper.id')
                            ->where('person.id','=',$idPerson)
                            ->get();

                            foreach($datosUsuario as $rowUsuario){
                                $arregloDatos["compania"]= ucfirst($rowUsuario->company);
                                $arregloDatos["nombre1"]= ucfirst($rowUsuario->firstname);
                                $arregloDatos["nombre2"]= ucfirst($rowUsuario->middlename);
                                $arregloDatos["apellido1"]=ucfirst($rowUsuario->lastname);
                                $arregloDatos["apellido2"]=ucfirst($rowUsuario->secondlastname);
                                $arregloDatos["imagen"]=$rowUsuario->img;
                                $arregloDatos["ruc"]=$rowUsuario->ruc;
                                $arregloDatos["dni"]=$rowUsuario->dni;
                                if($rowUsuario->balance==""){
                                    $arregloDatos["saldo"]=0.00;
                                }{
                                    $arregloDatos["saldo"]=  Funciones::formato_numeros($rowUsuario->balance, ",", ".");
                                }
                                
                                if($rowUsuario->available==""){
                                    $arregloDatos["disponible"]=0.00;
                                }{
                                    $arregloDatos["disponible"]=  Funciones::formato_numeros($rowUsuario->available, ",", ".");
                                }
                            }

                            return view('vvtransportista.cuentaEstado')->with("datosUsuario",$arregloDatos);

                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
        }
        
        
     /* Muestra informacion general de las promociones del tranasportista
     * Autor OT
     * Fecha 20-09-2016
     */
    public function misPromociones(){
            $idPerson = Auth::user()->personid;
            $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                           return view('vvtransportista.misPromociones');
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
        }
    
    
    /* Genera el datatable de promociones
     * Autor: OT
     * Fecha: 16-09-2016
    */
    public function listaMisPromociones(Request $request){
        $idPerson = Auth::user()->personid;
        $buscatitulo=trim($request["buscaTitulo"]);
        $fecha1=trim($request["fecha1"]);
        $fecha2=trim($request["fecha2"]);
        $fechaActual=date("Y-m-d H:i:s");
        
        $idShipper=0;
        $datosChofer=  Shipper::where("personid",$idPerson)->get();
        foreach($datosChofer as $chofer){
           $idShipper= $chofer->id;
        }
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (promotions.code ilike'%$buscatitulo%' 
                or trim(to_char(promotions.amount,'999999999')) ilike'%$buscatitulo%'
             )";
        }
        
         $filtroFechas="";
        if($fecha1!=""){
            $filtroFechas.=" and date(shipperpromotion.updated)>= '$fecha1'";
        }

        if($fecha2!=""){
            $filtroFechas.=" and date(shipperpromotion.updated)<= '$fecha2'";
        }
        
        $sWhere="shipperpromotion.shipperid=$idShipper" . $filtroTitulo . $filtroFechas;
        
               
        $datosPromociones = ShipperPromotion::select(["promotions.code","promotions.amount",
            DB::raw("to_char(shipperpromotion.updated,'DD/MM/YYYY') as fecha")])
            ->leftJoin('promotions', 'promotions.id', '=', 'shipperpromotion.promotionid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("shipperpromotion.updated","desc")
            ->get();
        
        
        
        return Datatables::of($datosPromociones)
        ->addColumn('codigo', function ($datosPromociones) {
            $ca="<font class='flet-lab'>". $datosPromociones->code."</font>";
            
            return $ca;
         })
         ->addColumn('vigencia', function ($datosPromociones) {
            $ca="<font class='flet-lab'>".$datosPromociones->fecha. "</font>";
            return $ca;
         })
         
         
         ->addColumn('monto', function ($datosPromociones) {
            $ca="<font class='flet-lab'>S/ ". Funciones::formato_numeros($datosPromociones->amount, ",", "."). "</font>";
            return $ca;
         })
         
        ->make(true);
    }
    
    
    /* Muestra el formulario para capturar el codigo de promoción
     * Autor OT
     * Fecha 20-09-2016
     */
    public function capturarCodigoPromocion(){
            $idPerson = Auth::user()->personid;
            $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                           return view('vvtransportista.capturaPromocion');
                        }else{
                            Auth::logout();
                            return redirect('login/inicio');
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
    }
    
    /* Guarda la promocion y suma el monto al saldo de la cuenta
     * Autor OT
     * Fecha 20-09-2016
     */
    public function guardarCodigoPromocion(Request $request){
        
        $codigoPromocion=trim($request["codigoPromocion"]);
        $fechaActual=date("Y-m-d H:i:s");
        $idPerson = Auth::user()->personid;
        
        try{
            $datosPromocion=  Promotions::where("code",$codigoPromocion)
                    ->where("active",1)
                    ->get();
            if(count($datosPromocion)==0){
                return response()->json(['respuesta' => 'errorCodigo', 'saldo' => '']);
            }
            
            foreach($datosPromocion as $promocion){
                if($promocion->groupid!=""){
                    $engrupo=  Groups::leftJoin('groupsperson', 'groupsperson.groupid', '=', 'groups.id')
                    ->where("groupsperson.personid",$idPerson)
                    ->where("groups.id",$promocion->groupid)
                    ->count();
                    if($engrupo==0){
                        return response()->json(['respuesta' => 'errorgrupo', 'saldo' => '']);
                    }
                }
                
                if(strtotime($promocion->expirationdate)<strtotime($fechaActual)){
                    return response()->json(['respuesta' => 'errorfecha', 'saldo' => '']);
                }
                
                $idShipper=0;
                $datosChofer=  Shipper::where("personid",$idPerson)->get();
                foreach($datosChofer as $chofer){
                    $idShipper= $chofer->id;
                    $enPromocion=  ShipperPromotion::where("shipperid",$idShipper)
                        ->where("promotionid",$promocion->id)
                        ->count();
                
                    if($enPromocion>0){
                       return response()->json(['respuesta' => 'promocionrepetida', 'saldo' => '']);
                    }
                }
                
                $cuentaTransporte=  ShipperAccount::where("shipperid",$idShipper)->get();
                if(count($cuentaTransporte)>0){
                    foreach($cuentaTransporte as $idCuenta){
                         $actualizados = DB::update("update shipperaccount set balance = balance+ ?, available=available+? , updated=? where id = ? and shipperid=?", [$promocion->amount,$promocion->amount,$fechaActual,$idCuenta->id,$idShipper]);
                    }
                }else{
                   $cuenta =  ShipperAccount::create([
                       'balance'=>$promocion->amount,
                       'available'=>$promocion->amount,
                       'createdat'=>$fechaActual,
                       'currencyid'=>1,
                       'shipperid'=>$idShipper,
                       'updated'=>$fechaActual,
                   ]);
                }
                
                $transportistaPromocion =  ShipperPromotion::create([
                       'shipperid'=>$idShipper,
                       'promotionid'=>$promocion->id,
                       'updated'=>$fechaActual,
                   ]);
                
                $historial =  ShipperAccountDetail::create([
                           'shipperid'=>$idShipper,
                           'amount'=>$promocion->amount,
                           'created'=>$fechaActual,
                           'carriercreditid'=>null,
                           'promotionid'=>$promocion->id,
                           'shippingrequestid'=>null,
                          ]);
                
            }
            
            DB::commit();
            
            $saldoActual=  ShipperAccount::where("shipperid",$idShipper)->get();
            $vSaldo=0.00;
            $vDisponible=0.00;
            foreach($saldoActual as $rSaldo){
                $vSaldo=Funciones::formato_numeros($rSaldo->balance, ",", ".");
                $vDisponible=Funciones::formato_numeros($rSaldo->available, ",", ".");
            }
            
            $cadenaSaldo="<center>
                              <h3 class='pricing-plan-title'>".trans("leng.Saldo actual")."</h3>
                              <span class='pricing-plan-price'>S/ $vSaldo</span>
                          <span class='pricing-plan-price-term'>".trans("leng.Disponible").":&nbsp;S/ $vDisponible</span>
                      </center>";
            return response()->json(['respuesta' => 'ok', 'saldo' =>$cadenaSaldo]);
            
            
            
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['respuesta' => $ex, 'saldo' => '']);
        }
                
    }
    
    /* Muestra informacion general del historial del transportista
     * Autor OT
     * Fecha 20-09-2016
     */
    public function historialCuenta(){
            $idPerson = Auth::user()->personid;
            $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                           return view('vvtransportista.historialCuenta');
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
    }
    
    /* Genera el datatable del historial
     * Autor: OT
     * Fecha: 16-09-2016
    */
    public function listaMiHistorial(Request $request){
        $idPerson = Auth::user()->personid;
        $tipoMovimiento=trim($request["tipoMovimiento"]);
        $fecha1=trim($request["fecha1"]);
        $fecha2=trim($request["fecha2"]);
        
        
        $idShipper=0;
        $datosChofer=  Shipper::where("personid",$idPerson)->get();
        foreach($datosChofer as $chofer){
           $idShipper= $chofer->id;
        }
        
        $filtroTipo="";
        if($tipoMovimiento!=0){
            switch ($tipoMovimiento){
                case 1:
                   $filtroTipo= " and shipperaccountdetail.carriercreditid>0" ;
                    break;
                case 2:
                    $filtroTipo= " and shipperaccountdetail.promotionid>0" ;
                    break;
                case 3:
                    $filtroTipo= " and shipperaccountdetail.shippingrequestid>0" ;
                    break;
            }
        }
        
         $filtroFechas="";
        if($fecha1!=""){
            $filtroFechas.=" and date(shipperaccountdetail.created)>= '$fecha1'";
        }

        if($fecha2!=""){
            $filtroFechas.=" and date(shipperaccountdetail.created)<= '$fecha2'";
        }
        
        $sWhere="shipperaccountdetail.shipperid=$idShipper" . $filtroTipo . $filtroFechas;
        
               
        $datosHistorial = ShipperAccountDetail::select(["promotions.code","shipperaccountdetail.amount",
            "shipperaccountdetail.carriercreditid","shipperaccountdetail.promotionid","shipperaccountdetail.shippingrequestid",
            DB::raw("to_char(shipperaccountdetail.created,'DD/MM/YYYY') as fecha")])
            ->leftJoin('carriercredit', 'carriercredit.id', '=', 'shipperaccountdetail.carriercreditid')
            ->leftJoin('promotions', 'promotions.id', '=', 'shipperaccountdetail.promotionid')
            ->leftJoin('shippingrequest', 'shippingrequest.id', '=', 'shipperaccountdetail.shippingrequestid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("shipperaccountdetail.created","desc")
            ->get();
        
        
        
        return Datatables::of($datosHistorial)
        ->addColumn('movimiento', function ($datosHistorial) {
            $mov="";
            if($datosHistorial->carriercreditid!="")$mov=trans("leng.Abono a cuenta");
            else if($datosHistorial->promotionid!="")$mov=trans("leng.Promocíon");
            else if($datosHistorial->shippingrequestid!="")$mov=trans("leng.Envío");
            
            $ca="<font class='flet-lab'>". $mov."</font>";
            
            return $ca;
         })
         ->addColumn('vigencia', function ($datosHistorial) {
            $ca="<font class='flet-lab'>".$datosHistorial->fecha. "</font>";
            return $ca;
         })
         
         
         ->addColumn('monto', function ($datosHistorial) {
             $montod=0;
             
             if($datosHistorial->shippingrequestid!="")$monto=$datosHistorial->amount * -1;
             else $monto=$datosHistorial->amount;
                 
            $ca="<font class='flet-lab'>S/ ". Funciones::formato_numeros($monto, ",", "."). "</font>";
            return $ca;
         })
         
        ->make(true);
    }
    
    
    /* Devuelve el saldo del trasportista formato html
     * Autor OT
     * Fecha 21-09-2016
     */
    public function consultaSaldoTransportista(){
            $idPerson = Auth::user()->personid;
            $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                            $idShipper=0;
                            $datosChofer=  Shipper::where("personid",$idPerson)->get();
                            foreach($datosChofer as $chofer){
                               $idShipper= $chofer->id;
                            }
                            $saldoActual=  ShipperAccount::where("shipperid",$idShipper)->get();
                            $vSaldo=0.00;
                            $vDisponible=0.00;
                            foreach($saldoActual as $rSaldo){
                                $vSaldo=Funciones::formato_numeros($rSaldo->balance, ",", ".");
                                $vDisponible=Funciones::formato_numeros($rSaldo->available, ",", ".");
                            }

                            $cadenaSaldo="<li>
                                <a href='".url('/transportista/estadoCuenta')."'><font style='color:#b9261e'><b>".trans("leng.Saldo disponible").": S/ $vDisponible</b></font></a></li>";
                            
                            return $cadenaSaldo;
                            
                           
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
    }
    
    
    /* Muestra la pantalla de choferes del transportista
     * Autor: OT
     * Fecha: 23-09-2016
    */
    public function misChoferes(){
        return view('vvtransportista.misChoferes');
    }
    
    
    /* Genera la lista de choferes
     * Autor: OT
     * Fecha: 23-09-2016
    */
    public function listaChoferesTransportista(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $idShipper=0;
        $datosChofer=  Shipper::where("personid",$idPerson)->get();
        foreach($datosChofer as $chofer){
                 $idShipper= $chofer->id;
        }
        
        $buscatitulo=trim($request["buscaTitulo"]);
        $dniFiltro=trim($request["dniFiltro"]);
        $licenciaFiltro=trim($request["licenciaFiltro"]);
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (person.firstname ilike'%$buscatitulo%' or person.middlename ilike'%$buscatitulo%' 
                or person.lastname ilike'%$buscatitulo%'
                or person.secondlastname ilike'%$buscatitulo%'
                or users.email ilike'%$buscatitulo%'
                or driver.phone ilike'%$buscatitulo%'
              )";
        }
        
        $buscaDni="";
        if($dniFiltro!=""){
            $buscaDni.=" and (person.dni ilike'%$dniFiltro%')";
        }
        
        
        $buscaLicencia="";
        if($licenciaFiltro!=""){
            $buscaDni.=" and (driver.license ilike'%$licenciaFiltro%')";
        }
        
        $sWhere="driver.shipperid=$idShipper and users.active=true and driver.deleted is null " . $filtroTitulo. $buscaDni . $buscaLicencia;
        
                
        $datosChoferes = Driver::select(["driver.id","person.firstname","person.middlename","person.lastname","person.secondlastname",
            "person.dni","users.email","users.active","driver.phone","driver.license"])
            ->leftJoin('person', 'person.id', '=', 'driver.personid')
            ->leftJoin('users', 'users.personid', '=', 'person.id')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("person.firstname")
            ->get();
        
        
        return Datatables::of($datosChoferes)
        ->addColumn('name', function ($datosChoferes) {
            
            $nombre= $datosChoferes->firstname . " " . $datosChoferes->middlename . " " . $datosChoferes->lastname . " ".  $datosChoferes->secondlastname;
            
            $ca="<font class='flet-lab'>".$nombre."</font>";
            
            return $ca;
         })
         ->addColumn('dni', function ($datosChoferes) {
            $ca="<font class='flet-lab'>".$datosChoferes->dni."</font>";
            return $ca;
         })
         
         ->addColumn('usuario', function ($datosChoferes) {
            $ca="<font class='flet-lab'>".$datosChoferes->email."</font>";
            return $ca;
         })
         
         ->addColumn('licencia', function ($datosChoferes) {
            $ca="<font class='flet-lab'>".$datosChoferes->license."</font>";
            return $ca;
         })
         
         ->addColumn('telefono', function ($datosChoferes) {
             
            
                $ca="<font class='flet-lab'>".$datosChoferes->phone."</font>";
            
            return $ca;
         })
         
         ->addColumn('acciones', function ($datosChoferes) {
            $ca="<button type='button' class='btn btn-secondary btn-xs' style='width:25px;' onclick='editarChofer(".$datosChoferes->id.")' title='".trans('leng.Editar chofer')."'><i class='fa fa-pencil-square-o'></i></button>&nbsp;";
            $ca.="<button type='button' class='btn btn-secondary btn-xs' style='width:25px;' onclick='cambiarPassChofer(".$datosChoferes->id.")' title='".trans('leng.Cambiar contraseña')."'><i class='fa fa-unlock-alt'></i></button>&nbsp;";
            $ca.="<button type='button' class='btn btn-danger btn-xs' style='width:25px;' onclick='eliminarChofer(".$datosChoferes->id.")' title='".trans('leng.Eliminar chofer')."'><i class='fa fa-trash-o'></i></button>&nbsp;";
            return $ca;
         })
         
         
         
        ->make(true);
    }
    
    
    /* Muestra la pantalla el formulario para agregar un chofer
     * Autor: OT
     * Fecha: 23-09-2016
    */
    public function nuevoChofer(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                            $clave= strtoupper(substr(str_replace(" ","",$dataPerson->company), 0,3));
                            return view('vvtransportista.choferNuevo')->with("clave",$clave);
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
    }
    
    /* Guarda chofer
     * Autor OT
     * Fecha 23-09-2016
     */
    public function guardarChofer(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $primerNombre=trim($request["primerNombre"]);
        $segundoNombre=trim($request["segundoNombre"]);
        $primerApellido=trim($request["primerApellido"]);
        $segundoApellido=trim($request["segundoApellido"]);
        $dniChofer=trim($request["dniChofer"]);
        $telefonoChofer=trim($request["telefonoChofer"]);
        $usuarioChofer=trim($request["usuarioChofer"]);
        $passChofer=trim($request["passChofer"]);
        $licenciaChofer=trim($request["licenciaChofer"]);
        
        $idPerson = Auth::user()->personid;
        
        $nombreCompania="";
        $idShipper="";
        
        $datosTransportista=Person::select("person.company","shipper.id")
                ->leftJoin("shipper","shipper.personid","=","person.id")
                ->where("person.id",$idPerson)
                ->get();
        foreach($datosTransportista as $transporte){
            $nombreCompania=$transporte->company;
            $idShipper=$transporte->id;
        }
        
        
       $existeDni = Person::leftJoin("users","users.personid","=","person.id")
               ->whereRaw(DB::raw("upper(person.dni)='".strtoupper(trim($dniChofer))."'"))
               ->where('users.active',true)
               ->count();
       
       $existeLicencia=  Driver::whereRaw(DB::raw("upper(license)='".strtoupper(trim($licenciaChofer))."'"))
               ->where("deleted",null)
               ->count();
       
       if($existeDni>0){
           return "dnirepetido";
       }
       
       if($existeLicencia>0){
           return "licenciarepetida";
       }
       
       if($nombreCompania=="")return "errorCompania";
       if($idShipper=="")return "errorShipper";
        
        try{
            DB::beginTransaction();
            
            $personUs=Person::create([
                 'firstname'=>$primerNombre,
                 'middlename'=>$segundoNombre,
                 'lastname'=>$primerApellido,
                 'secondlastname'=>$segundoApellido,
                 'isdriver'=>TRUE,
                 'isshipper'=>FALSE,
                 'suspended'=>FALSE,
                 'company'=>$nombreCompania,
                 'newoffer'=>FALSE,
                 'offercanceled'=>FALSE,
                 'newquestion'=>FALSE,
                 'offeraccepted'=>FALSE,
                 'offerrejected'=>FALSE,
                 'newreply'=>FALSE,
                 'shipmentcollected'=>FALSE,
                 'shipmentdelivered'=>FALSE,
                 'feedback'=>FALSE,
                 'assignedvehicle'=>FALSE,
                 'updated'=>$fechaActual,
                 'dni'=>$dniChofer,
                 'ruc'=>'',
                 'newshipping'=>FALSE,
                 'shippingexpiration'=>FALSE,
                 'shippingcheck'=>FALSE,
                 'competingoffer'=>FALSE,
           ]);
           
                        $idInsertado=$personUs->id;

                        $usuario=User::create([
                            'email'=>$usuarioChofer,
                            'password'=>bcrypt($passChofer),
                            'resetpasswordtoken'=>'',
                            'resetpasswordsentat'=>$fechaActual,
                            'remembercreatedat'=>$fechaActual,
                            'currentsigninat'=>$fechaActual,
                            'currentsigninip'=>'',
                            'lastsigninip'=>'',
                            'confirmedat'=>$fechaActual,
                            'lastsigninat'=>$fechaActual,
                            'confirmationsentat'=>$fechaActual,
                            'lockedat'=>$fechaActual,
                            'unlocktoken'=>'',
                            'confirmationtoken'=>'1',
                            'personid'=>$idInsertado,
                            'active'=>TRUE,
                            'verifiedaccount'=>TRUE,
                            'isverified'=>TRUE
                        ]);
                        
                        $usuarioInsertado=$usuario->id;
                        
                        $usuarioRol=  RolUsuario::create([
                            'created_at'=>$fechaActual,
                            'updated_at'=>$fechaActual,
                            'userid'=>$usuarioInsertado,
                            'roleid'=>4,
                        ]);
                        
                        $driver=  Driver::create([
                            'personid'=>$idInsertado,
                            'shipperid'=>$idShipper,
                            'updated'=>$fechaActual,
                            'phone'=>$telefonoChofer,
                            'license'=>$licenciaChofer,
                        ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    /* Muestra el formulario para edtiar un chofer
     * Autor: OT
     * Fecha: 24-09-2016
    */
    public function editarChofer(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $id=trim($request["idChofer"]);
        
        $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                            $datos=array();
                            $datosChofer=  Driver::select("person.firstname","person.middlename","person.lastname",
                                    "person.secondlastname","person.dni","users.email","driver.phone","driver.license")
                                    ->leftJoin("person","person.id","=","driver.personid")
                                    ->leftJoin("users","users.personid","=","person.id")
                                    ->where("driver.id",$id)
                                    ->get();
                            foreach($datosChofer as $chofer){
                                $datos["idChofer"]=$id;
                                $datos["primerNombre"]=$chofer->firstname;
                                $datos["segundoNombre"]=$chofer->middlename;
                                $datos["primerApellido"]=$chofer->lastname;
                                $datos["segundoApellido"]=$chofer->secondlastname;
                                $datos["dni"]=$chofer->dni;
                                $datos["usuario"]=$chofer->email;
                                $datos["telefono"]=$chofer->phone;
                                $datos["licencia"]=$chofer->license;
                            }
                            
                            return view('vvtransportista.choferEditar')->with("datos",$datos);
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
        
        
    }
    
    
    /* Actualiza los datos del chofer
     * Autor OT
     * Fecha 24-09-2016
     */
    public function actualizarChofer(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $primerNombre=trim($request["primerNombre"]);
        $segundoNombre=trim($request["segundoNombre"]);
        $primerApellido=trim($request["primerApellido"]);
        $segundoApellido=trim($request["segundoApellido"]);
        $idChofer=trim($request["idChofer"]);
        $telefonoChofer=trim($request["telefonoChofer"]);
        $licenciaChofer=trim($request["licenciaChofer"]);

        
        $idPersonDriver=0;
        $idShipperDriver=0;
        
        $datosChofer=Driver::where("id",$idChofer)->get();
        foreach($datosChofer as $chofer){
            $idPersonDriver=$chofer->personid;
            $idShipperDriver=$chofer->shipperid;
        }
        
        $existeLicencia=  Driver::whereRaw(DB::raw("upper(license)='".strtoupper(trim($licenciaChofer))."'"))
               ->where("deleted",null)
               ->where("id","!=",$idChofer)
               ->count();
       
        if($existeLicencia>0){
            return "licenciaRepetida";
        }
        
        try{
            DB::beginTransaction();
            
            DB::table('person')
                    ->where('id', $idPersonDriver)
                   ->update([
                            'firstname' => $primerNombre,
                            'middlename' => $segundoNombre,
                            'lastname' => $primerApellido,
                            'secondlastname' => $segundoApellido,
                            'updated' => $fechaActual,
                           ]);
            
            DB::table('driver')
                    ->where('id', $idChofer)
                   ->update([
                            'phone' => $telefonoChofer,
                            'updated' => $fechaActual,
                            'license' => $licenciaChofer,
                           ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Muestra el formulario para cambiar el pass del chofer
     * Autor: OT
     * Fecha: 26-09-2016
    */
    public function cambiarPassChofer(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        $idChofer=$request["idChofer"];
        
        $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                            return view('vvtransportista.cambiarPassChofer')->with("idChofer",$idChofer);
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
    }
    
    
    /* Guarda la nueva contraseña del chofer
     * Autor OT
     * Fecha 26-09-2016
     */
    public function actualizarPassChofer(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $idChofer=trim($request["idChofer"]);
        $nuevoPass=trim($request["nuevoPass"]);
        
        $idUsuario=0;
        
        
        $datosChofer=Driver::select("users.id")
                ->leftJoin("users","users.personid","=","driver.personid")
                ->where("driver.id",$idChofer)
                ->get();
        
        foreach($datosChofer as $chofer){
            $idUsuario=$chofer->id;
        }
        
        try{
            DB::beginTransaction();
            
            DB::table('users')
                    ->where('id', $idUsuario)
                   ->update([
                            'password'=>bcrypt($nuevoPass),
                            'updated_at' => $fechaActual,
                           ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    
    /* Elimina al usuario seleccionado
     * Autor OT
     * Fecha 26-09-2016
     */
    public function eliminarChofer(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $idChofer=trim($request["idChofer"]);
        
        $idUsuario=0;
        $idPerson=0;
        
        $datosChofer=Driver::select("users.id","driver.personid")
                ->leftJoin("users","users.personid","=","driver.personid")
                ->where("driver.id",$idChofer)
                ->get();
        
        $existeVehiculo=  Vehiculo::where("driverid",$idChofer)
                ->where("deleted",null)
                ->count();
        
        if($existeVehiculo>0){
            return "errorvehiculo";
        }
        
        foreach($datosChofer as $chofer){
            $idUsuario=$chofer->id;
            $idPerson=$chofer->personid;
        }
        
        try{
            DB::beginTransaction();
            
            DB::table('users')
                   ->where('id', $idUsuario)
                   ->update([
                            'active'=>false,
                            'updated_at' => $fechaActual,
                           ]);
            
            
            DB::table('driver')
                   ->where('id', $idChofer)
                   ->update([
                            'deleted'=>$fechaActual,
                            'updated' => $fechaActual,
                           ]);
            
            DB::table('person')
                   ->where('id', $idPerson)
                   ->update([
                            'updated' => $fechaActual,
                           ]);
            
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    
    /* Muestra la pantalla de listado de vehiculos
     * Autor: OT
     * Fecha: 26-09-2016
    */
    public function misVehiculos(){
        return view('vvtransportista.misVehiculos');
    }
    
    
    /* Genera de datatable de vehiculos
     * Autor: OT
     * Fecha: 26-09-2016
    */
    public function listaVehiculosTransportista(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $idShipper=0;
        $datosChofer=  Shipper::where("personid",$idPerson)->get();
        foreach($datosChofer as $chofer){
                 $idShipper= $chofer->id;
        }
        
        $buscatitulo=trim($request["buscaTitulo"]);
        $asignado=trim($request["asignado"]);
        $sinAsignar=trim($request["sinAsignar"]);
        $activo=trim($request["activo"]);
        $inactivo=trim($request["inactivo"]);
        
        $filtroTitulo="";
        if($buscatitulo!=""){
            $filtroTitulo.=" and (person.firstname ilike'%$buscatitulo%'
                or person.lastname ilike'%$buscatitulo%'
                or vehicle.description ilike'%$buscatitulo%'
                or vehicle.plate ilike'%$buscatitulo%'
                or vehicletype.name ilike'%$buscatitulo%'
              )";
        }
        
        $filtrosStatus="";
        $primerFiltro=0;
        
        if($asignado=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (vehicle.driverid>0 ";
                $primerFiltro=1;
            }
        }
        
        if($sinAsignar=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (vehicle.driverid is null";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or vehicle.driverid is null";
            }
        }
        
        if($activo=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (vehicle.active=true";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or vehicle.active=true";
            }
        }

        if($inactivo=="true"){
            if($primerFiltro==0){
                $filtrosStatus.=" and (vehicle.active=false";
                $primerFiltro=1;
            }else{
                $filtrosStatus.=" or vehicle.active=false";
            }
        }


        if($primerFiltro==1)$filtrosStatus.=")";
        
        
        $sWhere="vehicle.shipperid=$idShipper and vehicle.deleted is null " . $filtroTitulo . $filtrosStatus;
        
                
        $datosVehiculos = Vehicle::select("vehicle.id","vehicle.description","vehicle.active","vehicle.updated","vehicle.plate","vehicletype.name",
                "person.firstname","person.lastname")
            ->leftJoin('vehicletype', 'vehicletype.id', '=', 'vehicle.vehicletypeid')
            ->leftJoin('driver', 'driver.id', '=', 'vehicle.driverid')
            ->leftJoin('person', 'person.id', '=', 'driver.personid')
            ->whereRaw(DB::raw($sWhere))
            ->orderBy("vehicle.plate","asc")
            ->get();
        
        return Datatables::of($datosVehiculos)
                
        ->addColumn('name', function ($datosVehiculos) {
            
            $estado=trans("leng.Activo");
            $nombre= $datosVehiculos->description;
            
            if($datosVehiculos->active==false){
                $estado=trans("leng.Inactivo");
            }
            
            $ca="<font class='flet-lab'>".$nombre."</font>.<br>";
            $ca.="<font class='flet-lab'><b>".trans("leng.Estado").":&nbsp;</b>".$estado."</font>.";
            
            
            return $ca;
         })
         ->addColumn('placa', function ($datosVehiculos) {
            $ca="<font class='flet-lab'>".$datosVehiculos->plate."</font>";
            return $ca;
         })
         
         ->addColumn('tipo', function ($datosVehiculos) {
            $ca="<font class='flet-lab'>".$datosVehiculos->name."</font>";
            return $ca;
         })
         
         ->addColumn('chofer', function ($datosVehiculos) {
            $ca="<font class='flet-lab'>".ucfirst($datosVehiculos->firstname)." " .ucfirst($datosVehiculos->lastname) ."</font>";
            return $ca;
         })
         
         ->addColumn('acciones', function ($datosVehiculos) {
            $ca="<button type='button' class='btn btn-secondary btn-xs' style='width:25px;' onclick='editarVehiculo(".$datosVehiculos->id.")' title='".trans('leng.Editar vehículo')."'><i class='fa fa-pencil-square-o'></i></button>&nbsp;";
            
            if($datosVehiculos->active==true){
                $ca.="<button type='button' class='btn btn-danger btn-xs' style='width:25px;' onclick='activarDesactivarVehiculo(".$datosVehiculos->id.",1)' title='".trans('leng.Desactivar vehículo')."'><i class='fa fa-times'></i></button>&nbsp;";
            }else{
                $ca.="<button type='button' class='btn btn-success btn-xs' style='width:25px;' onclick='activarDesactivarVehiculo(".$datosVehiculos->id.",0)' title='".trans('leng.Activar vehículo')."'><i class='fa fa-check'></i></button>&nbsp;";
            }
            
            $ca.="<button type='button' class='btn btn-danger btn-xs' style='width:25px;' onclick='eliminarVehiculo(".$datosVehiculos->id.")' title='".trans('leng.Eliminar vehiculo')."'><i class='fa fa-trash-o'></i></button>&nbsp;";
            return $ca;
         })
         
         
         
        ->make(true);
    }
    
    
    
    /* Muestra la pantalla el formulario para crear un vehiculo
     * Autor: OT
     * Fecha: 26-09-2016
    */
    public function nuevoVehiculo(){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $idShipper=0;
        $datosChofer=  Shipper::where("personid",$idPerson)->get();
        foreach($datosChofer as $chofer){
                 $idShipper= $chofer->id;
        }
        
        $listaTipos="";
        $tiposVehiculo=DB::table('vehicletype')->select("vehicletype.id","vehicletype.name")
                  ->where('vehicletype.active','=',true)
                  ->orderBy("vehicletype.name")
                  ->get();
        foreach($tiposVehiculo as $tipo){
            $listaTipos.="<option value=$tipo->id>".  ucfirst($tipo->name)."</option>";
        }
        
        
        $listaChoferes="";
        $choferes=DB::table('driver')->select("driver.id","person.firstname","person.lastname")
                  ->leftJoin("person","person.id","=","driver.personid")
                  ->where('driver.shipperid','=',$idShipper)
                  ->where('driver.deleted','=',null)
                  ->get();
        foreach($choferes as $chofer){
            $listaChoferes.="<option value=$chofer->id>". ucfirst($chofer->firstname)." ". ucfirst($chofer->lastname)."</option>";
        }

        
        return view('vvtransportista.vehiculoNuevo')
                ->with("listaTipos",$listaTipos)
                ->with("listaChoferes",$listaChoferes);
    }
    
    /* Guarda el vehiculo nuevo
     * Autor OT
     * Fecha 26-09-2016
     */
    public function guardarVehiculo(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $idShipper=0;
        $datosChofer=  Shipper::where("personid",$idPerson)->get();
        foreach($datosChofer as $chofer){
                 $idShipper= $chofer->id;
        }
        
        
        $fechaActual=date("Y-m-d H:i:s");
        $idTipo=$request["idTipo"];
        $idChofer=trim($request["idChofer"]);
        $descripcion=trim($request["descripcion"]);
        $placa=strtoupper(trim($request["placa"]));
        $idPerson = Auth::user()->personid;
        
        
        $existePlaca=  Vehiculo::where("plate",$placa)->where("deleted",null)->count();
       
       if($existePlaca>0){
           return "placarepetida";
       }
       
        try{
            DB::beginTransaction();
            
            $vehiculo=Vehiculo::create([
                 'shipperid'=>$idShipper,
                 'description'=>$descripcion,
                 'active'=>TRUE,
                 'updated'=>$fechaActual,
                 'vehicletypeid'=>$idTipo,
                 'plate'=>$placa,
                 'driverid'=>($idChofer>0)?$idChofer:null
           ]);
           
            $vehiculoInsertado=$vehiculo->id;
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    
    
    /* Muestra el formulario para editar un vehiculo
     * Autor: OT
     * Fecha: 26-09-2016
    */
    public function editarVehiculo(Request $request){
        if (Auth::check()) {
            $idPerson = Auth::user()->personid;
        }else{
            Auth::logout();
            return redirect('transportista');
        }
        
        $idShipper=0;
        $datosChofer=  Shipper::where("personid",$idPerson)->get();
        foreach($datosChofer as $chofer){
                 $idShipper= $chofer->id;
        }
        
        $id=trim($request["idVehiculo"]);
        
        $dataPerson=Person::find($idPerson);
                    if(count($dataPerson)>0){
                        if($dataPerson->isshipper==true){
                            $datos=array();
                            $datosVehiculo= Vehiculo::select("vehicle.description","vehicle.vehicletypeid","vehicle.plate",
                                    "vehicle.driverid")
                                    ->where("vehicle.id",$id)
                                    ->get();
                            foreach($datosVehiculo as $vehiculo){
                                $datos["idVehiculo"]=$id;
                                $datos["descripcion"]=$vehiculo->description;
                                $datos["idtipo"]=$vehiculo->vehicletypeid;
                                $datos["placa"]=$vehiculo->plate;
                                $datos["idChofer"]=$vehiculo->driverid;
                            }
                            
                            $listaTipos="";
                            $tiposVehiculo=DB::table('vehicletype')->select("vehicletype.id","vehicletype.name")
                                      ->where('vehicletype.active','=',true)
                                      ->orderBy("vehicletype.name")
                                      ->get();
                            foreach($tiposVehiculo as $tipo){
                                if($datos["idtipo"]==$tipo->id){
                                    $listaTipos.="<option value='$tipo->id' selected>".  ucfirst($tipo->name)."</option>";
                                }else{
                                    $listaTipos.="<option value=$tipo->id>".  ucfirst($tipo->name)."</option>";
                                }
                            }
                            
                            $datos["listaTipos"]=$listaTipos;

                            $listaChoferes="";
                            $choferes=DB::table('driver')->select("driver.id","person.firstname","person.lastname")
                                      ->leftJoin("person","person.id","=","driver.personid")
                                      ->where('driver.shipperid','=',$idShipper)
                                      ->where('driver.deleted','=',null)
                                      ->get();
                            
                            foreach($choferes as $chofer){
                                if($datos["idChofer"]==$chofer->id){
                                   $listaChoferes.="<option value='$chofer->id' selected>". ucfirst($chofer->firstname)." ". ucfirst($chofer->lastname)."</option>"; 
                                }else{
                                   $listaChoferes.="<option value=$chofer->id>". ucfirst($chofer->firstname)." ". ucfirst($chofer->lastname)."</option>";
                                }
                            }
                            
                            $datos["listaChoferes"]=$listaChoferes;
                            
                            return view('vvtransportista.vehiculoEditar')->with("datos",$datos);
                        }
                    }else{
                        Auth::logout();
                        return redirect('login/inicio');
                    }
        
        
    }
    
    
    /* Actualiza los datos del vehiculo
     * Autor OT
     * Fecha 26-09-2016
     */
    public function actualizarVehiculo(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $idVehiculo=$request["idVehiculo"];
        $idTipo=$request["idTipo"];
        $idChofer=trim($request["idChofer"]);
        $descripcion=trim($request["descripcion"]);
        $placa=strtoupper(trim($request["placa"]));
        
        
        $existePlaca=  Vehiculo::where("plate",$placa)->where("deleted",null)->where("id","!=",$idVehiculo)->count();
        
        if($existePlaca>0){
            return "placarepetida";
        }
        
        try{
            DB::beginTransaction();
            
            DB::table('vehicle')
                    ->where('id', $idVehiculo)
                   ->update([
                            'description' => $descripcion,
                            'updated' => $fechaActual,
                            'vehicletypeid' => $idTipo,
                            'plate' => $placa,
                            'driverid' =>($idChofer>0)?$idChofer:null,
                           ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    /* Activa o desactiva el vehiculo
     * Autor OT
     * Fecha 27-09-2016
     */
    public function activarDesactivarVehiculo(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $idVehiculo=$request["idVehiculo"];
        $opcion=$request["opcion"];
        
        $activo=FALSE;
        
        if($opcion==0)$activo=TRUE;
        
        
        try{
            DB::beginTransaction();
            
            DB::table('vehicle')
                    ->where('id', $idVehiculo)
                   ->update([
                            'updated' => $fechaActual,
                            'active' => $activo,
                           ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /* Eliminar vehiculo
     * Autor OT
     * Fecha 27-09-2016
     */
    public function eliminarVehiculo2(Request $request){
        $fechaActual=date("Y-m-d H:i:s");
        $idVehiculo=$request["idVehiculo"];
        
        try{
            DB::beginTransaction();
            
            DB::table('vehicle')
                    ->where('id', $idVehiculo)
                   ->update([
                            'updated' => $fechaActual,
                            'deleted' => $fechaActual,
                            'active' => FALSE,
                           ]);
            
            DB::commit();
            
            return "ok";
            
        } catch (Exception $ex) {
            DB::rollback();
            return $ex;
        }
    }
    
    
    /**
     * Obtiene el precio por ofertar del transportista
     * Autor: OT
     * Fecha 29-09-2016
     */
    public function obtenerPrecioPorOfertar(Request $request){
        $idEnvio=$request["idEnvio"];
        $oferta=$request["oferta"];
        
        $costoPorOfertar=  Funciones::obtenerPrecioPorOfertar(Auth::user()->personid,$idEnvio,$oferta);
            if($costoPorOfertar["error"]>0){
                $error=trans("leng.Error desconocido,consulte al administrador").".";
                
                if($costoPorOfertar["error"]==1)$error=trans("leng.No hay precio establecido para realizar la oferta, consulte al administrador").".";
                
                $resultado=array();
                $resultado["error"]=1;
                $resultado["precioOfertarV"]=0;
                $resultado["tituloError"]=$error;
                return response()->json($resultado);
            }
        
        $resultado=array();
        $resultado["error"]=0;
        $resultado["precioOfertarV"]=$costoPorOfertar["precioEnvio"];
        $resultado["precioOfertarFormato"]="S/ ". Funciones::formato_numeros($costoPorOfertar["precioEnvio"], ",", ".");
        $resultado["tituloError"]="";
        return response()->json($resultado);
    }

    
    
}