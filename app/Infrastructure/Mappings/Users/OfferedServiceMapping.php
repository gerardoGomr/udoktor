<?php
namespace Udoktor\Infrastructure\Mappings\Users;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Users\OfferedService;
use Udoktor\Domain\Users\Service;
use Udoktor\Domain\Users\User;

/**
 * Class OfferedServiceMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Users
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class OfferedServiceMapping extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return OfferedService::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        $builder->increments('id');
        $builder->float('price');

        // a user lives in one a unit - aUnit is the place wher many users live
        $builder->manyToOne(User::class)->inversedBy('services')->nullable();
        $builder->manyToOne(Service::class)->nullable();
    }
}