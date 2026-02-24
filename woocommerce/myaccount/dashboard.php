<?php
/**
 * My Account Dashboard
 *
 * This template overrides WooCommerce's myaccount/dashboard.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$current_user_id = get_current_user_id();
$is_manager = current_user_can('edit_shop_orders') || current_user_can('manage_woocommerce');
$total_orders = $is_manager
    ? count(wc_get_orders(array('type' => 'shop_order', 'return' => 'ids', 'limit' => -1)))
    : (function_exists('wc_get_customer_order_count') ? wc_get_customer_order_count($current_user_id) : 0);
$total_spent = function_exists('wc_get_customer_total_spent') ? wc_get_customer_total_spent($current_user_id) : 0;

$last_order_query = array(
    'type' => 'shop_order',
    'limit' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
);
if (!$is_manager) {
    $last_order_query['customer_id'] = $current_user_id;
}
$last_order_arr = wc_get_orders($last_order_query);
$last_order = !empty($last_order_arr) ? $last_order_arr[0] : false;
$last_status = $last_order ? wc_get_order_status_name('wc-' . $last_order->get_status()) : __('No orders yet', 'miheli');

$processing_query = array(
    'type' => 'shop_order',
    'status' => array('wc-processing', 'wc-on-hold'),
    'limit' => -1,
    'return' => 'ids',
);
if (!$is_manager) {
    $processing_query['customer_id'] = $current_user_id;
}
$processing_count = count(wc_get_orders($processing_query));

$pending_query = array(
    'type' => 'shop_order',
    'status' => array('wc-pending'),
    'limit' => -1,
    'return' => 'ids',
);
if (!$is_manager) {
    $pending_query['customer_id'] = $current_user_id;
}
$pending_count = count(wc_get_orders($pending_query));

$completed_query = array(
    'type' => 'shop_order',
    'status' => array('wc-completed'),
    'limit' => -1,
    'return' => 'ids',
);
if (!$is_manager) {
    $completed_query['customer_id'] = $current_user_id;
}
$completed_count = count(wc_get_orders($completed_query));
?>

<div class="my-account-wrapper">
    <div class="my-account-header">
        <div class="title">My Dashboard</div>
        <div>
            <div class="account-actions-inline">
                <a class="btn btn-primary"
                    href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Dashboard</a>
                <?php if ($is_manager): ?>
                    <a class="btn btn-primary"
                        href="<?php echo esc_url(wc_get_account_endpoint_url('manage-orders')); ?>">Manage Orders</a>
                <?php endif; ?>
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account
                    Details</a>
                <a class="btn btn-primary js-confirm-logout" href="<?php echo esc_url(wc_logout_url()); ?>">Log
                    out</a>
            </div>
            <div class="account-actions-dropdown dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Account Menu
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                            href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Dashboard</a></li>
                    <?php if ($is_manager): ?>
                        <li><a class="dropdown-item"
                                href="<?php echo esc_url(wc_get_account_endpoint_url('manage-orders')); ?>">Manage
                                Orders</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item"
                            href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account Details</a></li>
                    <li><a class="dropdown-item js-confirm-logout" href="<?php echo esc_url(wc_logout_url()); ?>">Log
                            out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="card-kpi">
            <div class="icon"><i class="fa-solid fa-receipt"></i></div>
            <div class="meta">
                <div class="label">Total Orders</div>
                <div class="value"><?php echo esc_html($total_orders); ?></div>
            </div>
        </div>
        <div class="card-kpi">
            <div class="icon" style="background:#fdf2f8;color:#9d174d"><i class="fa-solid fa-sterling-sign"></i></div>
            <div class="meta">
                <div class="label">Total Spent</div>
                <div class="value"><?php echo wp_kses_post(wc_price($total_spent)); ?></div>
            </div>
        </div>
        <div class="card-kpi">
            <div class="icon" style="background:#f0fdf4;color:#166534"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="meta">
                <div class="label">Completed</div>
                <div class="value"><?php echo esc_html($completed_count); ?></div>
            </div>
        </div>
        <div class="card-kpi">
            <div class="icon" style="background:#fff7ed;color:#9a3412"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="meta">
                <div class="label">Pending / Processing</div>
                <div class="value"><?php echo esc_html($pending_count + $processing_count); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <span>Recent Orders</span>
            <a class="btn btn-primary"
                href="<?php if ($is_manager): ?><?php echo esc_url(wc_get_endpoint_url('manage-orders')); ?><?php else: ?><?php echo esc_url(wc_get_endpoint_url('orders')); ?><?php endif; ?>">See
                all</a>
        </div>
        <div class="table-modern-wrap">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recent_orders_query = array(
                        'type' => 'shop_order',
                        'limit' => 5,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    );
                    if (!$is_manager) {
                        $recent_orders_query['customer_id'] = $current_user_id;
                    }
                    $recent_orders = wc_get_orders($recent_orders_query);

                    if (!empty($recent_orders)):
                        foreach ($recent_orders as $order):
                            $status_key = 'status-' . $order->get_status();
                            ?>
                            <tr>
                                <td>#<?php echo esc_html($order->get_order_number()); ?></td>
                                <td><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></td>
                                <td><span
                                        class="status-badge <?php echo esc_attr($status_key); ?>"><?php echo esc_html(wc_get_order_status_name('wc-' . $order->get_status())); ?></span>
                                </td>
                                <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                            </tr>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="5">No recent orders.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <span>Last Order Status</span>
        </div>
        <div class="card-kpi">
            <div class="icon"><i class="fa-solid fa-truck"></i></div>
            <div class="meta">
                <div class="label">Latest Update</div>
                <div class="value"><?php echo esc_html($last_status); ?></div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Allow plugins/theme hooks below dashboard
 */
do_action('woocommerce_account_dashboard');
?>