/**
 * Internal dependencies
 */
const A11yDialog = require( '../tec-a11y-dialog' );

/*
 * jsdom has no layout, so `scrollTop` always reads 0 and its setter is a no-op. Both
 * candidate scrollers get a backing value the test can drive, and the getter reproduces
 * the one layout behaviour this code depends on: taking the body out of flow collapses
 * the document, so a locked page reports a scroll offset of 0. Without that, a re-entrant
 * lock would read the real offset back and the bug would be invisible here.
 */
const stubScrollTop = ( initial ) => {
	const state = { value: initial };

	[ document.documentElement, document.body ].forEach( ( element ) => {
		Object.defineProperty( element, 'scrollTop', {
			configurable: true,
			get: () => ( document.body.style.position === 'fixed' ? 0 : state.value ),
			set: ( next ) => {
				state.value = next;
			},
		} );
	} );

	return state;
};

const buildDialog = ( id ) => {
	document.body.insertAdjacentHTML(
		'beforeend',
		`<button data-js="trigger-dialog-${ id }" data-content="dialog-content-${ id }"></button>
		<script data-js="dialog-content-${ id }" type="text/template">
			<div class="tribe-dialog__wrapper"><button class="save"></button></div>
		</script>`
	);

	return new A11yDialog( {
		appendTarget: '#append-target',
		bodyLock: true,
		effect: 'none',
		trigger: `[data-js='trigger-dialog-${ id }']`,
	} );
};

describe( 'tec-a11y-dialog body lock', () => {
	/**
	 * The scroll offset the page sits at before any dialog opens. Arbitrary, but it has
	 * to be non-zero — zero is exactly the value the bug used to leave behind.
	 *
	 * @type {number}
	 */
	const SCROLL_OFFSET = 1500;

	let scrollState;

	beforeEach( () => {
		document.body.innerHTML = '<div id="append-target"></div>';
		document.body.removeAttribute( 'style' );
		scrollState = stubScrollTop( SCROLL_OFFSET );
	} );

	it( 'restores the scroll position after a single dialog closes', () => {
		const dialog = buildDialog( 'a' );

		dialog.show();

		expect( document.body.style.position ).toBe( 'fixed' );
		expect( document.body.style.marginTop ).toBe( `-${ SCROLL_OFFSET }px` );

		dialog.hide();

		expect( document.body.style.position ).toBe( '' );
		expect( scrollState.value ).toBe( SCROLL_OFFSET );
	} );

	it( 'keeps the saved scroll position when a second dialog locks the body', () => {
		const outer = buildDialog( 'a' );
		const inner = buildDialog( 'b' );

		outer.show();
		inner.show();

		expect( document.body.style.marginTop ).toBe( `-${ SCROLL_OFFSET }px` );

		inner.hide();

		expect( document.body.style.position ).toBe( 'fixed' );

		outer.hide();

		expect( document.body.style.position ).toBe( '' );
		expect( scrollState.value ).toBe( SCROLL_OFFSET );
	} );
} );
