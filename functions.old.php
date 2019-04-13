<?php
if (file_exists(get_template_directory() . '/vendor/autoload.php')) {
    include get_template_directory() . '/vendor/autoload.php';
}

new \SSC\SSC_Chef();
new \SSC\SSC_Venue();

add_filter('wp_title', 'ssc_fixed_home_wp_title');
add_action('wp_enqueue_scripts', 'ssc_enqueue_styles');
add_action('wp_enqueue_scripts', 'ssc_enqueue_scripts');
add_action('after_setup_theme', 'ssc_theme_setup');
add_action('admin_enqueue_scripts', 'ssc_admin_styles');

add_action('init', 'ssc_log_user_in');
add_action('init', 'ssc_register_user');
add_action('init', 'ssc_log_user_out');
add_action('init', 'ssc_forgot_password_action');

add_action('acf/init', 'my_acf_init');

/* Set up google maps API key */
define('MAPS_API_KEY', 'AIzaSyDlY9WuG4FYD1LPQ3ivSevNHjTxlKwi5oQ');

/**
 * Customize the title for the home page, if one is not set.
 *
 * @param string $title The original title.
 * @return string The title to use.
 */
function ssc_fixed_home_wp_title($title)
{
    if (empty($title) && (is_home() || is_front_page())) {
        $title = __('Home', 'textdomain') . ' | ' . get_bloginfo('description');
    }
    return $title;
}

function ssc_admin_styles()
{
    wp_enqueue_style('teknet-admin-css', get_template_directory_uri() . '/public/css/admin-style.css');
}

/**
 * Enqueues styles for the theme.
 */
function ssc_enqueue_styles()
{

    wp_enqueue_style('theme-screen-style', get_template_directory_uri() . '/public/css/screen' . ssc_get_asset_suffix() . '.css');
    wp_enqueue_style('theme-print-style', get_template_directory_uri() . '/public/css/print' . ssc_get_asset_suffix() . '.css');
    //wp_enqueue_style('fontawesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('owl', get_template_directory_uri() . '/bower_components/owl.carousel/dist/assets/owl.carousel' . ssc_get_asset_suffix() . '.css');
}

/**
 *  Enqueues the scripts for the theme.
 */
function ssc_enqueue_scripts()
{
    wp_enqueue_script('theme-script', get_template_directory_uri() . '/public/js/main' . ssc_get_asset_suffix() . '.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('html5shiv', get_template_directory_uri() . '/bower_components/html5shiv/dist/html5shiv' . ssc_get_asset_suffix() . '.js');
    wp_enqueue_script('parallax.js', get_template_directory_uri() . '/bower_components/parallax.js/parallax' . ssc_get_asset_suffix() . '.js');
    wp_enqueue_script('owlCarousel', get_template_directory_uri() . '/bower_components/owl.carousel/dist/owl.carousel' . ssc_get_asset_suffix() . '.js');
}

/**
 * Returns '.min' if Wordpress is not in DEBUG mode.
 *
 * Useful for including production files when DEBUG is off.
 *
 * @return string '.min' if Debug is on otherwise empty string.
 */
function ssc_get_asset_suffix()
{
    return (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
}

function ssc_theme_setup()
{
    add_theme_support('post-thumbnails');

    register_nav_menus(array(
        'main-navigation' => 'Main Navigation',
    ));
}

/*
 * Checks if ACF has been installed and activated and set up an ACF options page.
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

function my_acf_init()
{
    acf_update_setting('google_api_key', MAPS_API_KEY);
}

add_filter('woocommerce_worldpay_args', 'ssc_woocommerce_worldpay_args', 10, 2);

function ssc_woocommerce_worldpay_args($worldpay_args, $order)
{
    $worldpay_args['MC_SuccessURL'] = site_url() . '/my-account';
    $worldpay_args['MC_FailureURL'] = site_url() . '/events';
    return $worldpay_args;
}

function wc_dropdown_variation_attribute_options($args = array())
{
    $args = wp_parse_args(apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args), array(
        'options' => false,
        'attribute' => false,
        'product' => false,
        'selected' => false,
        'name' => '',
        'id' => '',
        'class' => '',
        'show_option_none' => __('Choose an option', 'woocommerce')
    ));

    $options = $args['options'];
    $product = $args['product'];
    $attribute = $args['attribute'];
    $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
    $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
    $class = $args['class'];
    $show_option_none = $args['show_option_none'] ? true : false;
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce'); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

    if (empty($options) && !empty($product) && !empty($attribute)) {
        $attributes = $product->get_variation_attributes();
        $options = $attributes[$attribute];
    }

    $html = '<select id="' . esc_attr($id) . '" class="' . esc_attr($class) . '" name="' . esc_attr($name) . '" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute)) . '"' . '" data-show_option_none="' . ($show_option_none ? 'yes' : 'no') . '">';
    $html .= '<option value="">' . esc_html($show_option_none_text) . '</option>';

    if (!empty($options)) {
        if ($product && taxonomy_exists($attribute)) {
            // Get terms if this is a taxonomy - ordered. We need the names too.
            $terms = wc_get_product_terms($product->id, $attribute, array('fields' => 'all'));

            foreach ($terms as $term) {
                if (in_array($term->slug, $options)) {
                    $html .= '<option value="' . esc_attr($term->slug) . '" ' . selected(sanitize_title($args['selected']), $term->slug, false) . '>' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name)) . '</option>';
                }
            }
        } else {
            foreach ($options as $val => $option) {
                // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'], sanitize_title($option), false) : selected($args['selected'], $option, false);
                $html .= '<option value="' . esc_attr($val) . '" ' . $selected . '>' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '</option>';
            }
        }
    }

    $html .= '</select>';

    echo apply_filters('woocommerce_dropdown_variation_attribute_options_html', $html, $args);
}

// Hook in
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields($fields)
{
    $fields['order']['order_comments']['placeholder'] = 'Please state any dietary restrictions, food allergies and/or religious restrictions you may have';
    $fields['order']['order_comments']['label'] = 'Dietary Requirements';
    return $fields;
}

function ssc_log_user_in()
{
    //wp_update_user(array('ID' => 1, 'user_pass' => 'G1bb0n5!*'));
    if (!isset($_POST['ssc-user-login'])) {
        return;
    }
    global $ssc_errors;

    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key])) {
            $ssc_errors[$key][] = 'This field must not be empty!';
        }
    }

    if (!empty($ssc_errors)) {
        echo 'r';

        return;
    }

    $username_email = sanitize_text_field($_POST['ssc-user-email']);
    $password = sanitize_text_field($_POST['ssc-user-pass']);

    if (is_email($username_email)) {
        $temp_user = get_user_by('email', $username_email);
        if (!$temp_user) {
            $ssc_errors['ssc-user-email'][] = 'A user with this email hasn\'t been registered';
        } else {
            $username_email = $temp_user->user_login;
        }
    } else {
        if (!username_exists($username_email)) {
            $ssc_errors['ssc-user-email'][] = 'A user with this username does not exist';
        }
    }

    if (!empty($ssc_errors)) {
        return;
    }

    $remember = isset($_POST['ssc-user-remember']) ? true : false;

    $credentials = array(
        'user_login' => $username_email,
        'user_password' => $password,
        'remember' => $remember,
    );

    $user = wp_signon($credentials);

    if (is_wp_error($user)) {
        if ($user->get_error_code() == 'incorrect_password') {
            $ssc_errors['ssc-user-pass'][] = 'Incorrect Password!';
        } else {
            $ssc_errors['ssc-login-error'][] = 'Something went wrong!';
        }
    } else {
        wp_set_current_user($user->ID);
        $redirect = isset($_POST['ssc-redirect-to']) ? $_POST['ssc-redirect-to'] : '/account';
        wp_redirect($redirect);
        exit;
    }
}

function ssc_register_user()
{
    if (!isset($_POST['ssc-register-user'])) {
        return;
    }

    global $ssc_errors;

    $required_fields = array('ssc-register-email', 'ssc-register-password', 'ssc-register-repeat', 'ssc-register-fname', 'ssc-register-lname');

    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key]) && in_array($key, $required_fields)) {
            $ssc_errors[$key][] = 'This field must not be empty!';
        }
    }

    if (!empty($ssc_errors)) {
        return;
    }

    $email = sanitize_email($_POST['ssc-register-email']);
    $password = sanitize_text_field($_POST['ssc-register-password']);
    $password2 = sanitize_text_field($_POST['ssc-register-repeat']);
    $firstname = sanitize_text_field($_POST['ssc-register-fname']);
    $lastname = sanitize_text_field($_POST['ssc-register-lname']);
    $how_did_you_find_us = sanitize_text_field($_POST['ssc-how-you-found-us']);

    if (!is_email($email)) {
        $ssc_errors['ssc-register-email'][] = 'Invalid Email Address Format!';
    }

    if (email_exists($email)) {
        $ssc_errors['ssc-register-email'][] = 'Email Address already in use!';
    }

    if (strlen($password) < 6) {
        $ssc_errors['ssc-register-password'][] = 'Your password must be at least 6 characters.';
    }

    if ($password != $password2) {
        $ssc_errors['ssc-register-repeat'][] = 'This field must match the Password field!';
    }

    if (!empty($how_did_you_find_us)) {
        $options = get_field('how_did_you_find_us', 'options');
        if (!in_array($how_did_you_find_us, $options)) {
            $ssc_errors['ssc-how-you-found-us'][] = 'Invalid Option';
        }
    }

    if (!empty($ssc_errors)) {
        return;
    }

    $userdata = array(
        'user_login' => $email,
        'user_email' => $email,
        'user_pass' => $password,
        'first_name' => $firstname,
        'last_name' => $lastname,
    );

    $user_id = wp_insert_user($userdata);
    if (!is_wp_error($user_id)) {
        $user = wp_signon(array('user_login' => $email, 'user_password' => $password, 'remember' => false));
        if (!empty($how_did_you_find_us)) {
            update_user_meta($user_id, 'how_did_you_find_us', $how_did_you_find_us);
        }
        wp_set_current_user($user_id);
        wp_redirect('/account');
        exit;
    }
}

function ssc_log_user_out()
{
    //$booking_data = get_checkout_booking_data();
    if (isset($_REQUEST['logout'])) {
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        if (is_user_logged_in()) {
            wp_logout();
            wp_set_current_user(0);
        }
        wp_redirect('http://' . $_SERVER['HTTP_HOST'] . $uri_parts[0]);
        exit;
    }
}

function ssc_forgot_password_action()
{
    if (!isset($_POST['ssc-user-fgt'])) {
        return;
    }

    global $ssc_errors, $ssc_success_messages;

    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key])) {
            $ssc_errors[$key][] = 'This field must not be empty!';
        }
    }

    if (!empty($ssc_errors)) {
        return;
    }

    $email = sanitize_text_field($_POST['ssc-user-email-fgt']);
    $email_repeat = sanitize_text_field($_POST['ssc-user-email-repeat']);

    if (!is_email($email)) {
        $ssc_errors['ssc-user-email-fgt'][] = 'Invalid Email Format!';
    }

    if ($email != $email_repeat) {
        $ssc_errors['ssc-user-email-fgt'][] = 'These fields must match!';
        $ssc_errors['ssc-user-email-repeat'][] = 'These fields must match!';
    }

    if (!empty($ssc_errors)) {
        return;
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        $ssc_errors['ssc-forgot-password'][] = 'A user with this email address doesn\'t exist!';
    }

    if (empty($ssc_errors)) {
        $new_password = ssc_generate_secure_password();
        $to = $email;
        $subject = 'Forgot Password?';
        $message = 'Hello ' . $user->user_firstname . ' ' . $user->user_lastname . ' your password has been reset to <b>' . $new_password . '</b>';
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Sauce Supper Club <info@saucesupperclub.co.uk>');
        $body = ssc_email_template($subject, $message);
        $mail = mail($to, $subject, $body, implode("\r\n", $headers));
        if ($mail) {
            wp_update_user(array('ID' => $user->ID, 'user_pass' => $new_password));
            $ssc_success_messages['ssc-user-fgt'][] = 'Password reset successfully.';
            $ssc_success_messages['ssc-user-fgt'][] = 'Please check your emails.';
            $_POST[] = array();
        } else {
            $ssc_errors['ssc-forgot-password'][] = $mail . ' Sorry, something went wrong. Try again later';
        }
    }
}

//Generates a random secure password.
function ssc_generate_secure_password()
{
    $alphas = array_merge(range('A', 'Z'), range('a', 'z'));
    $numbers = range('0', '20');
    $special_chars = array('*', '!', '#', '$', '?', '@', '|', '~', ':', '!', ';', '&', '!');

    $pre_pass = '';
    $rnd_p = rand(8, 12);

    $chars_in_pass = array();

    for ($i = 0; $i < $rnd_p; $i++) {
        $max = count($alphas) - 1;
        $rnd_a = rand(0, $max);

        $pre_pass .= $alphas[$rnd_a];
        $chars_in_pass[] = $alphas[$rnd_a];
    }

    $rnd_c = rand(0, (count($chars_in_pass) - 1));
    $rnd_c2 = rand(0, (count($chars_in_pass) - 1));
    $rnd_c3 = rand(0, (count($chars_in_pass) - 1));

    $rnd_n = rand(0, (count($numbers) - 1));
    $rnd_n2 = rand(0, (count($numbers) - 1));
    $rnd_s = rand(0, (count($special_chars) - 1));

    $pass = str_replace(array($chars_in_pass[$rnd_c], $chars_in_pass[$rnd_c2]), array($numbers[$rnd_n], $numbers[$rnd_n2]), $pre_pass);
    $final_pass = str_replace($chars_in_pass[$rnd_c3], $special_chars[$rnd_s], $pass);

    return str_shuffle($final_pass);
}

add_filter('woocommerce_variation_is_active', 'grey_out_variations_when_out_of_stock', 10, 2);

function grey_out_variations_when_out_of_stock($grey_out, $variation)
{
    if (!$variation->is_in_stock()) {
        return false;
    }
    return true;
}

add_filter('wpseo_metabox_prio', 'move_yoast_to_bottom');

function move_yoast_to_bottom()
{
    return 'low';
}

// Register sidebars etc

if (function_exists('register_sidebar')) {

	register_sidebar(array(
		'name' => 'Woo Cart',
		'id'   => 'woo-cart',
		'description'   => 'This is for the woocommerce cart in the header.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	));

}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Woocommerce shopping cart in nav

function themename_add_to_cart_fragment( $fragments) {
    ob_start();
    $count= WC()->cart->cart_contents_count;
    ?><a class="cart-contents"href="<?php echo WC()->cart->get_cart_url(); ?>"title="<?php _e( 'View your shopping cart' ); ?>"><?php
    if( $count> 0 ) {
        ?>
        <span class="cart-contents-count"><?php echoesc_html( $count); ?></span>
        <?php
    }
        ?></a><?php
    $fragments['a.cart-contents'] = ob_get_clean();

    return$fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'themename_add_to_cart_fragment');

/**
 * Register the Bookings custom menu page.
 */
function teknet_bookings_menu_page()
{
    add_menu_page(
            'Bookings', 'Bookings', 'manage_options', 'bookings.php', 'teknet_bookings_html', 'dashicons-calendar-alt', 40
    );
}

add_action('admin_menu', 'teknet_bookings_menu_page');

/**
 * Display the Bookings custom menu page
 */
function teknet_bookings_html()
{
    $booking_type = ">=";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['booking_type']) && $_POST['booking_type'] === 'historic') {
            $booking_type = '<';
        }
    }
    ?>
    <div class="wrap">

        <div id="icon-themes" class="icon32"></div>
        <h1>Bookings</h1>

        <form action="" method="POST">
            <label>Booking Type</label>
            <select name="booking_type">
                <option value="upcoming"<?= isset($_POST['booking_type']) && $_POST['booking_type'] === 'upcoming' ? ' selected="selected"' : ''; ?>>Upcoming</option>
                <option value="historic"<?= isset($_POST['booking_type']) && $_POST['booking_type'] === 'historic' ? ' selected="selected"' : ''; ?>>Historic</option>
            </select>
            <input type="submit" value="Filter"/>
        </form>

        <br />

        <?php
        global $wpdb;
        $products_table = $wpdb->prefix . 'posts';
        $prefix = $wpdb->prefix;
        $today = date('Ymd');
        $products = $wpdb->get_results("SELECT posts.ID, posts.post_title FROM $products_table AS posts
                LEFT JOIN " . $prefix . "postmeta AS pmeta ON posts.ID = pmeta.post_id
                WHERE posts.post_type='product' AND pmeta.meta_key='event_date' AND pmeta.meta_value $booking_type $today
                ORDER BY FIELD(pmeta.meta_key, 'event_date'), pmeta.meta_value;", OBJECT);
        foreach ($products as $product) {
            $orders_sql = "SELECT items.order_id AS OrderID FROM " . $prefix . "woocommerce_order_items AS items
                INNER JOIN " . $prefix . "woocommerce_order_itemmeta AS imeta ON items.order_item_id = imeta.order_item_id
                INNER JOIN " . $prefix . "posts AS posts ON items.order_id = posts.ID
                INNER JOIN " . $prefix . "postmeta AS pmeta ON items.order_id = pmeta.post_id
                WHERE (imeta.meta_key = '_product_id' AND imeta.meta_value = $product->ID) AND (posts.post_status = 'wc-pending' OR posts.post_status = 'wc-processing' OR posts.post_status = 'wc-completed');";
            $order_ids = $wpdb->get_col($orders_sql);
            if ($order_ids) {
                $times_sql = "SELECT *, items.order_id AS OrderID FROM " . $prefix . "woocommerce_order_items AS items
                INNER JOIN " . $prefix . "woocommerce_order_itemmeta AS imeta ON items.order_item_id = imeta.order_item_id
                INNER JOIN " . $prefix . "posts AS posts ON items.order_id = posts.ID
                INNER JOIN " . $prefix . "postmeta AS pmeta ON items.order_id = pmeta.post_id
                WHERE items.order_id IN (" . implode(",", $order_ids) . ") AND imeta.meta_key = 'reservation-time'
                GROUP BY items.order_id
                ORDER BY FIELD(imeta.meta_key, 'reservation-time') DESC, imeta.meta_value ASC;";
                $times = $wpdb->get_results($times_sql, OBJECT);
                if ($times) {
                    echo '<table class="wp-list-table widefat striped bookings-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th colspan="9"><h3>' . $product->post_title . '</h3></th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Order ID</th>';
                    echo '<th>Name</th>';
                    echo '<th>Reservation time</th>';
                    echo '<th>Party size</th>';
                    echo '<th>Email address</th>';
                    echo '<th>Phone number</th>';
                    echo '<th>Dietary requirements</th>';
                    echo '<th>Total</th>';
                    echo '<th></th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($times as $this_order) {
                        $order = new WC_Order($this_order->OrderID);
                        foreach ($order->get_items() as $item) {
                            $link = 'post.php?post=' . $order->get_order_number() . '&action=edit';
                            if ($order->billing_first_name && $order->billing_last_name && ($order->billing_email || $order->billing_phone)) {
                                echo '<tr>';
                                echo '<td>#' . $order->get_order_number() . '</td>';
                                echo '<td>' . $order->billing_first_name . ' ' . $order->billing_last_name . '</td>';
                                echo '<td>' . $item['reservation-time'] . '</td>';
                                echo '<td>' . $item['qty'] . '</td>';
                                echo '<td>' . $order->billing_email . '</td>';
                                echo '<td>' . $order->billing_phone . '</td>';
                                echo '<td style="width: 35%;">' . $order->customer_message . '</td>';
                                echo '<td>' . $order->get_formatted_order_total() . '</td>';
                                echo '<td><a href="' . $link . '">View/Edit</a></td>';
                                echo '</tr>';
                            }
                        }
                    }
                    echo '</tbody>';
                    echo '</table>';
                }
            }
        }
        ?>

    </div>
    <?php
}
