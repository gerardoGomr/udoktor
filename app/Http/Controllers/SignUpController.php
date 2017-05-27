<?php
namespace Udoktor\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Log;
use Mail;
use Udoktor\Application\CustomCollection;
use Udoktor\Domain\Persons\FullName;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Users\Classification;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Domain\Users\ServiceType;
use Udoktor\Domain\Users\User;
use Udoktor\Http\Controllers\Controller;
use Udoktor\Http\Requests\SignUpRequest;
use Udoktor\Mail\AccountCreated;

/**
 * Class SignUpController
 *
 * @package Udoktor\Http\Controllers
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class SignUpController extends Controller
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * Class constructor
     *
     * @param UsersRepository $repository
     */
    public function __construct(UsersRepository $repository)
    {
        $this->usersRepository = $repository;
    }

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

        return view('accounts.sign_up', compact('countries', 'serviceTypesJson', 'classifications'));
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

        $response['html'] = view('accounts.sign_up_aunit', compact('aUnits'))->render();

        return response()->json($response);
    }

    /**
     * register a new user
     *
     * @param SignUpRequest $request
     * @return Illuminate\Support\Response\JsonResponse
     */
    public function store(SignUpRequest $request)
    {
        $response = ['estatus' => 'OK'];

        try {
            $user = new User(new FullName($request->get('nombre'), $request->get('paterno'), $request->get('materno')),
                $request->get('email'),
                $request->get('pass'),
                $request->get('telefono'),
                (int) $request->get('tipoCuenta'));

            if ($user->isServiceProvider()) {
                $services       = new CustomCollection;
                $classification = EntityManager::getRepository(Classification::class)->find((int) $request->get('clasificacion'));
                $serviceTypeIds = explode(',', $request->get('servicios'));

                foreach ($serviceTypeIds as $serviceId) {
                    $serviceType = EntityManager::getRepository(ServiceType::class)->find((int) $serviceId);
                    $services->add($serviceType);
                }

                $user->addComponentsForServiceProvider($classification, $services);
            }

            $user->register();

            // persisting
            $this->usersRepository->persist($user);

            // code for email sending
            Mail::to($user->getEmail())->send(new AccountCreated($user));

        } catch (Exception $e) {
            // log exception & construct response
            $response['estatus'] = 'fail';
            $response['message'] = '¡Hubo un error! ' . $e->getMessage();

            Log::error($e->getMessage());

        } finally {
            return response()->json($response);
        }
    }
}