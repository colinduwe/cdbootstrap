<?php
/**
 * CDBootstrap Theme Customizer
 *
 * @package cdbootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if ( ! function_exists( 'cdbootstrap_customize_register' ) ) {
	/**
	 * Register basic customizer support.
	 *
	 * @param object $wp_customize Customizer reference.
	 */
	function cdbootstrap_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	}
}
add_action( 'customize_register', 'cdbootstrap_customize_register' );

if ( ! function_exists( 'cdbootstrap_theme_customize_register' ) ) {
	/**
	 * Register individual settings through customizer's API.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function cdbootstrap_theme_customize_register( $wp_customize ) {

		// Theme layout settings.
		$wp_customize->add_section(
			'cdbootstrap_theme_layout_options',
			array(
				'title'       => __( 'Theme Layout Settings', 'cdbootstrap' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Container width and sidebar defaults', 'cdbootstrap' ),
				'priority'    => apply_filters( 'cdbootstrap_theme_layout_options_priority', 160 ),
			)
		);

		/**
		 * Select sanitization function
		 *
		 * @param string               $input   Slug to sanitize.
		 * @param WP_Customize_Setting $setting Setting instance.
		 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
		 */
		function cdbootstrap_theme_slug_sanitize_select( $input, $setting ) {

			// Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
			$input = sanitize_key( $input );

			// Get the list of possible select options.
			$choices = $setting->manager->get_control( $setting->id )->choices;

			// If the input is a valid key, return it; otherwise, return the default.
			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

		}

		$wp_customize->add_setting(
			'cdbootstrap_container_type',
			array(
				'default'           => 'container',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'cdbootstrap_theme_slug_sanitize_select',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'cdbootstrap_container_type',
				array(
					'label'       => __( 'Container Width', 'cdbootstrap' ),
					'description' => __( 'Choose between Bootstrap\'s container and container-fluid', 'cdbootstrap' ),
					'section'     => 'cdbootstrap_theme_layout_options',
					'settings'    => 'cdbootstrap_container_type',
					'type'        => 'select',
					'choices'     => array(
						'container'       => __( 'Fixed width container', 'cdbootstrap' ),
						'container-fluid' => __( 'Full width container', 'cdbootstrap' ),
					),
					'priority'    => apply_filters( 'cdbootstrap_container_type_priority', 10 ),
				)
			)
		);

		$wp_customize->add_setting(
			'cdbootstrap_sidebar_position',
			array(
				'default'           => 'right',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'cdbootstrap_sidebar_position',
				array(
					'label'             => __( 'Sidebar Positioning', 'cdbootstrap' ),
					'description'       => __(
						'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
						'cdbootstrap'
					),
					'section'           => 'cdbootstrap_theme_layout_options',
					'settings'          => 'cdbootstrap_sidebar_position',
					'type'              => 'select',
					'sanitize_callback' => 'cdbootstrap_theme_slug_sanitize_select',
					'choices'           => array(
						'right' => __( 'Right sidebar', 'cdbootstrap' ),
						'left'  => __( 'Left sidebar', 'cdbootstrap' ),
						'both'  => __( 'Left & Right sidebars', 'cdbootstrap' ),
						'none'  => __( 'No sidebar', 'cdbootstrap' ),
					),
					'priority'          => apply_filters( 'cdbootstrap_sidebar_position_priority', 20 ),
				)
			)
		);
	}
} // endif function_exists( 'cdbootstrap_theme_customize_register' ).
add_action( 'customize_register', 'cdbootstrap_theme_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if ( ! function_exists( 'cdbootstrap_customize_preview_js' ) ) {
	/**
	 * Setup JS integration for live previewing.
	 */
	function cdbootstrap_customize_preview_js() {
		wp_enqueue_script(
			'cdbootstrap_customizer',
			get_template_directory_uri() . '/dist/js/customizer.js',
			array( 'customize-preview' ),
			'20130508',
			true
		);
	}
}
add_action( 'customize_preview_init', 'cdbootstrap_customize_preview_js' );

if( class_exists( 'Kirki' ) ){
	Kirki::add_config( 'theme_config_id', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );
	Kirki::add_panel( 'panel_id', array(
	    'priority'    => 10,
	    'title'       => esc_html__( 'My Panel', 'kirki' ),
	    'description' => esc_html__( 'My panel description', 'kirki' ),
	) );
	Kirki::add_section( 'section_id', array(
	    'title'          => esc_html__( 'My Section', 'kirki' ),
	    'description'    => esc_html__( 'My section description.', 'kirki' ),
	    'panel'          => 'panel_id',
	    'priority'       => 160,
	) );
	Kirki::add_field( 'theme_config_id', [
		'type'        => 'radio',
		'settings'    => 'my_setting',
		'label'       => esc_html__( 'Radio Control', 'kirki' ),
		'section'     => 'section_id',
		'default'     => 'red',
		'priority'    => 10,
		'choices'     => [
			'red'   => esc_html__( 'Red', 'kirki' ),
			'green' => esc_html__( 'Green', 'kirki' ),
			'blue'  => esc_html__( 'Blue', 'kirki' ),
		],
	] );			
}
