<?php
/**
 * Map / Location Module
 *
 * Address + opening hours + a "Get Directions" link — no embedded iframe,
 * so no Cookiebot consent-wrapping is required.
 * ACF Flexible Content Layout: map_location
 *
 * Related CSS: assets/css/modules/map_location.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_map_location)
 */

$section_title    = get_sub_field( 'section_title' );
$address          = get_sub_field( 'address' );
$directions_link  = get_sub_field( 'directions_link' );
$phone            = get_sub_field( 'phone' );
$opening_hours    = get_sub_field( 'opening_hours' );
?>

<section class="section map-location" aria-labelledby="map-location-heading">
	<div class="max-w max-w-narrow">
		<?php if ( $section_title ) : ?>
		<h2 id="map-location-heading" class="section-title">
			<?php echo esc_html( $section_title ); ?>
		</h2>
		<?php endif; ?>

		<div class="map-location-grid">
			<?php if ( $address || $directions_link || $phone ) : ?>
			<div class="map-location-details">
				<?php if ( $address ) : ?>
				<p class="map-location-address"><?php echo wp_kses_post( $address ); ?></p>
				<?php endif; ?>

				<?php if ( $phone ) : ?>
				<p class="map-location-phone">
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
						<?php echo esc_html( $phone ); ?>
					</a>
				</p>
				<?php endif; ?>

				<?php if ( $directions_link ) : ?>
				<a class="btn btn-primary map-location-directions" href="<?php echo esc_url( $directions_link ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Get Directions', 'fika-bonsai' ); ?>
				</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $opening_hours && is_array( $opening_hours ) && count( $opening_hours ) > 0 ) : ?>
			<table class="map-location-hours">
				<caption class="visually-hidden"><?php esc_html_e( 'Opening hours', 'fika-bonsai' ); ?></caption>
				<tbody>
					<?php foreach ( $opening_hours as $row ) :
						if ( empty( $row['day'] ) ) {
							continue;
						}
						?>
					<tr>
						<th scope="row"><?php echo esc_html( $row['day'] ); ?></th>
						<td><?php echo esc_html( $row['hours'] ?? '' ); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
		</div>
	</div>
</section>
