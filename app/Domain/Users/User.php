<?php
namespace Udoktor\Domain\Users;

use DateTime;
use Udoktor\Domain\Persons\Person;
use Illuminate\Contracts\Auth\Authenticatable;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;

/**
 * Class User
 *
 * @package Udoktor\Domain\Users
 * @category Entity
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class User extends Person implements Authenticatable
{
    use AuthenticatableTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var DateTime
     */
    private $deletedAt;

    /**
     * User Constructor
     *
     * @param string $name
     * @param string $lastName1
     * @param string $lastName2
     * @param string $email
     * @param string $password
     */
    public function __construct($name, $lastName1, $lastName2, $email, $password)
    {
        $this->name      = $name;
        $this->lastName1 = $lastName1;
        $this->lastName2 = $lastName2;
        $this->email     = $email;
        $this->password  = $password;
        $this->active    = true;

        parent::__construct($name, $lastName1, $lastName2);
    }
}