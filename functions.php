<?php
/**
 * CDBootstrap functions and definitions
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// CDBootstrap's includes directory.
$cdbootstrap_inc_dir = get_template_directory() . '/inc';

// Array of files to include.
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
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
	'/editor.php',                          // Load Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
	// '/class-kirki-installer-section.php', // Require Kirki plugin
	'/advanced-custom-fields.php',			// Advanced Custom Field Support
	'/search.php',							// Search functions like the search in the primary nav
	'/ajax-load-more-filter.php',			// Support of Ajax load more and Ajax filter functions
);

// Load WooCommerce functions if WooCommerce is activated.
if ( class_exists( 'WooCommerce' ) ) {
	$cdbootstrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if ( class_exists( 'Jetpack' ) ) {
	$cdbootstrap_includes[] = '/jetpack.php';
}

// Include files.
foreach ( $cdbootstrap_includes as $file ) {
	require_once $cdbootstrap_inc_dir . $file;
}
