<?php
/*

Plugin Name: Notice Bar Plugin
Plugin URI: https://example.com/notice-bar
Description: A simple notice bar plugin for WordPress.
Version: 1.0
Author: Farjana Dipa
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: notice-bar-plugin


*/


// Exit if accessed directly
if ( !defined ('ABSPATH')){
    exit;
}

// Enqueue Styles and Scripts

function dp_notice_bar_assets() {
    wp_enqueue_style(
        'dp-notice-bar-style',
        plugin_dir_url(__FILE__) . 'assets/css/dp_notice_bar.css'
    );

    wp_enqueue_script(
        'dp-notice-bar-script',
        plugin_dir_url(__FILE__) . 'assets/js/dp_notice_bar.js',
        [],
        false,
        true
    );
}
add_action('wp_enqueue_scripts', 'dp_notice_bar_assets');






add_action('admin_menu', 'dp_notice_bar_menu_page');


function dp_notice_bar_menu_page(){
    add_menu_page(
        'Notice Bar Settings',
        'Notice Bar', 
        'manage_options',
        'notice-bar-settings',
        'dp_settings_page' 
       );
}


function dp_settings_page(){
    ?>
      <div class="wrap">
        <h1>Notice Bar Settings</h1>
        <form method="post" action="options.php">
            <?php 
                settings_fields('dp_notice_bar_settings_group');
                do_settings_sections('notice-bar-settings');
                submit_button();
            ?>
        </form>
      </div>
    <?php
}



add_action('admin_init', 'dp_notice_bar_settings');

function dp_notice_bar_settings(){
    register_setting(
        'dp_notice_bar_settings_group',
        'dp_notice_bar_text'
    );

    register_setting(
        'dp_notice_bar_settings_group',
        'dp_notice_bar_color'
    );

    register_setting(
        'dp_notice_bar_settings_group',
        'dp_notice_bar_bg_color'
    );

    register_setting(
        'dp_notice_bar_settings_group',
        'dp_notice_bar_enable'
    );

    add_settings_section(
        'dp_notice_bar_main_section',
        'Notice Bar Configuration',
        null,
        'notice-bar-settings'
    );

    add_settings_field(
        'dp_notice_bar_text_field',
        'Notice Bar Text',
        'dp_notice_bar_text_field_callback',
        'notice-bar-settings',
        'dp_notice_bar_main_section'
    );

    add_settings_field(
        'dp_notice_bar_color_field',
        'Text Color',
        'dp_notice_bar_color_field_callback',
        'notice-bar-settings',
        'dp_notice_bar_main_section'
    );

    add_settings_field(
        'dp_notice_bar_bg_color_field',
        'Background Color',
        'dp_notice_bar_bg_color_field_callback',
        'notice-bar-settings',
        'dp_notice_bar_main_section'
    );

    add_settings_field(
        'dp_notice_bar_enable_field',
        'Enable Notice Bar',
        'dp_notice_bar_enable_field_callback',
        'notice-bar-settings',
        'dp_notice_bar_main_section'
    );
}


function dp_notice_bar_text_field_callback(){
    $text = get_option('dp_notice_bar_text', 'This is a notice bar!');
    echo '<input type="text" name="dp_notice_bar_text" value="' . esc_attr($text) . '" style="width: 400px;">';
    
}

function dp_notice_bar_color_field_callback(){
    $color = get_option('dp_notice_bar_color', '#000000');

    echo '<input type="color" name="dp_notice_bar_color" value="' . esc_attr($color) . '">';
}

function dp_notice_bar_bg_color_field_callback(){
    $bg_color = get_option('dp_notice_bar_bg_color', '#ffffff');

    echo '<input type="color" name="dp_notice_bar_bg_color" value="' . esc_attr($bg_color) . '">';
}

function dp_notice_bar_enable_field_callback(){
    $enable = get_option('dp_notice_bar_enable', false);

    echo '<input type="checkbox" name="dp_notice_bar_enable" value="1" ' . checked(1, $enable, false) . '>';
}


add_action('wp_body_open', 'snb_show_notice_bar');

function snb_show_notice_bar() {

    if (!get_option('dp_notice_bar_enable')) {
        return;
    }

    $text = get_option('dp_notice_bar_text');
    if (empty($text)) {
        return;
    }

    //echo '<div class="dp-notice-bar">' . esc_html($text) . '</div>';

    ?>
      <div id="dp-notice-bar" class="dp-notice-bar">
        <span class="dp-notice-text">
          <?php echo esc_html($text); ?>
        </span>

         <button id="dp-notice-close" class="dp-notice-close">×</button>
      </div>

    <?php
}


add_action('wp_head', 'dp_notice_bar_styles', 99);

function dp_notice_bar_styles() {

    $text_color = get_option('dp_notice_bar_color', '#ffffff');
    $bg_color   = get_option('dp_notice_bar_bg_color', '#000000');
    ?>
    <style>
        .dp-notice-bar {
            display: none;
            background-color: <?php echo esc_attr($bg_color); ?> !important;
            color: <?php echo esc_attr($text_color); ?> !important;
            text-align: center;
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
            position: relative;
            width: 100%;
            z-index: 999999;
        }

        .dp-notice-close {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: red;
            border: none;
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            opacity: 1;
        }

        


    </style>
    <?php




}

