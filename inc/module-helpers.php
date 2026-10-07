<?php
/**
 * Module Helper Functions
 *
 * Utility functions for ACF modules (icons, formatting, etc.)
 * Included via functions.php
 */

/**
 * Get feature icon SVG by name.
 *
 * @param string $icon_name Icon identifier.
 * @return string SVG markup or empty string.
 */
function bonsai_get_feature_icon( $icon_name = '' ) {
	if ( empty( $icon_name ) ) {
		return '';
	}

	switch ( $icon_name ) {
		case 'chart':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 19h16M6 16l3-10 4 8 3-6 2 8" stroke-linecap="round" stroke-linejoin="round" /></svg>';

		case 'calendar':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 10h18" /></svg>';

		case 'location':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 21s-4-3.2-4-7 1.8-6 4-6 4 2.2 4 6-4 7-4 7Z" stroke-linecap="round" /><circle cx="12" cy="11" r="2" /></svg>';

		default:
			return '';
	}
}
