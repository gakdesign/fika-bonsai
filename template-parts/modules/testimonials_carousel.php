<?php
/**
 * Testimonials Carousel Module
 *
 * Repeater of quote/name/role, rotated with a Slick carousel.
 * ACF Flexible Content Layout: testimonials_carousel
 *
 * Related CSS: assets/css/modules/testimonials_carousel.css
 * Related JS: assets/js/main.js (.testimonials-carousel-track)
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_testimonials_carousel)
 */

$section_title = get_sub_field( 'section_title' );
$testimonials = get_sub_field( 'testimonials' );

if ( ! ( $testimonials && is_array( $testimonials ) && count( $testimonials ) > 0 ) ) {
	return;
}
?>

<section class="section testimonials-carousel" aria-labelledby="testimonials-heading">
	<div class="max-w max-w-narrow">
		<?php if ( $section_title ) : ?>
		<h2 id="testimonials-heading" class="section-title">
			<?php echo esc_html( $section_title ); ?>
		</h2>
		<?php endif; ?>

		<div class="testimonials-carousel-track">
			<?php foreach ( $testimonials as $testimonial ) :
				if ( empty( $testimonial['quote'] ) ) {
					continue;
				}
				?>
			<div class="testimonial-slide">
				<blockquote>
					<?php echo esc_html( $testimonial['quote'] ); ?>
				</blockquote>
				<?php if ( ! empty( $testimonial['name'] ) ) : ?>
				<p class="testimonial-name">
					<?php echo esc_html( $testimonial['name'] ); ?>
					<?php if ( ! empty( $testimonial['role'] ) ) : ?>
					<span class="testimonial-role"><?php echo esc_html( $testimonial['role'] ); ?></span>
					<?php endif; ?>
				</p>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
