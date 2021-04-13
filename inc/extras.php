<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'body_class', 'cdbootstrap_body_classes' );

if ( ! function_exists( 'cdbootstrap_body_classes' ) ) {
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function cdbootstrap_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}
		
		// Determine type of infinite scroll.
		$pagination_type = get_theme_mod( 'cdbootstrap_pagination_type', 'button' );
		$pagination_type = 'links';
		
		switch ( $pagination_type ) {
			case 'button':
				$classes[] = 'pagination-type-button';
				break;
			case 'scroll':
				$classes[] = 'pagination-type-scroll';
				break;
			case 'links':
				$classes[] = 'pagination-type-links';
				break;
		}
	
		// Check for dark mode.
		if ( get_theme_mod( 'cdbootstrap_enable_dark_mode_palette', false ) ) {
			$classes[] = 'has-dark-mode-palette';
		}
		
		// Check for disabled animations.
		$classes[] = get_theme_mod( 'cdbootstrap_disable_animations', false ) ? 'no-anim' : 'has-anim';			

		return $classes;
	}
}

if ( function_exists( 'cdbootstrap_adjust_body_class' ) ) {
	/*
	 * cdbootstrap_adjust_body_class() deprecated in v0.9.4. We keep adding the
	 * filter for child themes which use their own cdbootstrap_adjust_body_class.
	 */
	add_filter( 'body_class', 'cdbootstrap_adjust_body_class' );
}

// Filter custom logo with correct classes.
add_filter( 'get_custom_logo', 'cdbootstrap_change_logo_class' );

if ( ! function_exists( 'cdbootstrap_change_logo_class' ) ) {
	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return string
	 */
	function cdbootstrap_change_logo_class( $html ) {

		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html );
		$html = str_replace( 'alt=""', 'title="Home" alt="logo"', $html );

		return $html;
	}
}

/**
 * Display navigation to next/previous post when applicable.
 */

if ( ! function_exists( 'cdbootstrap_post_nav' ) ) {
	function cdbootstrap_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="container navigation post-navigation">
			<h2 class="sr-only"><?php esc_html_e( 'Post navigation', 'cdbootstrap' ); ?></h2>
			<div class="row nav-links justify-content-between">
				<?php
				if ( get_previous_post_link() ) {
					previous_post_link( '<span class="nav-previous">%link</span>', _x( '<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'cdbootstrap' ) );
				}
				if ( get_next_post_link() ) {
					next_post_link( '<span class="nav-next">%link</span>', _x( '%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'cdbootstrap' ) );
				}
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'cdbootstrap_pingback' ) ) {
	/**
	 * Add a pingback url auto-discovery header for single posts of any post type.
	 */
	function cdbootstrap_pingback() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'cdbootstrap_pingback' );

if ( ! function_exists( 'cdbootstrap_mobile_web_app_meta' ) ) {
	/**
	 * Add mobile-web-app meta.
	 */
	function cdbootstrap_mobile_web_app_meta() {
		echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( get_bloginfo( 'name' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'cdbootstrap_mobile_web_app_meta' );

if ( ! function_exists( 'cdbootstrap_default_body_attributes' ) ) {
	/**
	 * Adds schema markup to the body element.
	 *
	 * @param array $atts An associative array of attributes.
	 * @return array
	 */
	function cdbootstrap_default_body_attributes( $atts ) {
		$atts['itemscope'] = '';
		$atts['itemtype']  = 'http://schema.org/WebSite';
		return $atts;
	}
}
add_filter( 'cdbootstrap_body_attributes', 'cdbootstrap_default_body_attributes' );

// Escapes all occurances of 'the_archive_description'.
add_filter( 'get_the_archive_description', 'cdbootstrap_escape_the_archive_description' );

if ( ! function_exists( 'cdbootstrap_escape_the_archive_description' ) ) {
	/**
	 * Escapes the description for an author or post type archive.
	 *
	 * @param string $description Archive description.
	 * @return string Maybe escaped $description.
	 */
	function cdbootstrap_escape_the_archive_description( $description ) {
		if ( is_author() || is_post_type_archive() ) {
			return wp_kses_post( $description );
		}

		/*
		 * All other descriptions are retrieved via term_description() which returns
		 * a sanitized description.
		 */
		return $description;
	}
} // End of if function_exists( 'cdbootstrap_escape_the_archive_description' ).

// Escapes all occurances of 'the_title()' and 'get_the_title()'.
add_filter( 'the_title', 'cdbootstrap_kses_title' );

// Escapes all occurances of 'the_archive_title' and 'get_the_archive_title()'.
add_filter( 'get_the_archive_title', 'cdbootstrap_kses_title' );

if ( ! function_exists( 'cdbootstrap_kses_title' ) ) {
	/**
	 * Sanitizes data for allowed HTML tags for post title.
	 *
	 * @param string $data Post title to filter.
	 * @return string Filtered post title with allowed HTML tags and attributes intact.
	 */
	function cdbootstrap_kses_title( $data ) {
		// Tags not supported in HTML5 are not allowed.
		$allowed_tags = array(
			'abbr'             => array(),
			'aria-describedby' => true,
			'aria-details'     => true,
			'aria-label'       => true,
			'aria-labelledby'  => true,
			'aria-hidden'      => true,
			'b'                => array(),
			'bdo'              => array(
				'dir' => true,
			),
			'blockquote'       => array(
				'cite'     => true,
				'lang'     => true,
				'xml:lang' => true,
			),
			'cite'             => array(
				'dir'  => true,
				'lang' => true,
			),
			'dfn'              => array(),
			'em'               => array(),
			'i'                => array(
				'aria-describedby' => true,
				'aria-details'     => true,
				'aria-label'       => true,
				'aria-labelledby'  => true,
				'aria-hidden'      => true,
				'class'            => true,
			),
			'code'             => array(),
			'del'              => array(
				'datetime' => true,
			),
			'ins'              => array(
				'datetime' => true,
				'cite'     => true,
			),
			'kbd'              => array(),
			'mark'             => array(),
			'pre'              => array(
				'width' => true,
			),
			'q'                => array(
				'cite' => true,
			),
			's'                => array(),
			'samp'             => array(),
			'span'             => array(
				'dir'      => true,
				'align'    => true,
				'lang'     => true,
				'xml:lang' => true,
			),
			'small'            => array(),
			'strong'           => array(),
			'sub'              => array(),
			'sup'              => array(),
			'u'                => array(),
			'var'              => array(),
		);
		$allowed_tags = apply_filters( 'cdbootstrap_kses_title', $allowed_tags );

		return wp_kses( $data, $allowed_tags );
	}
} // End of if function_exists( 'cdbootstrap_kses_title' ).

/*	-----------------------------------------------------------------------------------------------
	NO-JS CLASS
	If we're missing JavaScript support, the HTML element will have a no-js class.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'cdbootstrap_no_js_class' ) ) :
	function cdbootstrap_no_js_class() {

		?>
		<script>document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );</script>
		<?php

	}
	add_action( 'wp_head', 'cdbootstrap_no_js_class', 0 );
endif;


/*	-----------------------------------------------------------------------------------------------
	NOSCRIPT STYLES
	Unset CSS animations triggered in JavaScript within a noscript element, to prevent the flash of 
	unstyled animation elements that occurs when using the .no-js class.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'cdbootstrap_noscript_styles' ) ) :
	function cdbootstrap_noscript_styles() {

		?>
		<noscript>
			<style>
				.spot-fade-in-scale, .no-js .spot-fade-up { 
					opacity: 1.0 !important; 
					transform: none !important;
				}
			</style>
		</noscript>
		<?php

	}
	add_action( 'wp_head', 'cdbootstrap_noscript_styles', 0 );
endif;
