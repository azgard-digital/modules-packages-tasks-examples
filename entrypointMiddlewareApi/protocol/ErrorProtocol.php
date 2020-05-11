<?php
namespace components\middlewareApi\protocol;

use components\middlewareApi\interfaces\IProtocol;
use components\middlewareApi\interfaces\IResponseHttpCode;


class ErrorProtocol extends \FormModel implements IProtocol
{

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
        ];
    }


    public function getProtocolResponse(int $action, string $errorMessage = '', array $data = [])
    {
        return [
            'version' => '',
            'status_code' => IResponseHttpCode::PROBLEM,
            'action' => $action,
            'error' => ['code' => IResponseHttpCode::PROBLEM, 'message' => $errorMessage, 'data' => '']
        ];
    }
}