<?php
/**
 * The template for displaying single product
 * WooCommerce template override with custom styling
 *
 * @package Miheli_Solutions_Child
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

<main id="primary" class="site-main single-product-page test">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <?php wc_get_template_part('content', 'single-product'); ?>

        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer('shop');
