jQuery( function () {
	let maxHeight = 0;
	jQuery( 'div.tribe-addon .caption' ).each( function () {
		const h = jQuery( this ).height();
		maxHeight = h > maxHeight ? h : maxHeight;
	} );

	jQuery( 'div.tribe-addon:not(.first) .caption' ).css( 'height', maxHeight );
} );
