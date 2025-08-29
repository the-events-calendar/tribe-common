/**
 * Detects the user agent and adds a class to the body to indicate the device type.
 *
 * @since TBD
 */
( function() {
	const userAgent = window.navigator.userAgent;
	const deviceClasses = {
		android: /android/i,
		iphone: /iPhone/i
	};

	Object.entries( deviceClasses ).forEach( ( [ device, pattern ] ) => {
		if ( userAgent.match( pattern ) ) {
			document.body.classList.add( `tec-is-${ device }` );
		}
	} );

	// Hide parent list items that contain specific subscription links
	document.addEventListener( 'DOMContentLoaded', function() {
		document.querySelectorAll( '.tec-is-android.post-type-archive-tribe_events .tribe-events-c-subscribe-dropdown__list-item > a[href*="google"], .tec-is-android.post-type-archive-tribe_events .tribe-events-c-subscribe-dropdown__list-item > a[href*="ical=1&eventDisplay=list"]' ).forEach( link => {
			link.parentElement.style.display = 'none';
		} );
	} );
} )();
