<?php

namespace Udoktor\Providers;

use Doctrine\Common\Persistence\ObjectRepository;
use Illuminate\Support\ServiceProvider;
use Udoktor\Domain\Regions\AdministrativeUnit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*$this->app
            ->when(SignUpController::class)
            ->needs(ObjectRepository::class)
            ->give(function(){
                return EntityManager::getRepository(AdministrativeUnit::class);
            });*/
    }
}
