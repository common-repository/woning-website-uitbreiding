<?php

namespace Tussendoor\PropertyWebsite\Helpers;

use Tussendoor\PropertyWebsite\Realworks3\Module as Realworks3;
use Tussendoor\PropertyWebsite\Realworks4\Module as Realworks4;

class Compatibility
{
    public function getModules()
    {
        return apply_filters('_property_website_modules', [
            Realworks3::class,
            Realworks4::class,
        ]);
    }
}
