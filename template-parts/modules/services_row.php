<?php
/**
 * Services Row Module
 *
 * Three-column service blocks (Classes, Shop, Gift Vouchers).
 * ACF Flexible Content Layout: services_row
 *
 * Related CSS: assets/css/modules/_services-row.css
 * Related ACF: acf-json/group_fika_services_row.json
 */

$services = get_sub_field( 'services' );
?>

<section class="section services" aria-labelledby="services-heading">
	<div class="max-w">
		<h2 id="services-heading" class="visually-hidden">What we offer</h2>
		<?php if ( $services && is_array( $services ) && count( $services ) > 0 ) : ?>
		<div class="services-grid">
			<?php foreach ( $services as $service ) : ?>
			<div class="service-block">
				<?php if ( ! empty( $service['title'] ) ) : ?>
				<h2><?php echo esc_html( $service['title'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $service['description'] ) ) : ?>
				<p><?php echo wp_kses_post( $service['description'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $service['link_text'] ) && ! empty( $service['link_url'] ) ) : ?>
				<a href="<?php echo esc_url( $service['link_url'] ); ?>">
					<?php echo esc_html( $service['link_text'] ); ?>
				</a>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
