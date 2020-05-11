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

class TokenCheck implements IMiddleware
{
    public static function handle(IMiddlewareRequest $request):bool
    {
        $tokenAllowed = Yii::app()->getModule()->getAuthorizationToken();
        $token = $_SERVER['HTTP_AUTH_TOKEN'] ?? null;

        if ($tokenAllowed == $token) {
            return true;
        }

        $request->getResponse()
            ->setHttpCode(IResponseHttpCode::PROBLEM)
            ->setBody(json_encode($request->getProtocol()->getProtocolResponse(
                    IResponseAction::ERROR,
                    'Invalid authorization token!'
                ))
            );

        return false;
    }
}