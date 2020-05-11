<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 13:13
 */

namespace components\middlewareApi\interfaces;


use components\middlewareApi\factories\AProtocolFactory;

interface IProtocolFactory
{
    /**
     * Default protocol version
     */
    const DEFAULT_VERSION = 1;

    /**
     * Create protocol by version in request
     *
     * @return IProtocol
     */
    public function create(): IProtocol;


    /**
     * @var array $params
     * @return AProtocolFactory
     */
    public function setParams(array $params): AProtocolFactory;

    /**
     * @return array
     */
    public function getParams();
}