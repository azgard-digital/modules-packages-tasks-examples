<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 17:47
 */

namespace components\middlewareApi\interfaces;


interface IResponseHttpCode
{
    const PROBLEM = 500;
    const SUCCESS = 200;
    const RESEND  = 503;
}