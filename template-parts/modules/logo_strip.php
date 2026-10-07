<?php
/**
 * Logo Strip Module
 *
 * "As featured in" / press mentions row.
 * ACF Flexible Content Layout: logo_strip
 *
 * Related CSS: assets/css/modules/logo_strip.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_logo_strip)
 */

$section_title = get_sub_field( 'section_title' );
$logos = get_sub_field( 'logos' );

if ( ! ( $logos && is_array( $logos ) && count( $logos ) > 0 ) ) {
	return;
}
?>

<section class="section logo-strip" aria-labelledby="logo-strip-heading">
	<div class="max-w">
		<?php if ( $section_title ) : ?>
		<p id="logo-strip-heading" class="logo-strip-title">
			<?php echo esc_html( $section_title ); ?>
		</p>
		<?php endif; ?>

		<div class="logo-strip-row">
			<?php foreach ( $logos as $logo ) :
				if ( empty( $logo['image'] ) ) {
					continue;
				}

				$img_id = is_array( $logo['image'] ) ? $logo['image']['ID'] : $logo['image'];
				$name   = $logo['name'] ?? '';
				$link   = $logo['link'] ?? '';
				?>
			<div class="logo-strip-item">
				<?php if ( $link ) : ?>
				<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $name ); ?>">
				<?php endif; ?>

					<?php
					echo wp_get_attachment_image(
						$img_id,
						'medium',
						false,
						array( 'loading' => 'lazy', 'alt' => esc_attr( $name ) )
					);
					?>

				<?php if ( $link ) : ?>
				</a>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
