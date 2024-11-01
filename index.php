<?php
/**
 * Plugin Name:       Woning Website Uitbreiding
 * Plugin URI:        https://tussendoor.nl/wordpress-plugins/makelaar-addon-eigen-woning-website
 * Description:       Creëer een eigen -unieke- website voor iedere woning die je verkoopt, met een eigen domeinnaam en zelfs eigen visitekaartjes als je dat wilt.
 * Version:           1.1.3
 * Author:            Tussendoor internet & marketing
 * Author URI:        https://www.tussendoor.nl
 * Text Domain:       property-extension
 * Domain Path:       /lang
 * Requires PHP:      7.4
 * Requires at least: 5.9
 * Tested up to:      6.1.1
 */

namespace Tussendoor\PropertyWebsite;

if (!defined('WPINC')) {
    exit;
}

require __DIR__.'/bootstrap.php';

$plugin = new Plugin();
add_action('plugins_loaded', [$plugin, 'boot']);
