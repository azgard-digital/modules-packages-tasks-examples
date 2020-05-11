<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 07.06.18
 * Time: 15:50
 */

namespace components\multiLevelsCache;

use CCache;
use Yii;
use components\multiLevelsCache\interfaces\IMultiLevelsCache;

/**
 * MultiLevelsCache provides a multi-levels-based caching mechanism.
 * It provide possibility using few levels storing data.
 * First level it is temporary memory container which living in php runtime.
 * Next level use some cache storage for restore data and put to container,
 * by default we are using base cache component but you can set your own cache,
 * just override dependencies section in this component setting, see example:
 *'components' => [
 * ....
 * 'multiLevelsCache' => [
 *     'class' => '\components\multiLevelsCache\MultiLevelsCache',
 *     'dependencies' => [
 *       'mainCache' => 'Set your cache component witch extended from CCache'
 *     ]
 *  ],
 * ....
 * ]
 *
 * Class MultiLevelsCache
 *
 * @package components\multiLevelsCache
 * @author Igor Naydyonnyy
 */
class MultiLevelsCache extends CCache implements IMultiLevelsCache
{
    /**
     * @var array
     */
    public $dependencies = [];

    /**
     * @var int
     */
    public $superKeyExpire = 10800; //3h

    /**
     * Cache component
     *
     * @var null|CCache
     */
    private $cache = null;

    /**
     * @var array
     */
    private $containers = [];

    /**
     * If this parent key set,
     * the component will make group of children keys
     *
     * @var string
     */
    private $superKey = '';

    /**
     * Using just runtime cache
     *
     * @var bool
     */
    private $onlyMemory = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (isset($this->dependencies['mainCache']) && ($this->dependencies['mainCache'] instanceof CCache)) {
            $this->cache = Yii::app()->getComponent($this->dependencies['mainCache']);
        } else {
            $this->cache = Yii::app()->getCache();
        }
    }

    /**
     * It set temporary setting by default
     * each time when you get component
     * because this settings should be different
     * for each caching method
     *
     * @return void
     */
    protected function restoreDefaultValues()
    {
        $this->superKey = '';
        $this->onlyMemory = false;
    }

    /**
     * Function run each times when you get component
     *
     * @return $this
     */
    public function make():IMultiLevelsCache
    {
        $this->restoreDefaultValues();
        return $this;
    }

    /**
     * Set only memory cache
     *
     * @param bool $onlyMemory
     * @return $this
     */
    public function setOnlyMemory(bool $onlyMemory):IMultiLevelsCache
    {
        $this->onlyMemory = $onlyMemory;
        return $this;
    }

    /**
     * Making array of keys using super key
     *
     * @param string $superKey
     * @return $this;
     */
    public function setSuperKey(string $superKey):IMultiLevelsCache
    {
        $this->superKey = $superKey;
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function getValue($key)
    {
        if (isset($this->containers[$key])) {
            return $this->containers[$key];
        }

        if ($this->onlyMemory) {
            return false;
        }

        $value = $this->cache->get($key);

        if ($value) {
            $this->containers[$key] = $value;
            return $value;
        }

        return false;
    }

    /**
     * If super key is not empty
     *
     * @return bool
     */
    protected function existSuperKey(): bool
    {
        return ($this->superKey) ? true : false;
    }

    /**
     * Add children key to group of keys
     *
     * @param string $key
     */
    protected function addToKeysGroup(string $key)
    {
        $values = $this->cache->get($this->superKey);

        if (!is_array($values)) {
            $values = [];
        }

        array_push($values, $key);

        $this->cache->set($this->superKey, $values, $this->superKeyExpire);
    }

    /**
     * Remove all cache and containers by super keys
     *
     * @param $superKey
     * @return void
     */
    public function removeGroup(string $superKey)
    {
        $this->flushContainers();

        if ($this->onlyMemory) {
            return true;
        }

        $values = $this->cache->get($superKey);

        if (!is_array($values)) {
            $values = [];
        }

        foreach ($values as $value) {
            $this->cache->delete($value);
        }

        $this->cache->set($superKey, [], $this->superKeyExpire);
    }

    /**
     * @inheritdoc
     */
    protected function setValue($key,$value,$expire)
    {
        $this->containers[$key] = $value;

        if ($this->onlyMemory) {
            return true;
        }

        if ($this->existSuperKey()) {
            $this->addToKeysGroup($key);
        }

        return $this->cache->set($key, $value, $expire);
    }

    /**
     * @inheritdoc
     */
    protected function addValue($key,$value,$expire)
    {
        if (!isset($this->containers[$key])) {
            $this->containers[$key] = $value;
        }

        if ($this->onlyMemory) {
            return true;
        }

        if ($this->existSuperKey()) {
            $this->addToKeysGroup($key);
        }

        return $this->cache->add($key, $value, $expire);
    }

    /**
     * @inheritdoc
     */
    protected function deleteValue($key)
    {
        if (isset($this->containers[$key])) {
            unset($this->containers[$key]);
        }

        if ($this->onlyMemory) {
            return true;
        }

        return $this->cache->delete($key);
    }

    /**
     * Check data in the storage
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return (
            isset($this->containers[$this->generateUniqueKey($key)]) 
            || $this->get($key) 
            || $this->cache->get($key)
        );
    }

    /**
     * Remove data from runtime cache
     *
     * @return void
     */
    protected function flushContainers()
    {
        $this->containers = [];
    }

    /**
     * @inheritdoc
     */
    protected function flushValues()
    {
        $this->flushContainers();

        if ($this->onlyMemory) {
            return true;
        }

        return $this->cache->flush();
    }
}
