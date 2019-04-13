<?php
get_header();
global $product;
$title = get_the_title();
if ($product->is_type('variable')) :
    $get_variations = sizeof($product->get_children()) <= apply_filters('woocommerce_ajax_variation_threshold', 30, $product);
    $available_variations = $get_variations ? $product->get_available_variations() : false;
endif;
?>
    <div class="single-product">

        <div class="page-heading">
            <div class="perfect">
                <h1 class="big-heading"><?= $title; ?></h1>
            </div>
        </div>

        <?php $image = get_field('header_image', 'option'); ?>
        <div class="parallax-window parallax-page" data-parallax="scroll" data-speed="0.5"
             data-image-src="<?= $image['url']; ?>"></div>

        <div class="main-content">

            <div class="container">
                <div class="row single-block">
                    <div class="three-quarter">
                        <div class="row basics">
                            <div class="half">
                                <div class="product-image">
                                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->ID), 'single-post-thumbnail'); ?>
                                    <img src="<?= $image[0]; ?>" class="product-image"/>
                                </div>
                            </div>
                            <div class="half">
                                <div class="product-desc">
                                    <?php if ($product->is_type('variable')) : ?>
                                        <div class="row">
                                            <span>Event Date:</span>
                                            <h2 class="sub-heading">
                                                <?php
                                                $date = get_field('event_date');
                                                $show_date = DateTime::createFromFormat('d/m/Y', $date)->format('jS F Y');
                                                echo $show_date;
                                                ?>
                                            </h2>
                                            <?php if (empty($available_variations) && false !== $available_variations) : ?>
                                                <b>This event is now sold out.</b>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row event-location">
                                    <span>Address: <a href="#mapArea">(<i
                                                    class="fa fa-map-marker"></i>View Map)</a></span>
                                            <?php $location = get_field('location', $product->ID); ?>
                                            <h2><?= $location['address']; ?></h2>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row">
                                        <span>Price:</span>
                                        <?= woocommerce_price($product->get_price()) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <hr>
                        </div>
                        <div class="row">
                            <?php the_content(); ?>
                        </div>
                        <?php if (get_field('what_to_expect')): ?>
                            <?php if ($product->is_type('variable')) : ?>
                                <div class="row">
                                    <hr>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <?php if ($product->is_type('variable')) : ?>
                                    <h2>What to expect</h2>
                                <?php endif; ?>
                                <?php the_field('what_to_expect'); ?>
                            </div>
                            <?php
                        endif;
                        if (get_field('meal_format')):
                            ?>
                            <div class="row">
                                <hr>
                            </div>
                            <div class="row">
                                <h2>Meal Format</h2>
                                <?php the_field('meal_format'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="quarter">
                        <div class="woo-form">
                            <?php
                            $status = get_field('event_status');
                            if ($status === 'not_yet_available') :
                                ?>
                                <h4>Join Our Mailing List</h4>
                                <?php
                                the_field('not_yet_available_text', 'option');
                                include('mailchimp.php');
                            elseif ($status === 'available') :
                                if ($product->is_type('variable')) :
                                    // $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                                    $get_event_date = get_field('event_date');
                                    $event_date_parts = explode("/", $get_event_date);
                                    $event_date = $event_date_parts[2] . '-' . $event_date_parts[1] . '-' . $event_date_parts[0];
                                    if (strtotime($event_date) > strtotime('today')) :
                                        if (empty($available_variations) && false !== $available_variations) :
                                            ?>
                                            <h4>Join Our Mailing List</h4>
                                            <?php
                                            the_field('sold_out_text', 'option');
                                            include('mailchimp.php');
                                        else :
                                            ?>
                                            <h4>Book Reservation</h4>
                                            <?php
                                            woocommerce_template_single_add_to_cart();
                                        endif;
                                    else:
                                        ?>
                                        <h4>Join Our Mailing List</h4>
                                        <?php
                                        the_field('past_event_text', 'option');
                                        include('mailchimp.php');
                                    endif;
                                elseif ($product->is_type('simple')) :
                                    ?>
                                    <span>Quantity:</span>
                                    <?php
                                    woocommerce_template_single_add_to_cart();
                                endif;
                            elseif ($status === 'sold_out') :
                                ?>
                                <h4>Join Our Mailing List</h4>
                                <?php
                                the_field('sold_out_text', 'option');
                                include('mailchimp.php');
                            elseif ($status === 'updating_website') :
                                ?>
                                <h4>Important Notice...</h4>
                                <?php
                                the_field('updating_website_text', 'option');
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                //woocommerce_show_product_thumbnails();
                $images = get_field('gallery');
                if ($images): ?>
                    <div class="row event-gallery">
                        <?php foreach ($images as $image): ?>
                            <div class="third">
                                <a href="<?= $image['url']; ?>" data-rel="lightbox">
                                    <div class="gallery-image"
                                         style="background-image:url(<?= $image['url']; ?>);"></div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($product->is_type('variable')) : ?>
                    <div class="row" id="mapArea">
                        <?php if (!empty($location)): ?>
                            <div class="row">
                                <div class="acf-map">
                                    <div class="marker" data-lat="<?php echo $location['lat']; ?>"
                                         data-lng="<?php echo $location['lng']; ?>"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

<?php if (!empty($location)): ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY; ?>"></script>
    <script src="<?= get_template_directory_uri(); ?>/public/js/acf-maps.min.js"></script>
    <?php
endif;
get_footer();
