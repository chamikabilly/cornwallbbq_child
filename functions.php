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

    // Product gallery JS (only on single product)
        // Swiper (slider) and GLightbox (lightbox) from CDN
        wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper@9/swiper-bundle.min.css', array(), '9.0.0');
        wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@9/swiper-bundle.min.js', array(), '9.0.0', true);

        // GLightbox (lightbox)
        wp_enqueue_style('glightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', array(), '3.2.0');
        wp_enqueue_script('glightbox-js', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', array(), '3.2.0', true);

        // Bootstrap (for modal)
        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');
        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true);

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
    // Register a custom single product image size used by the gallery
    add_image_size('miheli-single-product', 1000, 1000, true);
}
add_action('after_setup_theme', 'miheli_child_theme_setup');

/**
 * Use the custom image size for the WooCommerce gallery images
 */
function miheli_gallery_image_size() {
    return 'miheli-single-product';
}
add_filter('woocommerce_gallery_image_size', 'miheli_gallery_image_size');
