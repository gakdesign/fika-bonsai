<?php
/**
 * security.php – Security and WordPress cleanup.
 *
 * @package Bonsai_Base_Theme
 */

/**
 * Head cleanup.
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'welcome_panel', 'wp_welcome_panel' );

// Hide WordPress version.
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Auto-updates & update emails.
 *
 * IMPORTANT:
 * This only disables auto-updates if the project explicitly opts in
 * by defining BONSAI_DISABLE_AUTO_UPDATES as true in wp-config.php, e.g.:
 *
 * define( 'BONSAI_DISABLE_AUTO_UPDATES', true );
 */
if ( defined( 'BONSAI_DISABLE_AUTO_UPDATES' ) && BONSAI_DISABLE_AUTO_UPDATES ) {

	// Disable plugin auto-update emails.
	add_filter( 'auto_plugin_update_send_email', '__return_false' );

	// Disable theme auto-update emails.
	add_filter( 'auto_theme_update_send_email', '__return_false' );

	// Disable plugin auto-updates.
	add_filter( 'auto_update_plugin', '__return_false' );

	// Disable theme auto-updates.
	add_filter( 'auto_update_theme', '__return_false' );

	// Optional: if you also want to disable core auto-updates and emails,
	// uncomment these:
	/*
	add_filter( 'auto_update_core', '__return_false' );
	add_filter( 'auto_core_update_send_email', '__return_false' );
	*/
}

/**
 * Limit post revisions.
 *
 * NOTE: Ideally WP_POST_REVISIONS is defined in wp-config.php.
 * This is a fallback if it isn't already defined.
 */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}

/**
 * Remove query strings from static resources.
 *
 * This can help with some proxy/caching setups, but be aware it also
 * removes cache-busting query vars like ?ver=1.2.3.
 *
 * @param string $src Script or style source URL.
 * @return string URL without query string.
 */
function bonsai_remove_query_strings( $src ) {
	$parts = explode( '?', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', 'bonsai_remove_query_strings', 15 );
add_filter( 'style_loader_src', 'bonsai_remove_query_strings', 15 );

/**
 * Disable XML-RPC.
 *
 * If you use Jetpack, the mobile app, or external publishing tools,
 * you may need to turn this back on.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove some default dashboard widgets.
 */
function bonsai_cleanup_dashboard_widgets() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'bonsai_cleanup_dashboard_widgets' );
