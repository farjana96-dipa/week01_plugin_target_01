<?php




add_shortcode('dp_property_listing', function($atts){
    
    $atts = shortcode_atts([
        'post_id' => 0,
    ], $atts);

    $post_id = intval($atts['post_id']);

    if(!$post_id){
        return '<p>No post id passed here</p>';
    }

    $fields = get_post_meta($post_id, true);

 

     ob_start();
     // thumbnail, title, excerpt 15 words

    $title = get_the_title($post_id);

    $thumbnail = get_the_post_thumbnail(
        $post_id,
        'medium',
        ['class' => 'dp-property-thumb']
    );

    $excerpt = get_the_excerpt($post_id);



   

   
    ?>
        <div class="dp-listing">
            <div class="dp-thumb">
                <?php echo $thumbnail ?>
            </div>
            <div class="dp-title">
                <h3><?php echo esc_html($title); ?> </h3>
            </div>
            <div class="dp-excerpt">
                <p><?php echo esc_html($excerpt); ?> </p>
            </div>
            <div class="dp-btn">
                <button type="button" class="read-more-btn">Read More</button>
            </div>
        </div>
    <?php
    return ob_get_clean();
});




// Single Post Property Shortcode

add_shortcode('dp-property-single-post',function(){

    if(!is_singular('dp_property')){
        return;
    }

    $post_id = get_queried_object_id();
    
    $title =  get_the_title($post_id);
    $img =  get_the_post_thumbnail(
        $post_id,
        'medium',
        ['class' => 'dp-post-thumb'],
    );

    $fields = get_post_meta($post_id,true);

    $owners = get_post_meta($post_id,'dp_owners',true);
    $gallery = get_post_meta($post_id,'dp_gallery',true);

    ob_start();
    ?>
        <div class="single-property">
         <div class="container-fluid">
            <div class="row">
                <div class="banner ">
                    
                    <h2 class="banner-title"><?php echo esc_html($title); ?> </h2>
                </div>
            </div>

            <div class="row dp-property-content">
                <div class="col-lg-8 col-md-6 ">
                  
                
                    <?php
                    $gallery = get_post_meta($post_id,'dp_gallery',true);
                        if(!empty($gallery) && is_array($gallery)) :
                        ?>
                       <div id="dpGalleryCarousel" class="carousel slide dp-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner">

                                    <?php foreach ($gallery as $index => $image_id) : ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                                'class' => 'd-block w-100'
                                            ]); ?>
                                        </div>
                                    <?php endforeach; ?>

                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#dpGalleryCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>

                                <button class="carousel-control-next" type="button" data-bs-target="#dpGalleryCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>

                        <?php endif; ?>
                       
                    
                  
                  <!-- Property Details --> 
                   <!-- Bedroom -->
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                                <div class="dp-icon-box text-center">
                                    <div class="dp-icon"><i class="fa-solid fa-bed"></i></div>
                                        <div class="dp-field">
                                            <?php 
                                                $bed = get_post_meta($post_id, 'dp_bedrooms',true);
                                                if($bed){
                                                    echo 'Bedroom<br>' . esc_html($bed);
                                                }
                                                else{
                                                    echo 'There is no field';
                                                }
                                            ?>
                                        </div>
                                </div>

                                <!-- Bathroom -->

                                <div class="dp-icon-box text-center">
                                    <div class="dp-icon"><i class="fa-solid fa-bath"></i></div>
                                        <div class="dp-field">
                                            <?php 
                                                $bath = get_post_meta($post_id, 'dp_bathrooms',true);
                                                if($bath){
                                                    echo 'Bathroom<br>' . esc_html($bath);
                                                }
                                                else{
                                                    echo 'There is no field';
                                                }
                                            ?>
                                        </div>
                                </div>

                                <!-- Area Size -->

                                <div class="dp-icon-box text-center">
                                    <div class="dp-icon"><i class="fa-solid fa-ruler-combined"></i></div>
                                        <div class="dp-field">

                                            <?php 
                                                $area = get_post_meta($post_id, 'dp_area',true);
                                                if($area){
                                                    echo 'Area Size<br>' . esc_html($area);
                                                }
                                                else{
                                                    echo 'There is no field';
                                                }
                                            ?>
                                        </div>
                                </div>
                        </div>

                        <div class="col-lg-6 col-md-6">

                            <!-- Lift -->

                            <div class="dp-icon-box text-center">
                                <div class="dp-icon"><i class="fa-solid fa-elevator"></i></div>
                                    <div class="dp-field">
                                        <?php 
                                            $lift = get_post_meta($post_id, 'dp_lift',true);
                                            if($lift){
                                                echo 'Lift<br>' . esc_html($lift);
                                            }
                                            else{
                                                echo 'There is no field';
                                            }
                                        ?> 
                                    </div>
                            </div>

                            <!-- Parking -->
                            
                            <div class="dp-icon-box text-center">
                                <div class="dp-icon"><i class="fa-solid fa-car"></i></div>
                                    <div class="dp-field">
                                        <?php 
                                            $park = get_post_meta($post_id, 'dp_parking',true);
                                            if($park){
                                                echo 'Parking<br>' . esc_html($park);
                                            }
                                            else{
                                                echo 'There is no field';
                                            }
                                        ?> 
                                    </div>
                            </div>


                            <!-- Sale Type -->
                            
                            <div class="dp-icon-box text-center">
                                <div class="dp-icon"><i class="fa-solid fa-tag"></i></div>
                                    <div class="dp-field">
                                        <?php 
                                            $sale_type = get_post_meta($post_id, 'dp_sale_type',true);
                                            if($sale_type){
                                                echo 'Sale Type<br>' . esc_html($sale_type);
                                            }
                                            else{
                                                echo 'There is no field';
                                            }
                                        ?> 
                                    </div>
                            </div>


                        </div>
                    </div>
                    
                    <!-- Description -->

                    <div class="description">
                        <?php $desc = get_the_excerpt($post_id); ?>
                        <p><?php echo esc_html($desc); ?></p>
                    </div>

                </div>

                <div class="col-lg-4 col-md-6 ">

                    <div class="title">
                        <h3><?php echo esc_html($title); ?> </h3>
                    </div>

                    <div class="price">
                        <strong><?php
                         $price = get_post_meta($post_id, 'dp_price', true);
                         echo esc_html($price);
                         
                        ?> </strong>
                    </div>

                    <div class="owner-card">
                        <?php $owners = get_post_meta($post_id, 'dp_owners',true);
                        if(!empty($owners) && is_array($owners)){
                            foreach($owners as $owner){
                                $name = $owner['name'];
                                $phone = $owner['phone'];
                                $email = $owner['email'];

                                echo '<strong> Name : </strong> '. esc_html($name) . '<br>';
                                echo '<strong>Phone : </strong> ' . esc_html($phone) . '<br>';
                                echo '<strong> Email : </strong> ' . esc_html($email);
                            }
                        }

                        ?>
                    </div>

                </div>
            </div>
          </div>
        </div>
    <?php
    return ob_get_clean();

    

});