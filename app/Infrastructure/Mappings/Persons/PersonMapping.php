<?php
namespace Udoktor\Infrastructure\Mappings\Persons;

use LaravelDoctrine\Fluent\Fluent;
use LaravelDoctrine\Fluent\MappedSuperClassMapping;
use Udoktor\Domain\Persons\Person;

/**
 * Class PersonMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Persons
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class PersonMapping extends MappedSuperClassMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return Person::class;
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
        $builder->string('phoneNumber')->length(20);
        $builder->string('cellphoneNumber')
            ->length(20)
            ->nullable();
    }
}
