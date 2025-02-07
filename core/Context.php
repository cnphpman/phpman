<?php
declare(strict_types=1);

namespace Core;

use Fiber;
use SplObjectStorage;
use StdClass;
use Swow\Coroutine;
use WeakMap;
use Workerman\Events\Revolt;
use Workerman\Events\Swoole;
use Workerman\Events\Swow;
use Workerman\Worker;
use function property_exists;

/**
 * Class Context
 *
 * @package Core
 */
class Context
{
    /**
     * @var SplObjectStorage|WeakMap
     */
    protected static $objectStorage;

    /**
     * @var StdClass
     */
    protected static $object;

    /**
     * @return StdClass
     */
    protected static function getObject(): StdClass
    {
        if (!static::$objectStorage) {
            static::$objectStorage = class_exists(WeakMap::class) ? new WeakMap() : new SplObjectStorage();
            static::$object = new StdClass;
        }
        $key = static::getKey();
        if (!isset(static::$objectStorage[$key])) {
            static::$objectStorage[$key] = new StdClass;
        }
        return static::$objectStorage[$key];
    }

    /**
     * Swoole
     *
     * @return mixed
     */
    protected static function getKey()
    {
        switch (Worker::$eventLoopClass) {
            case Revolt::class:
                return Fiber::getCurrent();
            case Swoole::class:
                return \Swoole\Coroutine::getContext();
            case Swow::class:
                return Coroutine::getCurrent();
        }
        return static::$object;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function get(string $key = null)
    {
        $obj = static::getObject();
        if ($key === null) {
            return $obj;
        }
        return $obj->$key ?? null;
    }

    /**
     * @param string $key
     * @param $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $obj = static::getObject();
        $obj->$key = $value;
    }

    /**
     * @param string $key
     * @return void
     */
    public static function delete(string $key): void
    {
        $obj = static::getObject();
        unset($obj->$key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        $obj = static::getObject();
        return property_exists($obj, $key);
    }

    /**
     * @return void
     */
    public static function destroy(): void
    {
        unset(static::$objectStorage[static::getKey()]);
    }
}