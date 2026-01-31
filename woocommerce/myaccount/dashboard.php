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
$total_orders = function_exists('wc_get_customer_order_count') ? wc_get_customer_order_count($current_user_id) : 0;
$total_spent = function_exists('wc_get_customer_total_spent') ? wc_get_customer_total_spent($current_user_id) : 0;

$last_order_arr = wc_get_orders(array(
    'customer_id' => $current_user_id,
    'limit' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
));
$last_order = !empty($last_order_arr) ? $last_order_arr[0] : false;
$last_status = $last_order ? wc_get_order_status_name('wc-' . $last_order->get_status()) : __('No orders yet', 'miheli');

$processing_count = count(wc_get_orders(array(
    'customer_id' => $current_user_id,
    'status' => array('wc-processing', 'wc-on-hold'),
    'limit' => -1,
    'return' => 'ids',
)));

$pending_count = count(wc_get_orders(array(
    'customer_id' => $current_user_id,
    'status' => array('wc-pending'),
    'limit' => -1,
    'return' => 'ids',
)));

$completed_count = count(wc_get_orders(array(
    'customer_id' => $current_user_id,
    'status' => array('wc-completed'),
    'limit' => -1,
    'return' => 'ids',
)));
?>

<div class="my-account-wrapper">
    <div class="my-account-header">
        <div class="title">My Dashboard</div>
        <div>
            <div class="account-actions-inline">
                <a class="btn btn-primary"
                    href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Dashboard</a>
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>">View Orders</a>
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account
                    Details</a>
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('customer-logout')); ?>">Log
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
                    <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>">View
                            Orders</a></li>
                    <li><a class="dropdown-item"
                            href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account Details</a></li>
                    <li><a class="dropdown-item"
                            href="<?php echo esc_url(wc_get_endpoint_url('customer-logout')); ?>">Log out</a></li>
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
            <a class="btn btn-sm btn-outline-secondary" href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>">See
                all</a>
        </div>
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
                $recent_orders = wc_get_orders(array(
                    'customer_id' => $current_user_id,
                    'limit' => 5,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));

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