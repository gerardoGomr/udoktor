<?php
namespace Udoktor\Domain\Regions;

/**
 * Class Location
 *
 * @package Udoktor\Domain\Regions
 * @category ValueObject
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class Location
{
    /**
     * @var string
     */
    private $longitude;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $location;

    /**
     * Location Constructor
     *
     * @param string $longitude
     * @param string $latitude
     * @param string $location
     */
    public function __construct($longitude, $latitude, $location)
    {
        $this->longitude = $longitude;
        $this->latitude  = $latitude;
        $this->location  = $location;
    }

    /**
     * Gets the value of longitude.
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Gets the value of latitude.
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Gets the value of location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }
}