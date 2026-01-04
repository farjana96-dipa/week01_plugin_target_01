<?php


if(!defined('ABSPATH')) exit;


add_action('init', 'dp_register_forms_cpt');

function dp_register_forms_cpt() {
    register_post_type('dp_forms', [
        'labels' => [
              'name'          => __( 'All Forms', 'dp-custom-form-builder' ),
            'singular_name' => __( 'Form', 'dp-custom-form-builder' ),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'dp-form-plugin', // Attaches with menu
        'supports' => ['title'],
    ]);
}


?>