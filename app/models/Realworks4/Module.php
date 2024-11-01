<?php

namespace Tussendoor\PropertyWebsite\Realworks4;

use Wonen;
use Tussendoor\PropertyWebsite\Module\Data;
use Tussendoor\PropertyWebsite\Module\Module as BaseModule;

class Module extends BaseModule
{
    const MIN_RW_VERSION = '4';

    protected $name = 'realworks';
    protected $posttype = 'realworks_wonen';

    public function isActive()
    {
        if ($this->pluginIsActive() === false) {
            return false;
        }

        $pluginData = $this->getPluginData('realworks/realworks.php');

        if (empty($pluginData) || !isset($pluginData['Version'])) {
            return false;
        }
        
        return version_compare($pluginData['Version'], self::MIN_RW_VERSION, '>=');
    }

    public function getData($objectId)
    {
        Macros::load();
        
        $woning = $this->getWoning($objectId);
        if (empty($woning)) {
            return false;
        }

        $data = new Data();
        $data->setProperties(new Properties($woning))
            ->setMedia(new Media($woning));

        return $data->toArray();
    }

    public function objectExists($objectId)
    {
        $woning = $this->getWoning($objectId);
    
        return !empty($woning);
    }

    protected function getWoning($objectId)
    {
        return $this->rescue(function () use ($objectId) {
            return Wonen::find($objectId);
        });
    }

    protected function pluginIsActive()
    {
        return is_plugin_active('realworks/realworks.php')
            && class_exists('Realworks\Realworks');
    }
}
