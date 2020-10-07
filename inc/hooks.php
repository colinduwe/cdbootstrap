<?php
/**
 * Custom hooks
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cdbootstrap_site_info' ) ) {
	/**
	 * Add site info hook to WP hook library.
	 */
	function cdbootstrap_site_info() {
		do_action( 'cdbootstrap_site_info' );
	}
}

if ( ! function_exists( 'cdbootstrap_add_site_info' ) ) {
	add_action( 'cdbootstrap_site_info', 'cdbootstrap_add_site_info' );

	/**
	 * Add site info content.
	 */
	function cdbootstrap_add_site_info() {
		$the_theme = wp_get_theme();

		$site_info = sprintf(
			'<a href="%1$s">%2$s</a><span class="sep"> | </span>%3$s(%4$s)',
			esc_url( __( 'http://wordpress.org/', 'cdbootstrap' ) ),
			sprintf(
				/* translators:*/
				esc_html__( 'Proudly powered by %s', 'cdbootstrap' ),
				'WordPress'
			),
			sprintf( // WPCS: XSS ok.
				/* translators:*/
				esc_html__( 'Theme: %1$s by %2$s.', 'cdbootstrap' ),
				$the_theme->get( 'Name' ),
				'<a href="' . esc_url( $the_theme->get( 'AuthorURI' ) ) . '">' . $the_theme->get( 'Author' ) . '</a>'
			),
			sprintf( // WPCS: XSS ok.
				/* translators:*/
				esc_html__( 'Version: %1$s', 'cdbootstrap' ),
				$the_theme->get( 'Version' )
			)
		);

		echo apply_filters( 'cdbootstrap_site_info_content', $site_info ); // WPCS: XSS ok.
	}
}
