<?php


/*

Plugin Name: DP Custom Form Builder
Description: Custom form builder plugin with submissions CPT
Version: 1.0
Author: Farjana Dipa
text-domain: dp-custom-form-builder



*/


if(!defined('ABSPATH')) exit;


/*------------- CPT for Form Submissions -------------*/

add_action('init', 'dp_custom_form_submission_cpt', 0);


function dp_custom_form_submission_cpt(){
    register_post_type('dp_form_builder', [
        'labels' => [
            'name' => 'Form Submissions',
            'singular_name' => 'Form Submission',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title', 'editor'],
    ]);
}


/*------------- End CPT -------------*/

/*------------- Admin Menu + Submenu -------------*/

// Hook admin menu
add_action('admin_menu', 'dp_register_admin_menu');

function dp_register_admin_menu() {

    add_menu_page(
        'DP Custom Forms',
        'DP Custom Forms',
        'manage_options',
        'dp-form-plugin',
        'dp_main_admin_page',
        'dashicons-feedback'
    );

    add_submenu_page(
        'dp-form-plugin',
        'Create Form',
        'Create Form',
        'manage_options',
        'create-form',
        'dp_create_form_page'
    );
}

function dp_main_admin_page() {
    include plugin_dir_path(__FILE__) . 'admin/admin-page.php';
}

function dp_create_form_page() {
    include plugin_dir_path(__FILE__) . 'admin/admin-page.php';
}
/* ------------- End Admin Menu -------------*/



function dp_save_form() {

    // Security check
    if (
        ! isset($_POST['dp_nonce']) ||
        ! wp_verify_nonce($_POST['dp_nonce'], 'dp_save_form')
    ) {
        wp_die('Security check failed');
    }

    if ( ! current_user_can('manage_options') ) {
        wp_die('Permission denied');
    }

    // Sanitize form name
    $form_name = sanitize_text_field($_POST['form_name']);

    // Insert form post
    $form_id = wp_insert_post([
        'post_type'   => 'dp_form_builder',
        'post_title'  => $form_name,
        'post_status' => 'publish'
    ]);

    if ( is_wp_error($form_id) ) {
        wp_die($form_id->get_error_message());
    }

    // Sanitize fields
    $fields = [];

    if ( isset($_POST['fields']) && is_array($_POST['fields']) ) {
        foreach ($_POST['fields'] as $field) {
            $fields[] = [
                'type'     => sanitize_text_field($field['type']),
                'label'    => sanitize_text_field($field['label']),
                'name'     => sanitize_key($field['name']),
                'required' => isset($field['required']) ? 1 : 0,
            ];
        }
    }

    update_post_meta($form_id, '_dp_fields', $fields);

    // 🔁 REDIRECT (VERY IMPORTANT)
        wp_redirect(
            admin_url('admin.php?page=create-form&saved=1')
        );
        exit;

}
add_action('admin_post_dp_save_form', 'dp_save_form');