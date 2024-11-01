<?php

namespace Tussendoor\PropertyWebsite\Module;

use Exception;
use Throwable;
use Tussendoor\PropertyWebsite\App;

abstract class Module
{
    protected $name = '';
    protected $posttype = '';

    public function __construct()
    {
        $this->prepare();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPosttype()
    {
        return $this->posttype;
    }

    abstract public function isActive();

    abstract public function getData($objectId);

    abstract public function objectExists($objectId);

    protected function prepare()
    {
        if (!is_admin() && !function_exists('get_plugin_data') || !function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
    }

    protected function getPluginData($relativePath)
    {
        $absolutePath = dirname(App::get('plugin.path')).'/'.ltrim($relativePath, '/');
        
        return @array_filter(get_plugin_data($absolutePath));
    }

    protected function rescue($callback, $default = false)
    {
        try {
            return $callback();
        } catch (Exception $e) {
            return $default;
        } catch (Throwable $e) {
            return $default;
        }
    }
}
