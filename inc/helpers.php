<?php
/**
 * helpers.php – Helper functions
 *
 * @package Bonsai_Base_Theme
 */

/**
 * Get a trimmed, safe excerpt.
 *
 * @param int $word_limit Number of words.
 * @return string Escaped excerpt.
 */
function bonsai_get_trimmed_excerpt( $word_limit = 30 ) {
	$excerpt = get_the_excerpt();

	if ( empty( $excerpt ) ) {
		return '';
	}

	$excerpt = wp_strip_all_tags( $excerpt, true );
	$words   = preg_split( '/\s+/', $excerpt );

	if ( count( $words ) > $word_limit ) {
		$words = array_slice( $words, 0, $word_limit );
		$excerpt = implode( ' ', $words ) . '&hellip;';
	}

	return esc_html( $excerpt );
}

/**
 * Get a trimmed, safe version of the content.
 *
 * @param int $word_limit Number of words.
 * @return string Escaped content.
 */
function bonsai_get_trimmed_content( $word_limit = 40 ) {
	$content = get_the_content( '' );

	if ( empty( $content ) ) {
		return '';
	}

	$content = wp_strip_all_tags( $content, true );
	$words   = preg_split( '/\s+/', $content );

	if ( count( $words ) > $word_limit ) {
		$words   = array_slice( $words, 0, $word_limit );
		$content = implode( ' ', $words ) . '&hellip;';
	}

	return esc_html( $content );
}

/**
 * Sanitise iframe/embed output from ACF fields.
 * Allows <iframe> with a broad but explicit attribute allowlist,
 * covering YouTube, Vimeo, Google Maps, and generic third-party embeds.
 * Use instead of esc_html() or wp_kses_post() for embed fields.
 *
 * @param  string $embed Raw HTML string from ACF field.
 * @return string        Sanitised HTML safe for output.
 */
function bonsai_kses_iframe( $embed ) {
    if ( empty( $embed ) ) {
        return '';
    }

    $allowed = array(
        'iframe' => array(
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'title'           => true,
            'frameborder'     => true,
            'allow'           => true,
            'allowfullscreen' => true,
            'loading'         => true,
            'referrerpolicy'  => true,
            'style'           => true,
            'class'           => true,
            'id'              => true,
            'name'            => true,
            'scrolling'       => true,
            'data-src'        => true,  // lazy-load patterns
        ),
        // Allow wrapping divs that embed providers sometimes output
        'div' => array(
            'class'          => true,
            'id'             => true,
            'style'          => true,
            'data-url'       => true,  // Typeform pattern
            'data-widget'    => true,
        ),
        'script' => false, // Never allow script tags
    );

    return wp_kses( $embed, $allowed );
}

/**
 * Returns the display date for a Market / Workshop / workshop product.
 *
 * Combines the optional date picker (event_date) with the optional free-text
 * Date Note (event_date_note, e.g. "Date to be arranged"). Either, both, or
 * neither may be set.
 *
 * @param int|null $post_id Post ID. Defaults to the current post.
 * @return string Unescaped display string, or empty string.
 */
function bonsai_get_event_date_display( $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}

	$post_id = $post_id ? $post_id : get_the_ID();

	$parts = array_filter(
		array(
			get_field( 'event_date', $post_id ),
			get_field( 'event_date_note', $post_id ),
		)
	);

	return implode( ' · ', $parts );
}
