<?php
namespace Udoktor\Domain\Users;

use DateTime;
use Illuminate\Contracts\Auth\Authenticatable;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;
use Udoktor\Domain\ICollection;
use Udoktor\Domain\Persons\FullName;
use Udoktor\Domain\Persons\Person;
use Udoktor\Exceptions\AddingComponentsForNonServiceProviderRoleException;
use Udoktor\Exceptions\InvalidRoleAssignmenException;
use Udoktor\Exceptions\InvalidVerificationTokenException;

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
     * @var string
     */
    private $tempPassword;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var bool
     */
    private $verified;

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
     * @var int
     */
    private $role;

    /**
     * @var Classification
     */
    private $classification;

    /**
     * @var ICollection
     */
    private $serviceTypes;

    /**
     * @var string
     */
    private $verificationToken;

    /**
     * @var DateTime
     */
    private $verificationDate;

    /**
     * @var string
     */
    private $requestToken;

    /**
     * @var DateTime
     */
    private $requestDate;

    const ADMIN            = 3;
    const CLIENT           = 1;
    const SERVICE_PROVIDER = 2;

    /**
     * User Constructor
     *
     * @param FullName $fullName
     * @param string $email
     * @param string $password
     * @param string $contactNumber
     * @param int $role
     */
    public function __construct(FullName $fullName, $email, $password, $contactNumber, $role)
    {
        $this->email        = $email;
        $this->password     = $password;
        $this->tempPassword = $password;
        $this->phoneNumber  = $contactNumber;

        if ($role !== self::ADMIN && $role !== self::SERVICE_PROVIDER && $role !== self::CLIENT) {
            throw new InvalidRoleAssignmenException('El rol que se solicita no existe.');
        }

        $this->role = $role;

        parent::__construct($fullName);
    }

    /**
     * add components for the user with service provider role
     *
     * @param Classification $classification [description]
     * @param ICollection $services
     */
    public function addComponentsForServiceProvider(Classification $classification, ICollection $services)
    {
        if ($this->role !== self::SERVICE_PROVIDER) {
            throw new AddingComponentsForNonServiceProviderRoleException('Solamente se pueden agregar estos componentes a usuarios con rol de prestador servicio.');

        }

        $this->classification = $classification;
        $this->serviceTypes   = $services;
    }

    /**
     * register user
     *
     * @return void
     */
    public function register()
    {
        $this->password          = password_hash($this->password, PASSWORD_DEFAULT);
        $this->createdAt         = new DateTime();
        $this->active            = true;
        $this->verified          = false;
        $this->verificationToken = md5(uniqid(rand(), 1) . 'udoktor');
    }

    /**
     * checks if user is service provider
     *
     * @return bool
     */
    public function isServiceProvider()
    {
        return $this->role === self::SERVICE_PROVIDER;
    }

    /**
     * checks if user is client
     *
     * @return bool
     */
    public function isClient()
    {
        return $this->role === self::CLIENT;
    }

    /**
     * Gets the value of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Gets the value of active.
     *
     * @return bool
     */
    public function isActive(): boolean
    {
        return $this->active;
    }

    /**
     * Gets the value of verified.
     *
     * @return bool
     */
    public function isVerified(): boolean
    {
        return $this->verified;
    }

    /**
     * Gets the value of verificationDate.
     *
     * @return DateTime
     */
    public function getVerificationDate(): DateTime
    {
        return $this->verificationDate;
    }

    /**
     * Gets the value of verificationToken
     *
     * @return string
     */
    public function getVerificationToken(): string
    {
        return $this->verificationToken;
    }

    /**
     * @return string
     */
    public function getRequestToken(): string
    {
        return $this->requestToken;
    }

    /**
     * @return DateTime
     */
    public function getRequestDate(): DateTime
    {
        return $this->requestDate;
    }

    /**
     * Gets the value of temp password
     *
     * @return string
     */
    public function getTempPassword()
    {
        return $this->tempPassword;
    }

    /**
     * verify user's account
     *
     * @param string $token
     * @return void
     * @throws InvalidVerificationTokenException when the sent token is invalid
     */
    public function verify($token)
    {
        if ($this->verificationToken !== $token) {
            throw new InvalidVerificationTokenException('No se puede activar la cuenta del usuario debido a que el token especificado no coincide.');
        }

        $this->verified          = true;
        $this->verificationDate  = new DateTime();
        $this->tempPassword      = null;
        $this->verificationToken = null;
    }

    /**
     * requesting a password reset
     *
     * @return void
     */
    public function requestPasswordReset()
    {
        $this->requestToken = md5(uniqid(rand(), 1) . 'udoktor');
        $this->requestDate  = new DateTime();
    }

    /**
     * checks if the user's password can be reset
     *
     * token must be equal and also the time elapsed since the request must be
     * less than 30 minutes
     *
     * @param string $token
     * @return boolean
     */
    public function canResetPassword($token)
    {
        $currentDate = new DateTime();

        if ($this->requestToken !== $token) {
            return false;
        }

        $diff    = $currentDate->diff($this->requestDate);
        $minutes = $diff->days * 24 * 60;
        if ($minutes > 30) {
            return false;
        }

        return true;
    }

    /**
     * resetting password and flags
     *
     * @param string $newPassword
     * @return void
     */
    public function resetPassword($newPassword)
    {
        $this->password     = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->requestToken = null;
        $this->requestDate  = null;
        $this->updatedAt    = new DateTime();
    }
}