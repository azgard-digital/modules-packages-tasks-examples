<?php

namespace HttpClient;

/**
 * Interface IHttpClient
 * @package HttpClient
 */
interface IHttpClient
{
    const DEFAULT_CONNECT_TIMEOUT = 5;
    const DEFAULT_TIMEOUT = 5;
    const DEFAULT_HTTP_METHOD = 'GET';
    const DEFAULT_HTTP_CODE = 523;

    /**
     * Provide response as object
     * Now it is adapter to Guzzle/Response
     * @return IResponse
     */
    public function execute();

    /**
     * @param string $name Header name example: "ACCEPT"
     * @param string $value Header value example: "application/json, text/javascript"
     * @return $this
     */
    public function addHeader($name, $value);

    /**
     * Set headers in bulk mode
     * @param array $headers
     * Example:
     *  [
     *      'ACCEPT' => 'application/json, text/javascript'
     *  ]
     * @return $this
     */
    public function addHeaders(array $headers = []);

    /**
     * Add requested url
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Set requested method
     * @param string $method example any of ['GET','POST',etc]
     * @return $this
     */
    public function setMethod($method);

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body);

    /**
     * Float describing the timeout of the request in seconds. Use 0 to wait indefinitely (the default behavior).
     * @param float $timeout
     * @return $this
     */
    public function setTimeout($timeout);

    /**
     * Float describing the number of seconds to wait while trying to connect to a server. Use 0 to wait indefinitely (the default behavior).
     * @param float $connectTimeout
     * @return $this
     */
    public function setConnectTimeout($connectTimeout);

    /**
     * HTTP authentication parameters to use with the request.
     * @param array $auth [username, password]
     * @return $this
     */
    public function setAuth(array $auth = []);

    /**
     * SSL certificate verification and use the default CA bundle provided by operating
     * @param bool $verify
     * @return $this
     */
    public function setVerify($verify);

    /**
     * Set custom cURL options
     * @param array $curl
     * @return $this
     */
    public function setCurlOptions(array $curl = []);
}