<?php
/**
 * Edit account form
 *
 * This template overrides WooCommerce's myaccount/form-edit-account.php
 */
if (!defined('ABSPATH')) {
    exit;
}

$nonce_action = 'save_account_details';
$nonce_name = 'woocommerce-save-account-details-nonce';

do_action('woocommerce_before_edit_account_form');
?>

<div class="my-account-header">
    <div class="title">Account Details</div>
    <div>
        <div class="account-actions-inline">
            <a class="btn btn-primary"
                href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Dashboard</a>
            <a class="btn btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account
                Details</a>
            <a class="btn btn-primary js-confirm-logout" href="<?php echo esc_url(wc_logout_url()); ?>">Log out</a>
        </div>
        <div class="account-actions-dropdown dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Account Menu
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item"
                        href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">Dashboard</a></li>
                <li><a class="dropdown-item" href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">Account
                        Details</a></li>
                <li><a class="dropdown-item js-confirm-logout" href="<?php echo esc_url(wc_logout_url()); ?>">Log
                        out</a></li>
            </ul>
        </div>
    </div>
</div>

<form class="woocommerce-EditAccountForm edit-account account-form" action="" method="post">
    <?php do_action('woocommerce_edit_account_form_start'); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?> <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control" name="account_first_name" id="account_first_name"
                    autocomplete="given-name" value="<?php echo esc_attr(wp_get_current_user()->first_name); ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?> <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control" name="account_last_name" id="account_last_name"
                    autocomplete="family-name" value="<?php echo esc_attr(wp_get_current_user()->last_name); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_display_name"><?php esc_html_e('Display name', 'woocommerce'); ?> <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control" name="account_display_name" id="account_display_name"
                    value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>" />
                <small
                    class="text-muted"><?php esc_html_e('This will be how your name will be displayed in the account section and in reviews', 'woocommerce'); ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?> <span
                        class="text-danger">*</span></label>
                <input type="email" class="form-control" name="account_email" id="account_email" autocomplete="email"
                    value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>" />
            </div>
        </div>
    </div>

    <fieldset>
        <legend class="h6 mb-2"><?php esc_html_e('Password change', 'woocommerce'); ?></legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label
                        for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                    <input type="password" class="form-control" name="password_current" id="password_current"
                        autocomplete="current-password" />
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label
                        for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                    <input type="password" class="form-control" name="password_1" id="password_1"
                        autocomplete="new-password" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
                    <input type="password" class="form-control" name="password_2" id="password_2"
                        autocomplete="new-password" />
                </div>
            </div>
        </div>
    </fieldset>

    <?php do_action('woocommerce_edit_account_form'); ?>

    <div class="d-flex gap-2 mt-3">
        <?php wp_nonce_field($nonce_action, $nonce_name); ?>
        <button type="submit" class="btn btn-primary" name="save_account_details"
            value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>">
            <?php esc_html_e('Save changes', 'woocommerce'); ?>
        </button>
    </div>

    <input type="hidden" name="action" value="save_account_details" />

    <?php do_action('woocommerce_edit_account_form_end'); ?>
</form>

<?php do_action('woocommerce_after_edit_account_form'); ?>