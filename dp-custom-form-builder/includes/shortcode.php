<?php

if(!defined('ABSPATH')) exit;

add_shortcode('dp_form', 'dp_render_form_shortcode');

function dp_render_form_shortcode($atts) {

       $atts = shortcode_atts(
        [
            'id' => '',
        ],
        $atts,
        'dp_form'
    );

     $form_id = absint( $atts['id'] );

    if ( ! $form_id ) {
        return '<p>' . esc_html__( 'Form ID is missing.', 'dp-custom-form-builder' ) . '</p>';
    }

    $fields = get_post_meta($form_id, '_dp_fields', true);

    if ( empty( $fields ) || ! is_array( $fields ) ) {
        return '<p>' . esc_html__( 'No fields found for this form.', 'dp-custom-form-builder' ) . '</p>';
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
                $type     = esc_attr( $field['type'] );
                $name     = esc_attr( $field['name'] );
                $required = ! empty( $field['required'] ) ? 'required' : '';

                switch ( $type ) {

                    case 'textarea':
                        echo '<textarea name="' . $name . '" ' . $required . '></textarea>';
                        break;

                    default:
                        echo '<input type="' . $type . '" name="' . $name . '" ' . $required . ' />';
                }
                ?>

            </div>
        <?php endforeach; ?>

         <button type="submit">
            <?php esc_html_e( 'Submit', 'dp-custom-form-builder' ); ?>
        </button>

    </form>
    <?php
    return ob_get_clean();
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