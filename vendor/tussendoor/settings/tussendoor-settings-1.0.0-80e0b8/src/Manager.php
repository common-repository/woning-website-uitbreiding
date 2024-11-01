<?php

namespace Tussendoor\Settings;

use Tussendoor\Settings\Providers\SettingsProviderOption;
use Tussendoor\Settings\Providers\SettingsProviderInterface;

class Manager
{

    protected $provider;

    /**
     * Provide a SettingsProvider to initialize the manager. Defaults to option if none is given.
     * @param SettingsProviderInterface|null $provider
     */
    public function __construct(SettingsProviderInterface $provider = null)
    {
        $this->provider = $provider ?: new SettingsProviderOption;
    }

    /**
     * Return the value of an setting
     * @param  string       $name
     * @param  mixed|null   $default
     * @return mixed|null               Setting value or null by default.
     */
    public function get($name, $default = null)
    {
        return $this->provider->get($name, $default);
    }

    /**
     * Determine if a setting exists within the current provider.
     * @param  string  $name
     * @return bool
     */
    public function has($name)
    {
        return $this->provider->has($name);
    }

    /**
     * Get all the settings from the settings provider
     * @return array
     */
    public function getAll()
    {
        return $this->provider->getAll();
    }

    /**
     * Save a setting to the ServiceProvider.
     * @param  string $name
     * @param  mixed  $data
     * @return bool
     */
    public function save($name, $data = null)
    {
        // We'll assume the developer is taking a shortcut
        // by supplying a associative array with settings.
        if ($this->isTraversable($name)) {
            return $this->saveBulk($name);
        }

        $settingName = sanitize_text_field($name);
        $sanitized = $this->sanitizeData($data);

        return $this->provider->save($settingName, $sanitized);
    }

    /**
     * Delete an option from the SettingsProvider.
     * @param  string $name
     * @return bool
     */
    public function delete($name)
    {
        return $this->provider->delete($name);
    }

    /**
     * Sanitize the supplied data. Applies sanitization recursivly to arrays.
     * Does not sanitize objects or resources!
     * @param  mixed $data
     * @return mixed
     */
    private function sanitizeData($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'escapeArray'], $data);
        }

        if (is_object($data) || is_resource($data)) {
            throw new \Exception('Objects and/or resources are not supported!');
        }

        return sanitize_text_field($data);
    }

    /**
     * Callback method for recursivly sanitizing an array
     * @param  mixed $value array/stirng
     * @return mixed
     */
    public function escapeArray($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'escapeArray'], $value);
        }

        return sanitize_text_field($value);
    }

    /**
     * Figure out if the supplied data is traversable.
     * @param  mixed  $data
     * @return boolean
     */
    private function isTraversable($data)
    {
        return is_array($data) || is_object($data);
    }

    /**
     * Save an associative array to the SettingsProvider
     * @param  Traversable $data
     * @return bool
     * @todo   Make $data actually an instance of a Traversable object
     */
    private function saveBulk($data)
    {
        $settings = (is_array($data) ? $data : get_object_vars($data));

        foreach ($settings as $name => $setting) {
            $this->provider->save(
                sanitize_text_field($name),
                (is_array($setting) ? $this->escapeArray($setting) : sanitize_text_field($setting))
            );
        }

        return true;
    }
}
