<?php
namespace Udoktor\Domain\Users;

use DateTime;

/**
 * Class Service
 *
 * @package Udoktor\Domain\Users
 * @category Entity
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class Service
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $minPrice;

    /**
     * @var float
     */
    private $maxPrice;

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
     * ServiceType Constructor
     *
     * @param string $name
     * @param string $description
     */
    public function __construct($name, $description)
    {
        $this->name        = $name;
        $this->description = $description;
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
     * Gets the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * gets the price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * gets the maximum price
     *
     * @return float
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * gets the minimum price
     *
     * @return float
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Gets the value of active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }
}