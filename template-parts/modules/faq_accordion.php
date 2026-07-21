<?php
/**
 * FAQ Accordion Module
 *
 * Repeater of question/answer pairs using native <details>/<summary> —
 * no JS required, accessible by default.
 * ACF Flexible Content Layout: faq_accordion
 *
 * Related CSS: assets/css/modules/faq_accordion.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_faq_accordion)
 */

$section_title = get_sub_field( 'section_title' );
$faqs = get_sub_field( 'faqs' );

if ( ! ( $faqs && is_array( $faqs ) && count( $faqs ) > 0 ) ) {
	return;
}
?>

<section class="section faq-accordion" aria-labelledby="faq-heading">
	<div class="max-w max-w-narrow">
		<?php if ( $section_title ) : ?>
		<h2 id="faq-heading" class="section-title">
			<?php echo esc_html( $section_title ); ?>
		</h2>
		<?php endif; ?>

		<div class="faq-list">
			<?php foreach ( $faqs as $faq ) :
				if ( empty( $faq['question'] ) ) {
					continue;
				}
				?>
			<details class="faq-item">
				<summary class="faq-question"><?php echo esc_html( $faq['question'] ); ?></summary>
				<?php if ( ! empty( $faq['answer'] ) ) : ?>
				<div class="faq-answer">
					<?php echo wp_kses_post( $faq['answer'] ); ?>
				</div>
				<?php endif; ?>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
