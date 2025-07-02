import * as React from 'react';
import { render } from '@testing-library/react';
import { userEvent } from '@testing-library/user-event';
import { describe, expect, it, jest } from '@jest/globals';
import { DatePicker } from '../../../src/resources/packages/classy/components';
import { DatePickerProps } from '../../../src/resources/packages/classy/components/DatePicker/DatePicker';
import { keyDownEscape } from '../_support/userEvents';

describe( 'DatePicker Component', () => {
	const defaultProps = {
		anchor: document.createElement( 'div' ),
		dateWithYearFormat: 'Y-m-d',
		endDate: new Date( '2023-12-23 13:00:00' ),
		isSelectingDate: false,
		isMultiday: false,
		onChange: jest.fn(),
		onClick: jest.fn(),
		onClose: jest.fn(),
		showPopover: false,
		startDate: new Date( '2023-12-23 10:00:00' ),
		startOfWeek: 0,
		currentDate: new Date( '2023-12-23 10:00:00' ),
	} as DatePickerProps;

	it( 'renders correctly with default props', () => {
		const { container } = render( <DatePicker { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	describe( 'isSelectingDate property', () => {
		it( 'selecting start date', () => {
			const props = {
				...defaultProps,
				isSelectingDate: 'start',
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'selecting end date', () => {
			const props = {
				...defaultProps,
				isSelectingDate: 'end',
				currentDate: defaultProps.endDate,
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'selecting neither start nor end date', () => {
			const props = {
				...defaultProps,
				isSelectingDate: false,
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'isMultiday property', () => {
		it( 'renders correctly when isMultiday is true', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				endDate: new Date( '2023-12-24 13:00:00' ),
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'renders correctly when isMultiday is false', () => {
			const props = {
				...defaultProps,
				isMultiday: false,
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'showPopover property', () => {
		it( 'renders correctly when showPopover is true', () => {
			const props = {
				...defaultProps,
				showPopover: true,
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'renders correctly when showPopover is false', () => {
			const props = {
				...defaultProps,
				showPopover: false,
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'date selection', () => {
		it.each( [
			[ 'start', defaultProps.startDate, '21', '2023-12-21T10:00:00' ],
			[ 'end', defaultProps.endDate, '25', '2023-12-25T13:00:00' ],
		] )(
			'handles picking new %s date',
			async ( isSelectingDate: string, currentDate: Date, selectionText: string, expected: string ) => {
				const user = userEvent.setup();
				const props = {
					...defaultProps,
					// The user is selecting the end date.
					isSelectingDate,
					// Show the popover, we start from the state where the user has clicked the date picker to pick a date.
					showPopover: true,
					onChange: jest.fn(),
					onClick: jest.fn(),
					onClose: jest.fn(),
					currentDate,
				} as DatePickerProps;

				const { getByText } = render( <DatePicker { ...props } /> );

				// The user picks a new end date: 2023-12-25.
				await user.click( getByText( selectionText ) );

				expect( props.onChange ).toHaveBeenCalledWith( isSelectingDate, expected );
				expect( props.onClick ).not.toHaveBeenCalled();
				expect( props.onClose ).not.toHaveBeenCalled();
			}
		);

		it.each( [ 'start', 'end' ] )(
			'handles closing the date selection modal while selecting %s',
			async ( isSelectingDate: string ) => {
				const user = userEvent.setup();
				const baseElement = document.createElement( 'div' );
				const props = {
					...defaultProps,
					anchor: baseElement,
					// The user is selecting the start date.
					isSelectingDate,
					// Show the popover, we start from the state where the user has clicked the date picker to pick a date.
					showPopover: true,
					onChange: jest.fn(),
					onClick: jest.fn(),
					onClose: jest.fn(),
				} as DatePickerProps;

				const { asFragment } = render( <DatePicker { ...props } /> );

				// Select the popover element from the document, it will not be attached to the component.
				const popover = document.body.querySelector( '.classy-component__popover--calendar' );

				expect( popover ).not.toBeNull();

				// The user presses Escape to close the modal.
				await keyDownEscape( popover );

				expect( props.onChange ).not.toHaveBeenCalled();
				expect( props.onClick ).not.toHaveBeenCalled();
				expect( props.onClose ).toHaveBeenCalledTimes( 1 );
			}
		);
	} );
} );
