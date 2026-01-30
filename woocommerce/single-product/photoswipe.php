<?php
/**
 * Photoswipe markup
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/photoswipe.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
}
?>

<!-- Bootstrap modal used in place of PhotoSwipe -->
<div class="modal fade" id="photoswipe-bootstrap-modal" tabindex="-1" role="dialog" aria-labelledby="photoswipeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="photoswipeModalLabel"><?php esc_html_e( 'Product images', 'miheli-solutions-child' ); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'woocommerce' ); ?>"></button>
			</div>
			<div class="modal-body">
				<div class="photoswipe-modal-inner" style="display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:nowrap;overflow:hidden">
					<!-- images will be injected here by JS -->
				</div>
			</div>
			<div class="modal-footer" style="justify-content:space-between">
				<div>
					<button type="button" class="btn btn-light modal-btn" id="photoswipe-prev">&larr; <?php esc_html_e( 'Prev', 'woocommerce' ); ?></button>
					<button type="button" class="btn btn-light modal-btn" id="photoswipe-next"><?php esc_html_e( 'Next', 'woocommerce' ); ?> &rarr;</button>
				</div>
				<button type="button" class="btn btn-secondary modal-btn" data-bs-dismiss="modal"><?php esc_html_e( 'Close', 'woocommerce' ); ?></button>
			</div>
		</div>
	</div>
</div>
