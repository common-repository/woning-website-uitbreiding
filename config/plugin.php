<?php


use Tussendoor\Settings\Providers\SettingsProviderJson;
use Tussendoor\Settings\Providers\SettingsProviderOption;

return [

    /**
     * Basic plugin data
     */
    'plugin.name'       => 'Woning Website Uitbreiding',
    'plugin.prefix'     => 'property-extension',
    'plugin.version'    => '1.1.3',
    'plugin.filename'   => 'helpmee/index.php',
    'plugin.path'       => dirname(__DIR__),
    'plugin.viewpath'   => dirname(__DIR__).'/assets/views',
    'plugin.url'        => plugins_url().'/woning-website-uitbreiding',
    'plugin.php'        => '7.4',
    'plugin.textdomain' => 'property-extension',

    /**
     * Settings Provider
     */
    // 'settingsprovider'      => new SettingsProviderJson(dirname(__DIR__), '/config/settings.json'),
    'settingsprovider'      => new SettingsProviderOption(),

];
