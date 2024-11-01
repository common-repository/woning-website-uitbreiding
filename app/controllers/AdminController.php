<?php

namespace Tussendoor\PropertyWebsite\Controllers;

use Tussendoor\PropertyWebsite\App;
use Tussendoor\PropertyWebsite\Helpers\Hasher;

class AdminController
{
    protected $errors;

    public function setup()
    {
        if (!App::bound('active_integration')) {
            return;
        }

        add_action('admin_menu', [$this, 'createMenu'], 99);
        add_action('admin_enqueue_scripts', [$this, 'loadScripts']);
        add_action('load-post.php', [$this, 'metaboxSetup']);
    }

    /**
     * Creates the Wordpress dashboard menu
     */
    public function createMenu()
    {
        $module = App::get('active_integration');
        
        add_submenu_page(
            strtolower($module->getName()),
            'Woning Website',
            'Woning Website',
            'manage_options',
            App::get('plugin.prefix').'-settings',
            [$this, 'createSettingsPage']
        );
    }

    /**
     * Load scripts
     */
    public function loadScripts()
    {
        wp_register_style(
            App::get('plugin.prefix').'_admin_style',
            App::get('plugin.url').'/assets/css/admin.index.css',
            false
        );

        wp_enqueue_style(App::get('plugin.prefix').'_admin_style');

        wp_register_script(
            App::get('plugin.prefix').'_admin_tabs',
            App::get('plugin.url').'/assets/js/admin.tabs.js',
            ['jquery'],
            App::get('plugin.version'),
            true
        );
        
        wp_enqueue_script(App::get('plugin.prefix').'_admin_tabs');
    }

    /**
     * Creates the dashboard
     *
     */
    public function createSettingsPage()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'property-extension'));
        }

        include App::get('plugin.viewpath').'/admin.index.php';
    }

    /**
     * The setup to add the MetaBox and add the required CSS and validator
     */
    public function metaboxSetup()
    {
        add_action('add_meta_boxes', function () {
            global $post;

            $module = App::get('active_integration');

            if ($post->post_type != $module->getPosttype()) {
                return;
            }

            add_meta_box('wwExtMetabox', 'Woning Website', [$this,  'loadMetabox'], '', 'side', 'low');
        });
    }

    /**
     * The callback function that loads the form within the MetaBox
     * @param WP_Post $object The Wordpress post object
     * @param Array   $box
     */
    public function loadMetabox()
    {
        global $post;
        $user = wp_get_current_user();
        $hash = Hasher::encode([
            'access_key'    => Hasher::accessKey(),
            'username'      => $user->display_name,
            'email'         => $user->user_email,
            'wpid'          => $post->ID,
            'hash'          => uniqid(),
            'invoice_key'   => get_option(App::get('plugin.prefix').'_debtor_key', false),
            'referer'       => get_site_url(),
            'timestamp'     => time(),
        ]);

        $request = 'https://straatnaamhuisnummer.nl/order-pagina/?q='.$hash;

        require_once App::get('plugin.viewpath').'/admin.metabox.php';
    }
}
