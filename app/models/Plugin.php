<?php

namespace Tussendoor\PropertyWebsite;

class Plugin
{
    public function boot()
    {
        $this->discoverIntegration();
        $this->loadTranslations();

        $this->setActions();
    }

    protected function setActions()
    {
        if (is_admin()) {
            (new Controllers\AdminController)->setup();
        }

        add_action('init', [new Controllers\SettingsController, 'watch']);
        add_action('rest_api_init', [(new Controllers\EndpointsController), 'setup']);

        add_action('admin_init', function () {
            if (!App::bound('active_integration')) {
                $this->displayErrorNotice();
            }
        });
    }

    protected function discoverIntegration()
    {
        $compatability = new Helpers\Compatibility();
        $modules = $compatability->getModules();

        foreach ($modules as $module) {
            $module = new $module();
            if ($module->isActive() === false) {
                continue;
            }

            App::bind('active_integration', $module);
        }
    }

    protected function loadTranslations()
    {
        return load_plugin_textdomain(
            'property-extension',
            false,
            basename(dirname(__FILE__)) . '/lang/'
        );
    }


    protected function displayErrorNotice()
    {
        add_action('admin_notices', function () {
            ?>
            <div class="notice notice-error">
                <p>
                    <strong>
                            <?php echo __('The "Woning Website Uitbreiding" is not fully activated!'); ?>
                    </strong>
                    <br>
                    <?php
                        printf(
                            __('There was no active and/or supported realtor plugin found. Please take a look at %sthe list of supported realtor plugins%s or %scontact us for assitance%s.', 'property-extension'),
                            '<a href="https://tussendoor.nl/wordpress-makelaar-koppeling" target="_BLANK">',
                            '</a>',
                            '<a href="https://tussendoor.nl/contact/" target="_BLANK">',
                            '</a>'
                        ); ?>
                </p>
            </div>
            <?php
        });
    }
}
