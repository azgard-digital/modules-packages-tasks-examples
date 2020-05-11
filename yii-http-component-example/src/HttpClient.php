<?php
/**
 * Created by PhpStorm.
 * Date: 20.09.17
 * Time: 12:32
 */

namespace HttpClient;

use CLogger;
use Exception;
use GuzzleHttp\Client;
use Yii;

/**
 * Class HttpClient
 * @package HttpClient
 */
class HttpClient implements IHttpClient
{
    /**
     * List of headers
     * @var array
     */
    private $headers = [];
    /**
     * Requested url
     * @var string
     */
    private $url = '';
    /**
     * Request method
     * @var string
     */
    private $method = '';
    /**
     * Request payload (body)
     * @var string
     */
    private $body = '';
    /**
     * @var float
     */
    private $timeout;
    /**
     * @var float
     */
    private $connectTimeout;
    /**
     * @var callable
     */
    private $exceptionHandler = null;
    /**
     * @var array
     */
    private $auth = [];
    /**
     * @var bool
     */
    private $verify = false;
    /**
     * @var array
     */
    private $curl = [];
    /**
     * @var bool
     */
    private $debug = false;
    /**
     * @var string
     */
    private $fileLog = 'http-request.log';

    /**
     * HttpClient constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->method = (isset($options['method'])) ? $options['method'] : static::DEFAULT_HTTP_METHOD;
        $this->timeout = (isset($options['timeout'])) ? $options['timeout'] : static::DEFAULT_TIMEOUT;
        $this->connectTimeout = (isset($options['connectTimeout'])) ? $options['connectTimeout'] : static::DEFAULT_CONNECT_TIMEOUT;
        $this->setDebug(isset($options['debug']) ? $options['debug'] : false);
        $this->setExceptionHandler(array_key_exists('exceptionHandler', $options) ? $options['exceptionHandler'] : null);
    }

    /**
     * Activate debug
     * @param bool $mode
     */
    private function setDebug($mode)
    {
        if ($mode) {
            $this->debug = true;
        }
    }

    /**
     * Add default headers if method POST
     * @return void
     */
    private function setDefaultHeaders()
    {
        if (preg_match("/post/i", $this->method) && !isset($this->headers['Content-Type'])) {
            $this->addHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
    }

    /**
     * Set exception handler
     * @param callable|null $handler
     * @return $this
     */
    private function setExceptionHandler($handler = null)
    {
        if (is_callable($handler)) {
            $this->exceptionHandler = $handler;
        } else {
            $this->exceptionHandler = [$this, 'defaultExceptionHandler'];
        }
        return $this;
    }

    /**
     * Provide response as object
     * Now it is adapter to Guzzle/Response
     * @return IResponse
     */
    public function execute()
    {
        return $this->sendHttpRequest();
    }

    /**
     * Send request using guzzle library
     * @return IResponse
     */
    private function sendHttpRequest()
    {
        $message = '';

        try {

            if (!$this->url) {
                throw new Exception('Validation failed, url for http request not exists');
            }

            $this->setDefaultHeaders();

            $client = new Client();

            $params = [
                'timeout' => $this->timeout,
                'connect_timeout' => $this->connectTimeout,
                'body' => $this->body,
                'headers' => $this->headers,
                'auth' => $this->auth,
                'verify' => $this->verify,
                'curl' => $this->curl,

            ];

            if ($this->debug) {
                $this->isWritableLogDir();
                $params = array_merge($params, ['debug' => fopen(Yii::app()->runtimePath.'/'.$this->fileLog, 'a')]);
            }

            $response = $client->request($this->method, $this->url, $params);

            if (is_object($response)) {
                return new Response($response->getStatusCode(), $response->getBody()->getContents(), $response->getHeaders());
            }

        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->handleException($e);
        }

        return new Response(self::DEFAULT_HTTP_CODE, $message);
    }

    /**
     * Check is writable runtime directory
     * @return void
     */
    private function isWritableLogDir()
    {
        if (!is_writable(Yii::app()->runtimePath)) {
            throw new Exception('No write permission for file: '.$this->fileLog);
        }
    }

    /**
     * @param string $name Header name example: "ACCEPT"
     * @param string $value Header value example: "application/json, text/javascript"
     * @return $this
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Set headers in bulk mode
     * @param array $headers
     * Example:
     *  [
     *      'ACCEPT' => 'application/json, text/javascript'
     *  ]
     * @return $this
     */
    public function addHeaders(array $headers = [])
    {
        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }
        return $this;
    }

    /**
     * Set requested url
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set requested method
     * @param string $method example any of ['GET','POST',etc]
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Float describing the timeout of the request in seconds. Use 0 to wait indefinitely (the default behavior).
     * @param float $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Float describing the number of seconds to wait while trying to connect to a server. Use 0 to wait indefinitely (the default behavior).
     * @param float $connectTimeout
     * @return $this
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    /**
     * Set HTTP authentication parameters to use with the request.
     * @param array $auth
     */
    public function setAuth(array $auth = [])
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * SSL certificate verification and use the default CA bundle provided by operating
     * @param bool $verify
     * @return $this
     */
    public function setVerify($verify)
    {
        $this->verify = $verify;
        return $this;
    }

    /**
     * Set custom cURL options
     * @param array $curl
     * @return $this
     */
    public function setCurlOptions(array $curl = [])
    {
        $this->curl = $curl;
        return $this;
    }

    /**
     * Handle exception
     * @param Exception $e
     */
    protected function handleException(Exception $e)
    {
        call_user_func($this->exceptionHandler, $e);
    }

    /**
     * Default function to handle exception
     * @param Exception $e
     */
    private function defaultExceptionHandler(Exception $e)
    {
        Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
    }
}
