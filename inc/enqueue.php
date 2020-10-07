<?php
/**
 * CDBootstrap enqueue scripts
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cdbootstrap_scripts' ) ) {
	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function cdbootstrap_scripts() {
		// Get the theme data.
		$the_theme     = wp_get_theme();
		$theme_version = $the_theme->get( 'Version' );

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/dist/css/app.css' );
		wp_enqueue_style( 'cdbootstrap-styles', get_template_directory_uri() . '/dist/css/app.css', array(), $css_version );

		wp_enqueue_script( 'jquery' );

		$js_version = $theme_version . '.' . filemtime( get_template_directory() . '/dist/js/app.js' );
		wp_enqueue_script( 'cdbootstrap-scripts', get_template_directory_uri() . '/dist/js/app.js', array(), $js_version, true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
} // endif function_exists( 'cdbootstrap_scripts' ).

add_action( 'wp_enqueue_scripts', 'cdbootstrap_scripts' );
