wp.domReady( () => {
	//wp.blocks.unregisterBlockStyle( 'core/button', 'default' );
	//wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
	//wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
	wp.blocks.registerBlockStyle(
		'core/button',
		[
			{
				name: 'fill',
				label: 'Fill',
			},
			{
				name: 'fill-chevron',
				label: 'Fill w Chevron',
				isDefault: true,
			},
			{
				name: 'outline-chevron',
				label: 'Outline w Chevron',
			}			
		]
	);
} );