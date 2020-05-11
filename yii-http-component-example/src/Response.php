<?php
/**
 * Created by PhpStorm.
 * Date: 20.09.17
 * Time: 12:32
 */

namespace HttpClient;


/**
 * Simple response class
 * Class Response
 * @package HttpClient
 */
class Response implements IResponse
{
    /**
     * Status state
     * @var integer|null
     */
    private $status;
    /**
     * Body content
     * @var string
     */
    private $body;
    /**
     * Headers array
     * @var array
     */
    private $headers = [];

    /**
     * Response constructor.
     * @param $status
     * @param string $body
     */
    public function __construct($status, $body = '', $headers = [])
    {
        $this->status = $status;
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * Provide a status code of http response
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Provide response content
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Provide response headers
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
