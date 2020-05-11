<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 12:55
 */
namespace components\middlewareApi\factories;

use components\middlewareApi\interfaces\IProtocolFactory;
use components\middlewareApi\interfaces\IProtocol;

/**
 * Class AProtocolFactory
 * @package \components\middlewareApi\factories\protocol
 * @author Igor Naydyonnyy
 *
 * @property array $params
 */
abstract class AProtocolFactory implements IProtocolFactory
{
    /**
     * @var array
     */
    private $params = [];

    /**
     * @internal
     */
    public function setParams(array $params): AProtocolFactory
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return string
     */
    protected function getProtocolVersion(): string
    {
        $version = (isset($this->params['version']) && $this->params['version']) ? $this->params['version'] : self::DEFAULT_VERSION;
        return (string)$version;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Create protocol by version in request
     * @return \components\middlewareApi\interfaces\IProtocol
     * @throws \Exception
     */
    public function create(): IProtocol
    {
        $version  = $this->getProtocolVersion();
        $class = $this->getProtocolClass($version);

        if (!class_exists($class) || !is_subclass_of($class, IProtocol::class)) {
            throw new \Exception('Protocol version: '.$version.' not found!');
        }

        $protocol = new $class;
        $protocol->setAttributes($this->getParams());
        return $protocol;
    }

    /**
     * @param string $version
     *
     * @return string
     */
    protected function getProtocolClass(string $version): string
    {
        $namespace = (new \ReflectionObject($this))->getNamespaceName();
        return $namespace.'\v'.$version.'\Protocol';
    }
}