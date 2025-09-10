/**
 * Detects the user agent and adds a class to the body to indicate the device type.
 *
 * @since 6.9.3
 */
( function() {
	const userAgent = window.navigator.userAgent;
	const deviceClasses = {
		android: /android/i,
		iphone: /iPhone/i,
        ipad: /iPad/i
	};

	Object.entries( deviceClasses ).forEach( ( [ device, pattern ] ) => {
		if ( userAgent.match( pattern ) ) {
			document.body.classList.add( `tec-is-${ device }` );
		}
	} );
} )();
