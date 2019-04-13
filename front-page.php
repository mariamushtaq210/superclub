<?php get_header(); ?>
<div class="front-page">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
        <?php $image = get_field('header_image'); ?>
        <div id="top-section">
            <div class="perfect">
                <div class="row">
                    <h1 class="big-heading"><?= get_field('header_heading'); ?></h1>
                    <?= get_field('header_text'); ?>
                </div>
            </div>
            <a class="to-main" href="#main-section">
                <span>Scroll Down</span>
            </a>
        </div>
        <div class="parallax-window parallax-full-height top-parallax" data-parallax="scroll" data-speed="0.5"
             data-image-src="<?= $image['url']; ?>"></div>

        <div id="main-section">
            <div class="container">
                <div class="row">
                    <?php if (have_rows('content')): ?>
                        <h2><?= get_field('title'); ?></h2>
                        <?php while (have_rows('content')) : the_row(); ?>
                            <?php if (get_row_layout() == 'full'): ?>
                                <div class="text-block">
                                    <p><?= get_sub_field('main_text'); ?></p>
                                    <?php $url = get_sub_field('url'); ?>
                                    <?php $link_text = str_replace('http://', '', $url); ?>
                                    <a href="<?= get_sub_field('url'); ?>"><?= $link_text; ?></a>
                                </div>
                                <?php $image = get_sub_field('image'); ?>
                                <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>"/>

                            <?php elseif (get_row_layout() == 'triple_image_links'): ?>

                                <?php if (have_rows('page_box')): ?>
                                    <div class="row third-split">
                                        <?php while (have_rows('page_box')) : the_row(); ?>
                                            <?php $bgImage = get_sub_field('page_image'); ?>
                                            <div class="third page-box"
                                                 style="background-image:url(<?= $bgImage['url'] ?>);">
                                                <div class="overlay"></div>
                                                <a href="<?php the_sub_field('page_link'); ?>">
                                                    <div class="perfect">
                                                        <h4><?php the_sub_field('page_title'); ?></h4>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php endif; ?>

                            <?php elseif (get_row_layout() == 'half'): ?>
                                <div class="half">
                                    <div class="text-block">
                                        <p><?= get_sub_field('main_text'); ?></p>
                                        <?php $url = get_sub_field('url'); ?>
                                        <?php $link_text = str_replace('http://', '', $url); ?>
                                        <a href="<?= get_sub_field('url'); ?>"><?= $link_text; ?></a>
                                    </div>
                                    <?php $image = get_sub_field('image'); ?>
                                    <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>"/>
                                </div>
                            <?php elseif (get_row_layout() == 'mailing_form'): ?>
                                <div class="row mailing-list">
                                    <div class="mailing-list-content">
                                        <h2><?php the_sub_field('mailing_form_title'); ?></h2>
                                        <?php the_sub_field('mailing_form_text'); ?>
                                    </div>
                                    <?php include('mailchimp.php'); ?>
                                </div>
                            <?php endif; ?>
                        <?php endwhile; endif; ?>
                </div>
            </div>
        </div>

        <?php $image = get_field('bottom_image'); ?>
        <div id="bottom-section">
            <div class="perfect">
                <div class="row">
                    <h2 class="big-heading"><?= get_field('bottom_heading'); ?></h2>
                    <?php $url = get_field('page_link'); ?>
                    <a href="<?= empty($url) ? '#' : $url; ?>">
                        <?= get_field('link_text'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="parallax-window parallax-full-height bottom-parallax" data-parallax="scroll" data-speed="0.5"
             data-image-src="<?= $image['url']; ?>"></div>
    <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
