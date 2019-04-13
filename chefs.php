<?php /* Template Name: Chef Page */ ?>

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
            <div class="row">
                <div class="container">
                    <?php the_content(); ?>
                </div>
                <div class="container grid">

                  <?php $chefargs = array (
                    'post_type' => 'chef',
                    'posts_per_page' => -1,
                    'orderby' => 'name',
                    'order' => 'DESC'
                  ); ?>

                  <?php $chef = new WP_Query( $chefargs ); ?>
                  <?php if ($chef -> have_posts () ): ?>
                    <?php while ( $chef->have_posts() ) : $chef->the_post(); ?>
                      <div class="third">
                      <a href="<?php the_permalink(); ?>">
                        <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?>
                        <div class="feat-image" style="background-image: url('<?php echo $thumb['0'];?>')"></div>
                        <div class="title-box"><h3><?php the_title(); ?></h3></div>
                      </a>
                      </div>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <p>We are currently uploading chefs to our system.</p>
                  <?php endif; ?>

                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
</div>
<?php get_footer() ?>
