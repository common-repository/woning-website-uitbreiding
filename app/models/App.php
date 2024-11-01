<?php

namespace Tussendoor\PropertyWebsite;

use Exception;
use Tussendoor\Helpers\ImmutableValue;

/**
 * Dependency Container
 */
class App
{
    protected static $registry = [];

    /**
     * Bind the key to a value and store it within $registry
     * @param string $key   The name of the the thing to bind
     * @param mixed  $value
     */
    public static function bind($key, $value)
    {
        if (App::bound($key) && static::$registry[$key] instanceof ImmutableValue) {
            throw new Exception("Unable to rebind immutable value {$key}");
        }

        static::$registry[$key] = $value;
    }

    /**
     * Lock a value within the registry. This prevent overiding the value. ...partially.
     * @param string $key
     * @param mixed  $value
     */
    public static function lock($key, $value = null)
    {
        if ($value === null) {
            if (!App::bound($key)) {
                throw new Exception("Cannot lock {$key} when not bound to App.");
            }
            
            return App::bind($key, new ImmutableValue(App::get($key)));
        }

        return App::bind($key, new ImmutableValue($value));
    }

    /**
     * Check if a certain value is bound to the registry
     * @param  string $key
     * @return bool
     */
    public static function bound($key)
    {
        return array_key_exists($key, static::$registry);
    }

    /**
     * Get a dependency from the registry
     * @param  string $key
     * @return mixed
     */
    public static function get($key)
    {
        if (!array_key_exists($key, static::$registry)) {
            throw new Exception("No {$key} availabile in registry!");
        }

        $value = static::$registry[$key];

        return $value instanceof ImmutableValue ? $value->getValue() : $value;
    }

    /**
     * Get a config from the registery
     * @param  string $keys
     * @return mixed
     */
    public static function getConfig($keys)
    {
        $keys = explode('.', $keys);
        if (empty($keys)) {
            throw new Exception("Invalid config key!");
        }

        $config = static::$registry;
        foreach ($keys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new Exception("No {$key} availabile in registry!");
            }

            $config = $config[$key];
        }

        return $config instanceof ImmutableValue ? $config->getValue() : $config;
    }

    /**
     * Load a config file into the app registry.
     * @param  string $path
     * @return bool
     */
    public static function loadFromConfig($path)
    {
        if (!file_exists($path)) {
            throw new Exception('Unloadable configuration file provided.');
        }

        $values = require $path;

        foreach ($values as $name => $setting) {
            self::bind($name, $setting);
        }

        return true;
    }
}
