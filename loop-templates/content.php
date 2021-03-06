<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class('do-spot spot-fade-up a-del-200 card'); ?> id="post-<?php the_ID(); ?>">

	<?php echo get_the_post_thumbnail( $post->ID, 'large', array('class' => 'card-img-top' ) ); ?>
	
	<div class="card-body">
	
		<header class="entry-header">
	
			<?php
			the_title(
				sprintf( '<h2 class="entry-title card-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
				'</a></h2>'
			);
			?>
	
			<?php if ( 'post' === get_post_type() ) : ?>
	
				<div class="entry-meta">
					<?php cdbootstrap_posted_on(); ?>
				</div><!-- .entry-meta -->
	
			<?php endif; ?>
	
		</header><!-- .entry-header -->
	
		<div class="entry-content card-text">
	
			<?php the_excerpt(); ?>
	
			<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'cdbootstrap' ),
					'after'  => '</div>',
				)
			);
			?>
	
		</div><!-- .entry-content -->
	
		<footer class="entry-footer">
	
			<?php cdbootstrap_entry_footer(); ?>
	
		</footer><!-- .entry-footer -->
	
	</div>

</article><!-- #post-## -->
