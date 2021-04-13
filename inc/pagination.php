<?php
/**
 * Pagination layout
 *
 * @package CDBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cdbootstrap_pagination' ) ) {
	/**
	 * Displays the navigation to next/previous set of posts.
	 *
	 * @param string|array $args {
	 *     (Optional) Array of arguments for generating paginated links for archives.
	 *
	 *     @type string $base               Base of the paginated url. Default empty.
	 *     @type string $format             Format for the pagination structure. Default empty.
	 *     @type int    $total              The total amount of pages. Default is the value WP_Query's
	 *                                      `max_num_pages` or 1.
	 *     @type int    $current            The current page number. Default is 'paged' query var or 1.
	 *     @type string $aria_current       The value for the aria-current attribute. Possible values are 'page',
	 *                                      'step', 'location', 'date', 'time', 'true', 'false'. Default is 'page'.
	 *     @type bool   $show_all           Whether to show all pages. Default false.
	 *     @type int    $end_size           How many numbers on either the start and the end list edges.
	 *                                      Default 1.
	 *     @type int    $mid_size           How many numbers to either side of the current pages. Default 2.
	 *     @type bool   $prev_next          Whether to include the previous and next links in the list. Default true.
	 *     @type bool   $prev_text          The previous page text. Default '&laquo;'.
	 *     @type bool   $next_text          The next page text. Default '&raquo;'.
	 *     @type string $type               Controls format of the returned value. Possible values are 'plain',
	 *                                      'array' and 'list'. Default is 'array'.
	 *     @type array  $add_args           An array of query args to add. Default false.
	 *     @type string $add_fragment       A string to append to each link. Default empty.
	 *     @type string $before_page_number A string to appear before the page number. Default empty.
	 *     @type string $after_page_number  A string to append after the page number. Default empty.
	 *     @type string $screen_reader_text Screen reader text for the nav element. Default 'Posts navigation'.
	 * }
	 * @param string       $class           (Optional) Classes to be added to the <ul> element. Default 'pagination'.
	 */
	function cdbootstrap_pagination( $args = array(), $class = 'pagination' ) {

		if ( ! isset( $args['total'] ) && $GLOBALS['wp_query']->max_num_pages <= 1 ) {
			return;
		}

		$args = wp_parse_args(
			$args,
			array(
				'mid_size'           => 2,
				'prev_next'          => true,
				'prev_text'          => __( '&laquo;', 'cdbootstrap' ),
				'next_text'          => __( '&raquo;', 'cdbootstrap' ),
				'type'               => 'array',
				'current'            => max( 1, get_query_var( 'paged' ) ),
				'screen_reader_text' => __( 'Posts navigation', 'cdbootstrap' ),
			)
		);

		$links = paginate_links( $args );
		if ( ! $links ) {
			return;
		}
		
		global $wp_query;

		// Set the type of pagination to use. Available types: button, links, and scroll.
		$pagination_type = get_theme_mod( 'cdbootstrap_pagination_type', 'button' );
		$pagination_type = 'scroll';
		
		// Combine the query with the query_vars into a single array.
		$query_args = array_merge( $wp_query->query, $wp_query->query_vars );
		
		// If max_num_pages is not already set, add it.
		if ( ! array_key_exists( 'max_num_pages', $query_args ) ) {
			$query_args['max_num_pages'] = $wp_query->max_num_pages;
		}
		
		// If post_status is not already set, add it.
		if ( ! array_key_exists( 'post_status', $query_args ) ) {
			$query_args['post_status'] = 'publish';
		}
		
		// Make sure the paged value exists and is at least 1.
		if ( ! array_key_exists( 'paged', $query_args ) || 0 == $query_args['paged'] ) {
			$query_args['paged'] = 1;
		}
		
		// Encode our modified query.
		$json_query_args = wp_json_encode( $query_args ); 
		
		// Set up the wrapper class.
		$wrapper_class = 'pagination-type-' . $pagination_type;
		
		// Indicate when we're loading into the last page, so the pagination can be hidden for the button and scroll types.
		if ( ! ( $query_args['max_num_pages'] > $query_args['paged'] ) ) {
			$wrapper_class .= ' last-page';
		} else {
			$wrapper_class .= '';
		}

		?>
		
		<div class="pagination-wrapper <?php echo esc_attr( $wrapper_class ); ?>">
		
			<div id="pagination" class="section-inner text-center" data-query-args="<?php echo esc_attr( $json_query_args ); ?>" data-pagination-type="<?php echo esc_attr( $pagination_type ); ?>" data-load-more-target=".load-more-target">
		
				<?php if ( ( $query_args['max_num_pages'] > $query_args['paged'] ) ) : ?>
		
					<?php if ( $pagination_type == 'scroll' ) : ?>
						<div class="scroll-loading">
							<div class="loading-icon">
								<span class="dot-pulse"></span>
							</div>
						</div>
					<?php endif; ?>
		
					<?php if ( $pagination_type == 'button' ) : ?>
						<button id="load-more" class="btn btn-primary btn-lg d-no-js-none do-spot spot-fade-up">
							<span class="load-text"><?php esc_html_e( 'Load More', 'cdbootstrap' ); ?></span>
							<span class="loading-icon"><span class="dot-pulse"></span></span>
						</button>
					<?php endif;
		
					// The pagination links also work as a no-js fallback, so they always need to be output.
					?>
					<nav class="link-pagination" aria-labelledby="posts-nav-label">

						<h2 id="posts-nav-label" class="sr-only">
							<?php echo esc_html( $args['screen_reader_text'] ); ?>
						</h2>
			
						<ul class="<?php echo esc_attr( $class ); ?>">
			
							<?php
							foreach ( $links as $key => $link ) {
								?>
								<li class="page-item <?php echo strpos( $link, 'current' ) ? 'active' : ''; ?>">
									<?php echo str_replace( 'page-numbers', 'page-link', $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</li>
								<?php
							}
							?>
			
						</ul>
			
					</nav>
		
				<?php endif; ?>
		
			</div><!-- #pagination -->
		
		</div><!-- .pagination-wrapper -->

		<?php
	}
}
