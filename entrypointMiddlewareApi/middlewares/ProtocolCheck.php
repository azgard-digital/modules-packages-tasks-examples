<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 13.09.18
 * Time: 15:10
 */
namespace components\middlewareApi\middlewares;

use components\middlewareApi\interfaces\IMiddlewareRequest;
use components\middlewareApi\interfaces\IMiddleware;
use components\middlewareApi\interfaces\IResponseAction;
use components\middlewareApi\interfaces\IResponseHttpCode;

class ProtocolCheck implements IMiddleware
{
    public static function handle(IMiddlewareRequest $request): bool
    {
        if ($request->getProtocol()->validate()) {
            return true;
        }

        \Yii::log('Protocol validation error: '.print_r($request->getProtocol()->getErrors(), true), \CLogger::LEVEL_ERROR);

        $request->getResponse()
            ->setHttpCode(IResponseHttpCode::PROBLEM)
            ->setBody(json_encode($request->getProtocol()->getProtocolResponse(
                IResponseAction::ERROR,
                "Protocol validation did not pass, invalid data in request!"
                ))
            );

        return false;
    }
}