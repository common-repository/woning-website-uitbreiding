<?php

namespace Tussendoor\PropertyWebsite\Helpers;

class RateLimiter
{
    protected $list = [];

    protected $optionKey = '_property_website_rate_limit';

    public function __construct()
    {
        $this->list = get_option($this->optionKey, []);
        if (!is_array($this->list)) {
            $this->list = [];
        }
    }
    

    public function add($ipaddress)
    {
        if (isset($this->list[$ipaddress])) {
            $this->list[$ipaddress]++;

            return $this->persist();
        }

        $this->list[$ipaddress] = 1;

        return $this->persist();
    }


    public function remove()
    {
        if (isset($this->list[$ipaddress])) {
            unset($this->list[$ipaddress]);
        }

        return $this->persist();
    }


    public function isBlocked($ipaddress)
    {
        return isset($this->list[$ipaddress]) && $this->list[$ipaddress] >= 3;
    }


    protected function persist()
    {
        update_option($this->optionKey, $this->list);

        return $this;
    }
}
