<?php
namespace Udoktor\Infrastructure\Mappings\Regions;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Regions\AdministrativeUnit;

/**
 * Class UserMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Regions
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class AdministrativeUnitMapping extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return AdministrativeUnit::class;
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
        $builder->boolean('active');
        $builder->belongsTo(AdministrativeUnit::class, 'parentUnit')
            ->nullable();
    }
}
