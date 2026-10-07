<?php
/**
 * acf-defaults.php – ACF Flexible Content default layouts for post types
 *
 * Pre-populates the page_builder field with default layouts on new posts.
 * Only runs when the field has no saved value (i.e. a brand-new post).
 *
 * @package Bonsai_Base_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default page_builder layouts per post type.
 *
 * Key   = WordPress post type slug (NOT template filename).
 * Value = ordered array of flexible content layout names.
 *
 * @return array
 */
function bonsai_acf_default_layouts() {
	return array(
		// Standard posts (template: single.php) — post type slug is 'post'.
		'post' => array(
			'page_header_module',
			'content_module',
		),
		// Add more post types below as needed:
		'case-study' => array(
			'page_header_module',
			'content_module',
		),
	);
}

/**
 * Inject default layouts into the page_builder field for new posts.
 *
 * Hooks into acf/prepare_field so ACF loads the defaults before rendering
 * the field — no JS, no post-save trickery.
 *
 * @param array $field ACF field array.
 * @return array Modified field array.
 */
add_filter( 'acf/prepare_field/name=page_builder', function ( $field ) {

	// Admin only.
	if ( ! is_admin() ) {
		return $field;
	}

	// Already has saved content — don't overwrite.
	if ( ! empty( $field['value'] ) ) {
		return $field;
	}

	$screen = get_current_screen();
	if ( ! $screen ) {
		return $field;
	}

	$post_type = $screen->post_type;
	$defaults  = bonsai_acf_default_layouts();

	if ( ! isset( $defaults[ $post_type ] ) ) {
		return $field;
	}

	// Build the value array ACF expects for flexible content.
	$layouts = array();
	foreach ( $defaults[ $post_type ] as $layout_name ) {
		$layouts[] = array(
			'acf_fc_layout' => $layout_name,
		);
	}

	$field['value'] = $layouts;

	return $field;
} );
