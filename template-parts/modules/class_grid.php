<?php
/**
 * Class Grid Module
 *
 * Featured classes / workshops grid with booking functionality.
 * ACF Flexible Content Layout: class_grid
 *
 * Related CSS: assets/css/modules/_class-grid.css
 * Related ACF: acf-json/group_fika_class_grid.json
 */

$section_title = get_sub_field( 'section_title' );
$section_intro = get_sub_field( 'section_intro' );
$classes = get_sub_field( 'classes' );
$view_all_link = get_sub_field( 'view_all_link' );
$view_all_text = get_sub_field( 'view_all_text' ) ?: 'View full calendar';
?>

<section class="section" aria-labelledby="classes-heading">
	<div class="max-w">
		<?php if ( $section_title ) : ?>
		<h2 id="classes-heading" class="section-title">
			<?php echo esc_html( $section_title ); ?>
		</h2>
		<?php endif; ?>

		<?php if ( $section_intro ) : ?>
		<p class="section-intro">
			<?php echo wp_kses_post( $section_intro ); ?>
		</p>
		<?php endif; ?>

		<?php if ( $classes && is_array( $classes ) && count( $classes ) > 0 ) : ?>
		<div class="card-grid">
			<?php foreach ( $classes as $class ) : ?>
			<a class="class-card" href="<?php echo esc_url( $class['link'] ); ?>">
				<?php if ( ! empty( $class['image'] ) ) : ?>
				<figure>
					<?php
					$img_id = is_array( $class['image'] ) ? $class['image']['ID'] : $class['image'];
					echo wp_get_attachment_image(
						$img_id,
						'card',
						false,
						array( 'loading' => 'lazy' )
					);
					?>
				</figure>
				<?php endif; ?>

				<?php if ( ! empty( $class['title'] ) ) : ?>
				<h3><?php echo esc_html( $class['title'] ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $class['meta'] ) ) : ?>
				<p class="class-meta"><?php echo esc_html( $class['meta'] ); ?></p>
				<?php endif; ?>

				<span class="btn btn-primary">
					<?php echo esc_html( $class['cta_text'] ?? 'Book' ); ?>
				</span>
			</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( $view_all_link ) : ?>
		<p class="section-cta">
			<a class="link-quiet" href="<?php echo esc_url( $view_all_link ); ?>">
				<?php echo esc_html( $view_all_text ); ?>
			</a>
		</p>
		<?php endif; ?>
	</div>
</section>
