<?php
/**
 * Media Module (Image / Video)
 *
 * Full-width image (natural aspect ratio, no cropping, optional caption) or
 * a full-width YouTube/Vimeo embed via ACF's oEmbed field. Only one of the
 * two renders, based on Media Type.
 * ACF Flexible Content Layout: media_module
 *
 * Related CSS: assets/css/modules/media_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_media_module)
 */

$media_type = get_sub_field( 'media_type' ) ?: 'image';
$image      = get_sub_field( 'image' );
$video      = get_sub_field( 'video' );
$caption    = get_sub_field( 'caption' );
$narrow     = (bool) get_sub_field( 'narrow' );

$is_image = ( 'image' === $media_type && ! empty( $image['url'] ) );
$is_video = ( 'video' === $media_type && $video );

if ( ! $is_image && ! $is_video ) {
	return;
}

$wrap_class = 'media-module-figure' . ( $narrow ? ' media-module-figure-narrow' : '' );
?>

<section class="section media-module" aria-label="<?php echo esc_attr__( 'Media', 'fika-bonsai' ); ?>">
	<div class="max-w">

		<?php if ( $is_image ) : ?>
		<figure class="<?php echo esc_attr( $wrap_class ); ?>">
			<img
				src="<?php echo esc_url( $image['url'] ); ?>"
				alt="<?php echo esc_attr( $image['alt'] ?: '' ); ?>"
				width="<?php echo esc_attr( $image['width'] ?: '' ); ?>"
				height="<?php echo esc_attr( $image['height'] ?: '' ); ?>"
				loading="lazy"
				class="media-module-image"
			/>
			<?php if ( $caption ) : ?>
			<figcaption class="media-module-caption"><?php echo esc_html( $caption ); ?></figcaption>
			<?php endif; ?>
		</figure>
		<?php endif; ?>

		<?php if ( $is_video ) : ?>
		<div class="<?php echo esc_attr( $wrap_class ); ?> media-module-video">
			<?php echo bonsai_kses_iframe( $video ); ?>
		</div>
		<?php endif; ?>

	</div>
</section>
