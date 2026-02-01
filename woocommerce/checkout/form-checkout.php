<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>
<div class="checkout-page-wrapper">
	<div class="container">
        <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
		<header class="checkout-page-header">
			<p class="checkout-kicker"><?php esc_html_e( 'Secure Checkout', 'miheli-solutions-child' ); ?></p>
			<h1 class="checkout-title"><?php esc_html_e( 'Complete Your Order', 'miheli-solutions-child' ); ?></h1>
			<!-- <p class="checkout-subtitle"><?php //esc_html_e( 'Fast, secure, and responsive checkout experience.', 'miheli-solutions-child' ); ?></p> -->
		</header>

		<form name="checkout" method="post" class="checkout woocommerce-checkout checkout-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<div class="row g-4">
				<div class="col-lg-12">
					<?php if ( $checkout->get_checkout_fields() ) : ?>

						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div class="row g-4" id="customer_details">
							<div class="col-md-6">
								<!-- Billing Details -->
								<section class="checkout-section">
									<?php do_action( 'woocommerce_checkout_billing' ); ?>
								</section>
								<!-- Billing Details -->
							</div>

							<div class="col-md-6">
								<section class="checkout-section">
									<!-- Shipping Details -->
									<?php do_action( 'woocommerce_checkout_shipping' ); ?>
									<!-- Shipping Details -->
								</section>
							</div>
						</div>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

					<?php endif; ?>
				</div>

				<div class="col-lg-12">
					<section class="checkout-section order-review-card">
						<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
						<h3 id="order_review_heading" class="checkout-section-title">
							<?php esc_html_e( 'Your order', 'woocommerce' ); ?>
						</h3>
						<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

						<div id="order_review" class="woocommerce-checkout-review-order">
							<?php do_action( 'woocommerce_checkout_order_review' ); ?>
						</div>

						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
					</section>
				</div>
			</div>

		</form>
	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
