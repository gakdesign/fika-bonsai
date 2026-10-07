<?php
/**
 * Product Grid Module
 *
 * Shop spotlight / featured products grid.
 * ACF Flexible Content Layout: product_grid
 *
 * Related CSS: assets/css/modules/_product-grid.css
 * Related ACF: acf-json/group_fika_product_grid.json
 */

$section_title = get_sub_field( 'section_title' );
$section_intro = get_sub_field( 'section_intro' );
$background_style = get_sub_field( 'background_style' ); // 'default' or 'raised'
$products = get_sub_field( 'products' );
$view_all_link = get_sub_field( 'view_all_link' );
$view_all_text = get_sub_field( 'view_all_text' ) ?: 'View all products';

$section_class = 'section';
if ( $background_style === 'raised' ) {
	$section_class .= ' product-section-raised';
}
?>

<section class="<?php echo esc_attr( $section_class ); ?>" aria-labelledby="shop-heading">
	<div class="max-w">
		<?php if ( $section_title ) : ?>
		<h2 id="shop-heading" class="section-title">
			<?php echo esc_html( $section_title ); ?>
		</h2>
		<?php endif; ?>

		<?php if ( $section_intro ) : ?>
		<p class="section-intro">
			<?php echo wp_kses_post( $section_intro ); ?>
		</p>
		<?php endif; ?>

		<?php if ( $products && is_array( $products ) && count( $products ) > 0 ) : ?>
		<div class="card-grid">
			<?php foreach ( $products as $product ) : ?>
			<a class="product-card" href="<?php echo esc_url( $product['link'] ); ?>">
				<?php if ( ! empty( $product['image'] ) ) : ?>
				<figure>
					<?php
					$img_id = is_array( $product['image'] ) ? $product['image']['ID'] : $product['image'];
					echo wp_get_attachment_image(
						$img_id,
						'card',
						false,
						array( 'loading' => 'lazy' )
					);
					?>
				</figure>
				<?php endif; ?>

				<?php if ( ! empty( $product['title'] ) ) : ?>
				<h3><?php echo esc_html( $product['title'] ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $product['price'] ) ) : ?>
				<p class="product-price">
					<?php echo esc_html( $product['price'] ); ?>
				</p>
				<?php endif; ?>
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
