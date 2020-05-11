<?php
/**
 * Created by PhpStorm.
 * Date: 20.09.17
 * Time: 18:46
 */

namespace HttpClient;

use CLogger;
use Exception;
use Yii;

/**
 * Class StreamClient
 * @package HttpClient
 */
class StreamClient implements IStreamClient
{
    /**
     * @var string $mode
     */
    private $mode;

    /**
     * @var int $bytes
     */
    private $bytes;

    /**
     * @var string $url
     */
    private $url;
    /**
     * @var callable
     */
    private $exceptionHandler = null;

    /**
     * StreamClient constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->mode = (isset($options['mode'])) ? $options['mode'] : static::DEFAULT_MODE;
        $this->bytes = (isset($options['bytes'])) ? $options['bytes'] : static::DEFAULT_BYTES;
        $this->setExceptionHandler(array_key_exists('exceptionHandler', $options) ? $options['exceptionHandler'] : null);
    }

    /**
     * Provide response as object
     * Now it is adapter to Guzzle/Response
     * @return IResponse
     */
    public function execute()
    {
        return $this->sendStreamRequest();
    }

    /**
     * Send request using guzzle library
     * @return IResponse
     */
    private function sendStreamRequest()
    {
        try {

            if (!$this->url) {
                throw new Exception('Validation failed, url for http request not exists');
            }

            $resource = \GuzzleHttp\Psr7\stream_for(fopen($this->url, $this->mode));
            $stream = new \GuzzleHttp\Psr7\CachingStream($resource);
            $response = '';

            while (!$stream->eof()) {
                $response .= $stream->read($this->bytes);
            }

            $stream->close();

            return new Response(self::DEFAULT_HTTP_SUCCESS_CODE, $response);

        } catch (Exception $e) {
            $this->handleException($e);
        }

        return new Response(self::DEFAULT_HTTP_CODE);
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
     * The mode parameter specifies the type of access you require to the stream
     * http://php.net/manual/en/function.fopen.php
     * @param string $mode
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @param int $bytes
     * @return $this
     */
    public function setBytes($bytes)
    {
        $this->bytes = $bytes;
        return $this;
    }

    /**
     * Add requested url
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
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