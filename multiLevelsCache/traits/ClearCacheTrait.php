<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 20.07.18
 * Time: 14:01
 */

namespace components\multiLevelsCache\traits;

use components\multiLevelsCache\interfaces\IClearCache;

/**
 * Trait ClearCache
 *
 * You must implement your model from IClearCache
 * Rewrites ActiveRecord functions with clear cache functionality
 *
 * @package components\multiLevelsCache\traits
 * @internal ActiveRecord
 *
 * @method IClearCache clearModelCache()
 * @method IClearCache flushModelCache()
 *
 *
 * CActiveRecord deleteByPk()
 * CActiveRecord deleteAll()
 * CActiveRecord deleteAllByAttributes()
 * CActiveRecord updateByPk()
 * CActiveRecord updateAll()
 * CActiveRecord updateCounters()
 */
trait ClearCacheTrait
{
    /**
     * @internal
     */
    public function deleteByPk($pk,$condition='',$params=array())
    {
        $this->clearModelCache($pk);

        return parent::deleteByPk($pk, $condition, $params);
    }

    /**
     * @internal
     */
    public function deleteAll($condition='',$params=array())
    {
        $this->flushModelCache();

        return parent::deleteAll($condition, $params);
    }

    /**
     * @internal
     */
    public function deleteAllByAttributes($attributes,$condition='',$params=array())
    {
        $this->flushModelCache();

        return parent::deleteAllByAttributes($attributes, $condition, $params);
    }

    /**
     * @internal
     */
    public function updateByPk($pk,$attributes,$condition='',$params=array())
    {
        $this->clearModelCache($pk);

        return parent::updateByPk($pk, $attributes, $condition, $params);
    }

    /**
     * @internal
     */
    public function updateAll($attributes,$condition='',$params=array())
    {
        $this->flushModelCache();

        return parent::updateAll($attributes, $condition, $params);
    }

    /**
     * @internal
     */
    public function updateCounters($counters,$condition='',$params=array())
    {
        $this->flushModelCache();

        return parent::updateCounters($counters, $condition, $params);
    }
}