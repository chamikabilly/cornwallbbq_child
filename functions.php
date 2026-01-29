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

    // Single blog post styles
    if (is_singular('post')) {
        wp_enqueue_style(
            'miheli-child-single-blog',
            get_stylesheet_directory_uri() . '/assets/css/single-blog.css',
            array('miheli-child-custom-css'),
            '1.0.0'
        );
    }

    // Single product styles
    if (is_singular('product')) {
        wp_enqueue_style(
            'miheli-child-single-product',
            get_stylesheet_directory_uri() . '/assets/css/single-product.css',
            array('miheli-child-custom-css'),
            '1.0.1'
        );
    }

    // Cart page styles
    if (is_cart()) {
        wp_enqueue_style(
            'miheli-child-cart',
            get_stylesheet_directory_uri() . '/assets/css/cart.css',
            array('miheli-child-custom-css'),
            '1.0.1'
        );
    }

    // Checkout page styles
    if (is_checkout()) {
        wp_enqueue_style(
            'miheli-child-checkout',
            get_stylesheet_directory_uri() . '/assets/css/checkout.css',
            array('miheli-child-custom-css'),
            '1.0.1'
        );
    }

    // Scripts - Load jQuery first, then Bootstrap, then your scripts
    wp_enqueue_script('jquery'); // Make sure jQuery is loaded

    wp_enqueue_script(
        'miheli-shop-js',
        get_stylesheet_directory_uri() . '/assets/js/shop.js',
        array('jquery', 'meheli-swiper-js'), // Add jQuery and Swiper as dependencies
        _S_VERSION,
        true
    );

    // Localize shop data for AJAX
    if (is_shop() || is_product_taxonomy()) {
        $miheli_shop_data = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('miheli_shop_nonce'),
        );
        wp_localize_script('miheli-shop-js', 'miheliShop', $miheli_shop_data);
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
}
add_action('after_setup_theme', 'miheli_child_theme_setup');
