<?php

namespace Tussendoor\Settings\Providers;

interface SettingsProviderInterface
{

    public function get($name, $default = null);

    public function has($name);

    public function save($name, $data);
    
    public function update($name, $default);

    public function delete($name);
}
