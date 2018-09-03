<?php

namespace Vinks\MemoryCaching;

/**
 * This trait is used to retrieved models with 'find' from memory if already loaded.
 */
trait SimpleMemoryCached
{

    private $memoryCachingIsOn = true;

    /**
     * Boot the memory caching trait for a model.
     *
     * @return void
     */
    public static function bootMemoryCaching()
    {

    }

    /**
     * Activate or de-activate memory caching
     *
     * @param boolean $boolean
     * @return void
     */
    public function setMemoryCaching(bool $boolean) {
        $this->$memoryCachingIsOn = $boolean;
    }

    /**
     * The maximum number of items stored.
     *
     * @return void
     */
    public static function getCacheLimit() {
        return defined('static::MEMORY_CACHE_LIMIT') ? static::MEMORY_CACHE_LIMIT : 50;
    }

    /**
     * Get the prefix of the caching repo. Should be 'simple' for SimpleMemoryCached traits.
     *
     * @return void
     */
    protected static function getCacheRepoPrefix() {
        return 'simple';
    }

    /**
     * Get the repository used for caching.
     *
     * @return void
     */
    public static function getCacheRepo() {
        return Repository::access(static::getCacheRepoPrefix(), static::class);
    }

    /**
     * Extension of Eloquent's newFromBuilder function implementing caching of models. 
     *
     * @param array $attributes
     * @param [type] $connection
     * @return void
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = parent::newFromBuilder($attributes, $connection);
        if ($this->memoryCachingIsOn) {
            $repo = static::getCacheRepo();
            $serialized = serialize([$model->getKey()]);
    
            return $repo->get($serialized) ?? $repo->set($serialized, $model);
        } else {
            return $model;
        }
    }

    /**
     *  dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($this->memoryCachingIsOn && in_array($method, ['find'])) {
            $serializedKeys = serialize($parameters);
            $repo = static::getCacheRepo();

            return $repo->get($serializedKeys) ?? $repo->set($serializedKeys, parent::__call($method, $parameters));
        }

        return parent::__call($method, $parameters);
    }


}
