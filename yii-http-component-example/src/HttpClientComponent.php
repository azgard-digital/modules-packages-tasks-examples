<?php
/**
 * Created by PhpStorm.
 * Date: 20.09.17
 * Time: 12:44
 */

namespace HttpClient;

use CApplicationComponent;

/**
 * Class HttpClientComponent
 * @package HttpClient
 */
class HttpClientComponent extends CApplicationComponent implements IHttpClientComponent
{

    /**
     * Creates a new instance of httpClient
     * @param array $options
     * @return IHttpClient
     */
    public function make(array $options = [])
    {
        return new HttpClient($options);
    }

    /**
     * Creates a new instance of StreamClient
     * @param array $options
     * @return IStreamClient
     */
    public function makeStream(array $options = [])
    {
        return new StreamClient($options);
    }
}
