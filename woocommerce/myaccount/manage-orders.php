<?php
/**
 * My Account: Manage Orders (Admin/Shop Manager)
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('edit_shop_orders')) {
    wc_print_notice(__('You do not have permission to view this page.', 'miheli'), 'error');
    return;
}

$statuses = wc_get_order_statuses();

// Optional: filters
$limit = isset($_GET['limit']) ? max(5, intval($_GET['limit'])) : 20;
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

$args = array(
    'type' => 'shop_order',
    'limit' => $limit,
    'orderby' => 'date',
    'order' => 'DESC',
);

if ($status_filter) {
    if (strpos($status_filter, 'wc-') !== 0) {
        $status_filter = 'wc-' . $status_filter;
    }
    $args['status'] = array($status_filter);
}

$orders = wc_get_orders($args);
?>

<div class="my-account-wrapper">
    <div class="my-account-header">
        <div class="title">Manage Orders</div>
        <div>
            <a class="btn btn-primary" href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Back to
                Dashboard</a>
        </div>
    </div>

    <div class="manage-orders-toolbar">
        <form method="get">
            <input type="hidden" name="manage-orders" value="" />
            <select name="status" class="form-select" style="max-width:220px">
                <option value="">All statuses</option>
                <?php foreach ($statuses as $key => $label): ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($status_filter, $key); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="limit" class="form-select" style="max-width:140px">
                <?php foreach (array(20, 50, 100) as $opt): ?>
                    <option value="<?php echo esc_attr($opt); ?>" <?php selected($limit, $opt); ?>>Show
                        <?php echo esc_html($opt); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Apply</button>
        </form>
    </div>

    <div class="table-modern-wrap">
        <table class="table-modern manage-orders-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Change Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order):
                        if (!($order instanceof WC_Order) || $order->get_type() !== 'shop_order') {
                            continue;
                        }

                        $customer_name = trim($order->get_formatted_billing_full_name());
                        if (!$customer_name) {
                            $customer_name = __('Guest', 'miheli');
                        }
                        $current_status = 'wc-' . $order->get_status();
                        ?>
                        <tr data-order-id="<?php echo esc_attr($order->get_id()); ?>">
                            <td><a
                                    href="<?php echo esc_url($order->get_edit_order_url()); ?>">#<?php echo esc_html($order->get_order_number()); ?></a>
                            </td>
                            <td><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></td>
                            <td><?php echo esc_html($customer_name); ?></td>
                            <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                            <td><span
                                    class="js-status-label"><?php echo esc_html(wc_get_order_status_name($current_status)); ?></span>
                            </td>
                            <td class="action-cell">
                                <select class="form-select status-select">
                                    <?php foreach ($statuses as $key => $label): ?>
                                        <option value="<?php echo esc_attr($key); ?>" <?php selected($current_status, $key); ?>>
                                            <?php echo esc_html($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="action-cell">
                                <button type="button" class="btn btn-sm btn-primary js-update-status">Update</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>