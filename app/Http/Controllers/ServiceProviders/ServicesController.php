<?php

namespace Udoktor\Http\Controllers\ServiceProviders;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Domain\Users\OfferedService;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Domain\Users\Service;
use Udoktor\Http\Controllers\Controller;

/**
 * Class ServicesController
 *
 * @package Udoktor\Http\Controllers\ServiceProviders
 * @category Controller
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class ServicesController extends Controller
{
    /**
     * The repository from storage
     *
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
     * Display a listing of the current services offered by the service provider
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = EntityManager::getRepository(Service::class)->findBy(['active' => true]);
        return view('service_provider.services', compact('services'));
    }

    /**
     * add new services to user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addServices(Request $request)
    {
        $response = [];

        // first unset all services
        $user = Auth::user();

        // then add the new ones, if any
        try {
            if ($request->has('services')) {
                foreach ($request->input('services') as $serviceId) {
                    $service        = EntityManager::getRepository(Service::class)->find((int) $serviceId);
                    $offeredService = new OfferedService($user, $service);

                    $user->addService($offeredService);
                }
            }

            $this->usersRepository->persist($user);

            $response['status'] = 'success';
            $response['html']   = view('service_provider.services_list')->render();
            return response()->json($response);

        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['error']  = $e->getMessage();
            return response()->json($response);
        }
    }

    /**
     * remove the given service from user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeService(Request $request)
    {
        $response = [];

        // first unset all services
        $user = Auth::user();

        // then add the new ones, if any
        try {
            $offeredService = EntityManager::getRepository(OfferedService::class)->find((int) $request->input('id'));
            $user->removeService($offeredService);
            $this->usersRepository->persist($user);

            $response['status'] = 'success';
            $response['html']   = view('service_provider.services_list')->render();
            return response()->json($response);

        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['error']  = $e->getMessage();
            return response()->json($response);
        }
    }
}