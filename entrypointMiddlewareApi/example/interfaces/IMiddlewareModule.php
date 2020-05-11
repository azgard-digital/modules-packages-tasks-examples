<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 10.09.19
 * Time: 19:38
 */
namespace example\interfaces;

use components\middlewareApi\interfaces\IMiddlewareRequest;

interface IMiddlewareModule
{
    /**
     * @return IMiddlewareRequest
     */
    public function getMiddlewareRequest(): IMiddlewareRequest;
}