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
use Udoktor\Domain\Users\OfferedService;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Domain\Users\Service;
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
        $countries       = EntityManager::getRepository(AdministrativeUnit::class)->findBy(['parentUnit' => null]);
        $services        = EntityManager::getRepository(Service::class)->findBy(['active' => true]);
        $classifications = EntityManager::getRepository(Classification::class)->findBy(['active' => true]);
        $servicesJson    = [];
        $servicesString  = [];

        // creating list services on json format
        foreach ($services as $service) {
            $servicesJson[] = [
                'value' => $service->getId(),
                'text'  => $service->getName()
            ];

            $servicesString[] = '-' . $service->getName();
        }

        $servicesJson   = json_encode($servicesJson);
        $servicesString = implode("<br>", $servicesString);

        return view('accounts.sign_up', compact('countries', 'servicesJson', 'classifications', 'servicesString'));
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
        $aUnitId  = (int) $request->input('aUnitId');
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
            $aUnit  = EntityManager::getRepository(AdministrativeUnit::class)->find((int) $request->input('municipio'));
            $user   = new User(new FullName($request->input('nombre'), $request->input('paterno'), $request->input('materno')),
                $request->input('email'),
                $request->input('pass'),
                $request->input('telefono'),
                (int) $request->input('tipoCuenta'),
                $aUnit
            );

            if ($user->isServiceProvider()) {
                $offeredServices = new CustomCollection;
                $schedules       = new CustomCollection;
                $classification  = EntityManager::getRepository(Classification::class)->find((int) $request->input('clasificacion'));
                $servicesIds     = explode(',', $request->input('servicios'));

                foreach ($servicesIds as $serviceId) {
                    $service        = EntityManager::getRepository(Service::class)->find((int) $serviceId);
                    $offeredService = new OfferedService($user, $service);
                    $offeredServices->add($offeredService);
                }

                $user->addComponentsForServiceProvider($classification, $offeredServices, $schedules);
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
            Log::error($e->getTraceAsString());

        } finally {
            return response()->json($response);
        }
    }
}