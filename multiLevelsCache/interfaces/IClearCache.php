<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 13.06.18
 * Time: 18:22
 */

namespace components\multiLevelsCache\interfaces;

use components\multiLevelsCache\behaviors\ClearCache;

/**
 * Interface IClearCache
 * @package components\multiLevelsCache\interfaces
 */
interface IClearCache
{
    /**
     * This method will be run in @ClearCache behavior on afterDelete|afterSave event
     * @var mixed $key
     * @return void
     */
    public function clearModelCache($key = null);

    /**
     * @return void
     */
    public function flushModelCache();
}