<?php
/*
Plugin Name: DP Contact Form
Description: Contact form plugin with submissions CPT
Version: 1.0
Author: Farjana Dipa
*/

if ( ! defined('ABSPATH') ) exit;

/*--------------------------------------------------------------
# CONTACT SUBMISSION CPT
--------------------------------------------------------------*/
add_action('init', 'dp_contact_form_submission_cpt', 0);

function dp_contact_form_submission_cpt() {
    register_post_type('dp_contact_submission', [
        'labels' => [
            'name' => 'Contact Submissions',
            'singular_name' => 'Contact Submission',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false, // attach manually
        'menu_icon' => 'dashicons-feedback',
        'supports' => ['title', 'editor'],
    ]);
}

/*--------------------------------------------------------------
# ADMIN MENU + SUBMENU
--------------------------------------------------------------*/
add_action('admin_menu', 'dp_contact_form_admin_menu');

function dp_contact_form_admin_menu() {

    // Main plugin menu
    add_menu_page(
        'DP Contact Form Settings',
        'DP Contact Form',
        'manage_options',
        'dp-form-settings',
        'dp_contact_form_settings_page',
        'dashicons-email-alt',
        26
    );

    // Submenu with redirect callback
    add_submenu_page(
        'dp-form-settings',
        'Contact Submissions',
        'Submissions',
        'manage_options',
        'dp-contact-submissions', // custom slug
        'dp_redirect_to_cpt_page'
    );
}

// Callback function that redirects submenu to CPT list table
function dp_redirect_to_cpt_page() {
    $cpt_url = admin_url('edit.php?post_type=dp_contact_submission');
    wp_redirect($cpt_url);
    exit;
}


/*--------------------------------------------------------------
# SETTINGS PAGE
--------------------------------------------------------------*/
function dp_contact_form_settings_page() {
    ?>
    <div class="wrap">
        <h1>DP Contact Form</h1>
        <p>Use shortcode to display the form: <code>[dp_contact_form]</code></p>
    </div>
    <?php
}

/*--------------------------------------------------------------
# SHORTCODE
--------------------------------------------------------------*/
add_shortcode('dp_contact_form', 'dp_contact_form_shortcode');

function dp_contact_form_shortcode() {
    ob_start();
    ?>
    <form method="post" class="dp-contact-form">
        <p><label>Name</label><br><input type="text" name="dp_name" required></p>
        <p><label>Email</label><br><input type="email" name="dp_email" required></p>
        <p><label>Phone</label><br><input type="text" name="dp_phone"></p>
        <p><label>Message</label><br><textarea name="dp_message" required></textarea></p>
        <?php wp_nonce_field('dp_contact_form_nonce', 'dp_contact_form_nonce_field'); ?>
        <p><input type="submit" name="dp_contact_submit" value="Send Message"></p>
    </form>
    <?php
    return ob_get_clean();
}

/*--------------------------------------------------------------
# FORM HANDLER
--------------------------------------------------------------*/
add_action('init', 'dp_handle_contact_form');

function dp_handle_contact_form() {
    if (!isset($_POST['dp_contact_submit'])) return;

    if (!isset($_POST['dp_contact_form_nonce_field']) ||
        !wp_verify_nonce($_POST['dp_contact_form_nonce_field'], 'dp_contact_form_nonce')
    ) return;

    $name    = sanitize_text_field($_POST['dp_name']);
    $email   = sanitize_email($_POST['dp_email']);
    $phone   = sanitize_text_field($_POST['dp_phone']);
    $message = sanitize_textarea_field($_POST['dp_message']);

    $post_id = wp_insert_post([
        'post_type' => 'dp_contact_submission',
        'post_title' => 'Submission from ' . $name,
        'post_content'=> "Name: $name\nEmail: $email\nPhone: $phone\nMessage:\n$message",
        'post_status' => 'publish',
    ]);

    if ($post_id) {
        update_post_meta($post_id, 'name', $name);
        update_post_meta($post_id, 'email', $email);
        update_post_meta($post_id, 'phone', $phone);
        update_post_meta($post_id, 'message', $message);
    }
}
