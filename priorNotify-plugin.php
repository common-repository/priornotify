<?php

/**
 * @package PriorNotify
 */

/*
Plugin Name: PriorNotify
Plugin URI: https://rudicoder.com/priornotify/
Description: PriorNotify
Version: 1.0.7
Author: RudiCoder LLC
License: GPLv2 or later
Text Domain: priornotify-plugin
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// If this file is called directly, abort!!!
defined('ABSPATH') or die('ABSPATH is not defined');

// Require once composer autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

function activate_proireNotify()
{
    $need = false;

    if ( is_multisite() ) {
        if ( is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {
            $need = is_plugin_active_for_network('woocommerce/woocommerce.php') ? false : true; 
        } else {
            $need = is_plugin_active( 'woocommerce/woocommerce.php')  ? false : true;   
        }
    } else {
        $need =  is_plugin_active( 'woocommerce/woocommerce.php') ? false : true;  
    }

    if ($need) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( __( 'Please activate WooCommerce.', 'my-plugin' ), 'Plugin dependency check', array( 'back_link' => true ) );
    } else {
        if ( version_compare( WC_VERSION, '5.2', '<' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( 'Installation failed. This plugin requires WooCommerce v5.2 or later.', 'my-plugin' ), 'Plugin dependency check', array( 'back_link' => true ) );
        }
    }
}
register_activation_hook( __FILE__, 'activate_proireNotify' );

remove_filter('the_content', 'wptexturize');

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists('PriorNotify\\Init') ) {
    PriorNotify\Init::register_services();
    PriorNotify\Init::initScriptUrls();
}
