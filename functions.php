<?php
/**
 * CDBootstrap functions and definitions
 *
 * @package cdbootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$cdbootstrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/jetpack.php',                         // Load Jetpack compatibility file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/cdbootstrap/cdbootstrap/issues/567
	'/woocommerce.php',                     // Load WooCommerce functions.
	'/editor.php',							// Load Gutenberg Editor functions.
	'/tinymce.php',                         // Load TinyMCE Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
	// '/class-kirki-installer-section.php', // Require Kirki plugin
	'/advanced-custom-fields.php',			// Advanced Custom Field Support
	'/search.php',							// Search functions like the search in the primary nav
);

foreach ( $cdbootstrap_includes as $file ) {
	require_once get_template_directory() . '/inc' . $file;
}
