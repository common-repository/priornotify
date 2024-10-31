<?php
/**
 * Setup admin panel for PriorNotify
 * 
 * @package PriorNotify
 */

namespace PriorNotify\Pages;

use \PriorNotify\Base\BaseController;
use \PriorNotify\Api\SettingsApi;
use \PriorNotify\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{
    public $settings;
    public $pages;
    public $callbacks;

    /*
     * Set up pages of PriorNotify and register them
     */
    public function register()
    {
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setPages();
        $this->settings->addPages( $this->pages )->register();
    }

    /*
     * Create the pages of PriorNotify plugin
     */ 
    public function setPages()
    {
        $this->pages = [
            [
                'page_title' => $this->config["admin"]["pageTitle"], 
                'menu_title' => $this->config["admin"]["menuTitle"], 
                'capability' => 'manage_options', 
                'menu_slug' => 'priorNotify', 
                'callback' => array( $this->callbacks, 'adminDashboard' ),
                'icon_url' => 'dashicons-admin-site-alt3',
                'position' => 110
            ]
        ];
    }
}