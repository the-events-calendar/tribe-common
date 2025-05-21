import * as React from 'react';
import { render } from '@testing-library/react';
import { describe, expect, it, jest } from '@jest/globals';
import { DatePicker } from '../../../src/resources/packages/classy/components';
import { DatePickerProps } from '../../../src/resources/packages/classy/components/DatePicker/DatePicker';

describe( 'DatePicker Component', () => {
	const defaultProps = {
		anchor: document.createElement( 'div' ),
		dateWithYearFormat: 'Y-m-d',
		endDate: new Date(),
		isSelectingDate: false,
		isMultiday: false,
		onChange: jest.fn(),
		onClick: jest.fn(),
		onClose: jest.fn(),
		onFocusOutside: jest.fn(),
		showPopover: false,
		startDate: new Date(),
		startOfWeek: 0,
		currentDate: new Date(),
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
				startDate: new Date( new Date().setHours( 10 ) ),
				endDate: new Date( new Date().setHours( 12 ) ),
				currentDate: new Date( new Date().setHours( 10 ) ),
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'selecting end date', () => {
			const props = {
				...defaultProps,
				isSelectingDate: 'end',
				startDate: new Date( new Date().setHours( 10 ) ),
				endDate: new Date( new Date().setHours( 12 ) ),
				currentDate: new Date( new Date().setHours( 12 ) ),
			} as DatePickerProps;

			const { container } = render( <DatePicker { ...props } /> );

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'selecting neither start nor end date', () => {
			const props = {
				...defaultProps,
				isSelectingDate: false,
				startDate: new Date( new Date().setHours( 10 ) ),
				endDate: new Date( new Date().setHours( 12 ) ),
				currentDate: new Date( new Date().setHours( 12 ) ),
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
} );
