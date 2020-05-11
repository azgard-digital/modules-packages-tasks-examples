<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 11:41
 */

namespace components\middlewareApi\components;


use components\middlewareApi\interfaces\IContainer;

/**
 * Class used in middleware for saving intermediate data
 * Class Container
 * @author Igor Naydyonnyy
 */
class Container implements IContainer
{

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var mixed
     */
    private $data;

    /**
     * Container constructor.
     * @param string $name
     * @param $data
     */
    public function __construct(string $name, $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Get container name
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->data;
    }
}