<?php

namespace Vinks\MemoryCaching;

use Illuminate\Support\Collection;


/**
 * 
 */
class Repository
{
    private static $instances = [];

    private $classLinked;
    private $limit = 50;
    private $store;

    private function __construct($forClass) {
        $this->classLinked = $forClass;
        $this->limit = $forClass::getCacheLimit();
        $this->store = new Collection();
    }

    public function has($key) {
        return !($this->store->firstWhere('k', $key) === null);
    }

    public function get($key) {
        return $this->store->firstWhere('k', $key);
    }

    public function set($key, $value) {
        $this->store->push([
            'k' => $key,
            'v' => $value
        ]);
        $this->checkLimit();
        return $value;
    }
    
    public function checkLimit() {
        if ($this->store->count() > $this->limit) {
            $this->shift();
        }
    }
    public function shift() {
        $this->store->shift();
    }

    public static function access($prefix, $forClass) {
        if(!isset(self::$instances[$prefix . ':' . $forClass])) {
            self::$instances[$prefix . ':' . $forClass] = new Repository($forClass);  
        }
        
        return self::$instances[$prefix . ':' . $forClass];
    }

    public function debugBarHelper() {
        \DebugBar::info($this->store);
    }
}
