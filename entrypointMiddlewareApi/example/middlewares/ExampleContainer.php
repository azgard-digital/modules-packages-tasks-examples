<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 12.09.19
 * Time: 18:25
 */

namespace example\middlewares;


use components\middlewareApi\components\Container;
use components\middlewareApi\interfaces\IMiddleware;
use components\middlewareApi\interfaces\IMiddlewareRequest;
use components\middlewareApi\interfaces\IResponseAction;
use components\middlewareApi\interfaces\IResponseHttpCode;
use Yii;

class ExampleContainer implements IMiddleware
{
    public static function handle(IMiddlewareRequest $request):bool
    {
        $request->setContainer(new Container('param1', 'example_container'));
        return true;
    }
}