<?php


if(!defined('ABSPATH')) exit;

add_action('init', 'dp_register_property_cpt');

function dp_register_property_cpt(){
    $labels = array(
        'name' => __('Properties', 'dp-property-listing'),
        'singular_name' => __('Property', 'dp-property-listing'),
        'add_new' => __('Add New', 'dp-property-listing'),
        'add_new_item' => __('Add New Property', 'dp-property-listing'),
        'edit_item' => __('Edit Property', 'dp-property-listing'),
        'new_item' => __('New Property', 'dp-property-listing'),
        'view_item' => __('View Property', 'dp-property-listing'),
        'search_items' => __('Search Properties', 'dp-property-listing'),
        'not_found' => __('No properties found', 'dp-property-listing'),
        'not_found_in_trash' => __('No properties found in Trash', 'dp-property-listing'),
        'all_items' => __('All Properties', 'dp-property-listing'),
        'menu_name' => __('Properties', 'dp-property-listing'),
        'name_admin_bar' => __('Property', 'dp-property-listing'),
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'properties'),
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'menu_icon' => 'dashicons-admin-multisite',
        'show_in_rest' => true,
    );

    register_post_type('dp_property', $args);
}


// Register custom taxonomy for Property Type

add_action('init', 'dp_register_property_type_taxonomy');

function dp_register_property_type_taxonomy(){
    $args = array(
        'label' => __('Property Types', 'dp-property-listing'),
        'hierarchical' => true,
        'show_in_rest' => true
    );

    register_taxonomy('dp_property_type', 'dp_property', $args);
}

// Register custom taxonomy for Property Location

add_action('init', 'dp_register_property_location_taxonomy');

function dp_register_property_location_taxonomy(){
    $args = array(
        'label' => __('Property Locations', 'dp-property-listing'),
        'hierarchical' => true,
        'show_in_rest' => true
    );

    register_taxonomy('dp_property_location', 'dp_property', $args);
}






