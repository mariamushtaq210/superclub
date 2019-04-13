<?php /* Template Name: eventssss Page */ 
 get_header();

 ?>



  <div class="products-page">
        <div class="page-heading">
            <div class="perfect">
              <h1 class="big-heading"><?php the_title(); ?></h1>
            </div>
        </div>

        <?php $image = get_field('header_image', 'option'); ?>
        <div class="parallax-window parallax-page" data-parallax="scroll" data-speed="0.5" data-image-src="<?= $image['url']; ?>"></div>

        <div class="main-content">
            <div class="container grid">
                <div class="row">
                    <?php
                      global $post;
                      $currentdate = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
                      $args = array (
                        'post_type' => 'Event',
                       
                      );
                    ?>
                    <?php $the_query = new WP_Query( $args ); ?>
                    <?php if ( $the_query->have_posts() ) : ?>
                      <?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
?>

                        <?php
                      /*
                          $temp_date = get_post_meta( get_the_ID(), 'event_date', true );
                          if (strtotime($temp_date) > strtotime('now')):*/
                        ?>
                          <div class="third">
                            <a href="<?php the_permalink(); ?>">
                              <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?>
                              <div class="feat-image" style="background-image: url('<?php echo $thumb['0'];?>')"></div>
                              <div class="title-box">
                                <h3><?php the_title(); ?></h3>
                               
                              </div>
                            </a>
                          </div>

                        <?php/* endif;  */ ?>
                       
                      <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
  <?php get_footer() ?>