<?php
/**
 * Search related functions
 *
 * @package cdbootstrap
 */
 
add_filter('wp_nav_menu_items', 'cdbootstrap_add_search_trigger', 10, 2);
function cdbootstrap_add_search_trigger($items, $args){
    if( $args->theme_location == 'primary' ){
        $items .= '<li class="menu-item nav-item menu-search-item"><a href="#header-search-wrap" aria-controls="header-search-wrap" aria-expanded="false" role="button" class="toggle-header-search nav-link"><span class="screen-reader-text">' . __( 'Show Search', 'cdbootstrap') . '</span><i class="far fa-search"></i></a></li>';
    }
    return $items;
}