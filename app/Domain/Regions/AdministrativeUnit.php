<?php
namespace Udoktor\Domain\Regions;

/**
 * Class Person
 *
 * @package Udoktor\Domain\Regions
 * @category Entity
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class AdministrativeUnit
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
     * @var bool
     */
    private $active;

    /**
     * parent of current a. unit
     * @var AdministrativeUnit
     */
    private $parentUnit;

    /**
     * AdministrativeUnit constructor
     *
     * @param string $name
     * @param  AdministrativeUnit $parent
     */
    public function __construct($name, AdministrativeUnit $parent = null)
    {
        $this->name       = $name;
        $this->active     = true;
        $this->parentUnit = $parent;
    }

    /**
     * Gets the value of id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the value of active.
     *
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * Gets the parent of current a. unit.
     *
     * @return AdministrativeUnit
     */
    public function getParentUnit()
    {
        return $this->parentUnit;
    }
}