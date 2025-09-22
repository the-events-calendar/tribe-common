// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { fireEvent, render, screen, waitFor, act } from '@testing-library/react';
import { createRegistry, RegistryProvider } from '@wordpress/data';
import '@testing-library/jest-dom';
import PostTitle from '../../../src/resources/packages/classy/fields/PostTitle/PostTitle';
import { FieldProps } from '@tec/common/classy/types/FieldProps';

describe( 'PostTitle', () => {
	const mockProps: FieldProps = {
		title: 'Event Title',
	};

	let registry;
	let mockEditPost;
	let currentPostTitle = 'Initial Post Title';

	function setupRegistry( initialTitle?: string | undefined ) {
		registry = createRegistry();
		mockEditPost = jest.fn();
		// If no argument is passed, use default. If undefined is explicitly passed, use undefined
		currentPostTitle = arguments.length === 0 ? 'Initial Post Title' : initialTitle;

		// Register core/editor store for post title handling.
		registry.registerStore( 'core/editor', {
			reducer: ( state = { title: currentPostTitle }, action ) => {
				if ( action.type === 'EDIT_POST' && action.edits.title !== undefined ) {
					return {
						...state,
						title: action.edits.title,
					};
				}
				return state;
			},
			selectors: {
				getEditedPostAttribute: ( state, attribute ) => {
					if ( attribute === 'title' ) {
						return state.title === undefined ? undefined : state.title;
					}
					return null;
				},
			},
			actions: {
				editPost: ( edits ) => {
					mockEditPost( edits );
					if ( edits.title !== undefined ) {
						currentPostTitle = edits.title;
					}
					return { type: 'EDIT_POST', edits };
				},
			},
		} );
	}

	beforeEach( () => {
		jest.clearAllMocks();
		setupRegistry();
	} );

	afterEach( () => {
		jest.clearAllMocks();
	} );

	it( 'renders with the correct structure and title', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		// Check the title heading.
		expect( screen.getByRole( 'heading', { level: 3 } ) ).toHaveTextContent( 'Event Title' );

		// Check the input field.
		const input = screen.getByDisplayValue( 'Initial Post Title' );
		expect( input ).toBeInTheDocument();
		// InputControl from WordPress doesn't apply custom classes to the actual input element

		// Check the container structure.
		const container = document.querySelector( '.classy-field.classy-field--post-title' );
		expect( container ).toBeInTheDocument();

		const titleContainer = document.querySelector( '.classy-field__title' );
		expect( titleContainer ).toBeInTheDocument();

		const inputsContainer = document.querySelector( '.classy-field__inputs' );
		expect( inputsContainer ).toBeInTheDocument();
	} );

	it( 'displays the initial post title from the store', () => {
		setupRegistry( 'My Amazing Event' );

		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'My Amazing Event' );
		expect( input ).toBeInTheDocument();
	} );

	it( 'updates the title when user types in the input field', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Change the input value.
		fireEvent.change( input, { target: { value: 'Updated Event Title' } } );

		// Check that editPost was called with the new title.
		expect( mockEditPost ).toHaveBeenCalledWith( {
			title: 'Updated Event Title',
		} );

		// The input should show the new value.
		expect( input ).toHaveValue( 'Updated Event Title' );
	} );

	it( 'handles empty title input', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Clear the input.
		fireEvent.change( input, { target: { value: '' } } );

		// Check that editPost was called with empty string.
		expect( mockEditPost ).toHaveBeenCalledWith( {
			title: '',
		} );

		// The input should be empty.
		expect( input ).toHaveValue( '' );
	} );

	it( 'handles undefined value in onChange correctly', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Simulate undefined value (edge case).
		act( () => {
			const inputElement = input as HTMLInputElement;
			const nativeInputValueSetter = Object.getOwnPropertyDescriptor( window.HTMLInputElement.prototype, 'value' )
				?.set;
			// When undefined is set to input value, it becomes "undefined" string
			nativeInputValueSetter?.call( inputElement, undefined );

			const event = new Event( 'input', { bubbles: true } );
			inputElement.dispatchEvent( event );
		} );

		// The InputControl component treats undefined as "undefined" string
		expect( mockEditPost ).toHaveBeenCalledWith( {
			title: 'undefined',
		} );
	} );

	it( 'updates when the store title changes externally', async () => {
		const { rerender } = render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		// Initial value.
		expect( screen.getByDisplayValue( 'Initial Post Title' ) ).toBeInTheDocument();

		// Simulate external store update.
		await waitFor( () => {
			registry.dispatch( 'core/editor' ).editPost( { title: 'Externally Updated Title' } );
		} );

		// Force re-render to trigger useEffect.
		rerender(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		// Wait for the component to update.
		await waitFor( () => {
			expect( screen.getByDisplayValue( 'Externally Updated Title' ) ).toBeInTheDocument();
		} );
	} );

	it( 'handles rapid consecutive updates correctly', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Simulate rapid typing.
		fireEvent.change( input, { target: { value: 'A' } } );
		fireEvent.change( input, { target: { value: 'AB' } } );
		fireEvent.change( input, { target: { value: 'ABC' } } );
		fireEvent.change( input, { target: { value: 'ABCD' } } );

		// Check all updates were dispatched.
		expect( mockEditPost ).toHaveBeenCalledTimes( 4 );
		expect( mockEditPost ).toHaveBeenNthCalledWith( 1, { title: 'A' } );
		expect( mockEditPost ).toHaveBeenNthCalledWith( 2, { title: 'AB' } );
		expect( mockEditPost ).toHaveBeenNthCalledWith( 3, { title: 'ABC' } );
		expect( mockEditPost ).toHaveBeenNthCalledWith( 4, { title: 'ABCD' } );

		// Final value should be displayed.
		expect( input ).toHaveValue( 'ABCD' );
	} );

	it( 'handles special characters in the title', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		const specialTitle = 'Event & Conference 2024 - "Special" <Edition> @#$%';
		fireEvent.change( input, { target: { value: specialTitle } } );

		// Check that editPost was called with special characters intact.
		expect( mockEditPost ).toHaveBeenCalledWith( {
			title: specialTitle,
		} );

		expect( input ).toHaveValue( specialTitle );
	} );

	it( 'handles very long titles', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Create a very long title.
		const longTitle = 'A'.repeat( 500 );
		fireEvent.change( input, { target: { value: longTitle } } );

		// Check that editPost was called with the long title.
		expect( mockEditPost ).toHaveBeenCalledWith( {
			title: longTitle,
		} );

		expect( input ).toHaveValue( longTitle );
	} );

	it( 'renders with custom title prop', () => {
		const customProps: FieldProps = {
			title: 'Custom Field Title',
		};

		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...customProps } />
			</RegistryProvider>
		);

		// Check the custom title is displayed.
		expect( screen.getByRole( 'heading', { level: 3 } ) ).toHaveTextContent( 'Custom Field Title' );
	} );

	it( 'handles whitespace-only input', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Input with only spaces.
		fireEvent.change( input, { target: { value: '   ' } } );

		// Check that editPost was called with the whitespace.
		expect( mockEditPost ).toHaveBeenCalledWith( {
			title: '   ',
		} );

		expect( input ).toHaveValue( '   ' );
	} );

	it( 'handles paste events with newlines', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Simulate pasting text with newlines.
		// Note: InputControl strips newlines internally
		const pastedText = 'Line 1\nLine 2\nLine 3';
		const expectedText = 'Line 1Line 2Line 3'; // InputControl removes newlines
		fireEvent.change( input, { target: { value: pastedText } } );

		// Check that editPost was called with the text (newlines removed by InputControl).
		expect( mockEditPost ).toHaveBeenCalledWith( {
			title: expectedText,
		} );

		expect( input ).toHaveValue( expectedText );
	} );

	it( 'initializes with empty string when store has no title', () => {
		setupRegistry( undefined );

		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		// Should show empty input when store returns undefined.
		const input = screen.getByRole( 'textbox' );
		// The component converts undefined to empty string
		expect( input ).toHaveValue( '' );
	} );

	it( 'maintains focus after typing', () => {
		render(
			<RegistryProvider value={ registry }>
				<PostTitle { ...mockProps } />
			</RegistryProvider>
		);

		const input = screen.getByDisplayValue( 'Initial Post Title' );

		// Focus the input.
		input.focus();
		expect( document.activeElement ).toBe( input );

		// Type in the input.
		fireEvent.change( input, { target: { value: 'New Title' } } );

		// Focus should be maintained.
		expect( document.activeElement ).toBe( input );
	} );
} );
