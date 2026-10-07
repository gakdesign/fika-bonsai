<?php
/**
 * Workshop Product (single product, product_cat: workshops)
 *
 * Loaded by woocommerce/content-single-product.php. Layout follows
 * hero_split (content left 2/3, featured image right 1/3 with the organic
 * clip; image above and unclipped on mobile). Details mirror
 * workshop_details_module (price, meta line, spots, description), but
 * WooCommerce supplies the booking side:
 *
 *   price            → WC price (get_price_html)
 *   spots remaining  → WC stock quantity ("Manage stock" on the Inventory tab)
 *   sold out         → WC out of stock
 *   book button      → WC add to basket form (+ any express payment buttons
 *                      gateways hook in around it — Apple Pay, PayPal, etc.)
 *
 * ACF (Market / Workshop Details group) supplies date, time, location and
 * description. Optional page_builder modules render underneath, then
 * related workshops.
 *
 * Related CSS: assets/css/woocommerce-workshop.css
 * Related ACF: acf-json/group_fika_market_workshop_details.json,
 *              acf-json/group_fika_page_builder.json
 *
 * @package Bonsai_Base_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core-generated form.
	return;
}

$has_acf     = function_exists( 'get_field' );
$event_date  = bonsai_get_event_date_display();
$event_time  = $has_acf ? get_field( 'event_time' ) : '';
$location    = $has_acf ? get_field( 'location' ) : '';
$description = $has_acf ? get_field( 'description' ) : '';

// Date · time on one line; location gets its own line with the map pop-up.
$meta = implode( ' · ', array_filter( array( $event_date, $event_time ) ) );

// Map URLs — plain embed, no API key. Iframe src is only set on first open (assets/js/workshop-map.js).
$map_query = $location ? rawurlencode( $location ) : '';
$map_embed = $map_query ? 'https://www.google.com/maps?q=' . $map_query . '&output=embed' : '';
$map_link  = $map_query ? 'https://www.google.com/maps/search/?api=1&query=' . $map_query : '';

$in_stock = $product->is_in_stock();
$spots    = ( $in_stock && $product->managing_stock() ) ? (int) $product->get_stock_quantity() : null;
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'workshop-product', $product ); ?>>

	<section class="workshop-product-hero" aria-labelledby="workshop-product-title">
		<!-- Organic clip, same shape as hero_split (own ID so both can sit on one page) -->
		<svg width="0" height="0" aria-hidden="true" focusable="false">
			<defs>
				<clipPath id="organicWorkshop" clipPathUnits="objectBoundingBox">
					<path d="M0.14,0 C0.02,0.22 0.02,0.78 0.14,1 L1,1 L1,0 Z" />
				</clipPath>
			</defs>
		</svg>

		<div class="workshop-product-grid">
			<div class="workshop-product-info">
				<!--<p class="workshop-product-kicker"><?php esc_html_e( 'Workshop', 'fika-bonsai' ); ?></p>-->

				<h1 id="workshop-product-title" class="product_title workshop-product-title"><?php the_title(); ?></h1>

				<p class="workshop-details-price price">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
				</p>

				<?php if ( $meta ) : ?>
				<p class="workshop-details-meta"><?php echo esc_html( $meta ); ?></p>
				<?php endif; ?>

				<?php if ( $location ) : ?>
				<p class="workshop-details-location">
					<?php // Falls back to a plain link to Google Maps if JS / <dialog> isn't available. ?>
					<a class="workshop-map-trigger" href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener" aria-haspopup="dialog" aria-controls="workshop-map-dialog">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
							<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z" stroke-linejoin="round" />
							<circle cx="12" cy="9.5" r="2.5" />
						</svg>
						<span class="workshop-map-trigger-text"><?php echo esc_html( $location ); ?></span>
						<span class="workshop-map-trigger-hint"><?php esc_html_e( 'View map', 'fika-bonsai' ); ?></span>
					</a>
				</p>
				<?php endif; ?>

				<?php if ( null !== $spots ) : ?>
				<p class="workshop-details-spots">
					<?php
					printf(
						/* translators: %d: number of spots remaining */
						esc_html( _n( '%d spot remaining', '%d spots remaining', $spots, 'fika-bonsai' ) ),
						(int) $spots
					);
					?>
				</p>
				<?php endif; ?>

				<?php if ( $description ) : ?>
				<div class="workshop-details-description content-block">
					<?php echo wp_kses_post( $description ); ?>
				</div>
				<?php endif; ?>

				<div class="workshop-product-booking">
					<?php if ( $in_stock && $product->is_purchasable() ) : ?>
						<?php
						// Quantity + "Book your place" + gateway express buttons hooked around the form.
						woocommerce_template_single_add_to_cart();
						?>
					<?php else : ?>
						<span class="btn btn-sold-out"><?php esc_html_e( 'Sold Out', 'fika-bonsai' ); ?></span>
						<?php
						// Waiting list sign-up (inc/waiting-list.php).
						if ( function_exists( 'bonsai_waitlist_render_form' ) ) {
							bonsai_waitlist_render_form( $product );
						}
						?>
					<?php endif; ?>
				</div>
			</div>

			<div class="workshop-product-media">
				<div class="workshop-product-media-inner">
					<?php
					// Featured image only — no gallery / zoom / lightbox on workshops.
					if ( has_post_thumbnail() ) {
						echo wp_get_attachment_image(
							get_post_thumbnail_id(),
							'large',
							false,
							array(
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'sizes'         => '(min-width: 900px) 34vw, 100vw',
							)
						);
					} else {
						echo wc_placeholder_img( 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC-generated <img>.
					}
					?>
				</div>
			</div>

		</div>
	</section>

	<?php if ( $location ) : ?>
	<dialog id="workshop-map-dialog" class="workshop-map-dialog" aria-labelledby="workshop-map-title">
		<div class="workshop-map-dialog-header">
			<h2 id="workshop-map-title" class="workshop-map-dialog-title"><?php echo esc_html( $location ); ?></h2>
			<button type="button" class="workshop-map-close" aria-label="<?php esc_attr_e( 'Close map', 'fika-bonsai' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
					<path d="M6 6l12 12M18 6 6 18" stroke-linecap="round" />
				</svg>
			</button>
		</div>

		<div class="workshop-map-frame">
			<iframe
				data-src="<?php echo esc_url( $map_embed ); ?>"
				title="<?php /* translators: %s: workshop location */ echo esc_attr( sprintf( __( 'Map of %s', 'fika-bonsai' ), $location ) ); ?>"
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				allowfullscreen
			></iframe>
		</div>

		<p class="workshop-map-external">
			<a class="link-quiet" href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'Open in Google Maps', 'fika-bonsai' ); ?>
				<span class="visually-hidden"><?php esc_html_e( '(opens in a new tab)', 'fika-bonsai' ); ?></span>
			</a>
		</p>
	</dialog>
	<?php endif; ?>

	<?php
	// Optional extra modules (FAQ, testimonials, map, etc.) added in the Page Builder.
	include get_template_directory() . '/template-parts/modules/page_builder.php';
	?>

	<section class="section workshop-product-related">
		<div class="max-w">
			<?php woocommerce_output_related_products(); ?>
		</div>
	</section>

	<?php
	// Product JSON-LD — normally hooked to woocommerce_single_product_summary, which this layout doesn't fire.
	if ( isset( WC()->structured_data ) ) {
		WC()->structured_data->generate_product_data();
	}
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
