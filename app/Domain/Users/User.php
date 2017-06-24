<?php
namespace Udoktor\Domain\Users;

use DateTime;
use Illuminate\Contracts\Auth\Authenticatable;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;
use Udoktor\Domain\ICollection;
use Udoktor\Domain\Persons\FullName;
use Udoktor\Domain\Persons\Person;
use Udoktor\Domain\Regions\AdministrativeUnit;
use Udoktor\Domain\Regions\Location;
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
     * @var bool
     */
    private $hasCompletedProfile;

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

    /**
     * @var AdministrativeUnit
     */
    private $administrativeUnit;

    /**
     * @var Location
     */
    private $location;

    /**
     * @var string
     */
    private $profilePicture;

    /**
     * @var string
     */
    private $notifications;

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
     * @param AdministrativeUnit $aUnit
     */
    public function __construct(FullName $fullName, $email, $password, $contactNumber, $role, AdministrativeUnit $aUnit)
    {
        $this->email              = $email;
        $this->password           = $password;
        $this->tempPassword       = $password;
        $this->phoneNumber        = $contactNumber;
        $this->administrativeUnit = $aUnit;

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
        $this->password            = password_hash($this->password, PASSWORD_DEFAULT);
        $this->createdAt           = new DateTime();
        $this->active              = true;
        $this->verified            = false;
        $this->verificationToken   = md5(uniqid(rand(), 1) . 'udoktor');
        $this->hasCompletedProfile = false;
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
    public function isVerified(): bool
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
     * @return AdministrativeUnit
     */
    public function getAdministrativeUnit()
    {
        return $this->administrativeUnit;
    }

    /**
     * @return Classification
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * @return ICollection
     */
    public function getServiceTypes()
    {
        return $this->serviceTypes;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * returns the notifications the user has
     *
     * @return string
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * checks if the user has a profile picture
     *
     * @return bool
     */
    public function hasProfilePicture()
    {
        return !is_null($this->profilePicture);
    }

    /**
     * updates user's profile picture
     *
     * @param string $picture
     */
    public function updateProfilePicture($picture)
    {
        $this->profilePicture = $picture;
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

    /**
     * checks whether the user has completed his/her profile
     *
     * @return bool
     */
    public function hasCompletedProfile()
    {
        return $this->hasCompletedProfile;
    }

    /**
     * user has completed profile
     *
     * @param FullName $fullName
     * @param string $email
     * @param string $contactNumber
     * @param AdministrativeUnit $aUnit
     * @return void
     */
    public function completeProfile(FullName $fullName, $email, $contactNumber, AdministrativeUnit $aUnit)
    {
        $this->fullName            = $fullName;
        $this->email               = $email;
        $this->phoneNumber         = $contactNumber;
        $this->administrativeUnit  = $aUnit;
        $this->hasCompletedProfile = true;
    }

    /**
     * clears the notifications the user has
     *
     * @return void
     */
    public function clearNotifications()
    {
        if ($this->hasNotifications()) {
            $this->notifications = null;
        }
    }

    /**
     * checks if the current user has notifications
     *
     * @return bool
     */
    public function hasNotifications()
    {
        return !is_null($this->notifications);
    }

    /**
     * add a new notification to the user
     *
     * @param string $newNotification
     */
    public function addNotification($newNotification)
    {
        if (!$this->hasNotifications()) {
            $this->notifications = $newNotification;
        } else {
            $this->notifications .= ',' . $newNotification;
        }
    }

    /**
     * updates user's current location
     *
     * @param Location $location
     * @return void
     */
    public function updateLocation(Location $location)
    {
        $this->location = $location;
    }
}