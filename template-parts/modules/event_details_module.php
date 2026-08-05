<?php
/**
 * Event Details Module
 *
 * Auto-pulls date, time, location, price, description, and booking state
 * from the *current post's own fields* (Market / Workshop Details field
 * group — acf-json/group_fika_market_workshop_details.json). Intended for
 * use in the Page Builder on a single Market post; add nothing here to
 * duplicate — editing the Market post's own fields is enough.
 * ACF Flexible Content Layout: event_details_module
 *
 * Related CSS: assets/css/modules/event_details_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_event_details_module)
 */

$heading      = get_sub_field( 'heading' ) ?: __( 'Event Details', 'fika-bonsai' );
$show_map     = (bool) get_sub_field( 'show_map' );

$event_date   = get_field( 'event_date' );
$event_time   = get_field( 'event_time' );
$location     = get_field( 'location' );
$price        = get_field( 'price' );
$description  = get_field( 'description' );
$book_link    = get_field( 'book_link' );
$sold_out     = get_field( 'sold_out' );

// Nothing to show — this post doesn't carry the Market / Workshop Details fields.
if ( ! $event_date && ! $location && ! $price && ! $description ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		trigger_error( 'event_details_module: no Market/Workshop Details fields found on the current post (ID ' . get_the_ID() . ') — this module only works on a single Market post.', E_USER_NOTICE );
	}
	return;
}

$meta_parts = array_filter( array( $event_date, $event_time, $price ) );
$meta       = implode( ' · ', $meta_parts );
?>

<section class="section event-details-module" aria-labelledby="event-details-heading">
	<div class="max-w">
		<h2 id="event-details-heading" class="section-title">
			<?php echo esc_html( $heading ); ?>
		</h2>

		<div class="event-details-grid">
			<div class="event-details-info">
				<?php if ( $meta ) : ?>
				<p class="event-details-meta"><?php echo esc_html( $meta ); ?></p>
				<?php endif; ?>

				<?php if ( $location ) : ?>
				<p class="event-details-location"><?php echo esc_html( $location ); ?></p>
				<?php endif; ?>

				<?php if ( $description ) : ?>
				<div class="event-details-description content-block">
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
			<div class="event-details-map">
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
