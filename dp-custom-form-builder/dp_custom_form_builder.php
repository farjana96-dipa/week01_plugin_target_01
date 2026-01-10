<?php


/*

 * Plugin Name: DP Custom Form Builder
 * Description: Lightweight custom form builder with submissions management.
 * Version: 1.0.0
 * Author: Farjana Dipa
 * Text Domain: dp-custom-form-builder
 * Domain Path: /languages
 */

if(!defined('ABSPATH')) exit;

define('DP_CFB_PATH', plugin_dir_path(__FILE__));
define('DP_CFB_URL', plugin_dir_url(__FILE__));


//Include necessary files

require_once DP_CFB_PATH . 'includes/cpt-forms.php';
require_once DP_CFB_PATH . 'includes/cpt-submissions.php';
require_once DP_CFB_PATH . 'includes/admin-menu.php';
require_once DP_CFB_PATH . 'includes/form-save.php';
require_once DP_CFB_PATH . 'includes/form-submit.php';
require_once DP_CFB_PATH . 'includes/shortcode.php';


add_filter( 'admin_title', function ( $title ) {
    return is_string( $title ) ? $title : '';
}, 10, 1 );
 


add_action('wp_enqueue_scripts', 'dp_enqueue_frontend_scripts');

function dp_enqueue_frontend_scripts(){
    wp_enqueue_style(
        'dp-form-builder-style',
        plugin_dir_url(__FILE__) . 'assets/admin.css',
        [],
        '1.0.0'
    );
}



