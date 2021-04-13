/*	-----------------------------------------------------------------------------------------------
	Namespace
--------------------------------------------------------------------------------------------------- */

var cdbootstrap = cdbootstrap || {},
    $ = jQuery;


/*	-----------------------------------------------------------------------------------------------
	Global variables
--------------------------------------------------------------------------------------------------- */

var $cdbootstrapDoc = $( document ),
    $cdbootstrapWin = $( window );


/*	-----------------------------------------------------------------------------------------------
	Helper functions
--------------------------------------------------------------------------------------------------- */

/* Output AJAX errors ------------------------ */

function cdbootstrapAjaxErrors( jqXHR, exception ) {
	var message = '';
	if ( jqXHR.status === 0 ) {
		message = 'Not connect.n Verify Network.';
	} else if ( jqXHR.status == 404 ) {
		message = 'Requested page not found. [404]';
	} else if ( jqXHR.status == 500 ) {
		message = 'Internal Server Error [500].';
	} else if ( exception === 'parsererror' ) {
		message = 'Requested JSON parse failed.';
	} else if ( exception === 'timeout' ) {
		message = 'Time out error.';
	} else if ( exception === 'abort' ) {
		message = 'Ajax request aborted.';
	} else {
		message = 'Uncaught Error.n' + jqXHR.responseText;
	}
	console.log( 'AJAX ERROR:' + message );
}

/*	-----------------------------------------------------------------------------------------------
	Interval Scroll
--------------------------------------------------------------------------------------------------- */

cdbootstrap.intervalScroll = {

	init: function() {

		var didScroll = false;

		// Check for the scroll event.
		$cdbootstrapWin.on( 'scroll load', function() {
			didScroll = true;
		} );

		// Once every 250ms, check if we have scrolled, and if we have, do the intensive stuff.
		setInterval( function() {
			if ( didScroll ) {
				didScroll = false;

				// When this triggers, we know that we have scrolled.
				$cdbootstrapWin.trigger( 'did-interval-scroll' );

			}

		}, 250 );

	},

} // cdbootstrap.intervalScroll

/*	-----------------------------------------------------------------------------------------------
	Load More
--------------------------------------------------------------------------------------------------- */

cdbootstrap.loadMore = {

	init: function() {

		var $pagination = $( '#pagination' );

		// First, check that there's a pagination.
		if ( $pagination.length ) {

			// Default values for variables.
			window.cdbootstrapIsLoading = false;
			window.cdbootstrapIsLastPage = $( '.pagination-wrapper' ).hasClass( 'last-page' );

			cdbootstrap.loadMore.prepare( $pagination );

		}

		// When the pagination query args are updated, reset the posts to reflect the new pagination
		$cdbootstrapWin.on( 'reset-posts', function() {

			// Fade out the pagination and existing posts.
			$pagination.add( $( $pagination.data( 'load-more-target' ) ).find( '.article-wrapper' ) ).animate( { opacity: 0 }, 300, 'linear' );

			// Reset posts.
			var resetPosts = true;
			cdbootstrap.loadMore.prepare( $pagination, resetPosts = true );
		} );

	},

	prepare: function( $pagination, resetPosts ) {

		// Default resetPosts to false.
		if ( typeof resetPosts === 'undefined' || ! resetPosts ) {
			resetPosts = false;
		}

		// Get the query arguments from the pagination element.
		var queryArgs = JSON.parse( $pagination.attr( 'data-query-args' ) );

		// If we're resetting posts, reset them.
		if ( resetPosts ) {
			cdbootstrap.loadMore.loadPosts( $pagination, resetPosts );
		}

		// If not, check the paged value against the max_num_pages.
		else {
			if ( queryArgs.paged == queryArgs.max_num_pages ) {
				$( '.pagination-wrapper' ).addClass( 'last-page' );
			}

			// Get the load more type (button or scroll).
			var loadMoreType = $pagination.data( 'pagination-type' ) ? $pagination.data( 'pagination-type' ) : 'button';

			// Do the appropriate load more detection, depending on the type.
			if ( loadMoreType == 'scroll' ) {
				cdbootstrap.loadMore.detectScroll( $pagination );
			} else if ( loadMoreType == 'button' ) {
				cdbootstrap.loadMore.detectButtonClick( $pagination );
			}
		}

	},

	// Load more on scroll
	detectScroll: function( $pagination, query_args ) {

		$cdbootstrapWin.on( 'did-interval-scroll', function() {

			// If it's the last page, or we're already loading, we're done here.
			if ( cdbootstrapIsLastPage || cdbootstrapIsLoading ) {
				return;
			}

			var paginationOffset 	= $pagination.offset().top,
				winOffset 			= $cdbootstrapWin.scrollTop() + $cdbootstrapWin.outerHeight();

			// If the bottom of the window is below the top of the pagination, start loading.
			if ( ( winOffset > paginationOffset ) ) {
				cdbootstrap.loadMore.loadPosts( $pagination, query_args );
			}

		} );

	},

	// Load more on click.
	detectButtonClick: function( $pagination, query_args ) {

		// Load on click.
		$( '#load-more' ).on( 'click', function() {

			// Make sure we aren't already loading.
			if ( cdbootstrapIsLoading ) return;

			cdbootstrap.loadMore.loadPosts( $pagination, query_args );
			return false;
		} );

	},

	// Load the posts
	loadPosts: function( $pagination, resetPosts ) {

		// Default resetPosts to false.
		if ( typeof resetPosts === 'undefined' || ! resetPosts ) {
			resetPosts = false;
		}

		// Get the query arguments.
		var queryArgs 			= $pagination.attr( 'data-query-args' ),
			queryArgsParsed 	= JSON.parse( queryArgs ),
			$paginationWrapper 	= $( '.pagination-wrapper' ),
			$articleWrapper 	= $( $pagination.data( 'load-more-target' ) );

		// We're now loading.
		cdbootstrapIsLoading = true;
		if ( ! resetPosts ) {
			$paginationWrapper.addClass( 'loading' );
		}

		// If we're not resetting posts, increment paged (reset = initial paged is correct).
		if ( ! resetPosts ) {
			queryArgsParsed.paged++;
		} else {
			queryArgsParsed.paged = 1;
		}

		// Prepare the query args for submission.
		var jsonQueryArgs = JSON.stringify( queryArgsParsed );

		$.ajax({
			url: cdbootstrap_ajax_load_more.ajaxurl,
			type: 'post',
			data: {
				action: 'cdbootstrap_ajax_load_more',
				json_data: jsonQueryArgs
			},
			success: function( result ) {

				// Get the results.
				var $result = $( result );

				// If we're resetting posts, remove the existing posts.
				if ( resetPosts ) {
					$articleWrapper.find( '*:not(.grid-sizer)' ).remove();
				}

				// If there are no results, we're at the last page.
				if ( ! $result.length ) {
					cdbootstrapIsLoading = false;
					$articleWrapper.addClass( 'no-results' );
					$paginationWrapper.addClass( 'last-page' ).removeClass( 'loading' );
				}

				if ( $result.length ) {

					$articleWrapper.removeClass( 'no-results' );

					// Add the paged attribute to the articles, used by updateHistoryOnScroll().
					$result.find( 'article' ).each( function() {
						$( this ).attr( 'data-post-paged', queryArgsParsed.paged );
					} );

					// Wait for the images to load.
					$result.imagesLoaded( function() {

						// Append the results.
						$articleWrapper.append( $result ).masonry( 'appended', $result ).masonry();

						$cdbootstrapWin.trigger( 'ajax-content-loaded' );
						$cdbootstrapWin.trigger( 'did-interval-scroll' );

						// We're now finished with the loading.
						cdbootstrapIsLoading = false;
						$paginationWrapper.removeClass( 'loading' );

						// Update the pagination query args.
						$pagination.attr( 'data-query-args', jsonQueryArgs );

						// Reset the resetting of posts.
						if ( resetPosts ) {
							setTimeout( function() {
								$pagination.animate( { opacity: 1 }, 600, 'linear' );
							}, 400 );
							$( 'body' ).removeClass( 'filtering-posts' );
						}

						// If that was the last page, make sure we don't check for more.
						if ( queryArgsParsed.paged == queryArgsParsed.max_num_pages ) {
							$paginationWrapper.addClass( 'last-page' );
							cdbootstrapIsLastPage = true;
							return;

						// If not, make sure the pagination is visible again.
						} else {
							$paginationWrapper.removeClass( 'last-page' );
							cdbootstrapIsLastPage = false;
						}

					} );

				}

			},

			error: function( jqXHR, exception ) {
				cdbootstrapAjaxErrors( jqXHR, exception );
			}
		} );

	},

} // cdbootstrap.loadMore

/*	-----------------------------------------------------------------------------------------------
	Filters
--------------------------------------------------------------------------------------------------- */

cdbootstrap.filters = {

	init: function() {

		$cdbootstrapDoc.on( 'click', '.filter-link', function() {

			if ( $( this ).hasClass( 'active' ) ) return false;

			$( 'body' ).addClass( 'filtering-posts' );

			var $link 		= $( this ),
				termId 		= $link.data( 'filter-term-id' ) ? $link.data( 'filter-term-id' ) : null,
				taxonomy 	= $link.data( 'filter-taxonomy' ) ? $link.data( 'filter-taxonomy' ) : null,
				postType 	= $link.data( 'filter-post-type' ) ? $link.data( 'filter-post-type' ) : '';

			$link.addClass( 'pre-active' );

			$.ajax({
				url: cdbootstrap_ajax_filters.ajaxurl,
				type: 'post',
				data: {
					action: 	'cdbootstrap_ajax_filters',
					post_type: 	postType,
					term_id: 	termId,
					taxonomy: 	taxonomy,
				},
				success: function( result ) {

					// Add them to the pagination.
					$( '#pagination' ).attr( 'data-query-args', result );

					// Reset the posts.
					$cdbootstrapWin.trigger( 'reset-posts' );

					// Update active class.
					$( '.filter-link' ).removeClass( 'pre-active active' );
					$link.addClass( 'active' );
	
				},
	
				error: function( jqXHR, exception ) {
					cdbootstrapAJAXErrors( jqXHR, exception );
				}
			} );

			return false;

		} );

	}

} // cdbootstrap.filters

/*	-----------------------------------------------------------------------------------------------
	Element In View
--------------------------------------------------------------------------------------------------- */

cdbootstrap.elementInView = {

	init: function() {

		var $targets = $( 'body.has-anim .do-spot' );
		cdbootstrap.elementInView.run( $targets );

		// Rerun on AJAX content loaded.
		$cdbootstrapWin.on( 'ajax-content-loaded', function() {
			$targets = $( 'body.has-anim .do-spot' );
			cdbootstrap.elementInView.run( $targets );
		} );

	},

	run: function( $targets ) {

		if ( $targets.length ) {

			// Add class indicating the elements will be spotted.
			$targets.each( function() {
				$( this ).addClass( 'will-be-spotted' );
			} );

			cdbootstrap.elementInView.handleFocus( $targets );

			$cdbootstrapWin.on( 'load resize orientationchange did-interval-scroll', function() {
				cdbootstrap.elementInView.handleFocus( $targets );
			} );

		}

	},

	handleFocus: function( $targets ) {

		// Check for our targets.
		$targets.each( function() {

			var $this = $( this );
			var checkAbove;

			if ( cdbootstrap.elementInView.isVisible( $this, checkAbove = true ) ) {
				$this.addClass( 'spotted' ).trigger( 'spotted' );
			}

		} );

	},

	// Determine whether the element is in view.
	isVisible: function( $elem, checkAbove ) {

		if ( typeof checkAbove === 'undefined' ) {
			checkAbove = false;
		}

		var winHeight 				= $cdbootstrapWin.height();

		var docViewTop 				= $cdbootstrapWin.scrollTop(),
			docViewBottom			= docViewTop + winHeight,
			docViewLimit 			= docViewBottom;

		var elemTop 				= $elem.offset().top;

		// If checkAbove is set to true, which is default, return true if the browser has already scrolled past the element.
		if ( checkAbove && ( elemTop <= docViewBottom ) ) {
			return true;
		}

		// If not, check whether the scroll limit exceeds the element top.
		return ( docViewLimit >= elemTop );

	}

} // cdbootstrap.elementInView

/*	-----------------------------------------------------------------------------------------------
	Masonry
--------------------------------------------------------------------------------------------------- */

cdbootstrap.masonry = {

	init: function() {

		var $wrapper = $( '.posts-grid' );

		if ( $wrapper.length ) {

			$wrapper.imagesLoaded( function() {

				var $grid = $wrapper.masonry( {
					columnWidth: 		'.grid-sizer',
					itemSelector: 		'.article-wrapper',
					percentPosition: 	true,
					stagger:			0,
					transitionDuration: 0,
				} );

				// Trigger will-be-spotted elements.
				$grid.on( 'layoutComplete', function() {
					$cdbootstrapWin.trigger( 'scroll' );
				} );

				// Check for Masonry layout changes on an interval. Accounts for DOM changes caused by lazyloading plugins.
				// The interval is cleared when all previews have been spotted.
				cdbootstrap.masonry.intervalUpdate( $grid );

				// Reinstate the interval when new content is loaded.
				$cdbootstrapWin.on( 'ajax-content-loaded', function() {
					cdbootstrap.masonry.intervalUpdate( $grid );
				} );

			} );

		}

	},

	intervalUpdate: function( $grid ) {

		var masonryLayoutInterval = setInterval( function() {

			$grid.masonry();

			// Clear the interval when all previews have been spotted.
			if ( ! $( '.preview.do-spot:not(.spotted)' ).length ) clearInterval( masonryLayoutInterval );

		}, 1000 );

	}

} // cdbootstrap.masonry


/*	-----------------------------------------------------------------------------------------------
	Function Calls
--------------------------------------------------------------------------------------------------- */

$cdbootstrapDoc.ready( function() {

	cdbootstrap.intervalScroll.init();			// Check for scroll on an interval.
	//cdbootstrap.toggles.init();					// Handle toggles.
	//cdbootstrap.coverModals.init();				// Handle cover modals.
	cdbootstrap.elementInView.init();			// Check if elements are in view.
	//cdbootstrap.instrinsicRatioVideos.init();	// Retain aspect ratio of videos on window resize.
	//cdbootstrap.stickyHeader.init();				// Stick the header.
	//cdbootstrap.scrollLock.init();				// Scroll Lock.
	//cdbootstrap.mainMenu.init();					// Main Menu.
	//cdbootstrap.focusManagement.init();			// Focus Management.
	cdbootstrap.loadMore.init();					// Load More.
	cdbootstrap.filters.init();					// Filters.
	cdbootstrap.masonry.init();					// Masonry.
	//cdbootstrap.dynamicHeights.init();			// Dynamic Heights.

	// Call css-vars-ponyfill.
	//cssVars();

} );