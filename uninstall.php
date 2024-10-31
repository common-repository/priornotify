<?php
/**
 * Delete PriorNotify
 * 
 * @package PriorNotify
 */

if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
    die;
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * global $wpdb
 * $wpdb->query("DELETE FROM ...");
 */