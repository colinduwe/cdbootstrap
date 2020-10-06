<?php
/**
 * The template for displaying search forms
 *
 * @package cdbootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<label class="sr-only" for="s"><?php esc_html_e( 'Search', 'cdbootstrap' ); ?></label>
	<div class="input-group">
		<input class="field form-control" id="s" name="s" type="text"
			placeholder="<?php esc_attr_e( 'Search &hellip;', 'cdbootstrap' ); ?>" value="<?php the_search_query(); ?>">
		<span class="input-group-append">
			<button type="submit" class="submit btn btn-primary" id="searchsubmit" name="submit">
				<span class="screen-reader-text"><?php esc_attr_e( 'Search', 'cdbootstrap' ); ?></span><i class="far fa-search"></i></button>
		</span>
	</div>
</form>
