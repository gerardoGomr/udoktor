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
use Udoktor\Exceptions\InvalidDiaryScheduleTypeException;
use Udoktor\Exceptions\InvalidPriceTypeException;
use Udoktor\Exceptions\InvalidRoleAssignmenException;
use Udoktor\Exceptions\InvalidVerificationTokenException;
use Udoktor\Exceptions\ScheduleForUserIsCoveredException;

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
     * The offered services by the user (OfferedService collection)
     *
     * @var ICollection
     */
    private $services;

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

    /**
     * the price type (fixed, recommended)
     *
     * @var integer
     */
    private $priceType;

    /**
     * how user's diary's schedules are setted
     *
     * Posible values: FIXED SCHEDULE OR INTERVAL SCHEDULE
     *
     * @var integer
     */
    private $diaryScheduleType;

    /**
     * the min amount of time that a service lasts
     *
     * @var integer
     */
    private $minServiceDuration;

    /**
     * the schedules assigned to service provider
     *
     * @var ICollection
     */
    private $schedules;

    const CLIENT           = 1;
    const SERVICE_PROVIDER = 2;
    const ADMIN            = 3;

    /**
     * the price the user offers
     *
     * @var integer
     */
    const FIXED_PRICE = 1;

    /**
     * the price the system offers
     *
     * @var integer
     */
    const RECOMMENDED_PRICE = 2;

    /**
     * user's diary has fixed schedules
     *
     * @var integer
     */
    const FIXED_SCHEDULE = 1;

    /**
     * user's diary has schedules as intervals
     */
    const INTERVAL_SCHEDULE = 2;

    /**
     * User Constructor
     *
     * @param FullName $fullName
     * @param string $email
     * @param string $password
     * @param string $contactNumber
     * @param int $role
     * @param AdministrativeUnit $aUnit
     * @param integer $priceType
     * @param integer $schedule type
     */
    public function __construct(FullName $fullName, $email, $password, $contactNumber, $role, AdministrativeUnit $aUnit, $priceType = self::RECOMMENDED_PRICE, $diaryScheduleType = self::FIXED_SCHEDULE)
    {
        $this->email              = $email;
        $this->password           = $password;
        $this->tempPassword       = $password;
        $this->phoneNumber        = $contactNumber;
        $this->administrativeUnit = $aUnit;

        if ($role !== self::ADMIN && $role !== self::SERVICE_PROVIDER && $role !== self::CLIENT) {
            throw new InvalidRoleAssignmenException('El rol que se solicita no existe.');
        }

        $this->role              = $role;
        $this->priceType         = $priceType;
        $this->diaryScheduleType = $diaryScheduleType;

        parent::__construct($fullName);
    }

    /**
     * add components for the user with service provider role
     *
     * @method addComponentsForServiceProvider
     * @param Classification $classification
     * @param ICollection $services
     *
     * @return void
     */
    public function addComponentsForServiceProvider(Classification $classification, ICollection $services, ICollection $schedules)
    {
        if ($this->role !== self::SERVICE_PROVIDER) {
            throw new AddingComponentsForNonServiceProviderRoleException('Solamente se pueden agregar estos componentes a usuarios con rol de prestador de servicio.');

        }

        $this->classification = $classification;
        $this->services       = $services;
        $this->schedules      = $schedules;
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
    public function isActive(): bool
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
    public function getservices()
    {
        return $this->services;
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
     * returns the configuerd price type
     *
     * @return integer
     */
    public function getPriceType()
    {
        return $this->priceType;
    }

    /**
     * returns the diary schedule type
     *
     * @return integer
     */
    public function getDiaryScheduleType()
    {
        return $this->diaryScheduleType;
    }

    /**
     * checks if the service provider has services
     *
     * @return bool
     */
    public function hasServices()
    {
        return !is_null($this->services);
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
        if ($this->hasCompletedProfile) {
            return true;
        }

        if ($this->services->count() === 0 || $this->schedules->count() === 0) {
            return false;
        }

        return true;
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

    /**
     * removing all services
     *
     * @return void
     */
    public function removeAllServices()
    {
        $this->services->clear();
    }

    /**
     * add a new service if it ins't contained in the collection
     *
     * @param OfferedService $service
     *
     * @return void
     *
     * @throws AddingComponentsForNonServiceProvierRoleException When the current user isn't a service provider
     */
    public function addService(OfferedService $service)
    {
        if ($this->role !== self::SERVICE_PROVIDER) {
            throw new AddingComponentsForNonServiceProviderRoleException('Solamente se pueden agregar estos componentes a usuarios con rol de prestador servicio.');

        }

        if (!$this->existsService($service)) {
            $this->services->add($service);
        }
    }

    /**
     * remove the service from the user
     *
     * @param OfferedService $service
     *
     * @return void
     */
    public function removeService(OfferedService $service)
    {
        if ($this->existsService($service)) {
            $this->services->removeElement($service);
        }
    }

    /**
     * checks the existence of the given service
     *
     * @param OfferedService $service
     *
     * @return boolean
     */
    private function existsService(OfferedService $service)
    {
        return $this->services->contains($service);
    }

    /**
     * changes the type of price
     *
     * @param integer $priceType
     *
     * @return void
     *
     * @throws InvalidPriceException when $priceType is out of the valid types
     */
    public function changePriceType($priceType)
    {
        if ($priceType !== self::FIXED_PRICE && $priceType !== self::RECOMMENDED_PRICE) {
            throw new InvalidPriceTypeException('El tipo de precio ' . (string) $priceType . ' no puede ser asignado al prestador de servicio.');
        }

        $this->priceType = $priceType;
    }

    /**
     * checks if the user offers fixed prices
     *
     * @return bool
     */
    public function offersFixedPrices()
    {
        if (!$this->isServiceProvider()) {
            return false;
        }

        return $this->priceType === self::FIXED_PRICE;
    }

    /**
     * checks if the user offers recommended prices
     *
     * @return bool
     */
    public function offersRecommendedPrices()
    {
        if (!$this->isServiceProvider()) {
            return false;
        }

        return $this->priceType === self::RECOMMENDED_PRICE;
    }

    /**
     * gets the service
     *
     * @param integer $serviceId
     *
     * @return OfferedService
     */
    public function getOfferedService($serviceId)
    {
        foreach ($this->services as $service) {
            if ($service->getId() === $serviceId) {
                return $service;
            }
        }

        return null;
    }

    /**
     * checks if the user has any schedule
     *
     * @return bool
     */
    public function hasSchedules()
    {
        return $this->schedules->count() > 0;
    }

    /**
     * checks if the user has a fixed schedule
     *
     * @return bool
     */
    public function hasFixedSchedules()
    {
        return $this->diaryScheduleType === self::FIXED_SCHEDULE;
    }

    /**
     * checks if the user has a interval schedule
     *
     * @return bool
     */
    public function hasIntervalSchedules()
    {
        return $this->diaryScheduleType === self::INTERVAL_SCHEDULE;
    }

    /**
     * change the diary type to user. Also restarts the schedules added to none
     *
     * @param integer $diaryScheduleType
     *
     * @return void
     *
     * @throws InvalidDiaryScheduleTypeException when $diaryScheduleType is not either fixed or interval
     */
    public function changeDiaryType($diaryScheduleType)
    {
        if ($diaryScheduleType !== self::FIXED_SCHEDULE && $diaryScheduleType !== self::INTERVAL_SCHEDULE) {
            throw new InvalidDiaryScheduleTypeException('El tipo de diario ' . (string) $diaryScheduleType . ' no puede ser asignado al prestador de servicio.');
        }

        $this->schedules->clear();
        $this->diaryScheduleType  = $diaryScheduleType;
        $this->minServiceDuration = $diaryScheduleType === self::INTERVAL_SCHEDULE ? 20 : 0;
    }

    /**
     * returns the minutes that a service lasts
     *
     * @return integer
     */
    public function getMinServiceDuration()
    {
        return $this->minServiceDuration;
    }

    /**
     * modifies the min service lasting
     *
     * @param integer $servicesLasting
     *
     * @return void
     */
    public function modifyServicesLasting($servicesLasting)
    {
        $this->minServiceDuration = $servicesLasting;
    }

    /**
     * add a new schedule to user by current diary schedule type
     *
     * @param string $startHour
     * @param string|null $endHour
     * @param string|null $clientsLimit
     *
     * @throws ScheduleForUserIsCoveredException when the interval is already covered
     */
    public function addSchedule($startHour, $endHour = null, $clientsLimit = null)
    {
        if ($this->diaryScheduleType === self::FIXED_SCHEDULE) {
            // validate there is not another schedule on the interval
            $startHour = strtotime($startHour);

            foreach ($this->schedules as $schedule) {
                if ($schedule->exists($startHour)) {
                    throw new ScheduleForUserIsCoveredException("El horario de $startHour ya está cubierto.");
                }
            }

            $this->schedules->add(new Schedule($this, $startHour, null, (int) $clientsLimit));
        }

        if ($this->diaryScheduleType === self::INTERVAL_SCHEDULE) {
            // validate there is not another schedule on the interval
            $startHour = strtotime($startHour);
            $endHour   = strtotime($endHour);

            foreach ($this->schedules as $schedule) {
                if ($schedule->exists($startHour, $endHour)) {
                    throw new ScheduleForUserIsCoveredException('El horario de ' . date('H:i', $startHour) . ' - ' . date('H:i', $endHour) . ' ya está cubierto.');
                }
            }

            $this->schedules->add(new Schedule($this, $startHour, $endHour));
        }
    }

    /**
     * returns all schedules from user
     *
     * @return IColecttion
     */
    public function getSchedules()
    {
        return $this->schedules;
    }
}