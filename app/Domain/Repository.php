<?php
namespace  Udoktor\Domain;

/**
 * Interface Repository
 *
 * @package Udoktor\Domain
 * @category Interface
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
interface Repository
{
    /**
     * find entity by id
     *
     * @param int $id
     * @return mixed
     */
    public function find($id);

    /**
     * find entities by the params
     *
     * @param array $params
     * @return array
     */
    public function findBy(array $params);

    /**
     * find entity by the params
     *
     * @param array $params
     * @return mixed
     */
    public function findOneBy(array $params);
}