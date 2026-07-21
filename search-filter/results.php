
<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( $query->have_posts() ) {
?>
<div class="row row-eq-height gx-5">
	
	<?php while ( $query->have_posts() ) { $query->the_post(); ?>
		
		<div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
			<?php

			  $listing_image_raw = get_field('listing_image'); 

			  // Resolve image URL safely for any ACF return type (Array | ID | URL)
			  $thumb_url = '';
			  if ($listing_image_raw) {
				if (is_array($listing_image_raw)) {
				  // Prefer a sized variant if available
				  if (!empty($listing_image_raw['sizes']['large'])) {
					$thumb_url = $listing_image_raw['sizes']['large'];
				  } elseif (!empty($listing_image_raw['url'])) {
					$thumb_url = $listing_image_raw['url'];
				  }
				} elseif (is_numeric($listing_image_raw)) {
				  $thumb_url = wp_get_attachment_image_url((int) $listing_image_raw, 'large');
				} elseif (is_string($listing_image_raw)) {
				  $thumb_url = $listing_image_raw; // already a URL
				}
			  }

			  // Fallback to post thumbnail if no ACF image found
			  if (!$thumb_url) {
				$thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
			  }

			  // Cache (escape on output ideally, but keeping your pattern here)
			  $permalink     = esc_url(get_permalink());
			  $thumb_url     = esc_url($thumb_url);
			  $listing_title = esc_html(get_field('listing_title'));
			  $event_date    = esc_html(get_field('event_date_details'));
			  $event_time    = esc_html(get_field('event_time_details'));
			  $excerpt       = wp_kses_post(get_field('listing_excerpt'));

			  // Get only terms attached to this post (guard against WP_Error)
			  $terms = get_the_terms(get_the_ID(), 'whats-on');
			  if (is_wp_error($terms)) {
				$terms = [];
			  }
			?>

			<div class="event-item">
				<a href="<?php echo $permalink; ?>">
					<?php if ( $thumb_url ) : ?>
						<div class="event-listings-banner background-image" style="background-image: url('<?php echo $thumb_url; ?>')">
							<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
								<ul class="whats-on-list">
									<?php foreach ( $terms as $term ) : ?>
										<li><?php echo esc_html( $term->name ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="event-listing-content content-block text-center">
						<?php if ( $listing_title ) : ?>
							<h3><?php echo $listing_title; ?></h3>
						<?php endif; ?>

						<?php if ( $event_date ) : ?>
							<div class="event-date"><p><?php echo $event_date; ?></p></div>
						<?php endif; ?>

						<?php if ( $event_time ) : ?>
							<div class="event-time"><p><?php echo $event_time; ?></p></div>
						<?php endif; ?>
						<div class="event-border-line"></div>
						<?php if ( $excerpt ) : ?>
							<div class="event-excerpt"><?php echo $excerpt; ?></div>
						<?php endif; ?>
					</div>
				</a>
			</div>
		</div>

	<?php	} ?>	
	<div class="pagination text-center">
		<?php
			/* example code for using the wp_pagenavi plugin */
		if ( function_exists( 'wp_pagenavi' ) ) {
			echo '<br />';
			wp_pagenavi( array( 'query' => $query ) );
		}
		?>
	</div>
</div>
<?php } ?>