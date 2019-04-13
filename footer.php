<footer class="ssc-footer">
    <div class="container">
        <div class="row">
            <div class="three-quarter">
                <h3>Twitter</h3>
                <?php db_twitter_feed(); ?>
            </div>
            <div class="quarter">
                <h3>Contact</h3>
                <ul class="ssc-contact">
                    <li>Contact: <?= get_field('name_of_person_to_contact', 'option'); ?></li>
                    <li>
                        <?php $email = get_field('email_address', 'option'); ?>
                        <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                    </li>
                    <?php
                    $tel = get_field('telephone_number', 'option');
                    if ($tel):
                        ?>
                        <li>Tel: <?= get_field('telephone_number', 'option'); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <?php
            $locations = get_nav_menu_locations();
            $menu_id = $locations['footer-navigation'];
            $menu_items = wp_get_nav_menu_items($menu_id);
            $list = [];
            foreach ($menu_items as $menu_item) :
                $list[] = '<a href="' . $menu_item->url . '">' . $menu_item->title . '</a>';
            endforeach;
            echo implode(' | ', $list);
            ?>
        </div>
        <div class="row credits">
            <div class="left"><p>&copy;<?php echo date("Y") ?> <?php bloginfo('name') ?>. All Rights Reserved.</p></div>
            <div class="right"><p>Crafted by <a href="http://teknetmarketing.co.uk">Teknet</a></p></div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
