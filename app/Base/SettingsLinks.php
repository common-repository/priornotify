<?php

/**
 * Set up PriorNotify active links
 * 
 * @package PriorNotify
 */
namespace PriorNotify\Base;

use \PriorNotify\Base\BaseController;

class SettingsLinks extends BaseController
{
    /*
     * Register action links displayed for PriorNotify
     */
    public function register()
    {
        add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_links') );
    }

    /**
     * Add plugin action links.
     * Add a link to the settings page on the plugins.php page.
     *
     * @param  array  $links List of existing plugin action links.
     * @return array         List of modified plugin action links.
     */
    public function settings_links($links)
    {
        $link = '<a href="admin.php?page=priorNotify">Settings</a>';
        array_push( $links, $link );
        return $links;
    }
}