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
    ? count(wc_get_orders(array('return' => 'ids', 'limit' => -1, 'type' => 'shop_order')))
    : (function_exists('wc_get_customer_order_count') ? wc_get_customer_order_count($current_user_id) : 0);
$total_spent = function_exists('wc_get_customer_total_spent') ? wc_get_customer_total_spent($current_user_id) : 0;

$last_order_query = array(
    'limit'   => 1,
    'orderby' => 'date',
    'order'   => 'DESC',
    'type'    => 'shop_order',
);
if (!$is_manager) {
    $last_order_query['customer_id'] = $current_user_id;
}
$last_order_arr = wc_get_orders($last_order_query);
$last_order = !empty($last_order_arr) ? $last_order_arr[0] : false;
$last_status = $last_order ? wc_get_order_status_name('wc-' . $last_order->get_status()) : __('No orders yet', 'miheli');

$processing_query = array(
    'status' => array('wc-processing', 'wc-on-hold'),
    'limit'  => -1,
    'return' => 'ids',
    'type'   => 'shop_order',
);
if (!$is_manager) {
    $processing_query['customer_id'] = $current_user_id;
}
$processing_count = count(wc_get_orders($processing_query));

$pending_query = array(
    'status' => array('wc-pending'),
    'limit'  => -1,
    'return' => 'ids',
    'type'   => 'shop_order',
);
if (!$is_manager) {
    $pending_query['customer_id'] = $current_user_id;
}
$pending_count = count(wc_get_orders($pending_query));

$completed_query = array(
    'status' => array('wc-completed'),
    'limit'  => -1,
    'return' => 'ids',
    'type'   => 'shop_order',
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
                <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('edit-account'));  ...