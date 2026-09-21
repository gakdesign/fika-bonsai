<?php
/**
 * Hero Split Module
 * 
 * Organic split layout: content on left, clipped image on right with decorative leaf.
 * ACF Flexible Content Layout: hero_split
 *
 * Related CSS: assets/css/modules/_hero-split.css
 * Related ACF: acf-json/group_fika_hero_split.json
 */

$kicker = get_sub_field( 'kicker' );
$heading = get_sub_field( 'heading' );
$heading_accent = get_sub_field( 'heading_accent_word' );
$lead = get_sub_field( 'lead_text' );
$primary_cta_text = get_sub_field( 'primary_cta_text' );
$primary_cta_link = get_sub_field( 'primary_cta_link' );
$secondary_cta_text = get_sub_field( 'secondary_cta_text' );
$secondary_cta_link = get_sub_field( 'secondary_cta_link' );
$features = get_sub_field( 'features' );
$hero_image = get_sub_field( 'hero_image' );
?>

<section class="hero" aria-labelledby="hero-heading">
	<!-- SVG defs: organic clip + decorative leaf -->
	<svg width="0" height="0" aria-hidden="true" focusable="false">
		<defs>
			<clipPath id="organicHero" clipPathUnits="objectBoundingBox">
				<path d="M0.14,0 C0.02,0.22 0.02,0.78 0.14,1 L1,1 L1,0 Z" />
			</clipPath>
		</defs>
	</svg>

	<div class="hero-grid">
		<div class="hero-content">
			<?php if ( $kicker ) : ?>
			<p class="hero-kicker">
				<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
					<path fill="currentColor" d="M12 21s-6.5-4.35-6.5-9.5A4.5 4.5 0 0 1 12 7a4.5 4.5 0 0 1 6.5 4.5C18.5 16.65 12 21 12 21Z" />
				</svg>
				<?php echo esc_html( $kicker ); ?>
			</p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
			<h1 id="hero-heading">
				<?php echo esc_html( $heading ); ?>
				<?php if ( $heading_accent ) : ?>
					<span class="accent-word"><?php echo esc_html( $heading_accent ); ?></span>
				<?php endif; ?>
			</h1>
			<?php endif; ?>

			<?php if ( $lead ) : ?>
			<p class="hero-lead">
				<?php echo wp_kses_post( $lead ); ?>
			</p>
			<?php endif; ?>

			<?php if ( $primary_cta_text || $secondary_cta_text ) : ?>
			<div class="hero-actions">
				<?php if ( $primary_cta_text && $primary_cta_link ) : ?>
				<a class="btn btn-primary" href="<?php echo esc_url( $primary_cta_link ); ?>">
					<?php echo esc_html( $primary_cta_text ); ?>
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</a>
				<?php endif; ?>

				<?php if ( $secondary_cta_text && $secondary_cta_link ) : ?>
				<a class="link-quiet" href="<?php echo esc_url( $secondary_cta_link ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M8 5v14l11-7L8 5Z" />
					</svg>
					<?php echo esc_html( $secondary_cta_text ); ?>
				</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $features && is_array( $features ) && count( $features ) > 0 ) : ?>
			<div class="hero-features">
				<?php foreach ( $features as $feature ) : ?>
				<article class="feature-card">
					<?php
					$icon_name = $feature['icon'] ?? '';
					$icon_html = bonsai_get_feature_icon( $icon_name );
					?>
					<?php if ( $icon_html ) : ?>
					<div class="feature-icon"><?php echo $icon_html; ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $feature['title'] ) ) : ?>
					<h3><?php echo esc_html( $feature['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $feature['description'] ) ) : ?>
					<p><?php echo esc_html( $feature['description'] ); ?></p>
					<?php endif; ?>
				</article>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>

		<div class="hero-media">
			<!-- class="hero-leaf" viewBox="0 0 120 140" aria-hidden="true">
				<path fill="currentColor" d="M98 8c-18 8-32 24-38 45-4 14-4 28 2 42-12-6-22-16-28-28C20 45 18 22 28 8c20 4 38 14 52 28 6-10 10-20 18-28Z" opacity="0.35" />
				<path fill="currentColor" d="M75 0c8 22 6 48-8 70-8 12-20 22-34 28C20 60 8 28 20 4c18 4 36 12 55-4Z" />
			</svg>-->
			<div class="hero-media-inner">
				<?php if ( $hero_image ) : ?>
				<?php
				$img_id = is_array( $hero_image ) ? $hero_image['ID'] : $hero_image;
				echo wp_get_attachment_image(
					$img_id,
					'hero',
					false,
					array( 'class' => '', 'loading' => 'eager', 'fetchpriority' => 'high' )
				);
				?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
