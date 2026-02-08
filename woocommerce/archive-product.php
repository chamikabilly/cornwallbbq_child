<?php

/**
 * Template for displaying products archive (Shop page)
 * Overrides WooCommerce default archive-product
 */

defined('ABSPATH') || exit;

// Get current category slug
$current_category = '';
if (is_product_category()) {
    $current_category = get_queried_object()->slug;
}

$filter_action_url = is_product_category()
    ? get_term_link(get_queried_object())
    : get_post_type_archive_link('product');
$min_price = isset($_GET['min_price']) ? wc_clean(wp_unslash($_GET['min_price'])) : '';
$max_price = isset($_GET['max_price']) ? wc_clean(wp_unslash($_GET['max_price'])) : '';
$preserve_query_keys = array('orderby', 'order', 's', 'post_type');
?>
<div class="container shop-archive" data-current-category="<?php echo esc_attr($current_category); ?>">

    <div class="shop-top-controls">
        <!-- Category titles slider -->
        <div class="swiper shop-cats-swiper">
            <div class="swiper-wrapper">
                <?php
                $terms = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                ));
                // Slides must be direct children of .swiper-wrapper
                echo '<a class="swiper-slide active" data-slug="" href="' . esc_url(get_post_type_archive_link('product')) . '">All</a>';
                if (!is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        echo '<a class="swiper-slide" data-slug="' . esc_attr($term->slug) . '" href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                    }
                }
                ?>
            </div>
            <div class="shop-cats-swiper-button-prev" aria-label="Previous categories"></div>
            <div class="shop-cats-swiper-button-next" aria-label="Next categories"></div>
        </div>

        <!-- Price filter -->
        <form id="shop-price-filter" class="shop-price-filter my-5 text-light" method="get"
            action="<?php echo esc_url($filter_action_url); ?>" data-ajax="0">
            <span class="filter-label mx-2 text-uppercase"><i class="fa-solid fa-arrow-down-wide-short mx-1"></i> Price
                Filter</span>
            <?php foreach ($preserve_query_keys as $key): ?>
                <?php if (isset($_GET[$key])): ?>
                    <input type="hidden" name="<?php echo esc_attr($key); ?>"
                        value="<?php echo esc_attr(wc_clean(wp_unslash($_GET[$key]))); ?>" />
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="d-flex align-items-center gap-2">
                <label for="min_price" class="text-uppercase">Min</label><input type="number" min="0" step="0.01"
                    name="min_price" class="form-control form-control-sm" value="<?php echo esc_attr($min_price); ?>" />
                <label for="max_price" class="text-uppercase">Max</label><input type="number" min="0" step="0.01"
                    name="max_price" class="form-control form-control-sm" value="<?php echo esc_attr($max_price); ?>" />
                <button type="submit" class="btn btn-sm btn-primary w-50">Apply Filter</button>
            </div>
        </form>
    </div>


    <?php if (woocommerce_product_loop()): ?>
        <div id="shop-products" class="mt-3">
            <?php
            woocommerce_product_loop_start();

            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();
                    wc_get_template_part('content', 'product');
                }
            }

            woocommerce_product_loop_end();
            ?>
        </div>
        <!-- Bottom pagination -->
        <div id="shop-pagination-bottom" class="my-3">
            <?php woocommerce_pagination(); ?>
        </div>
    <?php else: ?>
        <?php do_action('woocommerce_no_products_found'); ?>
    <?php endif; ?>
</div>