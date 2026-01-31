<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wp;
$order_id = isset($wp->query_vars['view-order']) ? absint($wp->query_vars['view-order']) : 0;
$order = $order_id ? wc_get_order($order_id) : false;

if (!$order) {
    wc_print_notice(__('Order not found.', 'woocommerce'), 'error');
    return;
}

$status_key = 'wc-' . $order->get_status();
$status_label = wc_get_order_status_name($status_key);
$order_date = $order->get_date_created() ? wc_format_datetime($order->get_date_created()) : '';
$total_paid = $order->get_total();
$payment_method = $order->get_payment_method_title();
?>

<div class="my-account-wrapper">
    <div class="my-account-header">
        <div class="title">Order #<?php echo esc_html($order->get_order_number()); ?></div>
        <div>
            <div class="account-actions-inline">
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Dashboard</a>
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>">View Orders</a>
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account
                    Details</a>
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('customer-logout')); ?>">Log out</a>
            </div>
            <div class="account-actions-dropdown dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Account Menu
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Dashboard</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>">View Orders</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account Details</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_endpoint_url('customer-logout')); ?>">Log out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="card-kpi">
            <div class="icon"><i class="fa-solid fa-hashtag"></i></div>
            <div class="meta">
                <div class="label">Order Number</div>
                <div class="value">#<?php echo esc_html($order->get_order_number()); ?></div>
            </div>
        </div>
        <div class="card-kpi">
            <div class="icon"><i class="fa-regular fa-calendar"></i></div>
            <div class="meta">
                <div class="label">Date</div>
                <div class="value"><?php echo esc_html($order_date); ?></div>
            </div>
        </div>
        <div class="card-kpi">
            <div class="icon"><i class="fa-solid fa-credit-card"></i></div>
            <div class="meta">
                <div class="label">Payment</div>
                <div class="value"><?php echo esc_html($payment_method); ?></div>
            </div>
        </div>
        <div class="card-kpi">
            <div class="icon"><i class="fa-solid fa-ticket"></i></div>
            <div class="meta">
                <div class="label">Status</div>
                <div class="value"><span
                        class="status-badge status-<?php echo esc_attr($order->get_status()); ?>"><?php echo esc_html($status_label); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <div>Items</div>
            <div></div>
        </div>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order->get_items() as $item_id => $item): ?>
                    <?php $product = $item->get_product(); ?>
                    <tr>
                        <td>
                            <?php
                            $name = $item->get_name();
                            echo esc_html($name);
                            ?>
                        </td>
                        <td><?php echo esc_html($item->get_quantity()); ?></td>
                        <td><?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            <div>Summary</div>
        </div>
        <table class="table-modern">
            <tbody>
                <?php foreach ($order->get_order_item_totals() as $key => $total): ?>
                    <tr>
                        <td><?php echo esc_html($total['label']); ?></td>
                        <td style="text-align:right"><?php echo wp_kses_post($total['value']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            <div>Addresses</div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="table-modern" style="padding:1rem">
                    <div style="font-weight:600; color:#6b7280; margin-bottom:0.5rem">Billing Address</div>
                    <address>
                        <?php echo wp_kses_post($order->get_formatted_billing_address() ?: __('N/A', 'woocommerce')); ?>
                    </address>
                    <div style="margin-top:0.5rem; color:#6b7280">Email:
                        <?php echo esc_html($order->get_billing_email()); ?>
                    </div>
                    <div style="color:#6b7280">Phone: <?php echo esc_html($order->get_billing_phone()); ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-modern" style="padding:1rem">
                    <div style="font-weight:600; color:#6b7280; margin-bottom:0.5rem">Shipping Address</div>
                    <address>
                        <?php echo wp_kses_post($order->get_formatted_shipping_address() ?: __('N/A', 'woocommerce')); ?>
                    </address>
                </div>
            </div>
        </div>
    </div>
</div>