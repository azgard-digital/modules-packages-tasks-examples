<?php
namespace example\models\protocol\v1;

use components\middlewareApi\interfaces\IProtocol;
use components\middlewareApi\interfaces\IResponseAction;
use components\middlewareApi\interfaces\IResponseHttpCode;
use Yii;

class Protocol extends \FormModel implements IProtocol
{
    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $module;

    /**
     * @var string
     */
    public $method;

    /**
     * @var array
     */
    public $params = [];


    /**
     * Validation rules
     *
     * @see CModel::rules()
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['version, module, method, id', 'required'],
            ['module', 'in', 'range' => \example\helpers\ExampleModules::getActiveModuleList()],
        ];
    }

    public function getProtocolResponse(int $action, string $errorMessage = '', array $data = [])
    {
        $response = [
            'version' => $this->version,
            'action'  => $action,
        ];

        if ($action == IResponseAction::ACCEPTED) {

            return array_merge([
                'status_code' => IResponseHttpCode::SUCCESS,
                'result' => $data
            ], $response);
        }

        return array_merge([
            'status_code' => IResponseHttpCode::PROBLEM,
            'error' => ['code' => IResponseHttpCode::PROBLEM, 'message' => $errorMessage, 'data' => '']
        ], $response);
    }
}