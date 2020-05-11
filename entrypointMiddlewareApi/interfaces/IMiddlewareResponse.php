<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 11:32
 */

namespace components\middlewareApi\interfaces;


interface IMiddlewareResponse
{
    /**
     * Set response http code
     * @param int $code
     */
    public function setHttpCode(int $code): IMiddlewareResponse;

    /**
     * Set response body
     * @param string $body
     */
    public function setBody(string $body): IMiddlewareResponse;

    /**
     * Get response body
     * @return string
     */
    public function getBody():string;

    /**
     * Get response http code
     * @return int
     */
    public function getHttpCode():int;
}