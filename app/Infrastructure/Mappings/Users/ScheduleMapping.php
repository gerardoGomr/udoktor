<?php
namespace Udoktor\Infrastructure\Mappings\Users;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Users\Schedule;
use Udoktor\Domain\Users\User;

/**
 * Class ScheduleMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Users
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class ScheduleMapping extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return Schedule::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        $builder->increments('id');
        $builder->integer('startHour')->nullable();
        $builder->integer('endHour')->nullable();
        $builder->integer('clientsLimit')->nullable();

        // a user lives in one a unit - aUnit is the place wher many users live
        $builder->manyToOne(User::class)
            ->inversedBy('schedules')
            ->nullable();
    }
}