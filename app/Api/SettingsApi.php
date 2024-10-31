<?php

/**
 * Plugin Settigns
 * 
 * @package PriorNotify
 */
namespace PriorNotify\Api;

class SettingsApi
{
    /**
     * Array of all priorNotify plugin pages
     *
     * @var array
     */
    public $admin_pages = array();
    /**
     * Array of all priorNotify plugin sections
     *
     * @var array
     */
    public $sections = array();
    /**
     * Array of all priorNotify plugin fields
     *
     * @var array
     */
    public $fields = array();
    /**
     * Array of all priorNotify plugin settings
     *
     * @var array
     */
    public $settings = array();

    /**
     * Set a hook before the administration menu loads in the admin
     */
    public function register()
    {
        if ( ! empty( $this->admin_pages ) ) {
            add_action( 'admin_menu', array( $this, 'addAdminMenu' ));
        }
    }

    /**
     * Set array of pages of PriorNotify plugin
     * 
     * @param array pages of plugin
     */
    public function addPages( array $pages )
    {
        $this->admin_pages = $pages;
        return $this;
    }

    /**
     * Add a Top-Level Menu Items
     */
    public function addAdminMenu()
    {
        foreach ($this->admin_pages as $page) {
            add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], 
                $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
        }
    }

    /**
     * Set array of settings of PriorNotify plugin
     * 
     * @param array settings of plugin
     */
    public function setSettings( array $settings )
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Set array of sections of PriorNotify plugin
     * 
     * @param array sections of plugin
     */
    public function setSections( array $sections )
    {
        $this->sections = $sections;
        return $this;
    }

    /**
     * Set array of fields of PriorNotify plugin
     * 
     * @param array fields of plugin
     */
    public function setFields( array $fields )
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Register a setting and its data if they are exists
     * Add the new sections to a settings page if any exists
     * Add the new fields to a sections of a settings page
     */
    public function registerCustomFields()
    {
        // register settings
        foreach ($this->settings as $setting) {
            register_setting( $setting['option_group'], $setting['option_name'], 
                ( isset($setting['callback']) ? $setting['callback'] : '' ) );
        }

        // add settings section
        foreach ($this->sections as $section) {
            add_settings_section( $section['id'], $section['title'], 
                ( isset($section['callback']) ? $section['callback'] : '' ), $section['page'] );
        }

        // add settings field
        foreach ($this->fields as $field) {
            add_settings_field( $field['id'], $field['title'], 
                ( isset($field['callback']) ? $field['callback'] : '' ), 
                $field['page'], $field['section'],   
                ( isset($field['args']) ? $field['args'] : '' ) );
        }
    }
}