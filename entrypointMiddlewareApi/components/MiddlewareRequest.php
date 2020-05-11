<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 14.09.18
 * Time: 18:45
 */

namespace components\middlewareApi\components;

use components\middlewareApi\interfaces\IContainer;
use components\middlewareApi\interfaces\IMiddlewareRequest;
use components\middlewareApi\interfaces\IMiddlewareResponse;
use components\middlewareApi\interfaces\IProtocol;

/**
 * Class MiddlewareRequest
 * @author Igor Naydyonnyy
 *
 * @property array $containers
 * @property IMiddlewareResponse $response
 * @property IProtocol $protocol
 */
class MiddlewareRequest implements IMiddlewareRequest
{

    /**
     * @var array
     */
    private $containers = [];

    /**
     * @var IMiddlewareResponse
     */
    private $response;

    /**
     * @var IProtocol
     */
    private $protocol;

    /**
     * MiddlewareRequest constructor.
     *
     * @param IMiddlewareResponse $response
     * @param IProtocol  $protocol
     */
    public function __construct(IMiddlewareResponse $response, IProtocol $protocol)
    {
        $this->response = $response;
        $this->protocol = $protocol;
    }

    /**
     * Get protocol
     *
     * @return IProtocol
     */
    public function getProtocol():IProtocol
    {
        return $this->protocol;
    }

    /**
     * Get Response
     *
     * @return IMiddlewareResponse
     */
    public function getResponse():IMiddlewareResponse
    {
        return $this->response;
    }

    /**
     * Set object in container for sharing in middleware
     *
     * @param IContainer $obj
     */
    public function setContainer(IContainer $obj)
    {
        $this->containers[$obj->getName()] = $obj();
    }

    /**
     * Get object from container
     *
     * @param string $name
     * @return mixed|null
     */
    public function getContainer(string $name)
    {
        if (isset($this->containers[$name])) {
            return $this->containers[$name];
        }

        return null;
    }
}