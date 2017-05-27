<?php
namespace Udoktor\Domain\Persons;

/**
 * Class FullName
 *
 * @package Udoktor\Domain\Persons
 * @category Value Object
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class FullName
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $lastName1;
    /**
     * @var string
     */
    protected $lastName2;

    /**
     * Person constructor
     *
     * @param string $name
     * @param string $lastName1
     * @param string $lastName2
     */
    public function __construct($name, $lastName1, $lastName2)
    {
        $this->name      = $name;
        $this->lastName1 = $lastName1;
        $this->lastName2 = $lastName2;
    }

    /**
     * name of the person
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * first last name of person
     *
     * @return string
     */
    public function getLastName1(): string
    {
        return $this->lastName1;
    }

    /**
     * second last name
     *
     * @return string
     */
    public function getLastName2(): string
    {
        return $this->lastName2;
    }

    /**
     * get the full name
     *
     * @return string
     */
    public function fullName(): string
    {
        $name = $this->name;

        if(strlen($this->lastName1)) {
            $name .= ' '.$this->lastName1;
        }

        if(strlen($this->lastName2)) {
            $name .= ' '.$this->lastName2;
        }
        return $name;
    }
}