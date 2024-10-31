<?php
/**
 * Enqueue all styles and scripts of PriorNotify
 * 
 * @package PriorNotify
 */

namespace PriorNotify\Base;

use \PriorNotify\Base\BaseController;

class Enqueue extends BaseController
{
    public function register()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue') );
    }

    /**
     * Enqueue all styles and scripts of PriorNotify
     */
    public function enqueue()
    {
        wp_enqueue_style( 'priornotifystyle', $this->plugin_url . 'assets/css/priorNotify.min.css');
        // wp_enqueue_script( 'priornotifyscript', $this->plugin_url . 'assets/js/priorNotify.js');
    }
}