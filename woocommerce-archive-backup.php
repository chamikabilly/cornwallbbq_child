<?php
/**
 * WooCommerce ARCHIVE Template (BACKUP - NOT USED)
 * 
 * This file is backed up here.
 * We should NOT have a woocommerce.php file because it intercepts ALL WooCommerce pages.
 * Instead, we rely on WordPress's natural template hierarchy:
 * - Cart uses: woocommerce/cart/cart.php
 * - Checkout uses: woocommerce/checkout/form-checkout.php
 * - Products use: woocommerce/single-product.php
 * - Shop uses: woocommerce/archive-product.php
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
