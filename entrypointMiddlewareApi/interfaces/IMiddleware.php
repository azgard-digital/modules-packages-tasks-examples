<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 13.09.18
 * Time: 15:13
 */

namespace components\middlewareApi\interfaces;

interface IMiddleware
{
    /**
     * Automatically run
     * @param IMiddlewareRequest $request
     * @return bool
     */
    public static function handle(IMiddlewareRequest $request): bool;
}