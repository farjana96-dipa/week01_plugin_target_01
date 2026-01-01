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
/*
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
*/

/*------------- End CPT -------------*/

/*------------- Admin Menu + Submenu -------------*/


/*
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

*/
/* ------------- End Admin Menu -------------*/

/*

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

*/


add_action('wp_enqueue_scripts', 'dp_enqueue_frontend_scripts');

function dp_enqueue_frontend_scripts(){
    wp_enqueue_style(
        'dp-form-builder-style',
        plugin_dir_url(__FILE__) . 'assets/admin.css',
        [],
        '1.0.0'
    );
}



/*---------- Admin + Submenu ----------*/


add_action('admin_menu', 'dp_register_admin_menu');

function dp_register_admin_menu(){
    add_menu_page(
        'DP Custom Forms',
        'DP Custom Forms',
        'manage_options',
        'dp-form-plugin',
        'dp_main_admin_page',
        'dashicons-buddicons-pm'
    );

    add_submenu_page(
        'dp-form-plugin',
        'Create Form',
        'Create Form',
        'manage_options',
        'create-form',
        'dp_create_form_admin_page'
    );
}

function dp_main_admin_page(){
    include( plugin_dir_path(__FILE__) . 'admin/admin-page.php' );
}
function dp_create_form_admin_page(){
    include( plugin_dir_path(__FILE__) . 'admin/admin-page.php' );
}



/* -------- End Admin + Submenu ---------*/




/*---------- Admin Side Save Form ,,, It save form structure in the CPT dp_forms ( All Forms) ----------*/

add_action('admin_post_dp_save_form', 'dp_save_form');


function dp_save_form(){
    // Security check

    if( !isset($_POST['dp_nonce']) || !wp_verify_nonce($_POST['dp_nonce'], 'dp_save_form') ){
        wp_die('Security check failed');
    }

    if( !current_user_can('manage_options') ){
        wp_die('Permission_denied');
    }

    //Sanitize form name

    $form_name = sanitize_text_field($_POST['form_name']);

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
                'type' => sanitize_text_field($field['type']),
                'label' => sanitize_text_field($field['label']),
                'name' => sanitize_key($field['name']),
                'required' => isset($field['required']) ? 1 : 0,
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

/* -------- Shortcode to render form on frontend ---------*/

add_shortcode('dp_form', 'dp_render_form_shortcode');

function dp_render_form_shortcode($atts) {

    $atts = shortcode_atts([ 'id' => '' ], $atts);

    $form_id = intval($atts['id']);

    if ( ! $form_id ) {
        return '<p><strong>Form ID is missing.</strong></p>';
    }

    $fields = get_post_meta($form_id, '_dp_fields', true);

    if ( empty($fields) || ! is_array($fields) ) {
        return '<p><strong>No fields found for this form.</strong></p>';
    }



    ob_start();
    ?>
    <form method="post" class="dp-custom-form">
        <input type="hidden" name="dp_form_id" value="<?php echo esc_attr($form_id); ?>">
        <?php wp_nonce_field('dp_form_submit', 'dp_form_nonce'); ?>

        <?php foreach ( $fields as $field ) :   
            //echo '<pre>';
           // print_r($fields);
            //echo '</pre>';
         ?>

            <div class="dp-form-field">
                <label>
                    <?php echo esc_html($field['label']); ?>
                    <?php if ( ! empty($field['required']) ) echo ' <span class="required" style="color:red">*</span>'; ?>
                </label>

                <?php
               $required = ! empty($field['required']) ? 'required' : '';

                switch ( $field['type'] ) {
                   
                    case 'textarea':
                        echo '<textarea name="'.esc_attr($field['name']).'" '.$required.'></textarea>';
                        break;

                    default:
                        echo '<input type="'.esc_attr($field['type']).'" name="'.esc_attr($field['name']).'" '.$required.'>';
                }
                ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="dp-submit-button">Submit</button>
    </form>
    <?php
    return ob_get_clean();
}


add_action('init', 'dp_register_forms_cpt');

function dp_register_forms_cpt() {
    register_post_type('dp_forms', [
        'labels' => [
            'name' => 'All Forms',
            'singular_name' => 'Form',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'dp-form-plugin', // 🔥 attaches to your menu
        'supports' => ['title'],
    ]);
}





// Add Shortcode column
add_filter('manage_dp_forms_posts_columns', function ($columns) {
    $columns['shortcode'] = 'Shortcode';
    return $columns;
});

// Render Shortcode column
add_action('manage_dp_forms_posts_custom_column', function ($column, $post_id) {
    if ($column === 'shortcode') {
        echo '<code>[dp_form id="' . esc_attr($post_id) . '"]</code>';
    }
}, 10, 2);





/*--------- CPT for Form Submissions ---------*/

add_action('init', 'dp_custom_form_builder_cpt', 0);


function dp_custom_form_builder_cpt(){
    register_post_type('dp_form_submissions', [
        'labels' => [
            'name' => 'Form Submissions',
            'singular_name' => 'Form Submission',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'dp-form-plugin',
        'menu_icon' => 'dashicons-feedback',
        'supports' => ['title', 'editor']
    ]);
}

/* -------- End CPT ---------*/

add_action('init', 'dp_log_form_submissions');

function dp_log_form_submissions(){
    if( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dp_form_id'])){
        //Security check

        if(!isset($_POST['dp_form_nonce']) || !wp_verify_nonce($_POST['dp_form_nonce'], 'dp_form_submit')){
            wp_die('Security Check Failed');
        }

        $form_id = intval($_POST['dp_form_id']);

        $fields = get_post_meta($form_id, '_dp_fields', true);

        if(empty($fields) || !is_array($fields)){
            wp_die('No fields found for this form.');
        }


        $submission_data = [];
        foreach($fields as $field){
            $field_name =  $field['name'];
            if(isset($_POST[$field_name])){
                $value = sanitize_text_field($_POST[$field_name]);
                $submission_data[$field_name] = $value;
            }
        }

        //Insert submission post

        $submission_id = wp_insert_post(
            [
                'post_type' => 'dp_form_submissions',
                'post_title' => 'Submission for Form ID ' . $form_id,
               
                'post_status' => 'publish'
            ]
        );


        if(is_wp_error($submission_id)){
            wp_die($submission_id->get_error_message());
        }

         // Save meta
        update_post_meta($submission_id, '_dp_form_id', $form_id);
      

        foreach($submission_data as $key => $value){
            update_post_meta($submission_id, sanitize_key($key), $value);
        }



        wp_redirect(
            wp_get_referer() . '?submitted=1'
        );
        exit;
    }
}


add_filter('manage_dp_form_submission_posts_columns', function ($columns) {
    $columns['form'] = 'Form';
    return $columns;
});

add_action('manage_dp_form_submission_posts_custom_column', function ($column, $post_id) {
    if ($column === 'form') {
        $form_id = get_post_meta($post_id, '_dp_form_id', true);
        echo $form_id ? esc_html(get_the_title($form_id)) : '—';
    }
}, 10, 2);



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
