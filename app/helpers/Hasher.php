<?php

namespace Tussendoor\PropertyWebsite\Helpers;

class Hasher
{
    public static function decode($hash)
    {
        $hash = str_pad(strtr($hash, '-_', '+/'), strlen($hash) % 4, '=', STR_PAD_RIGHT);

        return json_decode(gzinflate(base64_decode($hash)), true);
    }


    public static function encode($fields)
    {
        $hash = base64_encode(gzdeflate(json_encode($fields)));

        return rtrim(strtr($hash, '+/', '-_'), '=');
    }

    /**
     * Generate or retrieve the access key to the API. If no key was found,
     * a new one is generated. Note that this key is not cryptographically secure.
     * @return string
     */
    public static function accessKey()
    {
        $existing = get_option('_property_website_key');
        if (empty($existing)) {
            $existing = implode('-', str_split(uniqid('pw'), 5));
            update_option('_property_website_key', $existing);
        }

        return $existing;
    }
}
