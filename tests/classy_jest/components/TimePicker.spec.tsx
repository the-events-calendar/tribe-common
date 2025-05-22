import * as React from 'react';
import { act, fireEvent, render } from '@testing-library/react';
import { describe, expect, it, jest } from '@jest/globals';
import TimePicker from '../../../src/resources/packages/classy/components/TimePicker/TimePicker';

describe( 'TimePicker Component', () => {
	const defaultProps = {
		currentDate: new Date( '2023-12-23 10:00:00' ),
		endDate: null,
		highlight: false,
		onChange: jest.fn(),
		startDate: null,
		timeFormat: 'h:i a', // e.g., '10 am'.
		timeInterval: 30,
	};

	it( 'renders correctly with default values', () => {
		const { container } = render( <TimePicker { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders correctly with same startDate, endDate, currentDate', () => {
		const props = {
			...defaultProps,
			startDate: new Date( '2023-12-23 10:00:00' ),
			endDate: new Date( '2023-12-23 10:00:00' ),
		};

		const { container } = render( <TimePicker { ...props } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders correctly with no startDate, endDate eq currentDate', () => {
		const props = {
			...defaultProps,
			endDate: new Date( '2023-12-23 10:00:00' ),
		};

		const { container } = render( <TimePicker { ...props } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders correctly with no endDate, startDate eq currentDate', () => {
		const props = {
			...defaultProps,
			startDate: new Date( '2023-12-23 10:00:00' ),
		};

		const { container } = render( <TimePicker { ...props } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders correctly with highlight true', () => {
		const props = {
			...defaultProps,
			highlight: true,
		};

		const { container } = render( <TimePicker { ...props } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders correctly with highlight false', () => {
		const props = {
			...defaultProps,
			highlight: false,
		};

		const { container } = render( <TimePicker { ...props } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'displays correct options when start, end date on same day', async () => {
		const props = {
			...defaultProps,
			startDate: new Date( '2023-12-23 10:00:00' ),
			endDate: new Date( '2023-12-23 23:30:00' ),
			// Same as start date.
			currenDate: new Date( '2023-12-23 10:00:00' ),
			onChange: jest.fn(),
		};

		const { container, asFragment } = render( <TimePicker { ...props } /> );

		// Get hold of the input the user would use to input times.
		const input = container.querySelector( '.components-combobox-control__input' ) as Element;

		await act( async () => {
			// Focus on the input as a user would with a click or tabbing.
			// This will open the suggestions list.
			fireEvent.focus( input );
		} );

		// await act(async () => {
		// 	// Simulate the user entering a time.
		// 	fireEvent.change(input, { target: { value: '11' } });
		// });

		// Following the input, the options will be filtered down.
		const suggestions = Array.from(
			asFragment().querySelectorAll( '.components-form-token-field__suggestion' )
		).map( ( option: HTMLElement ) => option.innerHTML );

		expect( suggestions ).toEqual( [
			'10:00 am',
			'10:30 am',
			'11:00 am',
			'11:30 am',
			'12:00 pm',
			'12:30 pm',
			'01:00 pm',
			'01:30 pm',
			'02:00 pm',
			'02:30 pm',
			'03:00 pm',
			'03:30 pm',
			'04:00 pm',
			'04:30 pm',
			'05:00 pm',
			'05:30 pm',
			'06:00 pm',
			'06:30 pm',
			'07:00 pm',
			'07:30 pm',
			'08:00 pm',
			'08:30 pm',
			'09:00 pm',
			'09:30 pm',
			'10:00 pm',
			'10:30 pm',
			'11:00 pm',
			'11:30 pm',
		] );
	} );

	it( 'handles user input as filtering value', async () => {
		const props = {
			...defaultProps,
			startDate: new Date( '2023-12-23 10:00:00' ),
			endDate: new Date( '2023-12-23 23:30:00' ),
			// Same as start date.
			currenDate: new Date( '2023-12-23 10:00:00' ),
			onChange: jest.fn(),
		};

		const { container, asFragment } = render( <TimePicker { ...props } /> );

		// Get hold of the input the user would use to input times.
		let input = container.querySelector( '.components-combobox-control__input' ) as Element;

		await act( async () => {
			// Focus on the input as a user would with a click or tabbing.
			// This will open the suggestions list.
			fireEvent.focus( input );
			// Simulate the user entering a time.
			fireEvent.change( input, { target: { value: '11' } } );
		} );

		// Following the input, the options will be filtered down.
		const selectors = '.components-form-token-field__suggestion [aria-label]';
		const suggestions = Array.from( asFragment().querySelectorAll( selectors ) ).map( ( element: HTMLElement ) =>
			element.getAttribute( 'aria-label' )
		);

		expect( suggestions ).toEqual( [ '11:00 am', '11:30 am', '11:00 pm', '11:30 pm' ] );

		expect( props.onChange ).not.toHaveBeenCalled();

		const keyDownEnter = new KeyboardEvent( 'keydown', {
			key: 'Enter',
			code: 'Enter',
			keyCode: 13, // While deprecated, this is the property the dialog WordPress logic is actually using.
			bubbles: true,
			cancelable: true,
		} );

		input = asFragment().querySelector( '.components-combobox-control__input' ) as Element;
		await act( async () => {
			// Simulate the user entering a time that is among the options and submitting.
			fireEvent.change( input, { target: { value: '11:30 am' } } );

			// @TODO this should  trigger the onChange of the combobox, but does not. Why?
			fireEvent.keyDown( input, keyDownEnter );
		} );

		expect( props.onChange ).toHaveBeenCalled();
	} );
} );
