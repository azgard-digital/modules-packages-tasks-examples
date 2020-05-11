<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 14.09.18
 * Time: 18:45
 */

namespace components\middlewareApi\components;

use components\middlewareApi\interfaces\IMiddlewareResponse;

class MiddlewareResponse implements IMiddlewareResponse
{

    /**
     * @var int
     */
    private $httpCode = 200;

    /**
     * @var string
     */
    private $body = '';

    /**
     * Set response body
     * @param string $body
     */
    public function setBody(string $body):IMiddlewareResponse
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get response body
     * @return string
     */
    public function getBody():string
    {
        return $this->body;
    }

    /**
     * Set response http code
     * @param int $code
     */
    public function setHttpCode(int $code):IMiddlewareResponse
    {
        $this->httpCode = $code;
        return $this;
    }

    /**
     * Get response http code
     * @return int
     */
    public function getHttpCode():int
    {
        return $this->httpCode;
    }
}