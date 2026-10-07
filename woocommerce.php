<?php
/**
 * woocommerce.php – WooCommerce wrapper template
 *
 * WooCommerce's template loader picks this file up (theme root only) for the
 * shop, product archives, and single products. Without it, index.php would
 * route everything through templates/page.php → page_builder, which never
 * outputs WooCommerce content.
 *
 * Single products get no .max-w wrapper here — content-single-product.php
 * (and any page_builder modules under a workshop) set their own widths.
 *
 * Cart / Checkout / My Account are ordinary pages and are handled by
 * bonsai_wc_render_core_page() in inc/woocommerce.php instead.
 *
 * @package Bonsai_Base_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<?php if ( is_singular( 'product' ) ) : ?>

	<div class="wc-page wc-page--product">
		<div class="max-w">
			<?php woocommerce_breadcrumb(); ?>
		</div>

		<?php
		while ( have_posts() ) :
			the_post();
			wc_get_template_part( 'content', 'single-product' );
		endwhile;
		?>
	</div>

<?php else : ?>

	<section class="wc-page wc-page--archive">
		<div class="max-w">
			<?php
			woocommerce_breadcrumb();
			woocommerce_content();
			?>
		</div>
	</section>

<?php endif; ?>

<?php
get_footer();
