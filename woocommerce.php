<?php
/**
 * WooCommerce Template Wrapper
 *
 * This template is used as a fallback wrapper for WooCommerce pages.
 * The main routing is handled by template_redirect hook in functions.php
 * to ensure custom templates are properly loaded.
 *
 * @package Miheli_Solutions_Child
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main">
    <?php
    /**
     * Template routing for WooCommerce pages
     */
    
        // Fallback for any other WooCommerce pages (product pages, etc)
        woocommerce_content();
    ?>
</main><!-- #primary -->

<?php
get_footer();
