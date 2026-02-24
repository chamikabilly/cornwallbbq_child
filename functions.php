<?php

/**
 * Miheli Solutions Child Theme Functions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

// Include toast notification system
require_once get_stylesheet_directory() . '/inc/toast-notifications.php';

/**
 * Remove cross-sell from default cart collaterals position
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');


/**
 * Enqueue parent and child theme styles
 */
function miheli_child_theme_enqueue_styles()
{
    // Parent theme stylesheet
    wp_enqueue_style('miheli-solutions-parent-style', get_template_directory_uri() . '/style.css');

    // Child theme root stylesheet
    wp_enqueue_style('miheli-solutions-child-style', get_stylesheet_uri(), array('miheli-solutions-parent-style'));

    // Child theme CSS (WooCommerce and custom styles)
    wp_enqueue_style(
        'miheli-child-custom-css',
        get_stylesheet_directory_uri() . '/assets/css/style.css',
        array('miheli-solutions-child-style'),
        '1.0.0'
    );


    // Scripts - Load jQuery first, then Bootstrap, then your scripts
    wp_enqueue_script('jquery'); // Make sure jQuery is loaded

    wp_enqueue_script(
        'miheli-shop-js',
        get_stylesheet_directory_uri() . '/assets/js/shop.js',
        array('jquery', 'swiper-js'), // Ensure Swiper loads before shop.js
        _S_VERSION,
        true
    );

    // Bootstrap CSS and JS (global, used throughout theme)
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true);

    // FontAwesome icons (global, used in notifications, buttons, etc)
    wp_enqueue_style('fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // Localize shop data for AJAX
    if (is_shop() || is_product_taxonomy()) {
        $miheli_shop_data = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('miheli_shop_nonce'),
        );
        wp_localize_script('miheli-shop-js', 'miheliShop', $miheli_shop_data);
    }

    // Product gallery JS (only on single product)
    // Swiper (slider) and GLightbox (lightbox) from CDN
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper@9/swiper-bundle.min.css', array(), '9.0.0');
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@9/swiper-bundle.min.js', array(), '9.0.0', true);

    // GLightbox (lightbox)
    wp_enqueue_style('glightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', array(), '3.2.0');
    wp_enqueue_script('glightbox-js', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', array(), '3.2.0', true);

    wp_enqueue_script(
        'miheli-product-gallery',
        get_stylesheet_directory_uri() . '/assets/js/product-gallery.js',
        array('jquery', 'swiper-js', 'glightbox-js', 'bootstrap-js'),
        _S_VERSION,
        true
    );

    wp_enqueue_script(
        'miheli-product-tabs',
        get_stylesheet_directory_uri() . '/assets/js/product-tabs.js',
        array('bootstrap-js'),
        _S_VERSION,
        true
    );

    // Review star ratings replacement
    wp_enqueue_script(
        'miheli-review-stars',
        get_stylesheet_directory_uri() . '/assets/js/review-stars.js',
        array('jquery'),
        _S_VERSION,
        true
    );


    // Cart page styles and scripts (only on cart page)
    if (is_cart()) {
        // Cart notifications system
        wp_enqueue_script(
            'miheli-cart-notifications',
            get_stylesheet_directory_uri() . '/assets/js/cart-notifications.js',
            array('jquery'),
            _S_VERSION,
            true
        );

        // Localize AJAX URL for cart updates
        wp_localize_script('miheli-cart-notifications', 'cartAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('woocommerce-cart')
        ));
    }

    // Checkout page styles and scripts (only on checkout page)
    if (is_checkout()) {
        // Checkout interactions (optional)
        wp_enqueue_script(
            'miheli-checkout-js',
            get_stylesheet_directory_uri() . '/assets/js/checkout.js',
            array('jquery'),
            _S_VERSION,
            true
        );
    }

    // Order details styles (checkout/order received + my account order view)
    $is_view_order = function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('view-order');
    if (is_checkout() || $is_view_order) {
        $orderdetails_css_path = get_stylesheet_directory() . '/assets/css/orderdetails.css';
        $orderdetails_css_version = file_exists($orderdetails_css_path) ? filemtime($orderdetails_css_path) : _S_VERSION;

        wp_enqueue_style(
            'miheli-orderdetails-css',
            get_stylesheet_directory_uri() . '/assets/css/orderdetails.css',
            array('miheli-child-custom-css'),
            $orderdetails_css_version
        );
    }

    // My Account page styles (only on account pages)
    if (function_exists('is_account_page') && is_account_page()) {

        wp_enqueue_script(
            'miheli-logout-confirm',
            get_stylesheet_directory_uri() . '/assets/js/logout-confirm.js',
            array('jquery'),
            _S_VERSION,
            true
        );

        wp_enqueue_script(
            'miheli-account-dropdown',
            get_stylesheet_directory_uri() . '/assets/js/account-menu-dropdown.js',
            array('bootstrap-js'),
            _S_VERSION,
            true
        );

        wp_localize_script('miheli-logout-confirm', 'miheliLogoutConfirm', array(
            'message' => __('Are you sure you want to log out? Confirm and log out.', 'miheli')
        ));

        // Manage Orders interactions (only on manage-orders endpoint)
        $is_manage_orders = get_query_var('manage-orders', null) !== null;
        if ($is_manage_orders) {
            wp_enqueue_script(
                'miheli-manage-orders',
                get_stylesheet_directory_uri() . '/assets/js/manage-orders.js',
                array('jquery'),
                _S_VERSION,
                true
            );
            wp_localize_script('miheli-manage-orders', 'miheliManageOrders', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('miheli_update_order_status')
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'miheli_child_theme_enqueue_styles');

/**
 * Add WooCommerce theme support
 */
function miheli_child_theme_setup()
{
    // Add WooCommerce support
    add_theme_support('woocommerce');

    // Optional: Add support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    // Register a custom single product image size used by the gallery (uncropped)
    add_image_size('miheli-single-product', 1000, 0, false);
}
add_action('after_setup_theme', 'miheli_child_theme_setup');

/**
 * Use uncropped source image for WooCommerce main gallery image
 */
function miheli_gallery_image_size()
{
    return 'full';
}
add_filter('woocommerce_gallery_image_size', 'miheli_gallery_image_size');

/**
 * Override WooCommerce cart page template
 * This hooks into wp_loaded to ensure the cart page uses our custom wrapper
 */
function miheli_override_woocommerce_templates()
{
    // Always enable child-template lookup so thank-you endpoint cannot skip override
    add_filter('woocommerce_locate_template', 'miheli_locate_woocommerce_template', 10, 3);
}
add_action('wp_loaded', 'miheli_override_woocommerce_templates');

/**
 * Locate WooCommerce templates from child theme first
 */
function miheli_locate_woocommerce_template($template, $template_name, $template_path)
{
    if ($template_name === 'checkout/thankyou.php') {
        $order_details_template = get_stylesheet_directory() . '/woocommerce/checkout/orderdetails.php';
        if (file_exists($order_details_template)) {
            return $order_details_template;
        }
    }

    if (is_cart() || is_checkout()) {
        $is_cart_template = strpos($template_name, 'cart') !== false;
        $is_checkout_template = strpos($template_name, 'checkout') !== false;

        if ($is_cart_template || $is_checkout_template) {
            $child_template = get_stylesheet_directory() . '/woocommerce/' . $template_name;

            if (file_exists($child_template)) {
                return $child_template;
            }
        }
    }


    return $template;
}

/**
 * Force classic checkout template when the checkout block is used
 * so the child theme template (form-checkout.php) renders.
 */
function miheli_force_classic_checkout_content($content)
{
    if (!function_exists('is_checkout') || !is_checkout() || is_admin()) {
        return $content;
    }

    if (function_exists('has_block')) {
        $has_checkout_block = has_block('woocommerce/checkout', $content) || has_block('woocommerce/checkout-block', $content);
        if ($has_checkout_block) {
            return '[woocommerce_checkout]';
        }
    }

    return $content;
}
add_filter('the_content', 'miheli_force_classic_checkout_content', 8);

/**
 * Ensure WooCommerce cart and checkout pages use our wrapper template
 * This filter forces the use of the woocommerce.php wrapper for cart/checkout
 */
function miheli_force_woocommerce_wrapper()
{
    if (!function_exists('is_woocommerce')) {
        return;
    }

    // For cart page - ensure our wrapper loads
    if (is_cart()) {
        // Load the wrapper with our custom template
        get_header();
        ?>
        <main id="primary" class="site-main">
            <?php
            // Load the actual cart template
            wc_get_template('cart/cart.php');
            do_action('woocommerce_after_main_content');
            ?>
        </main><!-- #primary -->
        <?php
        get_footer();
        // Exit to prevent the normal template from loading
        exit;
    } elseif (is_shop() || is_product_taxonomy()) {

        // Load the wrapper with our custom template
        get_header();
        ?>
        <main id="primary" class="site-main">
            <?php
            // Shop Archive/Category Pages - Load custom archive
            wc_get_template('archive-product.php');
            do_action('woocommerce_after_main_content');
            ?>
        </main><!-- #primary -->
        <?php
        get_footer();
        // Exit to prevent the normal template from loading
        exit;

    }
}
// Use the template_redirect hook which runs before template loading
add_action('template_redirect', 'miheli_force_woocommerce_wrapper', 5);

/**
 * Register custom My Account endpoint for managing all orders (admin/shop manager only)
 */
function miheli_register_manage_orders_endpoint()
{
    add_rewrite_endpoint('manage-orders', EP_ROOT | EP_PAGES);
}
add_action('init', 'miheli_register_manage_orders_endpoint');

function miheli_add_manage_orders_query_var($vars)
{
    $vars[] = 'manage-orders';
    return $vars;
}
add_filter('query_vars', 'miheli_add_manage_orders_query_var');

function miheli_account_menu_items($items)
{
    if (current_user_can('edit_shop_orders')) {
        $items['manage-orders'] = __('Manage Orders', 'miheli');
    }
    return $items;
}
add_filter('woocommerce_account_menu_items', 'miheli_account_menu_items');

/**
 * Render the Manage Orders endpoint content
 */
function miheli_render_manage_orders_endpoint()
{
    if (!current_user_can('edit_shop_orders')) {
        wc_print_notice(__('You do not have permission to manage orders.', 'miheli'), 'error');
        return;
    }

    wc_get_template(
        'myaccount/manage-orders.php',
        array(),
        '',
        get_stylesheet_directory() . '/woocommerce/'
    );
}
add_action('woocommerce_account_manage-orders_endpoint', 'miheli_render_manage_orders_endpoint');

/**
 * AJAX: Update order status (admin/shop manager only)
 */
function miheli_ajax_update_order_status()
{
    if (!current_user_can('edit_shop_orders')) {
        wp_send_json_error(array('message' => __('Permission denied', 'miheli')), 403);
    }

    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'miheli_update_order_status')) {
        wp_send_json_error(array('message' => __('Security check failed', 'miheli')), 400);
    }

    $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
    $new_status = isset($_POST['new_status']) ? sanitize_text_field($_POST['new_status']) : '';

    if (!$order_id || !$new_status) {
        wp_send_json_error(array('message' => __('Invalid data', 'miheli')));
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_send_json_error(array('message' => __('Order not found', 'miheli')));
    }

    // Normalize status key (allow 'processing' or 'wc-processing')
    if (strpos($new_status, 'wc-') !== 0) {
        $new_status = 'wc-' . $new_status;
    }

    $valid_statuses = wc_get_order_statuses();
    if (!array_key_exists($new_status, $valid_statuses)) {
        wp_send_json_error(array('message' => __('Invalid status', 'miheli')));
    }

    $order->update_status(substr($new_status, 3)); // update_status expects slug without 'wc-'

    wp_send_json_success(array(
        'order_id' => $order_id,
        'status' => $order->get_status(),
        'label' => wc_get_order_status_name('wc-' . $order->get_status()),
    ));
}
add_action('wp_ajax_miheli_update_order_status', 'miheli_ajax_update_order_status');

/**
 * Add My Account action buttons to the Orders endpoint
 */
function miheli_account_actions_header()
{
    ?>
    <div class="my-account-header">
        <div class="title"><?php echo esc_html__('My Orders', 'miheli'); ?></div>
        <div>
            <div class="account-actions-inline">
                <a class="btn btn-primary"
                    href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>"><?php echo esc_html__('Dashboard', 'miheli'); ?></a>

                <a class="btn btn-primary"
                    href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>"><?php echo esc_html__('Account Details', 'miheli'); ?></a>
                <a class="btn btn-primary js-confirm-logout"
                    href="<?php echo esc_url(wc_logout_url()); ?>"><?php echo esc_html__('Log out', 'miheli'); ?></a>
            </div>
            <div class="account-actions-dropdown dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <?php echo esc_html__('Account Menu', 'miheli'); ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                            href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>"><?php echo esc_html__('Dashboard', 'miheli'); ?></a>
                    </li>

                    <li><a class="dropdown-item"
                            href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>"><?php echo esc_html__('Account Details', 'miheli'); ?></a>
                    </li>
                    <li><a class="dropdown-item js-confirm-logout"
                            href="<?php echo esc_url(wc_logout_url()); ?>"><?php echo esc_html__('Log out', 'miheli'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}
add_action('woocommerce_before_account_orders', 'miheli_account_actions_header');

/**
 * AJAX handler for updating a single cart item
 */
function miheli_update_cart_item_ajax()
{
    // Verify nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'woocommerce-cart')) {
        wp_send_json_error(array('message' => 'Security check failed'), 400);
    }

    if (!isset($_POST['cart_key']) || !isset($_POST['quantity'])) {
        wp_send_json_error(array('message' => 'Invalid request'));
    }

    $cart_key = sanitize_text_field($_POST['cart_key']);
    $quantity = intval($_POST['quantity']);

    // Validate quantity
    if ($quantity < 1) {
        wp_send_json_error(array('message' => 'Invalid quantity'));
    }

    // Update the cart
    WC()->cart->set_quantity($cart_key, $quantity);
    WC()->cart->calculate_totals();

    // Get the updated cart item data
    $cart = WC()->cart->get_cart();
    $cart_item = isset($cart[$cart_key]) ? $cart[$cart_key] : null;

    if (!$cart_item) {
        wp_send_json_error(array('message' => 'Cart item not found'));
    }

    // Get product
    $product = $cart_item['data'];
    $subtotal = WC()->cart->get_product_subtotal($product, $quantity);

    // Get updated totals HTML with wrapper
    ob_start();
    ?>
    <div class="cart-totals-wrapper">
        <?php
        do_action('woocommerce_before_cart_collaterals');
        wc_get_template('cart/cart-totals.php');
        do_action('woocommerce_after_cart_collaterals');
        ?>
    </div>
    <?php
    $totals_html = ob_get_clean();

    wp_send_json_success(array(
        'subtotal' => $subtotal,
        'cart_totals' => $totals_html,
        'message' => 'Cart item updated'
    ));
}
add_action('wp_ajax_woocommerce_update_cart_item', 'miheli_update_cart_item_ajax');
add_action('wp_ajax_nopriv_woocommerce_update_cart_item', 'miheli_update_cart_item_ajax');
/**
 * AJAX handler for applying coupon
 */
function miheli_apply_coupon_ajax()
{
    // Verify nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'woocommerce-cart')) {
        wp_send_json_error(array('message' => 'Security check failed'), 400);
    }

    if (!isset($_POST['coupon_code'])) {
        wp_send_json_error(array('message' => 'Coupon code is required'));
    }

    $coupon_code = sanitize_text_field($_POST['coupon_code']);

    // Validate coupon code
    if (empty($coupon_code)) {
        wp_send_json_error(array('message' => 'Please enter a coupon code'));
    }

    // Try to apply the coupon
    $result = WC()->cart->add_discount($coupon_code);

    if ($result === false) {
        // Coupon invalid - get the error message
        $notices = wc_get_notices('error');
        $error_message = 'Invalid coupon code';

        if (!empty($notices)) {
            // Get the first error message
            $error_message = strip_tags($notices[0]['notice']);
        }

        // Clear the notices so they don't show on page
        wc_clear_notices();

        wp_send_json_error(array('message' => $error_message));
    }

    // Coupon applied successfully
    WC()->cart->calculate_totals();

    // Clear any notices
    wc_clear_notices();

    // Get updated totals HTML
    ob_start();
    ?>
    <div class="cart-totals-wrapper">
        <?php
        do_action('woocommerce_before_cart_collaterals');
        wc_get_template('cart/cart-totals.php');
        do_action('woocommerce_after_cart_collaterals');
        ?>
    </div>
    <?php
    $totals_html = ob_get_clean();

    wp_send_json_success(array(
        'message' => sprintf('Coupon "%s" applied successfully!', $coupon_code),
        'cart_totals' => $totals_html
    ));
}
add_action('wp_ajax_apply_coupon', 'miheli_apply_coupon_ajax');
add_action('wp_ajax_nopriv_apply_coupon', 'miheli_apply_coupon_ajax');

function miheli_loop_shop_per_page($cols)
{
    return 9;
}
add_filter('loop_shop_per_page', 'miheli_loop_shop_per_page', 20);

function miheli_adjust_products_per_page($query)
{
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_taxonomy())) {
        $query->set('posts_per_page', 9);
    }
}
add_action('pre_get_posts', 'miheli_adjust_products_per_page', 9);