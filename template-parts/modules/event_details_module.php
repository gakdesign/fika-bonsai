<?php
/**
 * Event Details Module
 *
 * Auto-pulls date, time, location, price, description, image, and booking
 * state from the *current post's own fields* (Market / Workshop Details field
 * group — acf-json/group_fika_market_workshop_details.json). Intended for
 * use in the Page Builder on a single Market post; add nothing here to
 * duplicate — editing the Market post's own fields is enough.
 *
 * Layout matches the workshop product page / hero_split: content left (2/3),
 * image right (1/3) with the organic clip, then an optional full-width map.
 * On mobile the image sits above the content, unclipped.
 * ACF Flexible Content Layout: event_details_module
 *
 * Related CSS: assets/css/modules/event_details_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_event_details_module)
 */

$heading      = get_sub_field( 'heading' ) ?: __( 'Event Details', 'fika-bonsai' );
$show_map     = (bool) get_sub_field( 'show_map' );

$event_date   = bonsai_get_event_date_display();
$event_time   = get_field( 'event_time' );
$location     = get_field( 'location' );
$price        = get_field( 'price' );
$description  = get_field( 'description' );
$book_link    = get_field( 'book_link' );
$sold_out     = get_field( 'sold_out' );
$image        = get_field( 'image' );

// Nothing to show — this post doesn't carry the Market / Workshop Details fields.
if ( ! $event_date && ! $location && ! $price && ! $description ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		trigger_error( 'event_details_module: no Market/Workshop Details fields found on the current post (ID ' . get_the_ID() . ') — this module only works on a single Market post.', E_USER_NOTICE );
	}
	return;
}

// ACF image field first, featured image as fallback.
$image_id = 0;
if ( $image ) {
	$image_id = is_array( $image ) ? (int) $image['ID'] : (int) $image;
} elseif ( has_post_thumbnail() ) {
	$image_id = get_post_thumbnail_id();
}

$meta = implode( ' · ', array_filter( array( $event_date, $event_time ) ) );
?>

<section class="event-details-module<?php echo $image_id ? '' : ' event-details-module--no-image'; ?>" aria-labelledby="event-details-heading">
	<?php if ( $image_id ) : ?>
	<!-- Organic clip, same shape as hero_split (own ID so it works without a hero on the page) -->
	<svg width="0" height="0" aria-hidden="true" focusable="false">
		<defs>
			<clipPath id="organicEvent" clipPathUnits="objectBoundingBox">
				<path d="M0.14,0 C0.02,0.22 0.02,0.78 0.14,1 L1,1 L1,0 Z" />
			</clipPath>
		</defs>
	</svg>
	<?php endif; ?>

	<div class="event-details-grid">
		<div class="event-details-info">
			<p class="event-details-kicker"><?php echo esc_html( $heading ); ?></p>

			<h2 id="event-details-heading" class="event-details-title"><?php the_title(); ?></h2>

			<?php if ( $price ) : ?>
			<p class="event-details-price"><?php echo esc_html( $price ); ?></p>
			<?php endif; ?>

			<?php if ( $meta ) : ?>
			<p class="event-details-meta"><?php echo esc_html( $meta ); ?></p>
			<?php endif; ?>

			<?php if ( $location ) : ?>
			<p class="event-details-location">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
					<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z" stroke-linejoin="round" />
					<circle cx="12" cy="9.5" r="2.5" />
				</svg>
				<?php echo esc_html( $location ); ?>
			</p>
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

		<?php if ( $image_id ) : ?>
		<div class="event-details-media">
			<div class="event-details-media-inner">
				<?php
				echo wp_get_attachment_image(
					$image_id,
					'large',
					false,
					array(
						'loading' => 'lazy',
						'sizes'   => '(min-width: 900px) 34vw, 100vw',
					)
				);
				?>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<?php if ( $show_map && $location ) : ?>
	<div class="event-details-map">
		<iframe
			src="https://www.google.com/maps?q=<?php echo rawurlencode( $location ); ?>&output=embed"
			title="<?php /* translators: %s: event location */ echo esc_attr( sprintf( __( 'Map of %s', 'fika-bonsai' ), $location ) ); ?>"
			loading="lazy"
			referrerpolicy="no-referrer-when-downgrade"
			allowfullscreen
		></iframe>
	</div>
	<?php endif; ?>
</section>
