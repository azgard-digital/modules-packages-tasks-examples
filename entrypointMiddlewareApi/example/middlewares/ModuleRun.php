<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 12.09.19
 * Time: 16:30
 */
namespace example\middlewares;

use example\exceptions\InformException;
use components\middlewareApi\interfaces\IMiddleware;
use components\middlewareApi\interfaces\IMiddlewareRequest;
use components\middlewareApi\interfaces\IResponseAction;
use components\middlewareApi\interfaces\IResponseHttpCode;
use Yii;

class ModuleRun implements IMiddleware
{
    public static function handle(IMiddlewareRequest $request):bool
    {

        try {
            $param1 = $request->getContainer('param1'); //example_container
            $param2 = '';
            $method = $request->getProtocol()->method;
            $module = $request->getProtocol()->module;

            $moduleObj = Yii::app()->getModuleexample()->moduleFactory($module, $param1, $param2);

            if (!method_exists($moduleObj, $method)) {
                throw new InformException("Method is {$method} invalid for module {$module}!");
            }

            $result = call_user_func([$moduleObj, $method]);

            if (!is_array($result)) {
                throw new InformException("Invalid result in {$method}");
            }

            $request->getResponse()
                ->setHttpCode(IResponseHttpCode::SUCCESS)
                ->setBody(json_encode($request->getProtocol()->getProtocolResponse(
                        IResponseAction::ACCEPTED, '', $result
                    ))
                );

            return true;

        } catch (InformException $e) {

            $request->getResponse()
                ->setHttpCode(IResponseHttpCode::PROBLEM)
                ->setBody(json_encode($request->getProtocol()->getProtocolResponse(
                        IResponseAction::ERROR,
                        "Error in module or method has got invalid result: ".$e->getMessage()
                    ))
                );

            return false;

        } catch (\Exception $e) {
            \Yii::log("example module error: ".$e->getMessage(), \CLogger::LEVEL_ERROR);

            $request->getResponse()
                ->setHttpCode(IResponseHttpCode::PROBLEM)
                ->setBody(json_encode($request->getProtocol()->getProtocolResponse(
                        IResponseAction::ERROR,
                        "Error in module or method has got invalid result!"
                    ))
                );
        }

        return false;
    }
}