<?php
/**
 * WooCommerce Template
 * 
 * This template is used for ALL WooCommerce pages including the shop page.
 * It ensures our custom archive-product.php template is loaded.
 *
 * @package Miheli_Solutions_Child
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main">

    <?php
    /**
     * THIS TEMPLATE IS ONLY FOR SHOP/ARCHIVE PAGES
     * Cart and Checkout use their own dedicated template files:
     * - woocommerce/cart/cart.php
     * - woocommerce/checkout/form-checkout.php
     * 
     * Products use: woocommerce/single-product.php
     */
    if (is_shop() || is_product_taxonomy()) {
        // Shop Archive/Category Pages - Load our custom archive
        wc_get_template('archive-product.php');
        
        /**
         * Hook: woocommerce_after_main_content
         */
        do_action('woocommerce_after_main_content');
        
    } else {
        // Fallback for any other WooCommerce pages
        woocommerce_content();
    }
    ?>

</main><!-- #primary -->

<?php
get_footer();
