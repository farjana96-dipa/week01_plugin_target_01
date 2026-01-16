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



function dp_enqueue_gallery_slider() {
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
    );

   wp_enqueue_script(
    'swiper-js',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
    [],
    '11.0.0',
    true
    );
}
add_action('wp_enqueue_scripts', 'dp_enqueue_gallery_slider');

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


add_action('wp_enqueue_scripts', 'dp_enqueue_frontend_assets');

function dp_enqueue_frontend_assets() {

  
      wp_enqueue_style(
        'dp-single-property',
        DP_PROPERTY_LISTING_URL . 'assets/css/single_property.css',
        [],
        '1.0.0'
    );

      wp_enqueue_style(
        'dp-property-frontend',
        DP_PROPERTY_LISTING_URL . 'assets/css/listing.css',
        [],
        '1.0.0'
    );

      wp_enqueue_style(
        'dp-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    
}





add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
        [],
        '5.3.2'
    );

    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.2',
        true
    );
   
}, 99); // load LAST






