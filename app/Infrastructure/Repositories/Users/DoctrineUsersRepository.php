<?php
namespace Udoktor\Infrastructure\Repositories\Users;

use Doctrine\Common\Persistence\ObjectRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;
use PDOException;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Domain\Users\User;

/**
 * Class DoctrineUsersRepository
 *
 * @package Udoktor\Infrastructure\Repositories\Users
 * @category Repository
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class DoctrineUsersRepository implements UsersRepository
{
    /**
     * doctrine's default repository
     *
     * @var ObjectRepository
     */
    private $genericRepository;

    /**
     * Class constructor
     *
     * @param ObjectRepository $genericRepository
     */
    public function __construct(ObjectRepository $genericRepository)
    {
        $this->genericRepository = $genericRepository;
    }

    /**
     * @inherited doc
     */
    public function find($id)
    {
        return $this->genericRepository->find($id);
    }

    /**
     * @inherited doc
     */
    public function findBy(array $params)
    {
        return $this->genericRepository->findBy($params);
    }

    /**
     * @inheric doc
     */
    public function findOneBy(array $params)
    {
        return $this->genericRepository->findOneBy($params);
    }

    /**
     * @inherited doc
     */
    public function persist(User $user)
    {
        try {
            if (is_null($user->getId())) {
                EntityManager::persist($user);
            }

            EntityManager::flush();

        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getByEmail($email)
    {
        /*try {
            $user = EntityManager::createQuery('SELECT Users:User u WHERE u.email = :email')
                ->setParameter('email', $email)
                ->getResult();

            return $user;

        } catch (PDOException $e) {
            throw $e;
        }*/
    }

    /**
     * find users by their location
     *
     * @param AdministrativeUnit $location
     * @param integer|null $role
     *
     * @return array
     */
    public function getByLocation(AdministrativeUnit $location, $role = null)
    {
        try {

            $withRole = '';
            $params   = ['locationId' => $location->getId()];
            if (!is_null($role)) {
                $withRole       = ' AND u.role = :role';
                $params['role'] = $role;
            }

            $users = EntityManager::createQuery('SELECT u, a FROM Udoktor\Domain\Users\User u JOIN u.administrativeUnit a WHERE a.id = :locationId' . $withRole)
                ->setParameters($params)
                ->getResult();

            return $users;

        } catch (PDOException $e) {
            throw $e;
        }
    }
}