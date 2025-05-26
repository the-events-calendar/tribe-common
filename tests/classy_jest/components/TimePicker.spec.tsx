import * as React from 'react';
import { render } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { describe, expect, it, jest } from '@jest/globals';
import TimePicker from '../../../src/resources/packages/classy/components/TimePicker/TimePicker';

function getSuggestions( container: HTMLElement ) {
	return Array.from( container.querySelectorAll( '.components-form-token-field__suggestion [aria-label]' ) ).map(
		( element: Element ) => element.getAttribute( 'aria-label' )
	);
}

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
		const user = userEvent.setup();
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

		// Focus, clicking on it, on the input. This will open the suggestions list.
		await user.click( input );

		// Following the input, the options will be filtered down.
		const suggestions = Array.from(
			asFragment().querySelectorAll( '.components-form-token-field__suggestion' )
		).map( ( option: Element ) => option.innerHTML );

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
		const user = userEvent.setup();
		const props = {
			...defaultProps,
			startDate: new Date( '2023-12-23 10:00:00' ),
			endDate: new Date( '2023-12-23 23:30:00' ),
			// Same as start date.
			currenDate: new Date( '2023-12-23 10:00:00' ),
			onChange: jest.fn(),
		};

		const { container } = render( <TimePicker { ...props } /> );

		// Get hold of the input the user would use to input times.
		let input = container.querySelector( '.components-combobox-control__input' ) as Element;

		// The user types in a time. This should filter the list of times shown.
		await user.click( input );
		await user.type( input, '11' );

		expect( getSuggestions( container ) ).toEqual( [ '11:00 am', '11:30 am', '11:00 pm', '11:30 pm' ] );
		expect( props.onChange ).not.toHaveBeenCalled();

		// The user keeps typing to complete to "11:23 am".
		await user.type( input, ':23 am' );

		expect( getSuggestions( container ) ).toEqual( [ '11:23 am' ] );

		// The user presses Enter.
		await user.type( input, '{enter}' );

		expect( props.onChange ).toHaveBeenCalledWith( new Date( '2023-12-23 11:23:00' ) );
	} );
} );
