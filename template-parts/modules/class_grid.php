<?php
/**
 * Class Grid Module
 *
 * Featured classes / workshops grid with booking functionality.
 * Cards are populated from a Relationship field selecting `market`,
 * `workshop`, and `product` posts (products limited to the workshops
 * category — see inc/woocommerce.php). Card content comes from the Market /
 * Workshop Details field group on the related post
 * (acf-json/group_fika_market_workshop_details.json); for products, image,
 * price, booking link and sold-out state come from WooCommerce instead.
 * ACF Flexible Content Layout: class_grid
 *
 * Related CSS: assets/css/modules/class_grid.css
 * Related ACF: acf-json/group_fika_page_builder.json
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
			<?php foreach ( $classes as $class_id ) :
				$class_id = is_object( $class_id ) ? $class_id->ID : $class_id;

				$title     = get_the_title( $class_id );
				$image     = get_field( 'image', $class_id );
				$date      = bonsai_get_event_date_display( $class_id );
				$time      = get_field( 'event_time', $class_id );
				$price     = get_field( 'price', $class_id );
				$location  = get_field( 'location', $class_id );
				$book_link = get_field( 'book_link', $class_id );
				$sold_out  = get_field( 'sold_out', $class_id );

				// Workshop products: WooCommerce owns image, price, booking and stock.
				if ( 'product' === get_post_type( $class_id ) && function_exists( 'bonsai_wc_class_card_data' ) ) {
					$wc_card = bonsai_wc_class_card_data( $class_id );
					if ( $wc_card ) {
						$image     = $wc_card['image'];
						$price     = $wc_card['price'];
						$book_link = $wc_card['book_link'];
						$sold_out  = $wc_card['sold_out'];
					}
				}

				$meta_parts = array_filter( array( $date, $time, $price ) );
				$meta       = implode( ' · ', $meta_parts );
				$permalink  = get_permalink( $class_id );
				?>
			<div class="class-card<?php echo $sold_out ? ' class-card-sold-out' : ''; ?>">
				<?php if ( $image ) : ?>
				<figure class="class-card-figure">
					<?php
					$img_id = is_array( $image ) ? $image['ID'] : $image;
					echo wp_get_attachment_image(
						$img_id,
						'card',
						false,
						array( 'loading' => 'lazy' )
					);
					?>
				</figure>
				<?php endif; ?>

				<?php if ( $title ) : ?>
				<h3><a class="class-card-link" href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
				<?php endif; ?>

				<?php if ( $meta ) : ?>
				<p class="class-meta"><?php echo esc_html( $meta ); ?></p>
				<?php endif; ?>

				<?php if ( $location ) : ?>
				<p class="class-location"><?php echo esc_html( $location ); ?></p>
				<?php endif; ?>

				<?php if ( $sold_out ) : ?>
				<span class="btn btn-sold-out"><?php esc_html_e( 'Sold Out', 'fika-bonsai' ); ?></span>
				<?php elseif ( $book_link ) : ?>
				<a class="btn btn-primary class-card-book" href="<?php echo esc_url( $book_link ); ?>"><?php esc_html_e( 'Book', 'fika-bonsai' ); ?></a>
				<?php endif; ?>
			</div>
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
