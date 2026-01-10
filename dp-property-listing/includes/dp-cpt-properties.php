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

// Register Metaboxes for Property Features

add_action('add_meta_boxes', 'dp_add_property_metaboxes');

function dp_add_property_metaboxes(){
    add_meta_box(
        'dp_property_features',
        __('Property Features', 'dp-property-listing'),
        'dp_property_features_metabox_callback',
        'dp_property',
        'normal',
        'high'
    );
}


function dp_property_features_metabox_callback($post){
    $price = get_post_meta($post->ID, 'dp_price', true);
    $bedrooms = get_post_meta($post->ID, 'dp_bedrooms', true);
    $bathrooms = get_post_meta($post->ID, 'dp_bathrooms', true);
    $area = get_post_meta($post->ID, 'dp_area', true);
    $lift = get_post_meta($post->ID, 'dp_lift', true);
    $parking = get_post_meta($post->ID, 'dp_parking', true);
    $sale_type = get_post_meta($post->ID, 'dp_sale_type', true);


    ?>
       <p>
        <label>Price</label>
        <input type="text" name="dp_price" value="<?php echo esc_attr($price); ?>" />
       </p>
       <p>
        <label>Bedrooms</label>
        <input type="number" name="dp_bedrooms" value="<?php echo esc_attr($bedrooms); ?>" />
       </p>
       <p>
        <label>Bathrooms</label>
        <input type="number" name="dp_bathrooms" value="<?php echo esc_attr($bathrooms); ?>" />
       </p>
       <p>
        <label>Area Size (sqft)</label>
        <input type="text" name="dp_area" value="<?php echo esc_attr($area); ?>" />
       </p>
       <p>
        <label>Lift</label>
        <select name="dp_lift">
            <option value="">Select</option>
            <option value="yes" <?php selected($lift, 'yes'); ?>> Yes </option>
            <option value="no" <?php selected($lift, 'no'); ?>> No </option>
        </select>
       </p>
        <p>
        <label>Parking</label>
        <select name="dp_parking">
            <option value="">Select</option>
            <option value="yes" <?php selected($parking, 'yes'); ?>> Yes </option>
            <option value="no" <?php selected($parking, 'no'); ?>> No </option>
        </select>
       </p>
        <p>
        <label>Sale Type</label>
        <select name="dp_sale_type">
            <option value="">Select</option>
            <option value="sale" <?php selected($sale_type, 'sale'); ?>> For Sale </option>
            <option value="rent" <?php selected($sale_type, 'rent'); ?>> For Rent </option>
        </select>
       </p>
    <?php
}


// Save Metabox Data

add_action('save_post', 'dp_save_property_metabox_data');

function dp_save_property_metabox_data($post_id){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['dp_price'])){
        update_post_meta($post_id,'dp_price', sanitize_text_field($_POST['dp_price']));
    }
    if(isset($_POST['dp_bedrooms'])){
        update_post_meta($post_id,'dp_bedrooms', intval($_POST['dp_bedrooms']));
    }
    if(isset($_POST['dp_bathrooms'])){
        update_post_meta($post_id,'dp_bathrooms', intval($_POST['dp_bathrooms']));
    }
    if(isset($_POST['dp_area'])){
        update_post_meta($post_id,'dp_area', sanitize_text_field($_POST['dp_area']));
    }
    if(isset($_POST['dp_lift'])){
        update_post_meta($post_id,'dp_lift', sanitize_text_field($_POST['dp_lift']));
    }
    if(isset($_POST['dp_parking'])){
        update_post_meta($post_id,'dp_parking', sanitize_text_field($_POST['dp_parking']));
    }
    if(isset($_POST['dp_sale_type'])){          
        update_post_meta($post_id,'dp_sale_type', sanitize_text_field($_POST['dp_sale_type']));
    }
    
}