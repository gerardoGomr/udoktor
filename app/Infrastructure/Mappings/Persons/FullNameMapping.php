<?php
namespace Udoktor\Infrastructure\Mappings\Persons;

use LaravelDoctrine\Fluent\EmbeddableMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Persons\FullName;

/**
 * Class FullNameMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Persons
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class FullNameMapping extends EmbeddableMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return FullName::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        // Both strings will be varchars
        $builder->string('name')->length(80);
        $builder->string('lastName1')->length(80);
        $builder->string('lastName2')
            ->length(80)
            ->nullable();
    }
}
