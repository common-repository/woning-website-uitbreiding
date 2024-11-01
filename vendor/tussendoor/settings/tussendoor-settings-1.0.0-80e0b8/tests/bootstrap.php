<?php

require 'vendor/autoload.php';

/**
 * Mock all WordPress functions.
 */

$setting = [];

function get_option($name, $default = null)
{
    global $setting;

    return array_key_exists($name, $setting) ? $setting[$name] : $default;
}


function update_option($name, $value)
{
    global $setting;
    $setting[$name] = $value;

    return true;
}


function delete_option($name)
{
    global $setting;

    if (array_key_exists($name, $setting)) {
        unset($setting[$name]);
        return true;
    }

    return false;
}


function wp_load_alloptions()
{
    global $setting;
    return $setting;
}

function sanitize_text_field($string)
{
    return $string;
}
