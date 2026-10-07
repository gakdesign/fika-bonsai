<?php
/**
 * woocommerce.php – WooCommerce integration
 *
 * - Declares theme support (gallery zoom / lightbox / slider)
 * - Enqueues the Fika WooCommerce skin only on WooCommerce pages
 * - Renders Cart / Checkout / My Account via classic shortcodes
 *   (Gutenberg is disabled theme-wide and inc/gutenberg.php dequeues
 *   wc-block-style, so the block versions would render unstyled)
 * - Workshop products (product_cat: workshops): hides duplicate ACF fields,
 *   swaps the stock line for "spots remaining", and carries the workshop
 *   date/time through the basket, checkout, and order.
 *
 * Related templates: woocommerce.php (theme root),
 *   woocommerce/content-single-product.php,
 *   template-parts/woocommerce/workshop-product.php
 * Related CSS: assets/css/woocommerce.css, assets/css/woocommerce-workshop.css
 *
 * @package Bonsai_Base_Theme
 */

defined( 'ABSPATH' ) || exit;

// Bail cleanly if WooCommerce is deactivated — nothing below should run.
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Product category slug that switches a product to the workshop layout.
 *
 * @return string
 */
function bonsai_wc_workshop_cat() {
	return apply_filters( 'bonsai_wc_workshop_cat', 'workshops' );
}

/**
 * Whether a product is a workshop (in the workshops product category).
 *
 * @param int|WC_Product|null $product Product ID or object. Defaults to current post.
 * @return bool
 */
function bonsai_is_workshop_product( $product = null ) {
	if ( $product instanceof WC_Product ) {
		// Variations inherit categories from their parent.
		$product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	} else {
		$product_id = $product ? absint( $product ) : get_the_ID();
	}

	if ( ! $product_id ) {
		return false;
	}

	return has_term( bonsai_wc_workshop_cat(), 'product_cat', $product_id );
}

/**
 * Returns the workshop date/time meta line, e.g. "12/10/2026 · 10:00 am".
 *
 * @param int $product_id Product ID.
 * @return string Unescaped meta string, or empty string.
 */
function bonsai_wc_workshop_when( $product_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}

	$parts = array_filter(
		array(
			bonsai_get_event_date_display( $product_id ),
			get_field( 'event_time', $product_id ),
		)
	);

	return implode( ' · ', $parts );
}

/* =============================
  # Theme support
============================= */

/**
 * Declare WooCommerce support and enable the product gallery features.
 */
function bonsai_wc_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 900,
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'bonsai_wc_setup' );

/* =============================
  # Assets
============================= */

/**
 * Whether the current request is a WooCommerce page that needs the WC skin.
 *
 * @return bool
 */
function bonsai_is_wc_page() {
	return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Only load WooCommerce's own stylesheets on WooCommerce pages —
 * WC enqueues them site-wide by default.
 *
 * @param array $styles WooCommerce core styles.
 * @return array
 */
function bonsai_wc_limit_core_styles( $styles ) {
	return bonsai_is_wc_page() ? $styles : array();
}
add_filter( 'woocommerce_enqueue_styles', 'bonsai_wc_limit_core_styles' );

/**
 * Enqueue the Fika WooCommerce skin (and the workshop layout on workshop products).
 * Loaded after WooCommerce's core CSS so it overrides without !important.
 */
function bonsai_wc_assets() {
	if ( ! bonsai_is_wc_page() ) {
		return;
	}

	$deps = wp_style_is( 'woocommerce-general', 'registered' ) ? array( 'woocommerce-general', 'style' ) : array( 'style' );

	wp_enqueue_style(
		'fika-woocommerce',
		BONSAI_THEME_URI . '/assets/css/woocommerce.css',
		$deps,
		filemtime( BONSAI_THEME_DIR . '/assets/css/woocommerce.css' )
	);

	if ( is_product() && bonsai_is_workshop_product( get_queried_object_id() ) ) {
		wp_enqueue_style(
			'fika-woocommerce-workshop',
			BONSAI_THEME_URI . '/assets/css/woocommerce-workshop.css',
			array( 'fika-woocommerce' ),
			filemtime( BONSAI_THEME_DIR . '/assets/css/woocommerce-workshop.css' )
		);

		// Location map pop-up.
		wp_enqueue_script(
			'fika-workshop-map',
			BONSAI_THEME_URI . '/assets/js/workshop-map.js',
			array( 'jquery' ),
			filemtime( BONSAI_THEME_DIR . '/assets/js/workshop-map.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);

		// Sold-out waiting list form (inc/waiting-list.php).
		$product = wc_get_product( get_queried_object_id() );
		if ( $product && ! $product->is_in_stock() && function_exists( 'bonsai_waitlist_render_form' ) ) {
			wp_enqueue_script(
				'fika-workshop-waiting-list',
				BONSAI_THEME_URI . '/assets/js/workshop-waiting-list.js',
				array( 'jquery' ),
				filemtime( BONSAI_THEME_DIR . '/assets/js/workshop-waiting-list.js' ),
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
			wp_localize_script(
				'fika-workshop-waiting-list',
				'bonsaiWaitlist',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'sending' => __( 'Sending…', 'fika-bonsai' ),
					'error'   => __( "Sorry, something went wrong and we couldn't add you. Please try again, or contact us directly.", 'fika-bonsai' ),
				)
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'bonsai_wc_assets', 20 );

/* =============================
  # Cart / Checkout / My Account
============================= */

/**
 * Renders the Cart, Checkout, or My Account page via its classic shortcode.
 *
 * Called from template-parts/content/content-page.php. Those pages are
 * normal WP pages, which this theme renders through page_builder only
 * (the_content is never output), so without this they'd be blank.
 * The shortcodes also handle their endpoints (order-received, order-pay,
 * view-order, edit-address, etc.).
 *
 * @return bool True if a WooCommerce page was rendered.
 */
function bonsai_wc_render_core_page() {
	$shortcode = '';

	if ( is_cart() ) {
		$shortcode = '[woocommerce_cart]';
	} elseif ( is_checkout() ) {
		$shortcode = '[woocommerce_checkout]';
	} elseif ( is_account_page() ) {
		$shortcode = '[woocommerce_my_account]';
	}

	if ( ! $shortcode ) {
		return false;
	}
	?>
	<section class="wc-page">
		<div class="max-w">
			<header class="wc-page-header">
				<h1 class="wc-page-title"><?php the_title(); ?></h1>
			</header>
			<div class="woocommerce">
				<?php
				// Shortcode output is generated and escaped by WooCommerce's own templates.
				echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</section>
	<?php
	return true;
}

/* =============================
  # Breadcrumbs
============================= */

/**
 * Match the breadcrumb markup to the theme (nav landmark, no WC wrapper styling).
 *
 * @param array $defaults Breadcrumb defaults.
 * @return array
 */
function bonsai_wc_breadcrumb_defaults( $defaults ) {
	$defaults['delimiter']   = '<span class="wc-breadcrumb-sep" aria-hidden="true">/</span>';
	$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'fika-bonsai' ) . '">';
	$defaults['wrap_after']  = '</nav>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'bonsai_wc_breadcrumb_defaults' );

/* =============================
  # Workshop products — ACF
============================= */

/**
 * Whether ACF is currently rendering fields for a product edit screen.
 *
 * Covers the normal edit screen and ACF's AJAX field-group refresh (fired
 * when the Workshops category is ticked), where there's no global $post.
 *
 * @return bool
 */
function bonsai_wc_is_product_edit_context() {
	$post_id = get_the_ID();

	// ACF's own check_screen AJAX request — nonce is verified by ACF. We only read an int for a display decision.
	if ( ! $post_id && wp_doing_ajax() && isset( $_POST['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_id = absint( wp_unslash( $_POST['post_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}

	return $post_id && 'product' === get_post_type( $post_id );
}

/**
 * Hide Market / Workshop Details fields that WooCommerce owns on products.
 *
 * price           → WC Regular price
 * spots_remaining → WC Stock quantity (Inventory tab, "Manage stock")
 * sold_out        → WC Out of stock
 * book_link       → WC Add to basket
 * image           → Product image / gallery
 *
 * Returning false stops ACF rendering the field, so its "required"
 * validation doesn't fire on products either.
 *
 * @param array $field ACF field.
 * @return array|false
 */
function bonsai_wc_hide_duplicate_workshop_fields( $field ) {
	if ( is_admin() && bonsai_wc_is_product_edit_context() ) {
		return false;
	}
	return $field;
}
foreach ( array( 'price', 'spots_remaining', 'sold_out', 'book_link', 'image' ) as $bonsai_wc_field ) {
	add_filter( 'acf/prepare_field/key=field_fika_mw_' . $bonsai_wc_field, 'bonsai_wc_hide_duplicate_workshop_fields' );
}
unset( $bonsai_wc_field );

/**
 * class_grid picker: only list products that are in the workshops category.
 *
 * The relationship field allows market, workshop, and product. ACF's own
 * taxonomy filter would also hide market/workshop posts (they have no
 * product_cat), so non-workshop products are excluded by ID instead.
 *
 * @param array $args WP_Query args for the relationship picker.
 * @return array
 */
function bonsai_wc_class_grid_workshop_products_only( $args ) {
	$non_workshop_ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- admin-only picker query.
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => bonsai_wc_workshop_cat(),
					'operator' => 'NOT IN',
				),
			),
		)
	);

	if ( $non_workshop_ids ) {
		$existing             = isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array();
		$args['post__not_in'] = array_merge( $existing, $non_workshop_ids ); // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- small shop, admin-only.
	}

	return $args;
}
add_filter( 'acf/fields/relationship/query/key=field_fika_classes', 'bonsai_wc_class_grid_workshop_products_only' );

/**
 * Card data for a workshop product in class_grid.
 *
 * Products don't use the ACF price / image / book_link / sold_out fields
 * (WooCommerce owns those), so pull them from the product instead.
 *
 * @param int $product_id Product ID.
 * @return array|null { image, price, book_link, sold_out } or null if not a product.
 */
function bonsai_wc_class_card_data( $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return null;
	}

	$price = '';
	if ( '' !== $product->get_price() ) {
		// Plain text for the "date · time · price" meta line (wc_price returns HTML + entities).
		$price = html_entity_decode( wp_strip_all_tags( wc_price( wc_get_price_to_display( $product ) ) ), ENT_QUOTES, 'UTF-8' );
	}

	return array(
		'image'     => get_post_thumbnail_id( $product_id ),
		'price'     => $price,
		'book_link' => get_permalink( $product_id ), // Booking happens on the product page.
		'sold_out'  => ! $product->is_in_stock(),
	);
}

/* =============================
  # Workshop products — front end
============================= */

/**
 * Suppress WooCommerce's "X in stock" line on workshop products —
 * the workshop template shows "X spots remaining" instead.
 *
 * @param string     $html    Stock HTML.
 * @param WC_Product $product Product.
 * @return string
 */
function bonsai_wc_workshop_stock_html( $html, $product ) {
	return bonsai_is_workshop_product( $product ) ? '' : $html;
}
add_filter( 'woocommerce_get_stock_html', 'bonsai_wc_workshop_stock_html', 10, 2 );

/**
 * Workshop-friendly add to basket label on the single product page.
 *
 * @param string     $text    Button text.
 * @param WC_Product $product Product.
 * @return string
 */
function bonsai_wc_workshop_button_text( $text, $product ) {
	return bonsai_is_workshop_product( $product ) ? __( 'Book your place', 'fika-bonsai' ) : $text;
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'bonsai_wc_workshop_button_text', 10, 2 );

/**
 * "More workshops" heading for related products on workshop products.
 *
 * @param string $heading Heading text.
 * @return string
 */
function bonsai_wc_workshop_related_heading( $heading ) {
	return is_product() && bonsai_is_workshop_product( get_queried_object_id() ) ? __( 'More workshops', 'fika-bonsai' ) : $heading;
}
add_filter( 'woocommerce_product_related_products_heading', 'bonsai_wc_workshop_related_heading' );

/**
 * Show the workshop date/time under the title on shop / category cards.
 */
function bonsai_wc_loop_workshop_when() {
	global $product;

	if ( ! $product || ! bonsai_is_workshop_product( $product ) ) {
		return;
	}

	$when = bonsai_wc_workshop_when( $product->get_id() );
	if ( $when ) {
		echo '<p class="wc-workshop-when">' . esc_html( $when ) . '</p>';
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'bonsai_wc_loop_workshop_when', 5 );

/* =============================
  # Workshop date through basket → order
============================= */

/**
 * Show the workshop date/time against the line item in the basket and checkout.
 *
 * @param array $item_data Existing item data.
 * @param array $cart_item Cart item.
 * @return array
 */
function bonsai_wc_cart_workshop_when( $item_data, $cart_item ) {
	$product_id = $cart_item['product_id'] ?? 0;

	if ( $product_id && bonsai_is_workshop_product( $product_id ) ) {
		$when = bonsai_wc_workshop_when( $product_id );
		if ( $when ) {
			$item_data[] = array(
				'key'   => __( 'When', 'fika-bonsai' ),
				'value' => $when,
			);
		}
	}

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'bonsai_wc_cart_workshop_when', 10, 2 );

/**
 * Save the workshop date/time and location to the order line item, so it
 * appears in order emails and wp-admin even if the product is later edited.
 *
 * @param WC_Order_Item_Product $item          Order item.
 * @param string                $cart_item_key Cart item key.
 * @param array                 $values        Cart item values.
 */
function bonsai_wc_order_item_workshop_when( $item, $cart_item_key, $values ) {
	$product_id = $values['product_id'] ?? 0;

	if ( ! $product_id || ! bonsai_is_workshop_product( $product_id ) ) {
		return;
	}

	$when = bonsai_wc_workshop_when( $product_id );
	if ( $when ) {
		$item->add_meta_data( __( 'When', 'fika-bonsai' ), $when, true );
	}

	$location = function_exists( 'get_field' ) ? get_field( 'location', $product_id ) : '';
	if ( $location ) {
		$item->add_meta_data( __( 'Where', 'fika-bonsai' ), sanitize_text_field( $location ), true );
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'bonsai_wc_order_item_workshop_when', 10, 3 );
