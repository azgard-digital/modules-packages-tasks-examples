<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 11:33
 */

namespace components\middlewareApi\interfaces;


interface IContainer
{
    /**
     * Get container name
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function __invoke();
}