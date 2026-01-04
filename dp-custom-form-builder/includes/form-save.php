<?php

if(!defined('ABSPATH')) exit;


add_action('admin_post_dp_save_form', 'dp_save_form');


function dp_save_form(){
    // Security check

    if( !isset($_POST['dp_nonce']) || !wp_verify_nonce($_POST['dp_nonce'], 'dp_save_form') ){
         wp_die( esc_html__( 'Security check failed.', 'dp-custom-form-builder' ) );
    }

    if( !current_user_can('manage_options') ){
        wp_die( esc_html__( 'You are not allowed to do this.', 'dp-custom-form-builder' ) );
    }

    // Validate form name
    if ( empty( $_POST['form_name'] ) ) {
        wp_die( esc_html__( 'Form name is required.', 'dp-custom-form-builder' ) );
    }

    //Sanitize form name

   // $form_name = sanitize_text_field($_POST['form_name']);

    $form_name = sanitize_text_field( wp_unslash( $_POST['form_name'] ) );

    //insert form post

    $form_id = wp_insert_post([
        'post_type' => 'dp_forms',
        'post_title' => $form_name,
        'post_status' => 'publish'
    ]);

    if( is_wp_error($form_id)){
        wp_die($form_id->get_error_message());
    }

    // Sanitize fields

    $fields = [];

    if( isset($_POST['fields']) && is_array($_POST['fields']) ){
        foreach( $_POST['fields'] as $field ){
          
              
            $fields[] = [
                'type'     => sanitize_text_field( $field['type'] ?? '' ),
                'label'    => sanitize_text_field( $field['label'] ?? '' ),
                'name'     => sanitize_key( $field['name'] ?? '' ),
                'required' => ! empty( $field['required'] ) ? 1 : 0,
            ];
            
        }
    }

    update_post_meta($form_id, '_dp_fields', $fields);

    // REDIRECT VERY IMPORTANT

    wp_redirect(
        admin_url('admin.php?page=create-form&saved=1')
    );

    exit;
}