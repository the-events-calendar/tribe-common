/*
 * `timepicker.js` reads WordPress's global `_` (underscore);
 * lodash's `_.template` shares its `<%- %>` / `<%= %>` semantics.
 */
global._ = require( 'lodash' );

describe( 'timepicker timezone link', () => {
	const label = 'Time Zone:';
	const timezone = '<img src="x" onerror="window.tecXss = true">';
	let container;

	beforeAll( async () => {
		container = document.createElement( 'div' );
		container.className = 'tribe-datetime-block';

		const select = document.createElement( 'select' );
		select.className = 'tribe-field-timezone';
		select.setAttribute( 'data-timezone-label', label );
		select.setAttribute( 'data-timezone-value', timezone );
		container.appendChild( select );
		document.body.appendChild( container );

		require( '../timepicker.js' );
		await new Promise( ( resolve ) => jQuery( resolve ) );
	} );

	it( 'should render the timezone value as text, not markup', () => {
		const link = container.querySelector( '.tribe-change-timezone' );

		expect( link ).not.toBeNull();
		expect( link.textContent ).toBe( `${ label } ${ timezone }` );
		expect( container.querySelector( 'img' ) ).toBeNull();
	} );
} );
