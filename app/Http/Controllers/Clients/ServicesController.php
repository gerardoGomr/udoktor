<?php

namespace Udoktor\Http\Controllers\Clients;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Users\Classification;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Domain\Users\Service;
use Udoktor\Domain\Users\User;
use Udoktor\Http\Controllers\Controller;

/**
 * Class ServicesController
 *
 * @package Udoktor\Http\Controllers\Clients
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class ServicesController extends Controller
{
    /**
     * Users repository
     *
     * @var UsersRepository
     */
    private $serviceProviderRepository;

    /**
     * Class constructor
     *
     * @param UsersRepository $repository
     */
    public function __construct(UsersRepository $repository)
    {
        $this->serviceProviderRepository = $repository;
    }

    /**
     * returns index view
     *
     * @return \Illuminate\Support\Facades\View
     */
    public function index()
    {
        $countries        = EntityManager::getRepository(AdministrativeUnit::class)->findBy(['parentUnit' => null]);
        $services         = EntityManager::getRepository(Service::class)->findBy(['active' => true]);
        $classifications  = EntityManager::getRepository(Classification::class)->findBy(['active' => true]);
        $serviceProviders = $this->serviceProviderRepository->getByLocation(Auth::user()->getAdministrativeUnit(), User::SERVICE_PROVIDER);

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

        $locations = [];
        if (count($serviceProviders) > 0) {
            foreach ($serviceProviders as $serviceProvider) {
                $locations[] = [
                    'latitude'  => (double) $serviceProvider->getLocation()->getLatitude(),
                    'longitude' => (double) $serviceProvider->getLocation()->getLongitude(),
                ];
            }
        }

        $locations      = base64_encode(json_encode($locations));
        $servicesJson   = json_encode($servicesJson);
        $servicesString = implode("<br>", $servicesString);

        return view('clients.services', compact('countries', 'servicesJson', 'classifications', 'servicesString', 'serviceProviders', 'locations'));
    }
}
