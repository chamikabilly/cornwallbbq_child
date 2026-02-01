<?php
/**
 * Cart Page Template - Production Ready
 * 
 * Features:
 * - Full-width responsive cart table
 * - Coupon and Cart Totals side-by-side layout
 * - Modern card-based design with gradient backgrounds
 * - AJAX cart updates without page reload
 * - Cross-sell products with hover effects
 * - Toast notifications for all actions
 * - Fully responsive for all screen sizes
 * - Optimized for performance
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 * @package Miheli_Solutions_Child
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="woocommerce-cart cart-page-wrapper">
    <!-- Bootstrap Toast Container (Top-Right) -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11000;"></div>

    <div class="container ps-lg-5 pe-lg-3 py-5">
        <!-- Main Cart Content -->
        <div class="row">
            <!-- Cart Items Section - Full Width -->
            <div class="col-12">
                <form class="woocommerce-cart-form cart-form-modern" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                    <?php do_action('woocommerce_before_cart_table'); ?>

                    <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                        <div class="cart-items-table-wrapper">
                            <table class="table table-hover cart-items-table woocommerce-cart-form__contents">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="col-remove"></th>
                                        <th scope="col" class="col-image d-none d-md-table-cell"><?php esc_html_e('Image', 'woocommerce'); ?></th>
                                        <th scope="col" class="col-product"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                                        <th scope="col" class="col-price d-none d-sm-table-cell text-end"><?php esc_html_e('Price', 'woocommerce'); ?></th>
                                        <th scope="col" class="col-qty text-center"><?php esc_html_e('Qty', 'woocommerce'); ?></th>
                                        <th scope="col" class="col-subtotal text-end"><?php esc_html_e('Total', 'woocommerce'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php do_action('woocommerce_before_cart_contents'); ?>

                                    <?php
                                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                            ?>
                                            <tr class="woocommerce-cart-form__cart-item cart-item-row" data-product-id="<?php echo esc_attr($product_id); ?>">
                                                <!-- Remove Button -->
                                                <td class="col-remove text-center" data-label="<?php esc_attr_e('Remove', 'woocommerce'); ?>">
                                                    <?php
                                                        echo apply_filters(
                                                            'woocommerce_cart_item_remove_link',
                                                            sprintf(
                                                                '<a href="%s" class="btn btn-sm btn-remove-item" title="%s" data-product_id="%s" data-product_sku="%s" data-cart-key="%s"><i class="fas fa-trash-alt"></i></a>',
                                                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                                esc_html__('Remove this item', 'woocommerce'),
                                                                esc_attr($product_id),
                                                                esc_attr($_product->get_sku()),
                                                                esc_attr($cart_item_key)
                                                            ),
                                                            $cart_item_key
                                                        );
                                                    ?>
                                                </td>

                                                <!-- Product Image -->
                                                <td class="col-image d-none d-md-table-cell" data-label="<?php esc_attr_e('Image', 'woocommerce'); ?>">
                                                    <div class="cart-product-image-wrapper">
                                                        <?php
                                                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key);

                                                        if (!$product_permalink) {
                                                            echo $thumbnail;
                                                        } else {
                                                            printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                                        }
                                                        ?>
                                                    </div>
                                                </td>

                                                <!-- Product Info -->
                                                <td class="col-product" data-label="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                                    <div class="product-info">
                                                        <?php
                                                        if (!$product_permalink) {
                                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                                                        } else {
                                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="product-link">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                        }

                                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                                        // Meta data
                                                        if ($item_data = wc_get_formatted_cart_item_data($cart_item)) {
                                                            echo wp_kses_post($item_data);
                                                        }

                                                        // Backorder notification
                                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="alert alert-warning alert-sm mt-2">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                                        }
                                                        ?>
                                                    </div>
                                                </td>

                                                <!-- Price -->
                                                <td class="col-price d-none d-sm-table-cell text-end" data-label="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                                                    <?php
                                                        echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                                    ?>
                                                </td>

                                                <!-- Quantity -->
                                                <td class="col-qty" data-label="<?php esc_attr_e('Qty', 'woocommerce'); ?>">
                                                    <?php
                                                    if ($_product->is_sold_individually()) {
                                                        echo '1 <input type="hidden" name="cart[' . esc_attr($cart_item_key) . '][qty]" value="1" />';
                                                    } else {
                                                        ?>
                                                        <div class="quantity-wrapper d-flex align-items-center gap-2">
                                                            <div class="quantity">
                                                                <button type="button" class="qty-btn minus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">−</button>
                                                                <input type="number" 
                                                                       name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]" 
                                                                       value="<?php echo (int)$cart_item['quantity']; ?>" 
                                                                       min="1" 
                                                                       <?php $max = (int)$_product->get_max_purchase_quantity(); if ($max > 0) { ?>max="<?php echo $max; ?>"<?php } ?>
                                                                       class="qty"
                                                                       data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                                                                <button type="button" class="qty-btn plus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-update-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>" title="<?php esc_attr_e('Update', 'woocommerce'); ?>">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Subtotal -->
                                                <td class="col-subtotal text-end" data-label="<?php esc_attr_e('Total', 'woocommerce'); ?>">
                                                    <?php
                                                        echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                    <?php do_action('woocommerce_cart_contents'); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Cart Actions with Coupon and Totals -->
                        <div class="cart-actions-totals-wrapper mt-4">
                            <div class="row g-4">
                                <!-- Coupon Section -->
                                <?php if (wc_coupons_enabled()) { ?>
                                    <div class="col-lg-6">
                                        <div class="coupon-section p-4 rounded h-100">
                                            <h5 class="section-title mb-3"><?php esc_html_e('Have a coupon?', 'woocommerce'); ?></h5>
                                            <div class="input-group">
                                                <input type="text" name="coupon_code" class="form-control coupon-input" id="coupon_code" value="" placeholder="<?php esc_attr_e('Enter coupon code', 'woocommerce'); ?>" />
                                                <button type="submit" class="btn btn-apply-coupon" name="apply_coupon" value="<?php esc_attr_e('Apply', 'woocommerce'); ?>">
                                                    <i class="fas fa-tag me-2"></i><?php esc_html_e('Apply', 'woocommerce'); ?>
                                                </button>
                                            </div>
                                            <?php do_action('woocommerce_cart_coupon'); ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <!-- Cart Totals Section -->
                                <div class="col-lg-<?php echo wc_coupons_enabled() ? '6' : '12'; ?>">
                                    <div class="cart-totals-modern p-4 rounded h-100">
                                        <?php
                                        /**
                                         * Cart collaterals hook.
                                         * @hooked woocommerce_cart_totals - 10
                                         */
                                        do_action('woocommerce_cart_collaterals');
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php do_action('woocommerce_cart_actions'); ?>
                            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                        </div>

                        <!-- Cross-Sell Products Section -->
                        <?php
                        $cross_sells = WC()->cart->get_cross_sells();
                        if (!empty($cross_sells)) :
                            ?>
                            <div class="cross-sells-section mt-5">
                                <h3 class="cross-sells-title mb-4"><?php esc_html_e('You may also like...', 'woocommerce'); ?></h3>
                                <?php
                                $args = array(
                                    'post_type' => 'product',
                                    'post__in' => $cross_sells,
                                    'posts_per_page' => 4,
                                    'orderby' => 'rand'
                                );
                                
                                $products = new WP_Query($args);
                                
                                if ($products->have_posts()) :
                                    ?>
                                    <div class="row g-4">
                                        <?php
                                        while ($products->have_posts()) : $products->the_post();
                                            global $product;
                                            $product_id = $product->get_id();
                                            ?>
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="miheli-product-inner">
                                                    <div class="product-container animate-on-scroll">
                                                        <div class="product-img-holder">
                                                            <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                                                            <div class="product-hover-actions">
                                                                <button class="add-to-cart-btn" data-product-id="<?php echo $product_id; ?>"
                                                                    data-is-variable="<?php echo $product->is_type('variable') ? 'true' : 'false'; ?>">
                                                                    <i class="fa-solid fa-cart-plus"></i>
                                                                </button>
                                                                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="quick-view-btn">
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
                                                        <div class="product-content-holder">
                                                            <h6 class="product-title"><?php echo esc_html($product->get_name()); ?></h6>
                                                            <p class="product-prize"><?php echo $product->get_price_html(); ?></p>
                                                            <p class="star-ratings">
                                                                <?php
                                                                $rating = floatval($product->get_average_rating());
                                                                if ($rating < 0) $rating = 0;
                                                                if ($rating > 5) $rating = 5;
                                                                for ($s = 1; $s <= 5; $s++) {
                                                                    $star_fill = max(0, min(1, $rating - ($s - 1)));
                                                                    $clip_width = 17 * $star_fill;
                                                                    $clip_id = 'clip-cart-' . $product_id . '-' . $s;
                                                                ?>
                                                                    <svg class="star" width="17" height="17" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                                                        <defs>
                                                                            <clipPath id="<?php echo esc_attr($clip_id); ?>">
                                                                                <rect x="0" y="0" width="<?php echo esc_attr(number_format((float) $clip_width, 3, '.', '')); ?>" height="17" />
                                                                            </clipPath>
                                                                        </defs>
                                                                        <path d="M7.87292 2.00164C8.06503 1.42216 8.16104 1.13243 8.30311 1.05213C8.42601 0.982624 8.57397 0.982624 8.69696 1.05213C8.83895 1.13243 8.93496 1.42216 9.12707 2.00164L10.397 5.83256C10.4517 5.99749 10.479 6.07995 10.5283 6.14136C10.5718 6.19561 10.6273 6.23789 10.6899 6.26452C10.7608 6.29467 10.844 6.29644 11.0105 6.3L14.8774 6.38264C15.4624 6.39513 15.7548 6.40139 15.8716 6.51821C15.9726 6.61932 16.0183 6.76693 15.9933 6.91099C15.9644 7.07747 15.7313 7.26273 15.2651 7.63343L12.1829 10.0836C12.0502 10.1892 11.9839 10.2419 11.9434 10.31C11.9077 10.3702 11.8865 10.4386 11.8817 10.5092C11.8763 10.5893 11.9003 10.6728 11.9486 10.84L13.0686 14.722C13.238 15.3092 13.3227 15.6028 13.2528 15.7553C13.1923 15.8873 13.0726 15.9785 12.9342 15.9981C12.7743 16.0206 12.5342 15.8454 12.054 15.495L8.87919 13.1785C8.74252 13.0787 8.67422 13.0289 8.59995 13.0095C8.53431 12.9924 8.46568 12.9924 8.40012 13.0095C8.32585 13.0289 8.25747 13.0787 8.12079 13.1785L4.94607 15.495C4.46586 15.8454 4.22575 16.0206 4.06583 15.9981C3.92744 15.9785 3.80769 15.8873 3.7472 15.7553C3.67731 15.6028 3.76203 15.3092 3.93144 14.722L5.05145 10.84C5.09966 10.6728 5.12377 10.5893 5.11834 10.5092C5.11355 10.4386 5.09235 10.3702 5.05659 10.31C5.01612 10.2419 4.94978 10.1892 4.81709 10.0836L1.73499 7.63343C1.26878 7.26273 1.03567 7.07747 1.00674 6.91099C0.981685 6.76693 1.02743 6.61932 1.12844 6.51821C1.24515 6.40139 1.53762 6.39514 2.12254 6.38264L5.98949 6.3C6.15596 6.29644 6.2392 6.29467 6.31012 6.26452C6.37276 6.23789 6.42826 6.19561 6.47177 6.14136C6.52103 6.07995 6.54837 5.99749 6.60304 5.83256L7.87292 2.00164Z" fill="#FFAC42" clip-path="url(#<?php echo esc_attr($clip_id); ?>)" />
                                                                        <path d="M7.87292 2.00164C8.06503 1.42216 8.16104 1.13243 8.30311 1.05213C8.42601 0.982624 8.57397 0.982624 8.69696 1.05213C8.83895 1.13243 8.93496 1.42216 9.12707 2.00164L10.397 5.83256C10.4517 5.99749 10.479 6.07995 10.5283 6.14136C10.5718 6.19561 10.6273 6.23789 10.6899 6.26452C10.7608 6.29467 10.844 6.29644 11.0105 6.3L14.8774 6.38264C15.4624 6.39513 15.7548 6.40139 15.8716 6.51821C15.9726 6.61932 16.0183 6.76693 15.9933 6.91099C15.9644 7.07747 15.7313 7.26273 15.2651 7.63343L12.1829 10.0836C12.0502 10.1892 11.9839 10.2419 11.9434 10.31C11.9077 10.3702 11.8865 10.4386 11.8817 10.5092C11.8763 10.5893 11.9003 10.6728 11.9486 10.84L13.0686 14.722C13.238 15.3092 13.3227 15.6028 13.2528 15.7553C13.1923 15.8873 13.0726 15.9785 12.9342 15.9981C12.7743 16.0206 12.5342 15.8454 12.054 15.495L8.87919 13.1785C8.74252 13.0787 8.67422 13.0289 8.59995 13.0095C8.53431 12.9924 8.46568 12.9924 8.40012 13.0095C8.32585 13.0289 8.25747 13.0787 8.12079 13.1785L4.94607 15.495C4.46586 15.8454 4.22575 16.0206 4.06583 15.9981C3.92744 15.9785 3.80769 15.8873 3.7472 15.7553C3.67731 15.6028 3.76203 15.3092 3.93144 14.722L5.05145 10.84C5.09966 10.6728 5.12377 10.5893 5.11834 10.5092C5.11355 10.4386 5.09235 10.3702 5.05659 10.31C5.01612 10.2419 4.94978 10.1892 4.81709 10.0836L1.73499 7.63343C1.26878 7.26273 1.03567 7.07747 1.00674 6.91099C0.981685 6.76693 1.02743 6.61932 1.12844 6.51821C1.24515 6.40139 1.53762 6.39514 2.12254 6.38264L5.98949 6.3C6.15596 6.29644 6.2392 6.29467 6.31012 6.26452C6.37276 6.23789 6.42826 6.19561 6.47177 6.14136C6.52103 6.07995 6.54837 5.99749 6.60304 5.83256L7.87292 2.00164Z" fill="none" stroke="#FFAC42" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                    </svg>
                                                                <?php
                                                                }
                                                                ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                    <?php
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <!-- Empty Cart Message -->
                        <div class="alert alert-empty-cart p-5 text-center rounded-lg">
                            <i class="fas fa-shopping-cart icon-empty-cart mb-3"></i>
                            <h3 class="text-white mb-3"><?php esc_html_e('Your cart is empty', 'woocommerce'); ?></h3>
                            <p class="text-muted-light mb-4"><?php esc_html_e('Continue shopping to add items to your cart.', 'woocommerce'); ?></p>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-continue-shopping">
                                <i class="fas fa-arrow-left me-2"></i><?php esc_html_e('Continue Shopping', 'woocommerce'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php do_action('woocommerce_after_cart_table'); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php do_action('woocommerce_after_cart'); ?>
