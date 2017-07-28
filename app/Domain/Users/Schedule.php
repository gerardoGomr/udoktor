<?php
namespace Udoktor\Domain\Users;


/**
 * Class Schedule
 *
 * @package Udoktor\Domain\Users
 * @category Entity
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class Schedule
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $startHour;

    /**
     * @var integer
     */
    private $endHour;

    /**
     * number of clients that can be attended
     *
     * @var integer
     */
    private $clientsLimit;

    /**
     * The owner of the schedule
     *
     * @var User
     */
    private $user;

    /**
     * Schedule Constructor
     *
     * @param integer $startHour
     * @param integer|null $endHour
     * @param integer|null $clientsLimit
     */
    public function __construct(User $user, $startHour, $endHour = null, $clientsLimit = null)
    {
        $this->user         = $user;
        $this->startHour    = $startHour;
        $this->endHour      = $endHour;
        $this->clientsLimit = $clientsLimit;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * @return integer
     */
    public function getEndHour()
    {
        return $this->endHour;
    }

    /**
     * @return integer
     */
    public function getClientsLimit()
    {
        return $this->clientsLimit;
    }

    /**
     * checks if the current schedule is alreade in use
     *
     * @param integer $startHour
     * @param integer|null $endHour
     *
     * @return boolean
     */
    public function exists($startHour,  $endHour = null)
    {
        if (!is_null($endHour)) {
            return $this->startHour < $endHour && $this->endHour > $startHour;
        }

        return $this->startHour === $startHour;
    }
}