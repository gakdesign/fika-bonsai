<?php
/**
 * theme-setup.php – Theme setup functions
 *
 * @package Bonsai_Base_Theme
 */

if ( ! function_exists( 'bonsai_theme_setup' ) ) :

/**
 * Set up theme defaults and register support for WordPress features.
 */
function bonsai_theme_setup() {

	// Core supports.
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Menus – keep labels generic so the theme is reusable.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'bonsai-base-theme' ),
			'mobile'  => __( 'Mobile Navigation', 'bonsai-base-theme' ),
			'footer'  => __( 'Footer Navigation', 'bonsai-base-theme' ),
			'legal'   => __( 'Legal Navigation', 'bonsai-base-theme' ),
		)
	);
}

endif;

add_action( 'after_setup_theme', 'bonsai_theme_setup' );
