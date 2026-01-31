<?php
/**
 * Toast Notification System
 * Replaces default WooCommerce notices with Bootstrap toast notifications
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove default WooCommerce notices display
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
remove_action('woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10);
remove_action('woocommerce_account_content', 'woocommerce_output_all_notices', 5);

/**
 * Enqueue global toast notification script
 */
function miheli_enqueue_toast_script() {
    wp_enqueue_script(
        'miheli-toast-notifications',
        get_stylesheet_directory_uri() . '/assets/js/toast-notifications.js',
        array('jquery', 'bootstrap-js'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'miheli_enqueue_toast_script');

/**
 * Convert WooCommerce notices to toast notifications
 */
function miheli_woocommerce_notices_to_toast() {
    if (!function_exists('WC') || !WC()->session) {
        return;
    }
    
    $all_notices = WC()->session->get('wc_notices', array());
    
    if (empty($all_notices)) {
        return;
    }
    
    ?>
    <script type="text/javascript">
    jQuery(function($) {
        <?php
        foreach ($all_notices as $notice_type => $notices) {
            foreach ($notices as $notice) {
                $message = isset($notice['notice']) ? $notice['notice'] : $notice;
                $message = wp_strip_all_tags($message);
                $message = str_replace(array("'", "\n", "\r"), array("\\'", " ", ""), $message);
                
                $toast_type = 'info';
                if ($notice_type === 'success') {
                    $toast_type = 'success';
                } elseif ($notice_type === 'error') {
                    $toast_type = 'error';
                }
                ?>
                if (typeof showMiheliToast === 'function') {
                    showMiheliToast('<?php echo $message; ?>', '<?php echo $toast_type; ?>');
                }
                <?php
            }
        }
        ?>
    });
    </script>
    <?php
    
    wc_clear_notices();
}
add_action('wp_footer', 'miheli_woocommerce_notices_to_toast', 100);
