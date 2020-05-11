<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 18.09.18
 * Time: 13:17
 */
namespace components\middlewareApi\helpers;

use components\middlewareApi\interfaces\IResponseHttpCode;

class Http implements IResponseHttpCode
{
    /**
     * Get http message by code
     * @param $status
     * @return mixed|string
     */
    public static function getStatusCodeMessage($status)
    {
        $codes = [
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            503 => 'Service Unavailable'
        ];

        return $codes[$status] ?? '';
    }
}