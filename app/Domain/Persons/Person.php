<?php
namespace Udoktor\Domain\Persons;

/**
 * Class Person
 *
 * @package Udoktor\Domain\Persons
 * @category Entity
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
abstract class Person
{
    /**
     *
     * @var FullName
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var string
     */
    protected $cellphoneNumber;

    /**
     * Person constructor
     *
     * @param FullName $fullName
     */
    public function __construct(FullName $fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * phone number
     *
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Gets the cellphone
     *
     * @return string
     */
    public function getCellphoneNumber(): string
    {
        return $this->cellphoneNumber;
    }

    /**
     * Gets the value of fullName.
     *
     * @return FullName
     */
    public function getFullName()
    {
        return $this->fullName;
    }
}