<?php


if(!defined('ABSPATH')) exit;




add_action('init', 'dp_custom_form_builder_cpt', 0);

function dp_custom_form_builder_cpt() {
    $labels = [
        'name'               => __('Form Submissions', 'dp-custom-form-builder'),
        'singular_name'      => __('Form Submission', 'dp-custom-form-builder'),
        'menu_name'          => __('Form Submissions', 'dp-custom-form-builder'),
        'add_new'            => __('Add Submission', 'dp-custom-form-builder'),
        'add_new_item'       => __('Add New Submission', 'dp-custom-form-builder'),
        'edit_item'          => __('Edit Submission', 'dp-custom-form-builder'),
        'view_item'          => __('View Submission', 'dp-custom-form-builder'), // Will show in admin list
        'all_items'          => __('All Submissions', 'dp-custom-form-builder'),
        'search_items'       => __('Search Submissions', 'dp-custom-form-builder'),
        'not_found'          => __('No submissions found', 'dp-custom-form-builder'),
        'not_found_in_trash' => __('No submissions found in Trash', 'dp-custom-form-builder')
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false,          // keep it private
        'show_ui'            => true,           // show admin menu
        'show_in_menu'       => 'dp-form-plugin', 
        'menu_icon'          => 'dashicons-feedback',
        'supports'           => ['title'],      // we don’t need editor for now
        'capability_type'    => 'post',
        'has_archive'        => false,
    ];

    register_post_type('dp_form_submissions', $args);
}



add_action('init', 'dp_log_form_submissions');

function dp_log_form_submissions(){
    if( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dp_form_id'])){
        //Security check

        if(!isset($_POST['dp_form_nonce']) || !wp_verify_nonce($_POST['dp_form_nonce'], 'dp_form_submit')){
             wp_die( esc_html__( 'Security check failed.', 'dp-custom-form-builder' ) );
        }

         $form_id = absint( $_POST['dp_form_id'] );

        if ( ! $form_id ) {
        wp_die( esc_html__( 'Invalid Form ID.', 'dp-custom-form-builder' ) );
        }

        $fields = get_post_meta($form_id, '_dp_fields', true);

        if(empty($fields) || !is_array($fields)){
            wp_die( esc_html__( 'No fields found for this form.', 'dp-custom-form-builder' ) );
        }


        $submission_data = [];
        foreach($fields as $field){
            $field_name =  $field['name'];

            $value = isset($_POST[$field_name]) ? $_POST[$field_name] : '';



             if ( $value !== null ) {
               $submission_data[$field_name] = sanitize_text_field((string) $value);
            }
        }

         // Insert submission post
            $submission_id = wp_insert_post( [
                'post_type'   => 'dp_form_submissions',
                'post_title'  => sprintf( __( 'Submission for Form ID %d', 'dp-custom-form-builder' ), $form_id ),
                'post_status' => 'publish',
            ] );


        if(is_wp_error($submission_id)){
            wp_die($submission_id->get_error_message());
        }

        //Save submission data as post meta

        foreach($submission_data as $key => $value){
            update_post_meta($submission_id, sanitize_key($key), $value);
        }



        wp_redirect(
            wp_get_referer() . '?submitted=1'
        );
        exit;
    }
}





/*

//Meta boxes for submission details

add_action('add_meta_boxes', 'dp_add_submission_metabox');

function dp_add_submission_metabox() {

    add_meta_box(
        'dp_submission_data',
        'Form Submission Data',
        'dp_render_submission_metabox',
        'dp_form_submissions',
        'normal',
        'high'
    );
}


function dp_render_submission_metabox($post) {

    $meta = get_post_meta($post->ID);

    echo '<div class="dp-submission-data">';

    foreach ($meta as $key => $value) {

        // Skip internal/meta system fields
        if ( strpos($key, '_') === 0 ) continue;

        echo '<p>
                <strong>' . esc_html(ucwords(str_replace('_', ' ', $key))) . ':</strong>
                ' . esc_html($value[0]) . '
              </p>';
    }

    echo '</div>';
}
*/
 
