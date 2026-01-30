<?php
/**
 * Product tabs - Bootstrap 5 accordion layout.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( empty( $product_tabs ) ) {
	return;
}

$accordion_id = 'product-tabs-accordion';
?>

<div class="woocommerce-tabs product-tabs-section">
	<div class="accordion product-tabs-accordion" id="<?php echo esc_attr( $accordion_id ); ?>">
		<?php
		$index = 0;
		foreach ( $product_tabs as $key => $product_tab ) :
			$tab_id      = sanitize_title( $key );
			$heading_id  = $accordion_id . '-heading-' . $tab_id;
			$collapse_id = $accordion_id . '-collapse-' . $tab_id;
			$is_active   = ( 0 === $index );
			?>
			<div class="accordion-item product-tab-item">
				<h2 class="accordion-header" id="<?php echo esc_attr( $heading_id ); ?>">
					<button class="accordion-button<?php echo $is_active ? '' : ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>" data-toggle="collapse" data-target="#<?php echo esc_attr( $collapse_id ); ?>" aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $collapse_id ); ?>">
						<?php echo wp_kses_post( apply_filters( "woocommerce_product_{$key}_tab_title", $product_tab['title'], $key ) ); ?>
					</button>
				</h2>
				<div id="<?php echo esc_attr( $collapse_id ); ?>" class="accordion-collapse collapse<?php echo $is_active ? ' show' : ''; ?>" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>" data-bs-parent="#<?php echo esc_attr( $accordion_id ); ?>">
					<div class="accordion-body">
						<?php
						if ( isset( $product_tab['callback'] ) && is_callable( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
					</div>
				</div>
			</div>
			<?php
			$index++;
		endforeach;
		?>
	</div>
</div>
