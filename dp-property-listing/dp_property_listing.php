<?php


/*

**
    * Plugin Name: DP Property Listing
    * Description: A plugin to manage property listings.
    * Version: 1.0.0
    * Author: Farjana Dipa
    * Text Domain: dp-property-listing
    * Domain Path: /languages


*/


if(!defined('ABSPATH')) exit;


define('DP_PROPERTY_LISTING_PATH', plugin_dir_path(__FILE__));
define('DP_PROPERTY_LISTING_URL', plugin_dir_url(__FILE__));


// Include necessary files

require_once DP_PROPERTY_LISTING_PATH . 'includes/dp-cpt-properties.php';
require_once DP_PROPERTY_LISTING_PATH . 'includes/admin-menu.php';
require_once DP_PROPERTY_LISTING_PATH . 'includes/dp-property-save.php';
require_once DP_PROPERTY_LISTING_PATH . 'includes/dp-shortcode.php';
require_once DP_PROPERTY_LISTING_PATH . 'includes/dp-listing.php';
require_once DP_PROPERTY_LISTING_PATH . 'includes/dp-metaboxes.php';

// enque scripts
add_action('admin_enqueue_scripts', 'dp_enqueue_admin_scripts');

function dp_enqueue_admin_scripts($hook) {

    if ($hook !== 'post.php' && $hook !== 'post-new.php') {
        return;
    }

    wp_enqueue_media();

    wp_enqueue_script(
        'dp-gallery-js',
        plugin_dir_url(__FILE__) . 'assets/js/gallery.js',
        array('jquery'),
        null,
        true
    );

    wp_enqueue_script(
        'dp-owner-js',
        plugin_dir_url(__FILE__) . 'assets/js/owner.js',
        ['jquery'],
        '1.0',
        true
    );
}





