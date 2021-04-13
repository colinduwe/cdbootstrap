<?php
/**
 * Check and setup theme's default settings
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cdbootstrap_setup_theme_default_settings' ) ) {
	/**
	 * Store default theme settings in database.
	 */
	function cdbootstrap_setup_theme_default_settings() {
		$defaults = cdbootstrap_get_theme_default_settings();
		$settings = get_theme_mods();
		foreach ( $defaults as $setting_id => $default_value ) {
			// Check if setting is set, if not set it to its default value.
			if ( ! isset( $settings[ $setting_id ] ) ) {
				set_theme_mod( $setting_id, $default_value );
			}
		}
	}
}

if ( ! function_exists( 'cdbootstrap_get_theme_default_settings' ) ) {
	/**
	 * Retrieve default theme settings.
	 *
	 * @return array
	 */
	function cdbootstrap_get_theme_default_settings() {
		$defaults = array(
			'cdbootstrap_posts_index_style' => 'default',   // Latest blog posts style.
			'cdbootstrap_sidebar_position'  => 'right',     // Sidebar position.
			'cdbootstrap_container_type'    => 'container', // Container width.
			'cdbootstrap_pagination_type'	=> 'links',	// Pagination type (button, scroll, links)
			'cdbootstrap_enable_dark_mode_palette' => 'false', //Dark mode 
			'cdbootstrap_disable_animations' => 'false',		// Animations
		);

		/**
		 * Filters the default theme settings.
		 *
		 * @param array $defaults Array of default theme settings.
		 */
		return apply_filters( 'cdbootstrap_theme_default_settings', $defaults );
	}
}
