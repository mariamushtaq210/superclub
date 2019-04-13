<?php /* Template Name: About Page */ ?>

<?php get_header() ?>
<div class="generic-page">
<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>

        <div class="page-heading">
            <div class="perfect">
                <h1 class="big-heading"><?php the_title(); ?></h1>
            </div>
        </div>

        <?php $image = get_field('header_image', 'option'); ?>
        <div class="parallax-window parallax-page" data-parallax="scroll" data-speed="0.5" data-image-src="<?= $image['url']; ?>"></div>

        <div class="main-content">
            <div class="row text-content">
                <div class="container">
                    <?php the_content(); ?>
                </div>
            </div>
            <div class="row gallery">
              <div class="container">
                <h2><?php the_field('gallery_title'); ?></h2>
              </div>
              <div class="container">
                <?php $images = get_field('gallery'); if( $images ): ?>
                  <div id="carousel" class="owl-carousel">
                    <?php foreach( $images as $image ): ?>
                      <div class="item">
                        <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="row faq">
              <div class="container">
                <h2><?php the_field('faq_title'); ?></h2>
              </div>
              <div class="container">
                <?php if( have_rows('faq') ): ?>
                  <?php while ( have_rows('faq') ) : the_row(); ?>
                    <div class="question">
                      <h3><?php the_sub_field('question'); ?></h3>
                      <div class="answer">
                        <?php the_sub_field('answer'); ?>
                      </div>
                    </div>
                  <?php endwhile; ?>
                <?php endif; ?>
              </div>
            </div>
        </div>


        <div class="screen-overlay">
          <div class="screen-close">
            <p><i class="fa fa-times"></i> Close</p>
          </div>
          <div class="slider-container">
            <?php $images = get_field('gallery'); if( $images ): ?>
              <div id="slider" class="owl-carousel">
                <?php foreach( $images as $image ): ?>
                  <div class="item" style="background-image:url(<?php echo $image['sizes']['large']; ?>);"></div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>


    <?php endwhile; ?>
<?php endif; ?>
</div>
<?php get_footer() ?>
