<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="af9352ae-cdfa-41be-950f-dcf9522b6461" type="text/javascript" async></script>
    <title><?php wp_title(); ?></title>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if (is_singular() && pings_open(get_queried_object())) : ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>
    <script src="https://use.typekit.net/fbm8wpr.js"></script>
    <script>try {
            Typekit.load({async: true});
        } catch (e) {
        }</script>
    <script src="https://use.fontawesome.com/452836f90a.js"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php include_once("analytics.php"); ?>
<header class="ssc-header">
    <div class="row">
        <div class="logo-wrapper">
            <a href="<?php echo home_url(); ?>"><img src="<?= get_template_directory_uri(); ?>/images/sauce-supper-club.png" alt="Sauce Supper Club Logo"/></a>
        </div>
        <span class="mobile-menu-toggle">
            <i class="fa fa-bars"></i>
        </span>
        <nav class="ssc-navigation">
          <div class="my-cart">
            <?php $count = WC()->cart->cart_contents_count; ?>
            <a href="<?php echo WC()->cart->get_cart_url(); ?>">
              <?php if($count > 0): ?>
                <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
              <?php endif; ?>
              <i class="fa fa-shopping-cart"></i><span class="cart-title">Your Cart</span>
            </a>
          </div>
          <?php wp_nav_menu(array('theme_location' => 'main-navigation')) ?>
        </nav>
    </div>
</header>
