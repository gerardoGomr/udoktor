<?php
namespace Udoktor\Infrastructure\Mappings\Users;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Users\ServiceType;

/**
 * Class ServiceTypeMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Users
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class ServiceTypeMapping extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return ServiceType::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        // Both strings will be varchars
        $builder->increments('id');
        $builder->string('name')->length(80);
        $builder->string('description')->length(200);
        $builder->boolean('active');
        $builder->float('price');
        $builder->float('minPrice');
        $builder->float('maxPrice');
        $builder->datetime('createdAt');
        $builder->datetime('updatedAt')->nullable();
        $builder->datetime('deletedAt')->nullable();
    }
}
