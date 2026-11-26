/**
 * External dependencies
 */
import React from 'react';
import { createRoot } from 'react-dom/client';
import { act } from 'react-dom/test-utils';

/**
 * Internal dependencies
 */
import { withBlockCloser } from '@moderntribe/common/hoc';

const Panel = ( { open, isOpen } ) => (
	<div className="panel">
		<button className="panel__open" onClick={ open }>
			Open
		</button>
		{ isOpen && <span className="panel__content">Content</span> }
	</div>
);

const Closer = withBlockCloser( Panel );

/**
 * Appends a node to the body, the way a popover portal mounts outside the block.
 *
 * @param {string} className The class of the appended node.
 *
 * @return {HTMLButtonElement} A button inside the appended node.
 */
const mountOutsideNode = ( className ) => {
	const node = document.createElement( 'div' );
	node.className = className;
	const button = document.createElement( 'button' );
	node.appendChild( button );
	document.body.appendChild( node );

	return button;
};

// The closer listens for real document events, which react-test-renderer cannot dispatch, so these tests mount into the DOM.
describe( 'withBlockCloser', () => {
	let container;
	let root;
	let onClose;

	const render = ( props = {} ) => {
		act( () => {
			root.render( <Closer onClose={ onClose } { ...props } /> );
		} );
	};

	const openPanel = () => {
		act( () => {
			container.querySelector( '.panel__open' ).click();
		} );
	};

	const isPanelOpen = () => container.querySelector( '.panel__content' ) !== null;

	beforeAll( () => {
		global.IS_REACT_ACT_ENVIRONMENT = true;
	} );

	beforeEach( () => {
		onClose = jest.fn();
		container = document.createElement( 'div' );
		document.body.appendChild( container );
		root = createRoot( container );
	} );

	afterEach( () => {
		act( () => root.unmount() );
		document.body.replaceChildren();
	} );

	it( 'should stay open when clicking inside a date picker popover mounted after the panel opened', () => {
		render();
		openPanel();

		const navButton = mountOutsideNode( 'tribe-editor__date-input__popover' );
		act( () => navButton.click() );

		expect( onClose ).not.toHaveBeenCalled();
		expect( isPanelOpen() ).toBe( true );
	} );

	it( 'should stay open when clicking inside a date picker popover with a custom blacklist', () => {
		render( { classNameClickBlacklist: [ '.custom-sidebar' ] } );
		openPanel();

		const navButton = mountOutsideNode( 'tribe-editor__date-input__popover' );
		act( () => navButton.click() );

		expect( onClose ).not.toHaveBeenCalled();
		expect( isPanelOpen() ).toBe( true );
	} );

	it( 'should stay open when clicking inside a blacklisted node mounted after the panel opened', () => {
		render( { classNameClickBlacklist: [ '.custom-sidebar' ] } );
		openPanel();

		const sidebarButton = mountOutsideNode( 'custom-sidebar' );
		act( () => sidebarButton.click() );

		expect( onClose ).not.toHaveBeenCalled();
		expect( isPanelOpen() ).toBe( true );
	} );

	it( 'should stay open when clicking inside the panel', () => {
		render();
		openPanel();

		act( () => container.querySelector( '.panel__content' ).click() );

		expect( onClose ).not.toHaveBeenCalled();
		expect( isPanelOpen() ).toBe( true );
	} );

	it( 'should close when clicking outside the panel', () => {
		render();
		openPanel();

		const outsideButton = mountOutsideNode( 'unrelated' );
		act( () => outsideButton.click() );

		expect( onClose ).toHaveBeenCalledTimes( 1 );
		expect( isPanelOpen() ).toBe( false );
	} );

	it( 'should close on Escape', () => {
		render();
		openPanel();

		act( () => {
			document.dispatchEvent( new KeyboardEvent( 'keydown', { keyCode: 27, bubbles: true } ) );
		} );

		expect( onClose ).toHaveBeenCalledTimes( 1 );
		expect( isPanelOpen() ).toBe( false );
	} );
} );
