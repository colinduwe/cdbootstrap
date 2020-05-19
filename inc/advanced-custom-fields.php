<?php
/**
 * CDBootstrap Advanced Custom Field Integration
 *
 * @package cdbootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Hide ACF Menu on non-dev environments
 */
add_filter( 'acf/settings/show_admin', __NAMESPACE__ . '\\acf_show_admin' );

function acf_show_admin( $show ) {
	if ( defined( 'WP_LOCAL_DEV' ) ) {
		return true;
	}
	return false;
}

// Register blocks here. https://www.advancedcustomfields.com/resources/acf_register_block_type/
