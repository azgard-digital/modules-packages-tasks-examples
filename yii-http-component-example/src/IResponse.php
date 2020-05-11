<?php
/**
 * Created by PhpStorm.
 * Date: 20.09.17
 * Time: 12:28
 */

namespace HttpClient;

/**
 * Simple http response interface
 * Interface IResponse
 * @package HttpClient
 */
interface IResponse
{
    /**
     * Provide a status code of http response
     * @return integer
     */
    public function getStatus();

    /**
     * Provide response content
     * @return string
     */
    public function getBody();

    /**
     * Provide response headers
     * @return array
     */
    public function getHeaders();
}
