<?php
/**
 * waiting-list.php – Sold-out workshop waiting list
 *
 * On sold-out products in the workshops category, visitors can leave their
 * email to join a waiting list:
 * - Form: bonsai_waitlist_render_form() (called from workshop-product.php)
 * - Submit: AJAX (assets/js/workshop-waiting-list.js) with an admin-post.php
 *   fallback when JS is off — both use bonsai_waitlist_handle()
 * - Email: sent to Theme Settings → "Workshop Waiting List Email"
 *   (falls back to the site admin email), Reply-To the visitor
 * - Storage: product meta `_bonsai_waiting_list`, listed in a read-only
 *   "Waiting list" box on the product edit screen (with a clear option)
 * - GDPR: consent checkbox required; registered with WP's personal data
 *   exporter / eraser; suggested privacy policy text added
 *
 * @package Bonsai_Base_Theme
 */

defined( 'ABSPATH' ) || exit;

// Relies on the workshop helpers in inc/woocommerce.php.
if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'bonsai_is_workshop_product' ) ) {
	return;
}

/**
 * Meta key the waiting list is stored under on each product.
 */
define( 'BONSAI_WAITLIST_META', '_bonsai_waiting_list' );

/**
 * Where waiting list notifications are sent.
 *
 * @return string Email address.
 */
function bonsai_waitlist_recipient() {
	$email = function_exists( 'get_field' ) ? get_field( 'waiting_list_email', 'option' ) : '';
	return is_email( $email ) ? $email : get_option( 'admin_email' );
}

/**
 * Returns the stored waiting list for a product.
 *
 * @param int $product_id Product ID.
 * @return array[] List of [ 'email' => string, 'date' => string ].
 */
function bonsai_waitlist_get( $product_id ) {
	$list = get_post_meta( $product_id, BONSAI_WAITLIST_META, true );
	return is_array( $list ) ? $list : array();
}

/**
 * Visitor-facing messages, keyed by result code.
 *
 * @return array
 */
function bonsai_waitlist_messages() {
	return array(
		'joined'       => __( "Thanks — you're on the waiting list. We'll email you if a place becomes available.", 'fika-bonsai' ),
		'already'      => __( "You're already on the waiting list for this workshop — we'll be in touch if a place opens up.", 'fika-bonsai' ),
		'invalid'      => __( 'Please enter a valid email address.', 'fika-bonsai' ),
		'consent'      => __( 'Please tick the box to agree to us contacting you.', 'fika-bonsai' ),
		'available'    => __( 'Good news — places are available again, so you can book now.', 'fika-bonsai' ),
		'rate_limited' => __( 'Too many attempts — please try again in a little while.', 'fika-bonsai' ),
		'error'        => __( "Sorry, something went wrong and we couldn't add you. Please try again, or contact us directly.", 'fika-bonsai' ),
	);
}

/* =============================
  # Front-end form
============================= */

/**
 * Outputs the waiting list form for a sold-out workshop product.
 *
 * @param WC_Product $product Product.
 */
function bonsai_waitlist_render_form( $product ) {
	if ( ! $product instanceof WC_Product || $product->is_in_stock() || ! bonsai_is_workshop_product( $product ) ) {
		return;
	}

	$messages = bonsai_waitlist_messages();

	// Result from the no-JS admin-post.php fallback redirect. Display-only, no data processed.
	$code    = isset( $_GET['waitlist'] ) ? sanitize_key( wp_unslash( $_GET['waitlist'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$message = isset( $messages[ $code ] ) ? $messages[ $code ] : '';
	$success = in_array( $code, array( 'joined', 'already' ), true );
	$uid     = 'waitlist-' . $product->get_id();
	?>
	<div class="workshop-waitlist<?php echo $success ? ' is-complete' : ''; ?>" id="workshop-waitlist">
		<h2 class="workshop-waitlist-title"><?php esc_html_e( 'Join the waiting list', 'fika-bonsai' ); ?></h2>
		<p class="workshop-waitlist-intro">
			<?php esc_html_e( "This workshop is sold out. Leave your email and we'll let you know if a place becomes available.", 'fika-bonsai' ); ?>
		</p>

		<form class="workshop-waitlist-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="bonsai_waitlist_join">
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
			<?php wp_nonce_field( 'bonsai_waitlist_join', 'bonsai_waitlist_nonce' ); ?>

			<?php // Honeypot — hidden from people, bots tend to fill it in. ?>
			<div class="workshop-waitlist-hp" aria-hidden="true">
				<label for="<?php echo esc_attr( $uid ); ?>-website"><?php esc_html_e( 'Leave this empty', 'fika-bonsai' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $uid ); ?>-website" name="website" value="" tabindex="-1" autocomplete="off">
			</div>

			<label class="workshop-waitlist-label" for="<?php echo esc_attr( $uid ); ?>-email"><?php esc_html_e( 'Email address', 'fika-bonsai' ); ?></label>
			<div class="workshop-waitlist-row">
				<input type="email" id="<?php echo esc_attr( $uid ); ?>-email" name="waitlist_email" required autocomplete="email" placeholder="<?php esc_attr_e( 'you@example.com', 'fika-bonsai' ); ?>">
				<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Join waiting list', 'fika-bonsai' ); ?></button>
			</div>

			<label class="workshop-waitlist-consent" for="<?php echo esc_attr( $uid ); ?>-consent">
				<input type="checkbox" id="<?php echo esc_attr( $uid ); ?>-consent" name="waitlist_consent" value="1" required>
				<span>
					<?php
					printf(
						/* translators: %s: site name */
						esc_html__( 'I agree to %s emailing me about this workshop.', 'fika-bonsai' ),
						esc_html( get_bloginfo( 'name' ) )
					);
					$privacy_link = get_the_privacy_policy_link();
					if ( $privacy_link ) {
						echo ' ' . wp_kses_post( $privacy_link );
					}
					?>
				</span>
			</label>
		</form>

		<p class="workshop-waitlist-message<?php echo $message ? ( $success ? ' is-success' : ' is-error' ) : ''; ?>" role="status" aria-live="polite"><?php echo esc_html( $message ); ?></p>
	</div>
	<?php
}

/* =============================
  # Submit handler
============================= */

/**
 * Sends the handler result as JSON (AJAX) or redirects back (no-JS fallback).
 *
 * @param string $code       Result code (see bonsai_waitlist_messages()).
 * @param int    $product_id Product ID.
 */
function bonsai_waitlist_respond( $code, $product_id ) {
	$messages = bonsai_waitlist_messages();
	$success  = in_array( $code, array( 'joined', 'already' ), true );

	if ( wp_doing_ajax() ) {
		$data = array(
			'code'    => $code,
			'message' => $messages[ $code ] ?? $messages['error'],
		);
		if ( $success ) {
			wp_send_json_success( $data );
		}
		wp_send_json_error( $data, 'error' === $code ? 500 : 400 );
	}

	$url = $product_id ? get_permalink( $product_id ) : home_url( '/' );
	wp_safe_redirect( add_query_arg( 'waitlist', $code, $url ) . '#workshop-waitlist' );
	exit;
}

/**
 * Handles a waiting list sign-up (AJAX and admin-post.php).
 *
 * Public form, so there's no capability check — protection is the nonce,
 * honeypot, per-IP rate limit, and only accepting sold-out workshop products.
 */
function bonsai_waitlist_handle() {
	$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;

	try {
		$nonce = isset( $_POST['bonsai_waitlist_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['bonsai_waitlist_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'bonsai_waitlist_join' ) ) {
			bonsai_waitlist_respond( 'error', $product_id );
		}

		// Honeypot filled in — pretend it worked, store nothing.
		if ( ! empty( $_POST['website'] ) ) {
			bonsai_waitlist_respond( 'joined', $product_id );
		}

		// Rate limit: 5 attempts per IP per hour.
		$ip       = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$rate_key = 'bonsai_waitlist_' . md5( $ip );
		$attempts = (int) get_transient( $rate_key );
		if ( $attempts >= 5 ) {
			bonsai_waitlist_respond( 'rate_limited', $product_id );
		}
		set_transient( $rate_key, $attempts + 1, HOUR_IN_SECONDS );

		$product = $product_id ? wc_get_product( $product_id ) : null;
		if ( ! $product || 'publish' !== $product->get_status() || ! bonsai_is_workshop_product( $product ) ) {
			bonsai_waitlist_respond( 'error', $product_id );
		}

		if ( $product->is_in_stock() ) {
			bonsai_waitlist_respond( 'available', $product_id );
		}

		$email = isset( $_POST['waitlist_email'] ) ? sanitize_email( wp_unslash( $_POST['waitlist_email'] ) ) : '';
		if ( ! is_email( $email ) ) {
			bonsai_waitlist_respond( 'invalid', $product_id );
		}

		if ( empty( $_POST['waitlist_consent'] ) ) {
			bonsai_waitlist_respond( 'consent', $product_id );
		}

		// Already on the list — don't store or email twice.
		$list = bonsai_waitlist_get( $product_id );
		foreach ( $list as $entry ) {
			if ( isset( $entry['email'] ) && 0 === strcasecmp( $entry['email'], $email ) ) {
				bonsai_waitlist_respond( 'already', $product_id );
			}
		}

		$list[] = array(
			'email' => $email,
			'date'  => current_time( 'mysql' ),
		);
		update_post_meta( $product_id, BONSAI_WAITLIST_META, $list );

		if ( ! bonsai_waitlist_send_notification( $product, $email, count( $list ) ) ) {
			// Sign-up is stored, so the visitor is still on the list — just log it.
			error_log( 'Waiting list: notification email failed for product ' . $product_id . '.' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}

		bonsai_waitlist_respond( 'joined', $product_id );
	} catch ( Exception $e ) {
		error_log( 'Waiting list exception: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		bonsai_waitlist_respond( 'error', $product_id );
	}
}
add_action( 'wp_ajax_bonsai_waitlist_join', 'bonsai_waitlist_handle' );
add_action( 'wp_ajax_nopriv_bonsai_waitlist_join', 'bonsai_waitlist_handle' );
add_action( 'admin_post_bonsai_waitlist_join', 'bonsai_waitlist_handle' );
add_action( 'admin_post_nopriv_bonsai_waitlist_join', 'bonsai_waitlist_handle' );

/**
 * Emails the waiting list recipient about a new sign-up.
 *
 * @param WC_Product $product Product.
 * @param string     $email   Visitor's (validated) email.
 * @param int        $total   Total people now on the list.
 * @return bool Whether wp_mail() accepted the message.
 */
function bonsai_waitlist_send_notification( $product, $email, $total ) {
	$product_id = $product->get_id();
	$title      = wp_specialchars_decode( $product->get_name(), ENT_QUOTES );
	$site       = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );

	$when = implode(
		' · ',
		array_filter(
			array(
				bonsai_get_event_date_display( $product_id ),
				function_exists( 'get_field' ) ? get_field( 'event_time', $product_id ) : '',
			)
		)
	);

	/* translators: 1: site name, 2: workshop title */
	$subject = sprintf( __( '[%1$s] Waiting list: %2$s', 'fika-bonsai' ), $site, $title );

	$lines = array(
		__( 'Someone has joined the waiting list for a sold-out workshop.', 'fika-bonsai' ),
		'',
		__( 'Workshop:', 'fika-bonsai' ) . ' ' . $title,
	);
	if ( $when ) {
		$lines[] = __( 'When:', 'fika-bonsai' ) . ' ' . $when;
	}
	$lines[] = __( 'Email:', 'fika-bonsai' ) . ' ' . $email;
	$lines[] = __( 'Signed up:', 'fika-bonsai' ) . ' ' . wp_date( 'd/m/Y H:i' );
	$lines[] = __( 'People on the waiting list:', 'fika-bonsai' ) . ' ' . $total;
	$lines[] = '';
	$lines[] = __( 'View workshop:', 'fika-bonsai' ) . ' ' . get_permalink( $product_id );
	$lines[] = __( 'Full waiting list (edit product):', 'fika-bonsai' ) . ' ' . admin_url( 'post.php?post=' . $product_id . '&action=edit' );
	$lines[] = '';
	$lines[] = __( 'Reply to this email to contact them directly.', 'fika-bonsai' );

	// $email is sanitize_email() + is_email() validated, so safe in a header.
	$headers = array( 'Reply-To: ' . $email );

	return wp_mail( bonsai_waitlist_recipient(), $subject, implode( "\n", $lines ), $headers );
}

/* =============================
  # Admin: waiting list box on the product
============================= */

/**
 * Registers the "Waiting list" box on workshop product edit screens.
 *
 * @param WP_Post $post Product being edited.
 */
function bonsai_waitlist_add_meta_box( $post ) {
	if ( ! bonsai_is_workshop_product( $post->ID ) ) {
		return;
	}
	add_meta_box( 'bonsai-waiting-list', __( 'Waiting list', 'fika-bonsai' ), 'bonsai_waitlist_render_meta_box', 'product', 'side', 'default' );
}
add_action( 'add_meta_boxes_product', 'bonsai_waitlist_add_meta_box' );

/**
 * Renders the read-only waiting list box.
 *
 * @param WP_Post $post Product being edited.
 */
function bonsai_waitlist_render_meta_box( $post ) {
	$list = bonsai_waitlist_get( $post->ID );

	if ( ! $list ) {
		echo '<p>' . esc_html__( 'Nobody on the waiting list yet. The sign-up form shows on the workshop page while it is out of stock.', 'fika-bonsai' ) . '</p>';
		return;
	}

	wp_nonce_field( 'bonsai_waitlist_clear', 'bonsai_waitlist_clear_nonce' );

	$emails = wp_list_pluck( $list, 'email' );
	?>
	<p>
		<?php
		printf(
			/* translators: %d: number of people */
			esc_html( _n( '%d person waiting.', '%d people waiting.', count( $list ), 'fika-bonsai' ) ),
			count( $list )
		);
		?>
	</p>
	<ol>
		<?php foreach ( $list as $entry ) : ?>
		<li>
			<a href="<?php echo esc_url( 'mailto:' . $entry['email'] ); ?>"><?php echo esc_html( $entry['email'] ); ?></a><br>
			<small><?php echo esc_html( mysql2date( 'd/m/Y H:i', $entry['date'] ) ); ?></small>
		</li>
		<?php endforeach; ?>
	</ol>
	<p>
		<a class="button" href="<?php echo esc_url( 'mailto:?bcc=' . implode( ',', array_map( 'rawurlencode', $emails ) ) ); ?>">
			<?php esc_html_e( 'Email everyone (BCC)', 'fika-bonsai' ); ?>
		</a>
	</p>
	<p>
		<label>
			<input type="checkbox" name="bonsai_waitlist_clear" value="1">
			<?php esc_html_e( 'Clear the waiting list when I update', 'fika-bonsai' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Clears the waiting list when the box's checkbox is ticked on save.
 *
 * @param int $post_id Product ID.
 */
function bonsai_waitlist_maybe_clear( $post_id ) {
	if ( empty( $_POST['bonsai_waitlist_clear'] ) || ! isset( $_POST['bonsai_waitlist_clear_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bonsai_waitlist_clear_nonce'] ) ), 'bonsai_waitlist_clear' ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	delete_post_meta( $post_id, BONSAI_WAITLIST_META );
}
add_action( 'save_post_product', 'bonsai_waitlist_maybe_clear' );

/* =============================
  # GDPR: personal data export / erase + privacy policy text
============================= */

/**
 * Returns IDs of products whose waiting list contains an email.
 *
 * @param string $email Email address.
 * @return int[]
 */
function bonsai_waitlist_products_for_email( $email ) {
	return get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- only runs from Tools → Export/Erase Personal Data.
				array(
					'key'     => BONSAI_WAITLIST_META,
					'value'   => $email,
					'compare' => 'LIKE',
				),
			),
		)
	);
}

/**
 * Personal data exporter: waiting list entries for an email.
 *
 * @param string $email Email address.
 * @return array
 */
function bonsai_waitlist_exporter( $email ) {
	$items = array();

	foreach ( bonsai_waitlist_products_for_email( $email ) as $product_id ) {
		foreach ( bonsai_waitlist_get( $product_id ) as $entry ) {
			if ( 0 !== strcasecmp( $entry['email'], $email ) ) {
				continue;
			}
			$items[] = array(
				'group_id'    => 'bonsai-waiting-list',
				'group_label' => __( 'Workshop waiting lists', 'fika-bonsai' ),
				'item_id'     => 'waitlist-' . $product_id,
				'data'        => array(
					array(
						'name'  => __( 'Workshop', 'fika-bonsai' ),
						'value' => get_the_title( $product_id ),
					),
					array(
						'name'  => __( 'Email', 'fika-bonsai' ),
						'value' => $entry['email'],
					),
					array(
						'name'  => __( 'Signed up', 'fika-bonsai' ),
						'value' => $entry['date'],
					),
				),
			);
		}
	}

	return array(
		'data' => $items,
		'done' => true,
	);
}

/**
 * Personal data eraser: removes an email from every waiting list.
 *
 * @param string $email Email address.
 * @return array
 */
function bonsai_waitlist_eraser( $email ) {
	$removed = false;

	foreach ( bonsai_waitlist_products_for_email( $email ) as $product_id ) {
		$list = bonsai_waitlist_get( $product_id );
		$kept = array_values(
			array_filter(
				$list,
				function ( $entry ) use ( $email ) {
					return 0 !== strcasecmp( $entry['email'], $email );
				}
			)
		);

		if ( count( $kept ) !== count( $list ) ) {
			$removed = true;
			if ( $kept ) {
				update_post_meta( $product_id, BONSAI_WAITLIST_META, $kept );
			} else {
				delete_post_meta( $product_id, BONSAI_WAITLIST_META );
			}
		}
	}

	return array(
		'items_removed'  => $removed,
		'items_retained' => false,
		'messages'       => array(),
		'done'           => true,
	);
}

/**
 * Registers the exporter.
 *
 * @param array $exporters Exporters.
 * @return array
 */
function bonsai_waitlist_register_exporter( $exporters ) {
	$exporters['bonsai-waiting-list'] = array(
		'exporter_friendly_name' => __( 'Workshop waiting lists', 'fika-bonsai' ),
		'callback'               => 'bonsai_waitlist_exporter',
	);
	return $exporters;
}
add_filter( 'wp_privacy_personal_data_exporters', 'bonsai_waitlist_register_exporter' );

/**
 * Registers the eraser.
 *
 * @param array $erasers Erasers.
 * @return array
 */
function bonsai_waitlist_register_eraser( $erasers ) {
	$erasers['bonsai-waiting-list'] = array(
		'eraser_friendly_name' => __( 'Workshop waiting lists', 'fika-bonsai' ),
		'callback'             => 'bonsai_waitlist_eraser',
	);
	return $erasers;
}
add_filter( 'wp_privacy_personal_data_erasers', 'bonsai_waitlist_register_eraser' );

/**
 * Suggested privacy policy text (Settings → Privacy → Policy Guide).
 */
function bonsai_waitlist_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = '<p>' . __( 'If you join the waiting list for a sold-out workshop, we store your email address and the date you signed up against that workshop. We use it only to contact you if a place becomes available. It is kept until the workshop has run or the waiting list is cleared, and you can ask us to remove it at any time.', 'fika-bonsai' ) . '</p>';

	wp_add_privacy_policy_content( __( 'Workshop waiting list', 'fika-bonsai' ), wp_kses_post( wpautop( $content, false ) ) );
}
add_action( 'admin_init', 'bonsai_waitlist_privacy_policy_content' );
