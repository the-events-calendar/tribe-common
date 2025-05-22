import * as React from 'react';
import { fireEvent, render } from '@testing-library/react';
import { describe, expect, it, jest } from '@jest/globals';
import { DatePicker } from '../../../src/resources/packages/classy/components';
import { DatePickerProps } from '../../../src/resources/packages/classy/components/DatePicker/DatePicker';

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
		it( 'handles picking new start date', () => {
			const props = {
				...defaultProps,
				// The user is selecting the start date.
				isSelectingDate: 'start',
				// Show the popover, we start from the state where the user has clicked the date picker to pick a date.
				showPopover: true,
				onChange: jest.fn(),
				onClick: jest.fn(),
				onClose: jest.fn(),
			} as DatePickerProps;

			const { getByText } = render( <DatePicker { ...props } /> );

			// The user picks a new start date: 2023-12-21.
			fireEvent.click( getByText( '21' ) );

			expect( props.onChange ).toHaveBeenCalledWith( 'start', '2023-12-21T10:00:00' );
			expect( props.onClick ).not.toHaveBeenCalled();
			expect( props.onClose ).not.toHaveBeenCalled();
		} );

		it( 'handles closing the date selection modal', () => {
			const baseElement = document.createElement( 'div' );
			const props = {
				...defaultProps,
				anchor: baseElement,
				// The user is selecting the start date.
				isSelectingDate: 'start',
				// Show the popover, we start from the state where the user has clicked the date picker to pick a date.
				showPopover: true,
				onChange: jest.fn(),
				onClick: jest.fn(),
				onClose: jest.fn(),
			} as DatePickerProps;

			const { container, asFragment } = render( <DatePicker { ...props } /> );

			const initialRender = asFragment();

			// The user closes the modal by pressing Escape.
			const popover = document.body.querySelector( '.classy-component__popover--calendar' );

			const keyDownEscape = new KeyboardEvent( 'keydown', {
				key: 'Escape',
				code: 'Escape',
				keyCode: 27, // While deprecated, this is the property the dialog WordPress logic is actually using.
				bubbles: true,
				cancelable: true,
			} );

			// The user presses Escape to close the modal.
			// We're not using the fireEvent API as it will not dispatch correctly using the deprecated keyCode property.
			popover.dispatchEvent( keyDownEscape );

			expect( props.onChange ).not.toHaveBeenCalled();
			expect( props.onClick ).not.toHaveBeenCalled();
			expect( props.onClose ).toHaveBeenCalledTimes( 1 );
		} );
	} );
} );
