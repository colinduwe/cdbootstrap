( function( $ ) {

	// Cache DOM selectors.
	var $header    = $( '#main-nav' ),
		$hsToggle  = $( '.toggle-header-search' ),
		$hsWrap    = $( '#header-search-wrap' ),
		$hsInput   = $hsWrap.find( 'input[type="text"]' ),
		$footer    = $( '.site-footer' ),
		$container = $( '.site-container' );
		
	console.log($hsInput);

	// Handler for click a show/hide button.
	$hsToggle.on( 'click', function( event ) {

		event.preventDefault();

		if ( $( this ).hasClass( 'close' ) ) {
			hideSearch();
		} else {
			showSearch();
		}

	});

	// Handler for pressing show/hide button.
	$hsToggle.on( 'keydown', function( event ) {

		// If tabbing from toggle button, and search is hidden, exit early.
		if ( 9 === event.keyCode && ! $header.hasClass( 'search-visible' ) ) {
			return;
		}

		//event.preventDefault();
		//handleKeyDown( event );

	});
	
	document.addEventListener("keydown", function(event) {
		console.log(event.which);
	});

	// Hide search when tabbing or escaping out of the search bar.
	$hsInput.on( 'keydown', function( event ) {

		// Tab: 9, Esc: 27.
		if ( 9 === event.keyCode || 16 === event.keyCode || 27 === event.keyCode ) {
			hideSearch( event.target );
		}

	});

	// Hide search on blur, such as when clicking outside it.
	$hsInput.on( 'blur', hideSearch );

	// Helper function to show the search form.
	function showSearch() {

		$header.addClass( 'search-visible' );
		$hsWrap.fadeIn( 'fast' );
		$hsInput.focus();
		$hsToggle.attr( 'aria-expanded', true );

	}

	// Helper function to hide the search form.
	function hideSearch() {

		$hsWrap.fadeOut( 'fast', function(){
			$hsWrap.attr('style', '');
		} ).parents( '.site-header' ).removeClass( 'search-visible' );
		$hsToggle.attr( 'aria-expanded', false );
	}

	// Keydown handler function for toggling search field visibility.
	function handleKeyDown( event ) {								
		// Enter/Space, respectively.
		if ( 13 === event.keyCode || 32 === event.keyCode ) {

			event.preventDefault();

			if ( $( event.target ).hasClass( 'close' ) ) {
				hideSearch();
			} else {
				showSearch();
			}

		}

	}

}( jQuery ) );
