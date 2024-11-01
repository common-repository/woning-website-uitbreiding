<?php

namespace Tussendoor\Settings\Providers;

class SettingsProviderOption implements SettingsProviderInterface
{

    /**
     * Get a setting from the storage
     * @param  string   $name
     * @param  mixed    $default Default value te return if $name does not exist
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return get_option($name, $default);
    }

    /**
     * Check if the storage contains a setting called $name
     * @param  string   $name
     * @return bool
     */
    public function has($name)
    {
        return !is_null($this->get($name, null));
    }

    /**
     * Save a setting to the storage
     * @param  string   $name
     * @param  mixed    $data
     * @return bool
     */
    public function save($name, $data)
    {
        return update_option($name, $data);
    }

    /**
     * Update an existing setting. Basically save with an additional has() check.
     * @param  string   $name
     * @param  mixed    $data
     * @return bool
     */
    public function update($name, $data)
    {
        return $this->save($name, $data);
    }

    /**
     * Delete a setting from the storage
     * @param  string   $name
     * @return bool
     */
    public function delete($name)
    {
        return delete_option($name);
    }

    /**
     * Load all options from the options table or cache.
     * @return array
     */
    public function getAll()
    {
        return wp_load_alloptions();
    }
}
