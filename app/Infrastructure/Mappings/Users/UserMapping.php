<?php
namespace Udoktor\Infrastructure\Mappings\Users;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use Udoktor\Domain\Users\Classification;
use Udoktor\Domain\Users\ServiceType;
use Udoktor\Domain\Users\User;

/**
 * Class UserMapping
 *
 * @package Udoktor\Infrastructure\Mappings\Users
 * @category Mapping
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class UserMapping extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return User::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        $builder->increments('id');
        $builder->string('email')
            ->length(80)
            ->unique();
        $builder->string('password')->length(80);
        $builder->string('tempPassword')
            ->length(80)
            ->nullable();
        $builder->string('rememberToken')
            ->nullable();
        $builder->string('verificationToken')
            ->length(80)
            ->nullable();
        $builder->string('requestToken')
            ->length(80)
            ->nullable();
        $builder->boolean('active');
        $builder->boolean('verified');
        $builder->datetime('verificationDate')->nullable();
        $builder->datetime('requestDate')->nullable();
        $builder->smallInteger('role');
        $builder->datetime('createdAt');
        $builder->datetime('updatedAt')->nullable();
        $builder->datetime('deletedAt')->nullable();
        $builder->index('email');

        // many to one relationship
        $builder->manyToOne(Classification::class)
            ->nullable();

        // many to many
        $builder->manyToMany(ServiceType::class);
    }
}