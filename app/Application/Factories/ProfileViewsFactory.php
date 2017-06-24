<?php
namespace Udoktor\Application\Factories;

use Auth;
use Illuminate\Support\Facades\Storage;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Users\Classification;
use Udoktor\Domain\Users\ServiceType;

/**
 * Class ProfileViewsFactory
 *
 * @package Udoktor\Application\Factories
 * @category Simple Factory
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class ProfileViewsFactory
{
    /**
     * make view for the user's role
     *
     * @return Illuminate\Support\Facades\View
     */
    public static function make()
    {
        if (Auth::user()->isServiceProvider()) {
            $countries        = EntityManager::getRepository(AdministrativeUnit::class)->findBy(['parentUnit' => null]);
            $state            = EntityManager::getRepository(AdministrativeUnit::class)->find(Auth::user()->getAdministrativeUnit()->getParentUnit()->getId());
            $cities           = EntityManager::getRepository(AdministrativeUnit::class)->findBy(['parentUnit' => $state->getId()]);
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

            // creating list of services from user on json format
            foreach (Auth::user()->getServiceTypes() as $service) {
                $serviceTypesJsonUser[] = [
                    'value' => $service->getId(),
                    'text'  => $service->getName()
                ];
            }

            $serviceTypesJson     = json_encode($serviceTypesJson);
            $serviceTypesJsonUser = json_encode($serviceTypesJsonUser);

            $profilePictureUrl = static::checkUsersProfilePicture();
            $notifications     = explode(',', Auth::user()->getNotifications());

            return view('service_provider.profile', compact('countries', 'state', 'cities', 'serviceTypesJson', 'classifications', 'serviceTypesJsonUser', 'profilePictureUrl', 'notifications'));
        }
    }

    /**
     * checks if the user has profile picture
     *
     * @return string the url for the profile picture
     */
    private static function checkUsersProfilePicture()
    {
        if (Auth::user()->hasProfilePicture()) {
            return Storage::disk('public')->url('profile_pictures/' . Auth::user()->getProfilePicture());
        }

        return '';
    }
}