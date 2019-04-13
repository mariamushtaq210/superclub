<?php get_header() ?>
    <div class="chef-page">
        <?php $chef = new \SSC\SSC_Chef(); ?>
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

                        <div class="row single-block">
                          <div class="two-third">

                            <div class="row basics">
                              <div class="chef-info">
                                  <div class="chef-image" style="background-image: url(<?= $chef->getImage(); ?>);"></div>
                              </div>
                              <div class="chef-bio">
                                  <?php if( get_field('title') ): ?>
                                    <h2><?= $chef->get_field('title'); ?></h2>
                                  <?php endif; ?>

                                  <?php if( get_field('date') ): ?>
                                    <time><?= $chef->format_date(); ?></time>
                                  <?php endif; ?>

                                  <?php if( get_field('twitter_handle') ): ?>
                                    <?php $twitter = $chef->get_field('twitter_handle'); ?>
                                    <?php $twitter_url = 'http://twitter.com/' . str_replace('@', '', $twitter); ?>
                                    <a target="_blank" href="<?= $twitter_url; ?>">Twitter: <?= $twitter; ?></a>
                                  <?php endif; ?>

                                  <?php if( get_field('website') ): ?>
                                    <?php $web_url = $chef->get_field('website'); ?>
                                    <?php $web_short = str_replace(array('http://', 'https://', 'www.'), array('', '', ''), $web_url); ?>
                                    <a target="_blank" href="<?= $web_url; ?>">Website: <?= rtrim($web_short, '/'); ?></a>
                                  <?php endif; ?>
                              </div>
                            </div>

                            <div class="row"><hr></div>

                            <?php if ($chef->get_field('flexible_content')): ?>
                                <?php while (has_sub_field('flexible_content')) : ?>
                                    <?php if (get_row_layout() == 'quote'): ?>
                                        <div class="row quote-block">
                                            <blockquote>
                                                <?= get_sub_field('quote'); ?>
                                            </blockquote>
                                        </div>
                                    <?php elseif (get_row_layout() == 'content_block'): ?>
                                        <div class="row">
                                            <?= get_sub_field('content'); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php endif; ?>

                          </div>

                          <div class="third side">
                            <?php
                              $posts = get_field('upcoming_events');
                              if( $posts ):
                            ?>
                            <div class="row grid upcoming-events">
                              <h2>Upcoming Events</h2>
                                  <?php foreach( $posts as $post): ?>
                                    <?php setup_postdata($post); ?>
                                      <div class="row">
                                        <a href="<?php the_permalink(); ?>">
                                          <div class="title-box">
                                            <h3><?php the_title(); ?></h3>
                                            <p class="price">
                                              <span class="left"><?php the_field('event_date', $post->ID); ?></span>
                                              <span class="right"><?= woocommerce_price($product->get_price()) ?></span>
                                            </p>
                                            <hr>
                                            <?php $location = get_field('location', $post->ID); ?>
                                            <p><?= $location['address']; ?></p>
                                          </div>
                                        </a>
                                      </div>
                                  <?php endforeach; ?>
                                <?php wp_reset_postdata(); ?>
                            </div>
                            <div class="row"><hr></div>
                          <?php else: ?>
                            <div class="row">
                              <h2>Upcoming Events</h2>
                              <p>New events coming soon. Join our mailing list now for updates!</p>
                            </div>
                            <div class="row"><hr></div>
                          <?php endif; ?>
                            <div class="woo-form">
                              <h4>Join waiting list</h4>
                              <?php echo do_shortcode('[contact-form-7 id="54" title="Waiting List"]'); ?>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>


                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
<?php get_footer() ?>
