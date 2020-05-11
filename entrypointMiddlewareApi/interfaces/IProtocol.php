<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 16:46
 */

namespace components\middlewareApi\interfaces;

use IFormModel;

interface IProtocol extends IFormModel
{
    /**
     * Create response body
     *
     * @param int $action
     * @param string $errorMessage
     * @param array $data
     * @return array
     */
    public function getProtocolResponse(int $action, string $errorMessage = '', array $data = []);
}