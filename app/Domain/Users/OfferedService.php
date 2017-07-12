<?php
namespace Udoktor\Domain\Users;

use Udoktor\Exceptions\InvalidPriceAssigmentException;
use Udoktor\Exceptions\InvalidPriceForOfferedServiceException;

/**
 * Class OfferedService
 *
 * @package Udoktor\Domain\Users
 * @category Entity
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class OfferedService
{
    /**
     * @var integer
     */
    private $id;

    /**
     * the owner of the service
     *
     * @var User
     */
    private $user;

    /**
     * The given service
     *
     * @var Service
     */
    private $service;

    /**
     * the price the user has
     *
     * @var float
     */
    private $price;

    /**
     * OfferedService Constructor
     *
     * @param User $user
     * @param Service $service
     * @param float|null $price
     */
    public function __construct(User $user, Service $service, $price = null)
    {
        $this->user        = $user;
        $this->service     = $service;
        $this->price       = $this->verifyPrice($price);
    }

    /**
     * verifies the given price
     *
     * @param float|null $price
     *
     * @return float
     *
     * @throws InvalidPriceForOfferedServiceException when the price is fixed and is out of the max and min price
     */
    public function verifyPrice($price)
    {
        if (!is_null($price)) {
            if ($this->user->getPriceType() === User::RECOMMENDED_PRICE) {
                return $this->service->getPrice();
            }

            if ($this->user->getPriceType() === User::FIXED_PRICE) {
                if ($price < $this->service->getMinPrice() || $price > $this->service->getMaxPrice()) {
                    throw new InvalidPriceForOfferedServiceException('El precio asignado no es correcto para el servicio ' . $this->service->getName() . ' cuyos precios validos son : $' . (string)$this->service->getMinPrice() . ' y $' . $this->service->getMaxPrice() . '. El precio que se quiere asignar es: $' . (string)$price);
                }

                return $price;
            }
        } else {
            return $this->service->getPrice();
        }
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * updates the current price
     *
     * @param float $newPrice
     *
     * @return void
     *
     * @throws InvalidPriceAssigmentException when the user's price type is not fixed
     */
    public function changePrice($newPrice)
    {
        $this->price = $this->verifyPrice($newPrice);
    }
}