<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 17.09.18
 * Time: 16:42
 */

namespace components\middlewareApi\interfaces;


interface IResponseAction
{
    const REJECTED = 0;
    const ACCEPTED = 1;
    const ERROR    = 2;
}