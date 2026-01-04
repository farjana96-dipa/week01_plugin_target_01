<?php

if(!defined('ABSPATH')) exit;


add_action('admin_menu', 'dp_register_admin_menu');

function dp_register_admin_menu(){
    add_menu_page(
        __( 'DP Custom Forms', 'dp-custom-form-builder' ),
        __( 'DP Custom Forms', 'dp-custom-form-builder' ),
        'manage_options',
        'dp-form-plugin',
        'dp_main_admin_page',
        'dashicons-buddicons-pm'
    );

    add_submenu_page(
        'dp-form-plugin',
         __( 'Create Form', 'dp-custom-form-builder' ),
        __( 'Create Form', 'dp-custom-form-builder' ),
        'manage_options',
        'create-form',
        'dp_create_form_admin_page'
    );


    add_submenu_page(
        null, // hidden from menu
        __('View Submission', 'dp-custom-form-builder'),
        __('View Submission', 'dp-custom-form-builder'),
        'manage_options',
        'dp-view-submission',
        'dp_view_submission_page'
    );
  

}

function dp_main_admin_page(){
    if ( file_exists( DP_CFB_PATH . 'admin/admin-page.php' ) ) {
    include DP_CFB_PATH . 'admin/admin-page.php';
}

}
function dp_create_form_admin_page(){
    if ( file_exists( DP_CFB_PATH . 'admin/admin-page.php' ) ) {
    include DP_CFB_PATH . 'admin/admin-page.php';
}

}


   function dp_view_submission_page() {
    if (!isset($_GET['id']) || !($submission_id = absint($_GET['id']))) {
        echo '<div class="notice notice-error"><p>' . esc_html__('Submission ID missing.', 'dp-custom-form-builder') . '</p></div>';
        return;
    }

    $submission = get_post($submission_id);

    if (!$submission || $submission->post_type !== 'dp_form_submissions') {
        echo '<div class="notice notice-error"><p>' . esc_html__('Invalid submission.', 'dp-custom-form-builder') . '</p></div>';
        return;
    }

    $form_id = absint( get_post_meta( $submission_id, '_dp_form_id', true ) );
    $form_title = $form_id ? get_the_title( $form_id ) : '';
    $form_title = is_string( $form_title ) ? $form_title : '';

    $data = get_post_meta( $submission_id, '_dp_submission_data', true );
    $data = is_array( $data ) ? $data : [];

    ?>

    <div class="wrap">
        <h1><?php echo esc_html__('Submission Details', 'dp-custom-form-builder'); ?></h1>

       <h2>
            <?php 
            echo esc_html__( 'Form:', 'dp-custom-form-builder' ) . ' ' . esc_html( $form_title ?: __( 'Unknown Form', 'dp-custom-form-builder' ) ); 
            ?>
        </h2>

        <p><strong><?php esc_html_e('Submission ID:', 'dp-custom-form-builder'); ?></strong> <?php echo esc_html($submission_id); ?></p>
        <p><strong><?php esc_html_e('Submitted on:', 'dp-custom-form-builder'); ?></strong> <?php echo esc_html(get_the_date('', $submission_id)); ?></p>

        <h3><?php esc_html_e('Submission Data', 'dp-custom-form-builder'); ?></h3>

        <?php if (is_array($data) && !empty($data)) : ?>
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Field', 'dp-custom-form-builder'); ?></th>
                        <th><?php esc_html_e('Value', 'dp-custom-form-builder'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : 
                        $key = is_null($key) ? '' : (string) $key;
                        $value = is_null($value) ? '' : (string) $value;
                    ?>
                        <tr>
                            <?php
                            $key   = is_string( $key ) ? $key : '';
                            $label = ucwords( str_replace( '_', ' ', $key ) );
                            ?>
                            <td><?php echo esc_html( $label ); ?></td>
                            <td><?php echo esc_html($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e('No submission data found.', 'dp-custom-form-builder'); ?></p>
        <?php endif; ?>

        <p><a href="<?php echo esc_url(admin_url('edit.php?post_type=dp_form_submissions')); ?>" class="button"><?php esc_html_e('Back to Submissions', 'dp-custom-form-builder'); ?></a></p>
    </div>

    <?php
}




add_filter('post_row_actions', function($actions, $post) {
    if ($post->post_type === 'dp_form_submissions') {
        $view_url = admin_url('admin.php?page=dp-view-submission&id=' . $post->ID);
        $actions['view_submission'] = '<a href="' . esc_url($view_url) . '">' . __('View', 'dp-custom-form-builder') . '</a>';
    }
    return $actions;
}, 10, 2);




 