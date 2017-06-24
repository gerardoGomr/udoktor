<?php
namespace Udoktor\Infrastructure\Mappings\Regions;

use LaravelDoctrine\Fluent\EmbeddableMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Regions\Location;


/**
 * Class LocationMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Regions
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class LocationMapping extends EmbeddableMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return Location::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        $builder->string('longitude')
            ->length(80)
            ->nullable();
        $builder->string('latitude')
            ->length(80)
            ->nullable();
        $builder->string('location')
            ->length(80)
            ->nullable();
    }
}
