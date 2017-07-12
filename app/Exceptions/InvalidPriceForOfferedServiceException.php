<?php
namespace Udoktor\Exceptions;

use Exception;

/**
 * Class InvalidPriceForOfferedServiceException
 *
 * @package Udoktor\Exceptions
 * @category Exception
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class InvalidPriceForOfferedServiceException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}