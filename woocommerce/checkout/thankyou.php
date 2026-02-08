<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 *
 * @var int $order_id
 */

defined( 'ABSPATH' ) || exit;

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		/**
		 * Hook: woocommerce_thankyou.
		 *
		 * @hooked woocommerce_pay_order_button_thankyou - 10
		 * @hooked woocommerce_order_details_table - 10
		 * @hooked woocommerce_order_again_button - 20
		 */
		do_action( 'woocommerce_thankyou', $order_id );
	}
}
else {
	?>
	<div class="order-received-page-wrapper">
		<div class="container">
			<header class="order-received-page-header">
				<p class="order-received-kicker"><?php esc_html_e( 'Order Received', 'miheli-solutions-child' ); ?></p>
				<h1 class="order-received-title"><?php esc_html_e( 'Thank You!', 'miheli-solutions-child' ); ?></h1>
				<p class="order-received-subtitle"><?php esc_html_e( 'Your order has been successfully placed.', 'miheli-solutions-child' ); ?></p>
			</header>
		</div>
	</div>
	<?php
}

// Remove default WooCommerce thank you content and render our custom version
if ( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( $order ) {
		?>
		<div class="order-received-page-wrapper">
			<div class="container">
				
				<!-- Header -->
				<header class="order-received-page-header">
					<div class="order-received-icon">
						<i class="fas fa-check"></i>
					</div>
					<p class="order-received-kicker"><?php esc_html_e( 'Order Received', 'miheli-solutions-child' ); ?></p>
					<h1 class="order-received-title"><?php esc_html_e( 'Thank You!', 'miheli-solutions-child' ); ?></h1>
					<p class="order-received-subtitle"><?php esc_html_e( 'Your order has been successfully placed.', 'miheli-solutions-child' ); ?></p>
				</header>

				<!-- Order Summary -->
				<section class="order-received-section">
					<h2 class="order-received-section-title"><?php esc_html_e( 'Order Summary', 'miheli-solutions-child' ); ?></h2>
					<div class="order-info-grid">
						<div class="order-info-item">
							<p class="order-info-label"><?php esc_html_e( 'Order Number', 'miheli-solutions-child' ); ?></p>
							<p class="order-info-value">#<?php echo esc_html( $order->get_order_number() ); ?></p>
						</div>
						<div class="order-info-item">
							<p class="order-info-label"><?php esc_html_e( 'Order Date', 'miheli-solutions-child' ); ?></p>
							<p class="order-info-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
						</div>
						<div class="order-info-item">
							<p class="order-info-label"><?php esc_html_e( 'Order Status', 'miheli-solutions-child' ); ?></p>
							<p class="order-info-value"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></p>
						</div>
						<div class="order-info-item">
							<p class="order-info-label"><?php esc_html_e( 'Total Amount', 'miheli-solutions-child' ); ?></p>
							<p class="order-info-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
						</div>
					</div>
				</section>

				<!-- Order Details Table -->
				<section class="order-received-section">
					<h2 class="order-received-section-title"><?php esc_html_e( 'Order Details', 'miheli-solutions-child' ); ?></h2>
					<?php
					$items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
					if ( $items ) {
						?>
						<table class="woocommerce-table woocommerce-table--order-details">
							<thead>
								<tr>
									<th class="woocommerce-table__product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
									<th class="woocommerce-table__product-quantity"><?php esc_html_e( 'Qty', 'woocommerce' ); ?></th>
									<th class="woocommerce-table__product-table"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
									<th class="woocommerce-table__product-table"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ( $items as $item_id => $item ) {
									$product = $item->get_product();
									?>
									<tr class="woocommerce-table__line-item">
										<td class="woocommerce-table__product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
											<?php
											if ( $product ) {
												echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product->get_permalink() ), wp_kses_post( $item->get_name() ) ) );
											} else {
												echo wp_kses_post( $item->get_name() );
											}
											?>
										</td>
										<td class="woocommerce-table__product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
											<?php echo esc_html( $item->get_quantity() ); ?>
										</td>
										<td class="woocommerce-table__product-table" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
											<?php echo wp_kses_post( wc_price( $item->get_subtotal() / $item->get_quantity() ) ); ?>
										</td>
										<td class="woocommerce-table__product-table" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
											<?php echo wp_kses_post( wc_price( $item->get_subtotal() ) ); ?>
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
									<td><?php echo wp_kses_post( wc_price( $order->get_subtotal() ) ); ?></td>
								</tr>
								<?php
								// Show shipping cost
								if ( $order->get_shipping_total() ) {
									?>
									<tr>
										<th colspan="3"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
										<td><?php echo wp_kses_post( wc_price( $order->get_shipping_total() ) ); ?></td>
									</tr>
									<?php
								}
								// Show tax
								if ( $order->get_total_tax() ) {
									?>
									<tr>
										<th colspan="3"><?php esc_html_e( 'Tax', 'woocommerce' ); ?></th>
										<td><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></td>
									</tr>
									<?php
								}
								?>
								<tr>
									<th colspan="3"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
									<td><strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong></td>
								</tr>
							</tfoot>
						</table>
						<?php
					}
					?>
				</section>

				<!-- Billing & Shipping Details -->
				<section class="order-received-section">
					<h2 class="order-received-section-title"><?php esc_html_e( 'Delivery Information', 'miheli-solutions-child' ); ?></h2>
					<div class="woocommerce-customer-details">
						<?php
						if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) {
							?>
							<section>
								<h2><?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?></h2>
								<address>
									<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
								</address>
							</section>
							<?php
						}
						?>
						<section>
							<h2><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?></h2>
							<address>
								<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
							</address>
						</section>
					</div>
				</section>

				<!-- Payment Method -->
				<section class="order-received-section">
					<h2 class="order-received-section-title"><?php esc_html_e( 'Payment Method', 'miheli-solutions-child' ); ?></h2>
					<p><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
				</section>

				<!-- Customer Notes -->
				<?php
				if ( $order->get_customer_note() ) {
					?>
					<section class="woocommerce-order-details__customer-notes">
						<h2><?php esc_html_e( 'Order Notes', 'woocommerce' ); ?></h2>
						<p><?php echo wp_kses_post( wptexturize( $order->get_customer_note() ) ); ?></p>
					</section>
					<?php
				}
				?>

				<!-- Action Buttons -->
				<div class="order-received-actions">
					<?php
					if ( is_user_logged_in() ) {
						?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="woocommerce-button button woocommerce-forward">
							<?php esc_html_e( 'My Account', 'woocommerce' ); ?>
						</a>
						<?php
					}
					?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="woocommerce-button button woocommerce-forward">
						<?php esc_html_e( 'Continue Shopping', 'woocommerce' ); ?>
					</a>
				</div>

			</div>
		</div>

		<?php
		// Output additional hooks
		do_action( 'woocommerce_order_details_after_order_once', $order_id );
	}
}
