<?php


if(!defined('ABSPATH')) exit;

add_action('admin_menu', 'dp_register_admin_menu');

function dp_register_admin_menu(){
    add_menu_page(
        __('DP Property Listings', 'dp-property-listing'),
        __('DP Property Listings', 'dp-property-listing'),
        'manage_options',
        'dp-property-plugin',
        'dp_main_property_admin_page',
        'dashicons-admin-home'
    );
    add_submenu_page(
        'dp-property-plugin',
            __('Add New Property', 'dp-property-listing'),
            __('Add New Property', 'dp-property-listing'),
            'manage_options',
            'add-new-property',
            'dp_main_property_admin_page'
    );

    add_submenu_page(
        'dp-property-plugin',
        __('Property Listings', 'dp-property-listing'),
        __('Property Listings', 'dp-property-listing'),
        'manage_options',
        'dp-property-listings'

    );

    add_submenu_page(
        'dp-property-plugin',
        __('Metaboxes', 'dp-property-listing'),
        __('Metaboxes', 'dp-property-listing'),
        'manage_options',
        'dp-property-metaboxes'
    );
}

function dp_main_property_admin_page(){
    if ( file_exists( DP_PROPERTY_LISTING_PATH . 'admin/admin-page.php' ) ) {
    include DP_PROPERTY_LISTING_PATH . 'admin/admin-page.php';
}

}

function dp_add_new_property_admin_page(){
    if ( file_exists( DP_PROPERTY_LISTING_PATH . 'admin/admin-page.php' ) ) {
    include DP_PROPERTY_LISTING_PATH . 'admin/admin-page.php';
}

}