<?php

/**
 * Product content within loops
 */

defined('ABSPATH') || exit;

global $product;
if (empty($product) || !$product->is_visible()) {
    return;
}
$product_id = $product->get_id();
$product_type = $product->get_type();
$is_variable = $product_type === 'variable';
?>

<li <?php wc_product_class('miheli-product-card', $product); ?>>
    <div class="miheli-product-inner">

        <div class="product-container animate-on-scroll">
            <div class="product-img-holder ">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium');
                } else {
                    echo '<img src="' . $home . '/wp-content/themes/miheli-solutions/assets/images/test-img.png" alt="' . get_the_title() . '" loading="lazy">';
                }
                ?>
                <div class="product-hover-actions">
                    <button class="add-to-cart-btn" data-product-id="<?php echo $product_id; ?>"
                        data-is-variable="<?php echo $is_variable ? 'true' : 'false'; ?>">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                    <a href="<?php the_permalink(); ?>" class="quick-view-btn">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </div>
                <?php
                if ($product->is_on_sale()) {
                ?>
                    <div class="product-sale-badge">
                        <span class="sale-text">Sale</span>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="product-content-holder ">
                <h6 class="product-title"><?php the_title(); ?></h6>
                <p class="product-prize"><?php echo $product->get_price_html(); ?></p>
                <p class="star-ratings">
                    <?php
                    // Display star ratings as SVG stars (fallback uses SVG too)
                    $rating = floatval($product->get_average_rating());
                    // Normalize rating between 0 and 5
                    if ($rating < 0) {
                        $rating = 0;
                    }
                    if ($rating > 5) {
                        $rating = 5;
                    }
                    for ($s = 1; $s <= 5; $s++) {
                        // Calculate how much of this star should be filled (0.0 - 1.0)
                        $star_fill = max(0, min(1, $rating - ($s - 1)));
                        $clip_width = 17 * $star_fill; // SVG viewBox is 0 0 17 17
                        $clip_id = 'clip-' . $product_id . '-' . $s;
                    ?>
                        <svg class="star" width="17" height="17" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <defs>
                                <clipPath id="<?php echo esc_attr($clip_id); ?>">
                                    <rect x="0" y="0" width="<?php echo esc_attr(number_format((float) $clip_width, 3, '.', '')); ?>" height="17" />
                                </clipPath>
                            </defs>
                            <!-- Filled portion (clipped to percentage) -->
                            <path d="M7.87292 2.00164C8.06503 1.42216 8.16104 1.13243 8.30311 1.05213C8.42601 0.982624 8.57397 0.982624 8.69696 1.05213C8.83895 1.13243 8.93496 1.42216 9.12707 2.00164L10.397 5.83256C10.4517 5.99749 10.479 6.07995 10.5283 6.14136C10.5718 6.19561 10.6273 6.23789 10.6899 6.26452C10.7608 6.29467 10.844 6.29644 11.0105 6.3L14.8774 6.38264C15.4624 6.39513 15.7548 6.40139 15.8716 6.51821C15.9726 6.61932 16.0183 6.76693 15.9933 6.91099C15.9644 7.07747 15.7313 7.26273 15.2651 7.63343L12.1829 10.0836C12.0502 10.1892 11.9839 10.2419 11.9434 10.31C11.9077 10.3702 11.8865 10.4386 11.8817 10.5092C11.8763 10.5893 11.9003 10.6728 11.9486 10.84L13.0686 14.722C13.238 15.3092 13.3227 15.6028 13.2528 15.7553C13.1923 15.8873 13.0726 15.9785 12.9342 15.9981C12.7743 16.0206 12.5342 15.8454 12.054 15.495L8.87919 13.1785C8.74252 13.0787 8.67422 13.0289 8.59995 13.0095C8.53431 12.9924 8.46568 12.9924 8.40012 13.0095C8.32585 13.0289 8.25747 13.0787 8.12079 13.1785L4.94607 15.495C4.46586 15.8454 4.22575 16.0206 4.06583 15.9981C3.92744 15.9785 3.80769 15.8873 3.7472 15.7553C3.67731 15.6028 3.76203 15.3092 3.93144 14.722L5.05145 10.84C5.09966 10.6728 5.12377 10.5893 5.11834 10.5092C5.11355 10.4386 5.09235 10.3702 5.05659 10.31C5.01612 10.2419 4.94978 10.1892 4.81709 10.0836L1.73499 7.63343C1.26878 7.26273 1.03567 7.07747 1.00674 6.91099C0.981685 6.76693 1.02743 6.61932 1.12844 6.51821C1.24515 6.40139 1.53762 6.39514 2.12254 6.38264L5.98949 6.3C6.15596 6.29644 6.2392 6.29467 6.31012 6.26452C6.37276 6.23789 6.42826 6.19561 6.47177 6.14136C6.52103 6.07995 6.54837 5.99749 6.60304 5.83256L7.87292 2.00164Z" fill="#FFAC42" clip-path="url(#<?php echo esc_attr($clip_id); ?>)" />
                            <!-- Stroke (outline) on top so shape remains visible when partially filled) -->
                            <path d="M7.87292 2.00164C8.06503 1.42216 8.16104 1.13243 8.30311 1.05213C8.42601 0.982624 8.57397 0.982624 8.69696 1.05213C8.83895 1.13243 8.93496 1.42216 9.12707 2.00164L10.397 5.83256C10.4517 5.99749 10.479 6.07995 10.5283 6.14136C10.5718 6.19561 10.6273 6.23789 10.6899 6.26452C10.7608 6.29467 10.844 6.29644 11.0105 6.3L14.8774 6.38264C15.4624 6.39513 15.7548 6.40139 15.8716 6.51821C15.9726 6.61932 16.0183 6.76693 15.9933 6.91099C15.9644 7.07747 15.7313 7.26273 15.2651 7.63343L12.1829 10.0836C12.0502 10.1892 11.9839 10.2419 11.9434 10.31C11.9077 10.3702 11.8865 10.4386 11.8817 10.5092C11.8763 10.5893 11.9003 10.6728 11.9486 10.84L13.0686 14.722C13.238 15.3092 13.3227 15.6028 13.2528 15.7553C13.1923 15.8873 13.0726 15.9785 12.9342 15.9981C12.7743 16.0206 12.5342 15.8454 12.054 15.495L8.87919 13.1785C8.74252 13.0787 8.67422 13.0289 8.59995 13.0095C8.53431 12.9924 8.46568 12.9924 8.40012 13.0095C8.32585 13.0289 8.25747 13.0787 8.12079 13.1785L4.94607 15.495C4.46586 15.8454 4.22575 16.0206 4.06583 15.9981C3.92744 15.9785 3.80769 15.8873 3.7472 15.7553C3.67731 15.6028 3.76203 15.3092 3.93144 14.722L5.05145 10.84C5.09966 10.6728 5.12377 10.5893 5.11834 10.5092C5.11355 10.4386 5.09235 10.3702 5.05659 10.31C5.01612 10.2419 4.94978 10.1892 4.81709 10.0836L1.73499 7.63343C1.26878 7.26273 1.03567 7.07747 1.00674 6.91099C0.981685 6.76693 1.02743 6.61932 1.12844 6.51821C1.24515 6.40139 1.53762 6.39514 2.12254 6.38264L5.98949 6.3C6.15596 6.29644 6.2392 6.29467 6.31012 6.26452C6.37276 6.23789 6.42826 6.19561 6.47177 6.14136C6.52103 6.07995 6.54837 5.99749 6.60304 5.83256L7.87292 2.00164Z" fill="none" stroke="#FFAC42" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    <?php
                    }
                    ?>
                </p>
            </div>
        </div>

    </div>
</li>