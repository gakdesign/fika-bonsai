<?php
/**
 * Story Block Module
 *
 * Blockquote / testimonial with decorative divider.
 * ACF Flexible Content Layout: story_block
 *
 * Related CSS: assets/css/modules/_story-block.css
 * Related ACF: acf-json/group_fika_story_block.json
 */

$quote_text = get_sub_field( 'quote_text' );
$background_style = get_sub_field( 'background_style' ) ?: 'default';
?>

<section class="section story<?php echo $background_style !== 'default' ? ' story-' . esc_attr( $background_style ) : ''; ?>" aria-labelledby="story-heading">
	<div class="max-w">
		<h2 id="story-heading" class="visually-hidden">Our story</h2>
		<?php if ( $quote_text ) : ?>
		<blockquote>
			<?php echo wp_kses_post( $quote_text ); ?>
		</blockquote>
		<?php endif; ?>
	</div>
</section>
