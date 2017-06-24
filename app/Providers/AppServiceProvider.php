<?php

namespace Udoktor\Providers;

use Doctrine\Common\Persistence\ObjectRepository;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Domain\Users\User;
use Udoktor\Infrastructure\Repositories\Users\DoctrineUsersRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UsersRepository::class, function(){
            return new DoctrineUsersRepository(EntityManager::getRepository(User::class));
        });
    }
}
