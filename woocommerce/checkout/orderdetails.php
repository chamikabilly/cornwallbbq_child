<?php
/**
 * Order details content for checkout thank-you endpoint.
 */

defined('ABSPATH') || exit;
?>

<div class="miheli-orderdetails-page woocommerce-order">
    <?php if ($order): ?>

        <?php if ($order->has_status('failed')): ?>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
                <?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
            </p>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>"
                    class="button pay"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
                <?php if (is_user_logged_in()): ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                        class="button pay"><?php esc_html_e('My account', 'woocommerce'); ?></a>
                <?php endif; ?>
            </p>

        <?php else: ?>

            <div class="miheli-orderdetails-header">
                <h1 class="miheli-orderdetails-title"><?php esc_html_e('Order details', 'miheli'); ?></h1>
                <p class="miheli-orderdetails-subtitle">
                    <?php esc_html_e('Thank you. Your order has been received.', 'woocommerce'); ?>
                </p>
            </div>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
                <li class="woocommerce-order-overview__order order">
                    <?php esc_html_e('Order number:', 'woocommerce'); ?>
                    <strong><?php echo esc_html($order->get_order_number()); ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    <?php esc_html_e('Date:', 'woocommerce'); ?>
                    <strong><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></strong>
                </li>

                <?php if ($order->get_payment_method_title()): ?>
                    <li class="woocommerce-order-overview__payment-method method">
                        <?php esc_html_e('Payment method:', 'woocommerce'); ?>
                        <strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                    </li>
                <?php endif; ?>
            </ul>

            <?php
            wc_get_template('order/order-details.php', array(
                'order_id' => $order->get_id(),
                'show_downloads' => $order->has_downloadable_item() && $order->is_download_permitted(),
            ));
            ?>

        <?php endif; ?>

    <?php else: ?>

        <div class="miheli-orderdetails-header">
            <h1 class="miheli-orderdetails-title"><?php esc_html_e('Order details', 'miheli'); ?></h1>
            <p class="miheli-orderdetails-subtitle">
                <?php esc_html_e('Thank you. Your order has been received.', 'woocommerce'); ?>
            </p>
        </div>

    <?php endif; ?>
</div>