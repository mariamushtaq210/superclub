<?php
get_header();
global $product;
$title = get_the_title();
$get_variations = sizeof($product->get_children()) <= apply_filters('woocommerce_ajax_variation_threshold', 30, $product);
$available_variations = $get_variations ? $product->get_available_variations() : false;
?>
    <div class="single-product">

        <div class="page-heading">
            <div class="perfect">
                <h1 class="big-heading"><?= $title; ?></h1>
            </div>
        </div>

        <?php $image = get_field('header_image', 'option'); ?>
        <div class="parallax-window parallax-page" data-parallax="scroll" data-speed="0.5" data-image-src="<?= $image['url']; ?>"></div>

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
                        <div class="row">
                          <span>Event Date:</span>
                          <h2 class="sub-heading">
                              <?php
                                $date = get_field('event_date');
                                $show_date = DateTime::createFromFormat('d/m/Y', $date)->format('jS F Y');
                              ?>
                              <?= $show_date;  ?>
                          </h2>
                          <?php if (empty($available_variations) && false !== $available_variations) : ?>
                              <b>This event is now sold out.</b>
                          <?php endif; ?>
                        </div>
                        <div class="row event-location">
                           <span>Address: <a href="#mapArea">(<i class="fa fa-map-marker"></i>View Map)</a></span>
                           <?php $location = get_field('location', $product->ID); ?>
                           <h2><?= $location['address']; ?></h2>
                        </div>
                        <div class="row">
                          <span>Price:</span>
                          <?= woocommerce_price($product->get_price()) ?>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row"><hr></div>
                <div class="row">
                  <?php the_content(); ?>
                </div>
                <?php if( get_field('what_to_expect') ): ?>
                  <div class="row"><hr></div>
                  <div class="row">
                    <h2>What to expect</h2>
                    <?php the_field('what_to_expect'); ?>
                  </div>
                <?php endif; ?>
                <?php if( get_field('meal_format') ): ?>
                  <div class="row"><hr></div>
                  <div class="row">
                    <h2>Meal Format</h2>
                    <?php the_field('meal_format'); ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="quarter">
                <div class="woo-form">
                  <?php
                    $temp_date = get_post_meta( get_the_ID(), 'event_date', true );
                    if (strtotime($temp_date) > strtotime('now')):
                  ?>

                    <?php if (empty($available_variations) && false !== $available_variations) : ?>
                        <h4>Join Waiting List</h4>
                        <?php the_field('sold_out_text', 'option'); ?>
                        <?php echo do_shortcode('[contact-form-7 id="54" title="Waiting List"]'); ?>
                    <?php else: ?>
                      <h4>Book Reservation</h4>
                      <?php woocommerce_template_single_add_to_cart();?>
                    <?php endif; ?>

                  <?php else: ?>

                    <h4>Join Waiting List</h4>
                    <?php the_field('sold_out_text', 'option'); ?>
                    <?php echo do_shortcode('[contact-form-7 id="54" title="Waiting List"]'); ?>

                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="row" id="mapArea">
              <?php if (!empty($location)): ?>
                  <div class="row"><hr></div>
                  <div class="row">
                    <div class="acf-map">
                        <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                    </div>
                  </div>
              <?php endif; ?>
            </div>
          </div>

        </div>

    </div>

<?php if (!empty($location)): ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY; ?>"></script>
    <script src="<?= get_template_directory_uri(); ?>/public/js/acf-maps.min.js"></script>
<?php endif; ?>
<?php get_footer() ?>
