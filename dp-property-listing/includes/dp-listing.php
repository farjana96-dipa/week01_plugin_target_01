<?php

add_shortcode('dp_owner_card', function ($atts)  {

    $atts = shortcode_atts([
        'post_id' => 0,
    ], $atts);

    $post_id = intval($atts['post_id']);

   if(!$post_id){
    return '<p>No post id passed.</p>';
   }


    $owners = get_post_meta($post_id, 'dp_owners', true);
    if (empty($owners)) return '<p>No Information Available for Owner</p>';
    
    

    ob_start();
    ?>

        <div class="dp-property-owner">
            <?php foreach($owners as $owner) : ?>
                <div class="owner-card">
                    <?php if(!empty($owner['name'])): ?>
                    <p><strong> Name : </strong> <?php echo esc_html($owner['name']); ?> </p>
                    <?php endif ?>

                    <?php if(!empty($owner['phone'])): ?>
                    <p><strong> Phone : </strong> <?php echo esc_html($owner['phone']); ?> </p>
                    <?php endif ?>

                    <?php if(!empty($owner['email'])): ?>
                    <p><strong> Email : </strong> <?php echo esc_html($owner['email']); ?> </p>
                    <?php endif ?>


                </div>
                
            <?php endforeach ?>
        </div>

    <?php

    return ob_get_clean();
});



