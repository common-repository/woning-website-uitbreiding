<?php

namespace Tussendoor\PropertyWebsite\Controllers;

use Tussendoor\PropertyWebsite\App;
use Tussendoor\Settings\Manager as SettingsManager;

class SettingsController
{
    protected $optionName;

    /**
     * Set the option name we're watching. As there's only one, there's no need
     * to do something fancy here :).
     */
    public function __construct()
    {
        $this->optionName = App::get('plugin.prefix').'_debtor_key';
    }

    /**
     * Watch the request and check if it contains our option name.
     * @return bool
     */
    public function watch()
    {
        if ($this->requestHasOption() === false) {
            return false;
        }

        $manager = new SettingsManager(App::get('settingsprovider'));
        $manager->save($this->optionName, sanitize_text_field($_POST[$this->optionName]));

        
        return $this->wasSaved();
    }

    /**
     * Append our success message whenever the option was saved.
     * @return string
     */
    public function wasSaved()
    {
        add_filter('PropertyWebsiteUpdate', function ($messages) {
            return array_merge($messages, [[
                'status'    => true,
                'message'   => __('The paymentkey was successfuly added.', 'property-extension'),
            ]]);
        });
    }

    /**
     * Check if the $_POST array contains our option.
     * @return bool
     */
    protected function requestHasOption()
    {
        return isset($_POST['addDebtorKey']) && isset($_POST[$this->optionName]);
    }
}
