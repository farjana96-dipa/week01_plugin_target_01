<?php


if(!defined('ABSPATH')) exit;



if(!isset($_GET['id']) || !($submission_id = absint($_GET['id']))){
    wp_die('Invalid Submission ID.');
}

$submission_post = get_post($submission_id);

if(!$submission_post || $submission_post->post_type !== 'dp_form_submissions'){
    wp_die('Submission not found.');
}

$form_id = get_post_meta($submission_id, '_dp_form_id', true);
$data  = get_post_meta($submission_id, '_dp_fields', true);

if(empty($fields) || !is_array($fields)){
    wp_die('No fields found for this form.');
}

?>
    <div class="wrap">
        <h1><?php echo 'Submission Details'; ?></h1>

        <h2><?php echo esc_html('Form: ') . esc_html(get_the_title($form_id)); ?></h2>
        <p><strong><?php esc_html('Submission ID:'); ?></strong> <?php echo esc_html($submission_id); ?></p>
        <p><strong><?php esc_html('Submitted on: '); ?></strong> <?php echo esc_html(get_the_date('', $submission_id)); ?></p>

        <h3><?php esc_html('Submission Data'); ?></h3>

        <?php if (is_array($data) && !empty($data)) : ?>
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html('Field'); ?></th>
                        <th><?php esc_html('Value'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : 
                        $key = is_null($key) ? '' : (string) $key;
                        $value = is_null($value) ? '' : (string) $value;
                    ?>
                        <tr>
                            <td><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?></td>
                            <td><?php echo esc_html($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html('No submission data found.'); ?></p>
        <?php endif; ?>

        <p><a href="<?php echo esc_url(admin_url('edit.php?post_type=dp_form_submissions')); ?>" class="button"><?php esc_html('Back to Submissions'); ?></a></p>
    </div>
            

<?php


add_filter('post_row_actions', function($actions, $post) {
    if ($post->post_type === 'dp_form_submissions') {
        $view_url = admin_url('admin.php?page=dp-view-submission&id=' . $post->ID);
        $actions['view_submission'] = '<a href="' . esc_url($view_url) . '">' . __('View', 'dp-custom-form-builder') . '</a>';
    }
    return $actions;
}, 10, 2);