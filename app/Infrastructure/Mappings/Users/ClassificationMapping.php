<?php
namespace Udoktor\Infrastructure\Mappings\Users;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Users\Classification;

/**
 * Class ClassificationMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Users
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class ClassificationMapping extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return Classification::class;
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
        $builder->datetime('createdAt');
        $builder->datetime('updatedAt')->nullable();
        $builder->datetime('deletedAt')->nullable();
    }
}
