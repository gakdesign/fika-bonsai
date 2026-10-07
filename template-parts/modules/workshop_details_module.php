<?php
/**
 * Workshop Details Module
 *
 * Auto-pulls price, spots remaining, sold-out state, date, time, location,
 * and description from the *current post's own fields* (Market / Workshop
 * Details field group — acf-json/group_fika_market_workshop_details.json).
 * Intended for use in the Page Builder on a single Workshop post — a
 * booking-focused counterpart to event_details_module, which leads with
 * price/availability rather than a map.
 * ACF Flexible Content Layout: workshop_details_module
 *
 * Related CSS: assets/css/modules/workshop_details_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_workshop_details_module)
 */

$heading         = get_sub_field( 'heading' ) ?: __( 'Class Details', 'fika-bonsai' );
$show_map        = (bool) get_sub_field( 'show_map' );

$event_date      = bonsai_get_event_date_display();
$event_time      = get_field( 'event_time' );
$location        = get_field( 'location' );
$price           = get_field( 'price' );
$description     = get_field( 'description' );
$book_link       = get_field( 'book_link' );
$spots_remaining = get_field( 'spots_remaining' );
$sold_out        = get_field( 'sold_out' );

// Nothing to show — this post doesn't carry the Market / Workshop Details fields.
if ( ! $event_date && ! $price && ! $description ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		trigger_error( 'workshop_details_module: no Market/Workshop Details fields found on the current post (ID ' . get_the_ID() . ') — this module only works on a single Workshop post.', E_USER_NOTICE );
	}
	return;
}

$meta_parts = array_filter( array( $event_date, $event_time, $location ) );
$meta       = implode( ' · ', $meta_parts );
?>

<section class="section workshop-details-module" aria-labelledby="workshop-details-heading">
	<div class="max-w">
		<h2 id="workshop-details-heading" class="section-title">
			<?php echo esc_html( $heading ); ?>
		</h2>

		<div class="workshop-details-grid">
			<div class="workshop-details-info">
				<?php if ( $price ) : ?>
				<p class="workshop-details-price"><?php echo esc_html( $price ); ?></p>
				<?php endif; ?>

				<?php if ( $meta ) : ?>
				<p class="workshop-details-meta"><?php echo esc_html( $meta ); ?></p>
				<?php endif; ?>

				<?php if ( ! $sold_out && null !== $spots_remaining && '' !== $spots_remaining ) : ?>
				<p class="workshop-details-spots">
					<?php
					printf(
						/* translators: %d: number of spots remaining */
						esc_html( _n( '%d spot remaining', '%d spots remaining', (int) $spots_remaining, 'fika-bonsai' ) ),
						(int) $spots_remaining
					);
					?>
				</p>
				<?php endif; ?>

				<?php if ( $description ) : ?>
				<div class="workshop-details-description content-block">
					<?php echo wp_kses_post( $description ); ?>
				</div>
				<?php endif; ?>

				<?php if ( $sold_out ) : ?>
				<span class="btn btn-sold-out"><?php esc_html_e( 'Sold Out', 'fika-bonsai' ); ?></span>
				<?php elseif ( $book_link ) : ?>
				<a class="btn btn-primary" href="<?php echo esc_url( $book_link ); ?>">
					<?php esc_html_e( 'Book now', 'fika-bonsai' ); ?>
				</a>
				<?php endif; ?>
			</div>

			<?php if ( $show_map && $location ) : ?>
			<div class="workshop-details-map">
				<iframe
					src="https://www.google.com/maps?q=<?php echo rawurlencode( $location ); ?>&output=embed"
					title="<?php echo esc_attr( $location ); ?>"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					allowfullscreen
				></iframe>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
