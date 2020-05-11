<?php

namespace HttpClient;

/**
 * Interface IStreamClient
 * @package HttpClient
 */
interface IStreamClient
{
    const DEFAULT_MODE = 'r';
    const DEFAULT_BYTES = 1024;
    const DEFAULT_HTTP_CODE = 523;
    const DEFAULT_HTTP_SUCCESS_CODE = 200;

    /**
     * Provide response as object
     * Now it is adapter to Guzzle/Response
     * @return IResponse
     */
    public function execute();

    /**
     * The mode parameter specifies the type of access you require to the stream
     * http://php.net/manual/en/function.fopen.php
     * @param string $mode
     * @return $this
     */
    public function setMode($mode);

    /**
     * @param int $bytes
     * @return $this
     */
    public function setBytes($bytes);

    /**
     * Add requested url
     * @param string $url
     * @return $this
     */
    public function setUrl($url);
}