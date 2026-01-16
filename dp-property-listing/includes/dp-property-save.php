<?php

// Save Property Details Metabox Data

add_action('save_post', 'dp_save_property_metabox_data');

function dp_save_property_metabox_data($post_id){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['dp_price'])){
        update_post_meta($post_id,'dp_price', sanitize_text_field($_POST['dp_price']));
    }
    if(isset($_POST['dp_bedrooms'])){
        update_post_meta($post_id,'dp_bedrooms', intval($_POST['dp_bedrooms']));
    }
    if(isset($_POST['dp_bathrooms'])){
        update_post_meta($post_id,'dp_bathrooms', intval($_POST['dp_bathrooms']));
    }
    if(isset($_POST['dp_area'])){
        update_post_meta($post_id,'dp_area', sanitize_text_field($_POST['dp_area']));
    }
    if(isset($_POST['dp_lift'])){
        update_post_meta($post_id,'dp_lift', sanitize_text_field($_POST['dp_lift']));
    }
    if(isset($_POST['dp_parking'])){
        update_post_meta($post_id,'dp_parking', sanitize_text_field($_POST['dp_parking']));
    }
    if(isset($_POST['dp_sale_type'])){          
        update_post_meta($post_id,'dp_sale_type', sanitize_text_field($_POST['dp_sale_type']));
    }

}




// Save Gallery + Owner + Map Metabox Data

add_action('save_post', 'dp_save_advanced_meta');

function dp_save_advanced_meta($post_id) {

        if (isset($_POST['dp_gallery']) && is_array($_POST['dp_gallery'])) {
            update_post_meta(
                $post_id,
                'dp_gallery',
                array_map('intval', $_POST['dp_gallery'])
            );
        }


        if (isset($_POST['dp_mapbox_address'])) {
            update_post_meta($post_id, 'dp_mapbox_address', sanitize_text_field($_POST['dp_mapbox_address']));
        }

        if (isset($_POST['dp_mapbox_latitude'])) {
            update_post_meta($post_id, 'dp_mapbox_latitude', sanitize_text_field($_POST['dp_mapbox_latitude']));
        }

        if (isset($_POST['dp_mapbox_longitude'])) {
            update_post_meta($post_id, 'dp_mapbox_longitude', sanitize_text_field($_POST['dp_mapbox_longitude']));
        }


        if(!isset($_POST['dp_owners']) || !is_array($_POST['dp_owners'])){
            return;
        }

        $data = [];

        foreach($_POST['dp_owners'] as $owner){
            if(empty($owner['name']) && empty($owner['phone']) && empty($owner['email'])){
                continue;
            }

            $data[] = [
                'name' => sanitize_text_field($owner['name'] ?? ''),
                'phone' => sanitize_text_field($owner['phone'] ?? ''),
                'email' => sanitize_text_field($owner['email'] ?? ''),
            ];
        }

        update_post_meta($post_id, 'dp_owners', $data);
    
}


?>
