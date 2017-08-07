<?php
namespace Udoktor\Domain\Users\Repositories;

use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Repository;
use Udoktor\Domain\Users\User;

/**
 * Interface UsersRepository
 *
 * @package Udoktor\Domain\Users
 * @category Interface
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
interface UsersRepository extends Repository
{
    /**
     * persists changes on user
     *
     * @param User $user
     *
     * @return void
     */
    public function persist(User $user);

    /**
     * search an user by email
     *
     * @param string $email
     *
     * @return User
     */
    public function getByEmail($email);

    /**
     * find users by their location
     *
     * @param AdministrativeUnit $location
     * @param integer|null role
     *
     * @return array
     */
    public function getByLocation(AdministrativeUnit $location, $role = null);
}