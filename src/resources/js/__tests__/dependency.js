/*
 * `dependency.js` reads WordPress's global `_` (underscore), which lodash covers, and the
 * `String.prototype.className()` helper that `tribe-common.js` adds on every page.
 */
global._ = require( 'lodash' );
require( '../tribe-common.js' );

describe( 'dependency data-depends', () => {
	const payload = '<b>not a selector</b>';
	let parseHTML;
	let dependency;

	beforeAll( async () => {
		dependency = document.createElement( 'input' );
		dependency.id = 'tec-dependency';
		document.body.appendChild( dependency );

		const malicious = document.createElement( 'span' );
		malicious.className = 'tribe-dependent';
		malicious.setAttribute( 'data-depends', payload );
		document.body.appendChild( malicious );

		const dependent = document.createElement( 'div' );
		dependent.className = 'tribe-dependent';
		dependent.setAttribute( 'data-depends', '#tec-dependency' );
		document.body.appendChild( dependent );

		parseHTML = jest.spyOn( jQuery, 'parseHTML' );

		require( '../dependency.js' );
		await new Promise( ( resolve ) => jQuery( resolve ) );
	} );

	it( 'should not build markup from a data-depends value', () => {
		const parsedPayload = parseHTML.mock.calls.some( ( [ html ] ) => html === payload );

		expect( parsedPayload ).toBe( false );
	} );

	it( 'should still set up dependents with a valid selector', () => {
		expect( dependency.classList.contains( 'tribe-dependency' ) ).toBe( true );
	} );
} );
