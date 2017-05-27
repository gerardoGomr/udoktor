<?php
namespace Udoktor\Application;

use Doctrine\Common\Collections\ArrayCollection;
use Udoktor\Domain\ICollection;

/**
 * Class CustomCollection
 *
 * @package Udoktor\Application
 * @category CustomList
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class CustomCollection extends ArrayCollection implements ICollection {}