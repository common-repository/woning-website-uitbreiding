<?php namespace Tussendoor\PropertyWebsite;

use Carbon\Carbon;

// Load the Composer autoload file
require 'vendor/autoload.php';

// Set some sensible defaults
Carbon::setLocale('nl');
setlocale(LC_TIME, 'nl_NL');
date_default_timezone_set('Europe/Amsterdam');

// Bind some environment information to our app
App::bind('debug', WP_DEBUG);

// Load all configuration files into the App class
App::loadFromConfig(__DIR__.'/config/plugin.php');