<?php
/*	-----------------------------------------------------------------------------------------------
	AJAX LOAD MORE
	Called in construct.js when the the pagination is triggered to load more posts.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'cdbootstrap_ajax_load_more' ) ) :
	function cdbootstrap_ajax_load_more() {

		$query_args = json_decode( wp_unslash( $_POST['json_data'] ), true );

		$ajax_query = new WP_Query( $query_args );

		// Determine which preview to use based on the post_type.
		$post_type = $ajax_query->get( 'post_type' );

		// Default to the "post" post type for mixed content.
		if ( ! $post_type || is_array( $post_type ) ) {
			$post_type = 'post';
		}

		if ( $ajax_query->have_posts() ) :
			while ( $ajax_query->have_posts() ) : 
				$ajax_query->the_post();

				global $post;
				?>

				<div class="article-wrapper col-4">
					<?php get_template_part( 'loop-templates/content', $post_type ); ?>
				</div>

				<?php 
			endwhile;
		endif;

		wp_die();

	}
	add_action( 'wp_ajax_nopriv_cdbootstrap_ajax_load_more', 'cdbootstrap_ajax_load_more' );
	add_action( 'wp_ajax_cdbootstrap_ajax_load_more', 'cdbootstrap_ajax_load_more' );
endif;


/* ---------------------------------------------------------------------------------------------
	AJAX FILTERS
	Return the query vars for the query for the taxonomy and terms supplied by JS.
--------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'cdbootstrap_ajax_filters' ) ) : 
	function cdbootstrap_ajax_filters() {

		// Get the filters from AJAX.
		$term_id 	= isset( $_POST['term_id'] ) ? $_POST['term_id'] : null;
		$taxonomy 	= isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : '';
		$post_type 	= isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';

		$args = array(
			'ignore_sticky_posts'	=> false,
			'post_status'			=> 'publish',
			'post_type'				=> $post_type,
		);

		if ( $term_id && $taxonomy ) {
			$args['tax_query'] = array(
				array(
					'taxonomy'	=> $taxonomy,
					'terms'		=> $term_id,
				)
			);
		}

		$custom_query = new WP_Query( $args );

		// Combine the query with the query_vars into a single array.
		$query_args = array_merge( $custom_query->query, $custom_query->query_vars );

		// If max_num_pages is not already set, add it.
		if ( ! array_key_exists( 'max_num_pages', $query_args ) ) {
			$query_args['max_num_pages'] = $custom_query->max_num_pages;
		}

		// Format and return.
		echo json_encode( $query_args );

		wp_die();
	}
	add_action( 'wp_ajax_nopriv_cdbootstrap_ajax_filters', 'cdbootstrap_ajax_filters' );
	add_action( 'wp_ajax_cdbootstrap_ajax_filters', 'cdbootstrap_ajax_filters' );
endif;

/*	-----------------------------------------------------------------------------------------------
	OUTPUT ARCHIVE FILTER
	Output the archive filter beneath the archive header.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'cdbootstrap_the_archive_filter' ) ) :
	function cdbootstrap_the_archive_filter() {
		
		// Check if we're showing the filter
		if ( ! cdbootstrap_show_home_filter() ) return;

		$filter_taxonomy = is_post_type_archive( 'jetpack-portfolio' ) ? 'jetpack-portfolio-type' : 'category';

		// Use the cdbootstrap_home_filter_get_terms_args filter to modify which taxonomy is used for the filtration.
		$terms = get_terms( apply_filters( 'cdbootstrap_home_filter_get_terms_args', array(
			'depth'		=> 1,
			'taxonomy'	=> $filter_taxonomy,
		) ) );

		if ( ! $terms ) return;

		$home_url 	= '';
		$post_type 	= '';

		// Determine the correct home URL to link to.
		if ( is_home() ) {
			$post_type 	= 'post';
			$home_url 	= home_url();
		} elseif ( is_post_type_archive() ) {
			$post_type 	= get_post_type();
			$home_url 	= get_post_type_archive_link( $post_type );
		}

		// Make the home URL filterable. If you change the taxonomy of the filtration with `cdbootstrap_home_filter_get_terms_args`,
		// you might want to filter this to make sure it points to the correct URL as well (or maybe remove it altogether).
		$home_url = apply_filters( 'cdbootstrap_filter_home_url', $home_url );
	
		?>

		<nav class="filter-wrapper i-a a-fade-up a-del-200" aria-label="Navigate by <?php echo $filter_taxonomy; ?>">
			<ul class="filter-list reset-list-style pagination">

				<?php if ( $home_url ) : ?>
					<li class="page-item filter-link active" data-filter-post-type="<?php echo esc_attr( $post_type ); ?>" href="<?php echo esc_url( $home_url ); ?>"><a class="page-link"><?php esc_html_e( 'Show All', 'cdbootstrap' ); ?></a></li>
				<?php endif; ?>

				<?php foreach ( $terms as $term ) : ?>
					<li class="page-item filter-link" data-filter-term-id="<?php echo esc_attr( $term->term_id ); ?>" data-filter-taxonomy="<?php echo esc_attr( $term->taxonomy ); ?>" data-filter-post-type="<?php echo esc_attr( $post_type ); ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><a class="page-link"><?php echo $term->name; ?></a></li>
				<?php endforeach; ?>
				
			</ul><!-- .filter-list -->
		</nav><!-- .filter-wrapper -->

		<?php 

	}
	add_action( 'cdboostrap_archive_filter_bar', 'cdbootstrap_the_archive_filter' );
endif;


/*	-----------------------------------------------------------------------------------------------
	SHOW HOME FILTER?
	Helper function for determining whether to show the home filter. Defaults to on home or on 
	Jetpack Portfolio CPT archive, and when set to active in the Customizer. The value can be 
	filtered with cdbootstrap_show_home_filter.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'cdbootstrap_show_home_filter' ) ) :
	function cdbootstrap_show_home_filter() {

		global $paged;

		return apply_filters( 'cdbootstrap_show_home_filter', ( is_home() || is_post_type_archive( 'jetpack-portfolio' ) ) && $paged == 0 && get_theme_mod( 'cdbootstrap_show_home_filter', true ) );

	}
endif;