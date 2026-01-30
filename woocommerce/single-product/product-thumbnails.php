<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.8.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attachment_ids   = $product->get_gallery_image_ids();
$post_thumbnail_id = $product->get_image_id();

?>

<div class="swiper thumbs-swiper">
	<div class="swiper-wrapper">
		<?php
		// Always include the main product image as the first thumbnail so it can be restored
		if ( $post_thumbnail_id ) {
			$main_thumb_url = wp_get_attachment_image_url( $post_thumbnail_id, 'thumbnail' );
			$main_full_url  = wp_get_attachment_image_url( $post_thumbnail_id, 'full' );
			$main_alt       = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
			?>
			<div class="swiper-slide product-thumb active">
				<img
					src="<?php echo esc_url( $main_thumb_url ); ?>"
					data-large-image="<?php echo esc_url( $main_full_url ); ?>"
					alt="<?php echo esc_attr( $main_alt ); ?>"
					class="woocommerce-thumb-swiper"
				/>
			</div>
			<?php
		}

		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
				// Skip if this attachment is the main product image to avoid duplicate
				if ( $post_thumbnail_id && intval( $attachment_id ) === intval( $post_thumbnail_id ) ) {
					continue;
				}

				$thumbnail_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
				$full_url      = wp_get_attachment_image_url( $attachment_id, 'full' );
				$alt_text      = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
				?>
				<div class="swiper-slide product-thumb">
					<img
						src="<?php echo esc_url( $thumbnail_url ); ?>"
						data-large-image="<?php echo esc_url( $full_url ); ?>"
						alt="<?php echo esc_attr( $alt_text ); ?>"
						class="woocommerce-thumb-swiper"
					/>
				</div>
				<?php
			}
		}
		?>
	</div>

	<!-- Navigation buttons -->
	<div class="swiper-button-next thumbs-swiper-button-next"></div>
	<div class="swiper-button-prev thumbs-swiper-button-prev"></div>
</div>