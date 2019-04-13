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
        <div class="parallax-window parallax-page" data-parallax="scroll" data-speed="0.5"
             data-image-src="<?= $image['url']; ?>"></div>

        <div class="main-content">
            <div class="container">
                <div class="row">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
</div>
<?php get_footer() ?>
