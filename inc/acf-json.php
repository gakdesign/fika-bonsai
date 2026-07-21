<?php
/**
 * acf-json.php – ACF Local JSON paths
 *
 * @package Bonsai_Base_Theme
 */

/**
 * Change where ACF saves JSON.
 *
 * @param string $path Default path.
 * @return string New path.
 */
function bonsai_acf_json_save_point( $path ) {
	// Save JSON to the parent theme's /acf-json folder.
	return get_template_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'bonsai_acf_json_save_point' );

/**
 * Change where ACF loads JSON from.
 *
 * @param array $paths Default load paths.
 * @return array Modified paths.
 */
function bonsai_acf_json_load_points( $paths ) {
	// Remove the default path (theme root).
	if ( isset( $paths[0] ) ) {
		unset( $paths[0] );
	}

	// Add the parent theme's /acf-json folder.
	$paths[] = get_template_directory() . '/acf-json';

	return $paths;
}
add_filter( 'acf/settings/load_json', 'bonsai_acf_json_load_points' );
