<?php
/**
 * Contact Module
 *
 * Full-width title, then a 50/50 split: rich content (left) and a form
 * shortcode (right).
 * ACF Flexible Content Layout: contact_module
 *
 * Related CSS: assets/css/modules/contact_module.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_contact_module)
 */

$title            = get_sub_field( 'title' );
$content          = get_sub_field( 'content' );
$form_shortcode   = get_sub_field( 'form_shortcode' );
$background_style = get_sub_field( 'background_style' ) ?: 'default';
?>

<section class="section contact-module<?php echo $background_style !== 'default' ? ' contact-module-' . esc_attr( $background_style ) : ''; ?>"<?php echo $title ? ' aria-labelledby="contact-module-heading"' : ' aria-label="' . esc_attr__( 'Contact', 'fika-bonsai' ) . '"'; ?>>
	<div class="max-w">
		<?php if ( $title ) : ?>
		<h2 id="contact-module-heading" class="contact-module-title">
			<?php echo esc_html( $title ); ?>
		</h2>
		<?php endif; ?>

		<div class="contact-module-grid">
			<?php if ( $content ) : ?>
			<div class="contact-module-content">
				<?php echo wp_kses_post( $content ); ?>
			</div>
			<?php endif; ?>

			<?php if ( $form_shortcode ) : ?>
			<div class="contact-module-form">
				<?php echo do_shortcode( $form_shortcode ); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
