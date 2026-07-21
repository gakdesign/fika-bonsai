<?php
/**
 * Template Part: Content Block
 * File: template-parts/snippets/content-block.php
 *
 * Assumes ACF flexible content / repeater sub fields:
 * - section_header (text)
 * - set_header_to_h1 (true/false)
 * - section_introduction (WYSIWYG)
 * - section_cta_one (link array)
 * - section_cta_two (link array)
 * - additional_content (WYSIWYG)
 */

// Cache ACF values once (avoid repeated get_sub_field calls)
$header            = get_sub_field('section_header');
$use_h1            = (bool) get_sub_field('set_header_to_h1');
$intro             = get_sub_field('section_content');
$cta_one           = get_sub_field('section_cta');
$cta_two           = get_sub_field('section_cta_two');
$additional        = get_sub_field('additional_content');

// Decide heading tag safely
$heading_tag = $use_h1 ? 'h1' : 'h2';
$allowed_heading_tags = ['h1', 'h2'];
if ( ! in_array($heading_tag, $allowed_heading_tags, true) ) {
    $heading_tag = 'h2';
}

// Helper: render an ACF link array as a CTA button
$render_cta = static function( $link, $extra_classes = '' ) {
    if ( ! is_array($link) ) {
        return;
    }

    $url    = isset($link['url']) ? esc_url( $link['url'] ) : '';
    $title  = isset($link['title']) ? wp_strip_all_tags( $link['title'] ) : '';
    $target = isset($link['target']) && in_array($link['target'], ['_self','_blank'], true)
        ? $link['target']
        : '_self';

    if ( empty($url) || '' === $title ) {
        return;
    }

    $rel = ('_blank' === $target) ? ' rel="noopener"' : '';

    printf(
        '<a class="btn cta-btn bronze-cta %1$s" href="%2$s" target="%3$s"%4$s aria-label="%5$s">%6$s</a>',
        esc_attr(trim($extra_classes)),
        esc_url($url),
        esc_attr($target),
        $rel,
        esc_attr($title),
        esc_html($title)
    );
};
?>

<div class="content-block <?php if(get_sub_field('center_content')) { ?>text-center<?php } ?>">
	<?php if ( ! empty($header) ) : ?>
		<<?php echo $heading_tag; ?> class="section-title"><?php echo esc_html( $header ); ?></<?php echo $heading_tag; ?>>
	<?php endif; ?>

	<?php if ( ! empty($intro) ) : ?>
		<div class="section-introduction">
			<?php echo wp_kses_post( $intro ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty($cta_one) || ! empty($cta_two) ) : ?>
		<div class="section-ctas <?php echo ( ! empty($cta_one) && ! empty($cta_two) ) ? 'has-two' : 'has-one'; ?>">
			<?php
				if ( ! empty($cta_one) && empty($cta_two) ) {
					echo '<div class="single-cta">';
						$render_cta( $cta_one );
					echo '</div>';
				} elseif ( ! empty($cta_one) && ! empty($cta_two) ) {
					echo '<div class="two-ctas">';
						$render_cta( $cta_one );
						$render_cta( $cta_two );
					echo '</div>';
				}
			?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty($additional) ) : ?>
		<div class="section-additional">
			<?php echo wp_kses_post( $additional ); ?>
		</div>
	<?php endif; ?>
</div>
