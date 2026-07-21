<?php
/**
 * functions.php – Loader for modular includes
 */

// Define theme constants
if (!defined('BONSAI_THEME_DIR')) define('BONSAI_THEME_DIR', get_template_directory());
if (!defined('BONSAI_THEME_URI')) define('BONSAI_THEME_URI', get_template_directory_uri());

// Load all modules
$modules = [
    'assets',          // Enqueue scripts and styles
    'theme-setup',     // Theme setup, theme supports, menus
    'gutenberg',       // Disable Gutenberg and remove block styles
    'acf',             // ACF options pages and admin tweaks
    'security',        // Security cleanup, disable updates, remove version strings
    'comments',        // Disable comment system and UI
    'editor',          // TinyMCE buttons and custom editor styles
    'tinymce-styles',  // Custom TinyMCE style formats (buttons dropdown)
    'helpers',         // Helper functions (excerpts, content limits)
    'module-helpers',  // Module icon and formatting helpers
    'accessibility',   // ARIA roles, title attributes, admin bar styling
    'media',           // Image filters, SVG sanitization, email obfuscation
    'content',         // Content tweaks, external links new tab, JS tweaks
    'cleanup',         // Remove emojis and unused scripts
    'rss',             // Add featured images to RSS feed content
    'lazy-load',       // Add lazy loading attribute to images
    'webp',            // Support for WebP image uploads
    'post-labels',     // Rename "Posts" post type to "News"
    'frontend',        // Misc front end updates
    'header',          // Controls the site-header.php meta tags
    'acf-json',        // acf-json save/load paths
    'acf-defaults',    // Pre-populate page_builder field for new posts
];

foreach ($modules as $module) {
    $file = BONSAI_THEME_DIR . '/inc/' . $module . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("Missing module: $file");
    }
}