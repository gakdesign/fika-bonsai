<?php
/**
 * Stats Strip Module
 *
 * Full-width row of number + label stats (e.g. "500+ Loaves baked").
 * ACF Flexible Content Layout: stats_strip
 *
 * Related CSS: assets/css/modules/stats_strip.css
 * Related ACF: acf-json/group_fika_page_builder.json (layout_fika_stats_strip)
 */

$section_title = get_sub_field( 'section_title' );
$stats = get_sub_field( 'stats' );

if ( ! ( $stats && is_array( $stats ) && count( $stats ) > 0 ) ) {
	return;
}
?>

<section class="section stats-strip" aria-labelledby="stats-heading">
	<div class="max-w">
		<?php if ( $section_title ) : ?>
		<h2 id="stats-heading" class="section-title">
			<?php echo esc_html( $section_title ); ?>
		</h2>
		<?php endif; ?>

		<div class="stats-grid">
			<?php foreach ( $stats as $stat ) :
				if ( empty( $stat['number'] ) ) {
					continue;
				}
				?>
			<div class="stat-item">
				<p class="stat-number"><?php echo esc_html( $stat['number'] ); ?></p>
				<?php if ( ! empty( $stat['label'] ) ) : ?>
				<p class="stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
