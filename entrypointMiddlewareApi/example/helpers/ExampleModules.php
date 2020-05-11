<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 12.09.19
 * Time: 15:59
 */
namespace example\helpers;

abstract class ExampleModules
{
    const EXAMPLE = 'example';

    public static function getActiveModuleList(): array
    {
        return [
            self::EXAMPLE
        ];
    }
}