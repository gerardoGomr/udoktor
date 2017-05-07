<?php
namespace Udoktor\Http\Controllers;

use Illuminate\Http\Request;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Users\Classification;
use Udoktor\Domain\Users\ServiceType;
use Udoktor\Http\Controllers\Controller;

/**
 * Class SignUpController
 *
 * @package Udoktor\Http\Controllers
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class SignUpController extends Controller
{
    private $unitsRepository;

    /*public function __construct(ObjectRepository $unitsRepository)
    {
        $this->unitsRepository = $unitsRepository;
    }*/

    /**
     * shows main view
     *
     * @return
     */
    public function index()
    {
        $countries        = EntityManager::getRepository(AdministrativeUnit::class)->findBy(['parentUnit' => null]);
        $serviceTypes     = EntityManager::getRepository(ServiceType::class)->findBy(['active' => true]);
        $classifications  = EntityManager::getRepository(Classification::class)->findBy(['active' => true]);
        $serviceTypesJson = [];

        // creating list services on json format
        foreach ($serviceTypes as $serviceType) {
            $serviceTypesJson[] = [
                'value' => $serviceType->getId(),
                'text'  => $serviceType->getName()
            ];
        }

        $serviceTypesJson = json_encode($serviceTypesJson);

        return view('login.crear_cuenta', compact('countries', 'serviceTypesJson', 'classifications'));
    }

    /**
     * search administrative units by parent's id
     *
     * @param Request $request
     * @return Illuminate\Response\JsonResponse
     */
    public function searchAUnit(Request $request)
    {
        $response = ['status' => 'OK'];
        $aUnitId  = (int) $request->get('aUnitId');
        $aUnits   = EntityManager::getRepository(AdministrativeUnit::class)->findBy(['parentUnit' => $aUnitId]);

        if (count($aUnits) === 0) {
            $response['status']  = 'fail';
            $response['message'] = 'No existen unidades administrativas descendientes de la especificada';
        }

        $response['html'] = view('login.crear_cuenta_aunit', compact('aUnits'))->render();

        return response()->json($response);
    }

    public function store(Request $request)
    {

    }
}