<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'cdbootstrap_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php cdbootstrap_body_attributes(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">

	<!-- ******************* The Navbar Area ******************* -->
	<div id="wrapper-navbar">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'cdbootstrap' ); ?></a>

		<nav id="main-nav" class="navbar navbar-expand-md navbar-dark bg-primary" aria-labelledby="main-nav-label">

			<h2 id="main-nav-label" class="sr-only">
				<?php esc_html_e( 'Main Navigation', 'cdbootstrap' ); ?>
			</h2>

		<?php if ( 'container' === $container ) : ?>
			<div class="container">
		<?php endif; ?>

					<!-- Your site title as branding in the menu -->
					<?php if ( ! has_custom_logo() ) { ?>

						<?php if ( is_front_page() && is_home() ) : ?>

							<h1 class="navbar-brand mb-0"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

						<?php else : ?>

							<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a>

						<?php endif; ?>

						<?php
					} else {
						the_custom_logo();
					}
					?>
					<!-- end custom logo -->

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'cdbootstrap' ); ?>">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- The WordPress Menu goes here -->
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container_class' => 'collapse navbar-collapse',
						'container_id'    => 'navbarNavDropdown',
						'menu_class'      => 'navbar-nav ml-auto',
						'fallback_cb'     => '',
						'menu_id'         => 'main-menu',
						'depth'           => 2,
						'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
					)
				); ?>
				
				<div id="header-search-wrap" class="header-search-wrap navbarNavDropdown collapse">
					<div>
						<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
							<label class="sr-only" for="s"><?php esc_html_e( 'Search', 'cdbootstrap' ); ?></label>
							<div class="input-group">
								<input class="field form-control" id="s" name="s" type="text"
									placeholder="<?php esc_attr_e( 'Search &hellip;', 'cdbootstrap' ); ?>" value="<?php the_search_query(); ?>">
								<span class="input-group-append">
									<button type="submit" class="submit btn btn-primary" id="searchsubmit" name="submit">
										<span class="screen-reader-text"><?php esc_attr_e( 'Search', 'cdbootstrap' ); ?></span><i class="far fa-search"></i></button>
									<a href="#" role="button" aria-expanded="false" aria-controls="header-search-wrap" class="toggle-header-search close btn btn-secondary"><span class="screen-reader-text"><?php _e( 'Hide Search', 'cdbootstrap' ); ?></span><i class="fal fa-times"></i></a>
								</span>
							</div>
						</form>
					</div>
				</div>
				
			<?php if ( 'container' === $container ) : ?>
			</div><!-- .container -->
			<?php endif; ?>

		</nav><!-- .site-navigation -->

	</div><!-- #wrapper-navbar end -->
