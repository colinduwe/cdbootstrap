<?php
/**
 * The template for displaying search results pages
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'cdbootstrap_container_type' );

?>

<div class="wrapper" id="search-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<!-- Do the left sidebar check and opens the primary div -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">
				
				<header class="page-header">
					
							<h1 class="page-title">
								<?php
								printf(
									/* translators: %s: query term */
									esc_html__( 'Search Results for: %s', 'cdbootstrap' ),
									'<span>' . get_search_query() . '</span>'
								);
								?>
							</h1>
							
				</header><!-- .page-header -->
				<?php
				/*
				 * @hooked cdboostrap_the_archive_filter - 10
				 */
				do_action( 'cdboostrap_archive_filter_bar' );				

				if ( have_posts() ) {
					?>
					<div class="posts">
						<div class="section-inner">
							<?php
							do_action( 'cdbootstrap_posts_start' );
							?>
							
							<div class="posts-grid grid load-more-target card-columns row">
								<div class="col grid-sizer col-4"></div>
								<?php
								// Start the Loop.
								while ( have_posts() ) {
									the_post();
			
									/*
									 * Include the Post-Format-specific template for the content.
									 * If you want to override this in a child theme, then include a file
									 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
									 */
									?>
									<div class="article-wrapper col-4">
									<?php
									get_template_part( 'loop-templates/content', 'search' );
									?>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				<?php
				} else {
					get_template_part( 'loop-templates/content', 'none' );
				}
				?>

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php cdbootstrap_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #search-wrapper -->

<?php
get_footer();
