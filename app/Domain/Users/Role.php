<?php
namespace Udoktor\Domain\Users;

/**
 * Class Role
 *
 * @package Udoktor\Domain\Users
 * @category Value Object
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class Role
{
    const ADMIN            = 1;
    const CLIENT           = 2;
    const SERVICE_PROVIDER = 3;

    /**
     * checks if the given role is valid
     *
     * @param int $role
     * @return bool
     */
    public static function isAValidRole($role)
    {
        if ($role > 3) {
            return false;
        }

        return true;
    }
}