<?php
/**
 * Slider Module
 *
 * Full-width fade slider. Each slide can carry a left-aligned title (top)
 * and content (bottom) over the image, with an optional CTA button.
 * ACF Flexible Content Layout: slider_module
 *
 * Related CSS: assets/css/modules/slider_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_slider_module)
 */

$slides = get_sub_field( 'slides' );

if ( ! ( $slides && is_array( $slides ) && count( $slides ) > 0 ) ) {
	return;
}
?>

<section class="slider-module" aria-label="<?php esc_attr_e( 'Image slider', 'fika-bonsai' ); ?>">
	<div class="slider-module-track">
		<?php foreach ( $slides as $slide ) :
			if ( empty( $slide['image'] ) ) {
				continue;
			}

			$img_id       = is_array( $slide['image'] ) ? $slide['image']['ID'] : $slide['image'];
			$title        = $slide['title'] ?? '';
			$content      = $slide['content'] ?? '';
			$cta_text     = $slide['cta_text'] ?? '';
			$cta_link     = $slide['cta_link'] ?? '';
			$has_overlay  = $title || $content || ( $cta_text && $cta_link );
			?>
			<div class="slider-module-slide">
				<?php
				echo wp_get_attachment_image(
					$img_id,
					'full',
					false,
					array( 'class' => 'slider-module-image', 'loading' => 'lazy' )
				);
				?>

				<?php if ( $has_overlay ) : ?>
				<div class="slider-module-overlay">
					<?php if ( $title ) : ?>
					<h2 class="slider-module-title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<div class="slider-module-content-wrap">
						<?php if ( $content ) : ?>
						<p class="slider-module-content"><?php echo esc_html( $content ); ?></p>
						<?php endif; ?>

						<?php if ( $cta_text && $cta_link ) : ?>
						<a class="btn btn-primary slider-module-cta" href="<?php echo esc_url( $cta_link ); ?>">
							<?php echo esc_html( $cta_text ); ?>
						</a>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
