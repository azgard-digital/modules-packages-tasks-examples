<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 11:32
 */

namespace components\middlewareApi\interfaces;

interface IMiddlewareRequest
{
    /**
     * Get protocol
     *
     * @return IProtocol
     */
    public function getProtocol(): IProtocol;

    /**
     * Get Response
     *
     * @return IMiddlewareResponse
     */
    public function getResponse(): IMiddlewareResponse;

    /**
     * Set object in container for sharing in middleware
     *
     * @param IContainer $obj
     */
    public function setContainer(IContainer $obj);

    /**
     * Get object from container
     *
     * @param string $name
     * @return mixed|null
     */
    public function getContainer(string $name);
}