<?php

use example\helpers\exampleModules;
use example\interfaces\IMiddlewareModule;
use example\middlewares\ModuleRun;
use example\middlewares\TokenCheck;
use example\models\protocol\ProtocolFactory;
use components\middlewareApi\interfaces\IMiddlewareRequest;
use components\middlewareApi\Middleware;
use components\middlewareApi\middlewares\ProtocolCheck;
use example\models\modules\ModuleExample;
use example\middlewares\ExampleContainer;

/**
 * Class Module
 * @author Igor Naydyonnyy
 */
class Module extends CWebModule implements IMiddlewareModule
{
    /**
     * @var IMiddlewareRequest
     */
    private $middlewareRequest;

    protected function init()
    {
        parent::init();
        if ($this->controllerNamespace === null) {
            $this->controllerNamespace = $this->getId() . '\\controllers';
        }
    }

    /**
     * @inheritdoc
     */
    protected function getMiddlewareGroups(): array
    {
        return [
            'example/index' => [
                TokenCheck::class,
                ProtocolCheck::class,
                ExampleContainer::class,
                ModuleRun::class
            ]
        ];
    }

    /**
     * Run middleware group for specific action
     *
     * @param $controller
     * @param $action
     */
    private function middlewareProcess($controller, $action)
    {
        $routeName = $controller->getId() . '/' . $action->getId();
        $middlewares = $this->getMiddlewareGroups();

        if (isset($middlewares[$routeName]) && is_array($middlewares[$routeName])) {
            $protocolFactory = new ProtocolFactory();
            $this->middlewareRequest = (new Middleware($middlewares[$routeName], $protocolFactory))->middlewareProcess();
        }

    }

    /**
     * @inheritdoc
     */
    public function beforeControllerAction($controller, $action)
    {
        $this->middlewareProcess($controller, $action);
        return true;
    }

    /**
     * @return IMiddlewareRequest
     */
    public function getMiddlewareRequest(): IMiddlewareRequest
    {
        return $this->middlewareRequest;
    }

    /**
     * @param string $moduleId
     * @param mixed ...$params
     *
     * @throws \Exception
     */
    public function moduleFactory(string $moduleId, ...$params)
    {
        switch ($moduleId) {
            case $moduleId == exampleModules::EXAMPLE:
                return new ModuleExample(...$params);
        }

        throw new \Exception("Module {$moduleId} does not exist!");
    }

    /**
     * Return access token
     * @return string
     */
    public function getAuthorizationToken()
    {
        return '';
    }
}