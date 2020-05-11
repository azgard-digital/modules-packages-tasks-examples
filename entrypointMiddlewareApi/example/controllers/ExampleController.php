<?php
namespace example\controllers;

use Controller;
use components\middlewareApi\helpers\Http;
use Yii;

class ExampleController extends Controller
{
    public $layout = false;

    /**
     * Define access rules
     * @return array
     */
    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['index'],
            ],
        ];
    }

    public function filters()
    {
        return array_merge(
            [
                'postOnly + index',
            ],
            parent::filters()
        );
    }

    public function actionIndex()
    {
        $response = Yii::app()
            ->getModule()
            ->getMiddlewareRequest()
            ->getResponse();

        $status_header = 'HTTP/1.1 ' . $response->getHttpCode() . ' ' . Http::getStatusCodeMessage($response->getHttpCode());
        header($status_header);
        header('Content-type: application/json');

        echo $response->getBody();
        Yii::app()->end();
    }
}
