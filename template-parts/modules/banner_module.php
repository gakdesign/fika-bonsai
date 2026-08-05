<?php
/**
 * Banner Module
 *
 * Full-bleed background image with a title, optional subheader, and an
 * optional single CTA.
 * ACF Flexible Content Layout: banner_module
 *
 * Related CSS: assets/css/modules/banner_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_banner_module)
 */

$background_image = get_sub_field( 'background_image' );
$title             = get_sub_field( 'title' );
$subheader         = get_sub_field( 'subheader' );
$cta_text          = get_sub_field( 'cta_text' );
$cta_link          = get_sub_field( 'cta_link' );

if ( ! $title ) {
	return;
}
?>

<section class="banner-module" aria-labelledby="banner-module-heading">
	<?php if ( ! empty( $background_image['url'] ) ) : ?>
	<?php
	$img_id = is_array( $background_image ) ? $background_image['ID'] : $background_image;
	echo wp_get_attachment_image(
		$img_id,
		'full',
		false,
		array( 'class' => 'banner-module-bg-image', 'loading' => 'eager', 'fetchpriority' => 'high' )
	);
	?>
	<?php endif; ?>

	<div class="max-w banner-module-inner">
		<h1 id="banner-module-heading" class="banner-module-title">
			<?php echo esc_html( $title ); ?>
		</h1>

		<?php if ( $subheader ) : ?>
		<p class="banner-module-subheader"><?php echo esc_html( $subheader ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_text && $cta_link ) : ?>
		<a class="btn btn-primary banner-module-cta" href="<?php echo esc_url( $cta_link ); ?>">
			<?php echo esc_html( $cta_text ); ?>
		</a>
		<?php endif; ?>
	</div>
</section>
