<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 23.07.18
 * Time: 13:46
 */

namespace components\multiLevelsCache\interfaces;


interface IMultiLevelsCache
{
    /**
     * Check if data exist in storages
     * @param $key
     * @return bool
     */
    public function has($key);

    /**
     * Flush cache group
     * @param $superKey
     */
    public function removeGroup(string $superKey);

    /**
     * Making array of keys using super key
     * @param string $superKey
     * @return IMultiLevelsCache
     */
    public function setSuperKey(string $superKey):IMultiLevelsCache;

    /**
     * Retrieves a value from cache with a specified key.
     * @param string $id a key identifying the cached value
     * @return mixed the value stored in cache, false if the value is not in the cache, expired or the dependency has changed.
     */
    public function get($id);

    /**
     * Stores a value identified by a key into cache.
     * If the cache already contains such a key, the existing value and
     * expiration time will be replaced with the new ones.
     *
     * @param string $id the key identifying the value to be cached
     * @param mixed $value the value to be cached
     * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
     * @param ICacheDependency $dependency dependency of the cached item. If the dependency changes, the item is labeled invalid.
     * @return boolean true if the value is successfully stored into cache, false otherwise
     */
    public function set($id,$value,$expire=0,$dependency=null);

    /**
     * Stores a value identified by a key into cache if the cache does not contain this key.
     * Nothing will be done if the cache already contains the key.
     * @param string $id the key identifying the value to be cached
     * @param mixed $value the value to be cached
     * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
     * @param ICacheDependency $dependency dependency of the cached item. If the dependency changes, the item is labeled invalid.
     * @return boolean true if the value is successfully stored into cache, false otherwise
     */
    public function add($id,$value,$expire=0,$dependency=null);

    /**
     * Deletes a value with the specified key from cache
     * @param string $id the key of the value to be deleted
     * @return boolean if no error happens during deletion
     */
    public function delete($id);

    /**
     * Deletes all values from cache.
     * Be careful of performing this operation if the cache is shared by multiple applications.
     * @return boolean whether the flush operation was successful.
     */
    public function flush();

    /**
     * Set only memory cache
     * @param bool $onlyMemory
     * @return IMultiLevelsCache
     */
    public function setOnlyMemory(bool $onlyMemory):IMultiLevelsCache;

    /**
     * Function run each times when you get component
     * @return $this
     */
    public function make():IMultiLevelsCache;
}