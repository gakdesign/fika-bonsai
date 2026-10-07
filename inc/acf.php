<?php
/**
 * acf.php – ACF options page and admin tweaks
 */

function my_acf_op_init() {
    if (function_exists('acf_add_options_page')) {

        // Main Site Settings Page
        acf_add_options_page([
            'page_title' => 'Site Settings : General content used throughout the theme',
            'menu_title' => 'Site Settings',
            'menu_slug'  => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect'   => false
        ]);

        // Separate Page for Text Files (e.g. humans.txt)
        acf_add_options_page([
            'page_title' => 'Humans.txt Editor',
            'menu_title' => 'Humans.txt',
            'menu_slug'  => 'site-text-files',
            'capability' => 'manage_options',
            'redirect'   => false
        ]);

    }
}
add_action('acf/init', 'my_acf_op_init');


// Save humans.txt content from ACF field
add_action('acf/save_post', 'write_humans_txt_on_save', 20);
function write_humans_txt_on_save($post_id) {
    // Only trigger on Options page
    if ($post_id !== 'options') return;

    // Get content from ACF textarea field (you should name the field 'humans_txt_content')
    $content = get_field('humans_txt_content', 'option', false);

    // Write to /humans.txt if the field exists
    if ($content !== false) {
        $file_path = ABSPATH . 'humans.txt';
        file_put_contents($file_path, $content);
    }
}


// Hide escaped HTML admin notice
add_filter('acf/admin/prevent_escaped_html_notice', '__return_true');

/**
 * Tidy legacy values in the Market / Workshop "Time" field.
 *
 * event_time was a time picker (stored as "H:i:s", e.g. "10:00:00") and is
 * now free text (e.g. "10am (4½ hours)"). Old values are left in the DB and
 * reformatted on output as "10:00 am" so they don't need re-entering.
 * Free-text values pass through untouched.
 *
 * @param mixed $value Field value.
 * @return mixed
 */
function bonsai_format_legacy_event_time( $value ) {
	if ( is_string( $value ) && preg_match( '/^\d{2}:\d{2}:\d{2}$/', $value ) ) {
		$time = DateTime::createFromFormat( 'H:i:s', $value );
		if ( $time ) {
			return $time->format( 'g:i a' );
		}
	}
	return $value;
}
add_filter( 'acf/format_value/key=field_fika_mw_event_time', 'bonsai_format_legacy_event_time' );
