<?php

namespace Tussendoor\Settings\Providers;

class SettingsProviderJson implements SettingsProviderInterface
{

    protected $path;
    protected $storage;
    protected $filename;

    public function __construct($path, $filename, $mask = 0755)
    {
        $this->path = $path;
        $this->filename = $filename;

        if ($this->prepareStorage()) {
            $this->storage = $this->getFile();
        }
    }

    /**
     * Get a setting from the storage
     * @param  string   $name
     * @param  mixed    $default Default value te return if $name does not exist
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ($this->has($name)) {
            return $this->storage[$name];
        }

        return $default;
    }

    /**
     * Get all stored settings
     * @return array
     */
    public function getAll()
    {
        return $this->storage;
    }

    /**
     * Check if the storage contains a setting called $name
     * @param  string   $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->storage);
    }

    /**
     * Save a setting to the storage
     * @param  string   $name
     * @param  mixed    $data
     * @return bool
     */
    public function save($name, $data)
    {
        $this->storage[$name] = $data;
        return $this->toFile();
    }

    /**
     * Update an existing setting. Basically save with an additional has() check.
     * @param  string   $name
     * @param  mixed    $data
     * @return bool
     */
    public function update($name, $data)
    {
        if ($this->has($name)) {
            return $this->save($name, $data);
        }

        return false;
    }

    /**
     * Delete a setting from the storage
     * @param  string   $name
     * @return bool
     */
    public function delete($name)
    {
        if ($this->has($name)) {
            unset($this->storage[$name]); //Unset returns void

            return $this->toFile();
        }

        return false;
    }

    /**
     * Write the array storage to the configured json file
     * @return bool
     */
    private function toFile()
    {
        $encoded = json_encode($this->storage);
        if ($this->hasJsonError()) {
            throw new \Exception('Unable to parse settings storage!');
        }

        $written = file_put_contents($this->path.$this->filename, $encoded, LOCK_EX);
        return ($written === false ? false : true);
    }

    /**
     * Prepare the storage by checking the existance of the directory
     * and creating the file if it does not exist.
     * @param  int  $mask   The writing mode
     * @return bool
     */
    private function prepareStorage()
    {
        if (file_exists($this->path.$this->filename)) {
            return true;
        }

        return touch($this->path.$this->filename);
    }

    /**
     * Load the storage file to memory and convert it to an array.
     * @return array
     * @throws \Exception   Throws an exception if the json could not be parsed.
     */
    private function getFile()
    {
        if (!file_exists($this->path.$this->filename)) {
            throw new \Exception('Settings file not found!');
        }

        $contents = file_get_contents($this->path.$this->filename);
        if (empty($contents)) {
            return [];
        }

        $decoded = json_decode($contents, true);
        if ($this->hasJsonError()) {
            throw new \Exception('Unable to parse settings storage!');
        }

        return $decoded;
    }

    /**
     * Determine if there was an error while parsing json
     * @return bool
     */
    private function hasJsonError()
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            return true;
        }

        return false;
    }
}
