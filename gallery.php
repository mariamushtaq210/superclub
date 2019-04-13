<?php
/*
 * Template Name: Gallery Page
 */

get_header();
?>
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

                <?php
                $gallery = get_field('gallery');
                $images = [];

                $items_per_page = 12;
                $total_items = count($gallery);
                $total_pages = ceil($total_items / $items_per_page);

                $current_page = 1;
                if (get_query_var('paged')) :
                    $current_page = get_query_var('paged');
                elseif (get_query_var('page')) :
                    $current_page = 1;
                endif;
                $starting_point = (($current_page - 1) * $items_per_page);

                if ($gallery) :
                    $images = array_slice($gallery, $starting_point, $items_per_page);
                endif;

                if (!empty($images)) :
                    ?>
                    <div class="container">
                        <div class="row gallery-page">
                            <?php foreach ($images as $image) : ?>
                                <div class="third">
                                    <a href="<?= $image['url'] ?>" data-rel="lightbox">
                                        <span class="gallery-item"
                                              style="background-image:url(<?= $image['url'] ?>);"></span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php
                endif;

                $big = 999999999;
                echo paginate_links([
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $total_pages,
                    'show_all' => true,
                    'prev_next' => true,
                    'prev_text' => '<',
                    'next_text' => '>',
                    'type' => 'list',
                ]);

            endwhile;
        endif;
        ?>
    </div>
<?php
get_footer();
