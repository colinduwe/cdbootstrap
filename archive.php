<?php
/**
 * The template for displaying archive pages
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'cdbootstrap_container_type' );
?>

<div class="wrapper" id="archive-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">

				<header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
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
									get_template_part( 'loop-templates/content', get_post_format() );
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

		</div> <!-- .row -->

	</div><!-- #content -->

</div><!-- #archive-wrapper -->

<?php
get_footer();
