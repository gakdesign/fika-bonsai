<?php
/**
 * CTA Banner Module
 *
 * Full-width single heading + button on a background image or accent colour.
 * ACF Flexible Content Layout: cta_banner
 *
 * Related CSS: assets/css/modules/cta_banner.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_cta_banner)
 */

$heading          = get_sub_field( 'heading' );
$subtext          = get_sub_field( 'subtext' );
$cta_text         = get_sub_field( 'cta_text' );
$cta_link         = get_sub_field( 'cta_link' );
$background_type  = get_sub_field( 'background_type' ) ?: 'color';
$background_image = get_sub_field( 'background_image' );

if ( ! $heading ) {
	return;
}

$has_image = ( 'image' === $background_type && $background_image );
?>

<section class="cta-banner<?php echo $has_image ? ' cta-banner-image' : ' cta-banner-color'; ?>" aria-labelledby="cta-banner-heading">
	<?php if ( $has_image ) : ?>
	<?php
	$img_id = is_array( $background_image ) ? $background_image['ID'] : $background_image;
	echo wp_get_attachment_image(
		$img_id,
		'full',
		false,
		array( 'class' => 'cta-banner-bg-image', 'loading' => 'lazy' )
	);
	?>
	<?php endif; ?>

	<div class="max-w cta-banner-inner">
		<h2 id="cta-banner-heading" class="cta-banner-heading">
			<?php echo esc_html( $heading ); ?>
		</h2>

		<?php if ( $subtext ) : ?>
		<p class="cta-banner-subtext"><?php echo esc_html( $subtext ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_text && $cta_link ) : ?>
		<a class="btn btn-primary cta-banner-button" href="<?php echo esc_url( $cta_link ); ?>">
			<?php echo esc_html( $cta_text ); ?>
		</a>
		<?php endif; ?>
	</div>
</section>
