<?php
namespace Udoktor\Exceptions;

use Exception;

/**
 * Class InvalidPriceAssigmentException
 *
 * @package Udoktor\Exceptions
 * @category Exception
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class InvalidPriceAssigmentException extends Exception
{
    /**
     * InvalidPriceAssigmentException Constructor
     *
     * Calls parent's
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}