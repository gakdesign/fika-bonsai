<?php
/**
 * Split Content Module
 *
 * Full-width title, then a left/right split. Each side is independently
 * Text + CTA, Image, or Video, with optional order reverse and vertical
 * centering.
 * ACF Flexible Content Layout: split_content_module
 *
 * Related CSS: assets/css/modules/split_content_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_split_content_module)
 */

/**
 * Render one side of the split.
 *
 * @param array $side Sub-field values for left_content or right_content.
 */
if ( ! function_exists( 'bonsai_render_split_content_side' ) ) {
	function bonsai_render_split_content_side( $side ) {
		if ( ! is_array( $side ) ) {
			return;
		}

		$content_type = $side['content_type'] ?? 'text';
		?>
		<div class="split-content-side">
			<?php if ( 'text' === $content_type ) : ?>
				<?php if ( ! empty( $side['text'] ) ) : ?>
				<div class="split-content-text">
					<?php echo wp_kses_post( $side['text'] ); ?>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $side['cta_text'] ) && ! empty( $side['cta_link'] ) ) : ?>
				<a class="btn btn-primary split-content-cta" href="<?php echo esc_url( $side['cta_link'] ); ?>">
					<?php echo esc_html( $side['cta_text'] ); ?>
				</a>
				<?php endif; ?>

			<?php elseif ( 'image' === $content_type ) : ?>
				<?php if ( ! empty( $side['image'] ) ) : ?>
				<figure class="split-content-media">
					<?php
					$img_id = is_array( $side['image'] ) ? $side['image']['ID'] : $side['image'];
					echo wp_get_attachment_image(
						$img_id,
						'large',
						false,
						array( 'loading' => 'lazy' )
					);
					?>
				</figure>
				<?php endif; ?>

			<?php elseif ( 'video' === $content_type ) : ?>
				<?php if ( ! empty( $side['video_url'] ) ) : ?>
				<div class="split-content-media split-content-video">
					<?php echo bonsai_kses_iframe( $side['video_url'] ); ?>
				</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}
}

$main_title    = get_sub_field( 'main_title' );
$title_align   = get_sub_field( 'title_alignment' );
$reverse_order = get_sub_field( 'reverse_order' );
$vertical_align = get_sub_field( 'vertical_align' ) ?: 'top';
$left_content  = get_sub_field( 'left_content' );
$right_content = get_sub_field( 'right_content' );

$grid_classes = 'split-content-grid';
if ( $reverse_order ) {
	$grid_classes .= ' split-content-reverse';
}
if ( 'center' === $vertical_align ) {
	$grid_classes .= ' split-content-align-center';
}

// Whitelist the modifier — anything unexpected keeps the default centred title.
$title_classes = 'split-content-title';
if ( 'left' === $title_align ) {
	$title_classes .= ' split-content-title--left';
}
?>

<section class="section split-content-module"<?php echo $main_title ? ' aria-labelledby="split-content-heading"' : ' aria-label="' . esc_attr__( 'Split content', 'fika-bonsai' ) . '"'; ?>>
	<div class="max-w">
		<?php if ( $main_title ) : ?>
		<h2 id="split-content-heading" class="<?php echo esc_attr( $title_classes ); ?>">
			<?php echo esc_html( $main_title ); ?>
		</h2>
		<?php endif; ?>

		<div class="<?php echo esc_attr( $grid_classes ); ?>">
			<?php bonsai_render_split_content_side( $left_content ); ?>
			<?php bonsai_render_split_content_side( $right_content ); ?>
		</div>
	</div>
</section>
