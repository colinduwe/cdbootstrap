<?php
/**
 * CDBootstrap modify Gutenberg editor
 *
 * @package cdbootstrap
 */
if( ! function_exists( 'cdbootstrap_setup_theme_supported_features' ) ){
	function cdbootstrap_setup_theme_supported_features() {
	    add_theme_support( 'editor-color-palette', array(
	        array(
	            'name' => __( 'Primary', 'cdbootstrap' ),
	            'slug' => 'primary',
	            'color' => '#007bff',
	        ),
	        array(
	            'name' => __( 'Secondary', 'cdbootstrap' ),
	            'slug' => 'secondary',
	            'color' => '#6c757d',
	        ),
	        array(
	            'name' => __( 'Success', 'cdbootstrap' ),
	            'slug' => 'success',
	            'color' => '#28a745',
	        ),
	        array(
	            'name' => __( 'Info', 'cdbootstrap' ),
	            'slug' => 'Info',
	            'color' => '#17a2b8',
	        ),
	        array(
	            'name' => __( 'Warning', 'cdbootstrap' ),
	            'slug' => 'warning',
	            'color' => '#ffc107',
	        ),
	        array(
	            'name' => __( 'Danger', 'cdbootstrap' ),
	            'slug' => 'danger',
	            'color' => '#dc3545',
	        ),
	        array(
	            'name' => __( 'Light', 'cdbootstrap' ),
	            'slug' => 'light',
	            'color' => '#f8f9fa',
	        ),
	        array(
	            'name' => __( 'Dark', 'cdbootstrap' ),
	            'slug' => 'dark',
	            'color' => '#343a40',
	        ),                
	        array(
	            'name' => __( 'White', 'cdbootstrap' ),
	            'slug' => 'white',
	            'color' => '#ffffff',
	        ), 
	        array(
	            'name' => __( 'Text', 'cdbootstrap' ),
	            'slug' => 'text',
	            'color' => '#212529',
	        ),         
	        array(
	            'name' => __( 'Black', 'cdbootstrap' ),
	            'slug' => 'black',
	            'color' => '#000000',
	        ),
	    ) );
	    add_theme_support( 'wp-block-styles' );
	    add_theme_support( 'align-wide' );
	    add_theme_support( 'editor-font-sizes', array(
		    array(
		        'name' => __( 'Small', 'cdbootstrap' ),
		        'size' => 12,
		        'slug' => 'small'
		    ),
		    array(
		        'name' => __( 'Regular', 'cdbootstrap' ),
		        'size' => 16,
		        'slug' => 'regular'
		    ),
		    array(
		        'name' => __( 'Large', 'cdbootstrap' ),
		        'size' => 20,
		        'slug' => 'large'
		    )
		) );
		add_theme_support('disable-custom-font-sizes');
		add_theme_support( 'disable-custom-colors' );
		add_theme_support('editor-styles');
		add_theme_support( 'responsive-embeds' );
	}
} 
add_action( 'after_setup_theme', 'cdbootstrap_setup_theme_supported_features' );

/**
 * Gutenberg scripts and styles
 * @link https://www.billerickson.net/wordpress-color-palette-button-styling-gutenberg
 */
if( ! function_exists( 'cdbootstrap_gutenberg_scripts' ) ){
	function cdbootstrap_gutenberg_scripts() {
		wp_enqueue_script( 'cdbootstrap-editor', get_stylesheet_directory_uri() . '/dist/js/editor.js', array( 'wp-blocks', 'wp-dom' ), filemtime( get_stylesheet_directory() . '/assets/js/editor.js' ), true );
	}
}
add_action( 'enqueue_block_editor_assets', 'cdbootstrap_gutenberg_scripts' );