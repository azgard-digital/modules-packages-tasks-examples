<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 13.09.18
 * Time: 17:13
 */

namespace components\middlewareApi;

use components\middlewareApi\factories\AProtocolFactory;
use components\middlewareApi\components\MiddlewareRequest;
use components\middlewareApi\components\MiddlewareResponse;
use components\middlewareApi\interfaces\IMiddleware;
use components\middlewareApi\interfaces\IMiddlewareRequest;
use components\middlewareApi\interfaces\IProtocol;
use components\middlewareApi\interfaces\IResponseAction;
use components\middlewareApi\interfaces\IResponseHttpCode;
use components\middlewareApi\protocol\ErrorProtocol;
use Yii;

class Middleware
{
    /**
     * @var array
     */
    protected $middlewareList;

    /**
     * @var AProtocolFactory
     */
    protected $protocolFactory;

    /**
     * Middleware constructor.
     *
     * @param array $middlewareList
     * @param AProtocolFactory $protocolFactory
     */
    public function __construct(array $middlewareList, AProtocolFactory $protocolFactory)
    {
        $this->middlewareList = $middlewareList;
        $this->protocolFactory = $protocolFactory;
    }

    /**
     * Run middleware group for specific action
     * @return IMiddlewareRequest
     */
    public function middlewareProcess(): IMiddlewareRequest
    {
        try {
            $params = $this->getJsonParams();
            $protocol = $this->getProtocol($params);
            $request = new MiddlewareRequest((new MiddlewareResponse()), $protocol);

            if (count($this->middlewareList)) {

                array_reduce($this->middlewareList, function ($next, $item) use (&$request) {
                    try {

                        if (!(is_subclass_of($item, IMiddleware::class))) {
                            throw new \Exception('Class ' . $item . ' does not inherit from ' . IMiddleware::class);
                        }

                        if (!$next) {
                            return false;
                        }

                        return $item::handle($request);

                    } catch (\Exception $e) {
                        Yii::log('Dump Middleware (' . $item . ') exception: ' . $e->getMessage(), \CLogger::LEVEL_ERROR);

                        $request->getResponse()
                            ->setHttpCode(IResponseHttpCode::PROBLEM)
                            ->setBody(json_encode($request->getProtocol()->getProtocolResponse(
                                    IResponseAction::ERROR,
                                    'Server Error!'
                                ))
                            );
                    }
                }, true);
            }

        } catch (\Exception $e) {
            Yii::log($e->getMessage(), \CLogger::LEVEL_ERROR);

            $request = new MiddlewareRequest((new MiddlewareResponse()), (new ErrorProtocol()));

            $request->getResponse()
                ->setHttpCode(IResponseHttpCode::PROBLEM)
                ->setBody(json_encode($request->getProtocol()->getProtocolResponse(
                        IResponseAction::ERROR,
                        'Server Error!'
                    ))
                );
        }

        return $request;
    }

    /**
     * @return array
     */
    protected function getJsonParams():array
    {
        $rawBody = file_get_contents('php://input');
        $data = json_decode(utf8_decode($rawBody), true);

        if (!is_array($data)) {

            if (json_last_error()) {
                \Yii::log("Json cannot be parsed: ".json_last_error_msg(), \CLogger::LEVEL_ERROR);
            }

            return [];
        }

        return $data;
    }

    /**
     * Get communicate protocol with device
     *
     * @param array $params
     * @throws \Exception
     * @return IProtocol
     */
    protected function getProtocol(array $params):IProtocol
    {
        return $this->protocolFactory
            ->setParams($params)
            ->create();
    }
}