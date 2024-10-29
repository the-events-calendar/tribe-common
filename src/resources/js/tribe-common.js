// Run some magic to allow a better handling of class names for jQuery.hasClass type of methods
String.prototype.className = function () {
	// Prevent Non Strings to be included
	if (
		(
			'string' !== typeof this
			&& ! this instanceof String // eslint-disable-line no-unsafe-negation
		)
		|| 'function' !== typeof this.replace
	) {
		return this;
	}

	return this.replace( '.', '' );
};

// Add a method to convert ID/Classes into JS easy/safe variable
String.prototype.varName = function () {
	// Prevent Non Strings to be included
	if (
		(
			'string' !== typeof this
			&& ! this instanceof String // eslint-disable-line no-unsafe-negation
		)
		|| 'function' !== typeof this.replace
	) {
		return this;
	}

	return this.replace( '-', '_' );
};

/*
 Delayed deep-linking. Wait for initial mutations to have stopped for a while, then scroll to the fragment element.
 When DOM mutations do not happen for 250ms, scroll the linked element into view.
 Kudos: https://stackoverflow.com/a/50803220
 */
( function () {
	const url = new URL ( window.location.href );
	const hash = url.hash;

	// Do not handle deeplinking if not coming from the plugins.
	if ( !hash || !hash.match ( '#(tribe|tec)' ) ) {
		return;
	}

	let updatesDidOccurr = true;

	const mutationObserver = new MutationObserver ( function () {
		updatesDidOccurr = true;
	} );

	// Observe all window events.
	mutationObserver.observe ( window.document, {
		attributes: true,
		childList: true,
		characterData: true,
		subtree: true
	} );

	let mutationCallback = function () {
		if ( updatesDidOccurr ) {
			updatesDidOccurr = false;
			setTimeout ( mutationCallback, 250 );
		} else {
			mutationObserver.takeRecords ();
			mutationObserver.disconnect ();

			// Detect the element now: it might have been added by a script.
			const scrollTo = document.getElementById ( hash.substring ( 1 ) );

			if ( scrollTo ) {
				// Scroll to the element, if it exists.
				scrollTo.scrollIntoView ();
			}
		}
	};

	// Start the loop.
	mutationCallback ();
} ) ();

/**
 * Creates a global Tribe Variable where we should start to store all the things
 * @type {object}
 */
var tribe = tribe || {}; // eslint-disable-line no-redeclare


/**
 * Creates a `window.tec` variable where we should start to store all the things.
 * Eventually we will migrate all the things from `window.tribe` to `window.tec`.
 *
 * @since 6.3.0
 *
 * @type {object}
 */
window.tec = window.tec || {}; // eslint-disable-line no-redeclare
