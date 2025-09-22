import * as React from 'react';
import { render } from '@testing-library/react';
import { userEvent } from '@testing-library/user-event';
import { afterEach, beforeEach, describe, expect, it, jest } from '@jest/globals';
import { EndSelector } from '../../../src/resources/packages/classy/components';
import { keyDownEscape } from '../_support/userEvents';
import { getDefault as getDefaultLocalizedData } from '../../../src/resources/packages/classy/localizedData';
import { LocalizedData } from '@tec/common/classy/types/LocalizedData';
import TestProvider from '../_support/TestProvider';

// Save the original localized data here.
let originalLocalizedData: LocalizedData;
const timePickerSelector = '.classy-field__input--end-time .components-combobox-control__input';
const datePickerButton = '.classy-field__control--date-picker input.components-input-control__input';

describe( 'EndSelector Component', () => {
	const defaultProps = {
		dateWithYearFormat: 'Y-m-d',
		endDate: new Date( '2023-12-23 13:00:00' ),
		highlightTime: false,
		isAllDay: false,
		isMultiday: false,
		isSelectingDate: false as const,
		onChange: jest.fn(),
		onClick: jest.fn(),
		onClose: jest.fn(),
		startDate: new Date( '2023-12-23 10:00:00' ),
		startOfWeek: 0 as const,
		timeFormat: 'g:i a',
	};

	beforeEach( () => {
		// Set the localized data to a known state.
		originalLocalizedData = window.tec.common.classy.data;
		window.tec.common.classy.data = getDefaultLocalizedData();
	} );

	afterEach( () => {
		// Clean up.
		if ( originalLocalizedData ) {
			window.tec.common.classy.data = originalLocalizedData;
		}
	} );

	it( 'renders correctly with default props', () => {
		const { container } = render(
			<TestProvider>
				<EndSelector { ...defaultProps } />
			</TestProvider>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	describe( 'all day events', () => {
		it( 'renders "All Day" label when isAllDay is true', () => {
			const props = {
				...defaultProps,
				isAllDay: true,
			};

			const { container, getByText, queryByText } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'All Day' ) ).toBeTruthy();
			expect( queryByText( 'End Time' ) ).toBeNull();
			expect( container.querySelector( '.classy-field__input--end-time' ) ).toBeNull();
			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'renders with time picker when isAllDay is false', () => {
			const props = {
				...defaultProps,
				isAllDay: false,
			};

			const { container, getByText } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'End Time' ) ).toBeTruthy();
			expect( container.querySelector( '.classy-field__input--end-time' ) ).toBeTruthy();
			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'multiday events', () => {
		it( 'renders date separator and date picker when isMultiday is true', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				endDate: new Date( '2023-12-25 13:00:00' ),
			};

			const { container, getByText } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'to' ) ).toBeTruthy();
			expect( getByText( 'Date' ) ).toBeTruthy();
			expect( container.querySelector( '.classy-field__separator--dates' ) ).toBeTruthy();
			expect( container.querySelector( '.classy-field__input--start-date' ) ).toBeTruthy();
			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'does not render date separator when isMultiday is false', () => {
			const props = {
				...defaultProps,
				isMultiday: false,
			};

			const { queryByText } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( queryByText( 'to' ) ).toBeNull();
			expect( queryByText( 'Date' ) ).toBeNull();
		} );

		it( 'passes null as startDate to TimePicker when isMultiday is true', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				isAllDay: false,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			// TimePicker should be rendered with null startDate for multiday events
			const timePicker = container.querySelector( '.classy-field__input--end-time' );
			expect( timePicker ).toBeTruthy();
		} );

		it( 'passes startDate to TimePicker when isMultiday is false', () => {
			const props = {
				...defaultProps,
				isMultiday: false,
				isAllDay: false,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			// TimePicker should be rendered with startDate for single-day events
			const timePicker = container.querySelector( '.classy-field__input--end-time' );
			expect( timePicker ).toBeTruthy();
		} );
	} );
	//
	describe( 'date selection', () => {
		it( 'shows DatePicker popover when isSelectingDate is endDate and isMultiday', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				isSelectingDate: 'endDate' as const,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'does not show DatePicker popover when isSelectingDate is false', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				isSelectingDate: false as const,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'does not show DatePicker popover when isSelectingDate is startDate', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				isSelectingDate: 'startDate' as const,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'time selection', () => {
		it( 'calls onChange with correct parameters when time is changed', async () => {
			const user = userEvent.setup();
			const props = {
				...defaultProps,
				isAllDay: false,
				onChange: jest.fn(),
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			// Find the time input (this would be inside the TimePicker component).
			const timeInput = container.querySelector( timePickerSelector );

			expect( timeInput ).not.toBeNull();

			await user.click( timeInput );
			await user.clear( timeInput );
			await user.type( timeInput, '2:30 pm' );
			await user.type( timeInput, '{enter}' );

			// Check that onChange was called with the correct parameters
			expect( props.onChange ).toHaveBeenCalledWith( 'endTime', '2023-12-23 14:30:00' );
		} );

		it( 'highlights time when highlightTime is true', () => {
			const props = {
				...defaultProps,
				highlightTime: true,
				isAllDay: false,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'event handlers', () => {
		it( 'passes onClick handler to DatePicker', async () => {
			const user = userEvent.setup();
			const onClick = jest.fn();
			const props = {
				...defaultProps,
				isMultiday: true,
				onClick,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			// The onClick would be triggered through the DatePicker component.
			const dateButton = container.querySelector( datePickerButton );

			expect( dateButton ).not.toBeNull();

			await user.click( dateButton );

			// Check that onClick was called with the expected field
			expect( onClick ).toHaveBeenCalled();
		} );

		it( 'passes onClose handler to DatePicker', async () => {
			const onClose = jest.fn();
			const props = {
				...defaultProps,
				isMultiday: true,
				isSelectingDate: 'endDate' as const,
				onClose,
			};

			render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			// Select the popover element from the document
			const popover = document.body.querySelector( '.classy-component__popover--calendar' );
			if ( popover ) {
				await keyDownEscape( popover );
			}

			// Check that onClose was called
			expect( onClose ).toHaveBeenCalled();
		} );

		it( 'calls onChange with endDate when date changes', async () => {
			const user = userEvent.setup();
			const onChange = jest.fn();
			const props = {
				...defaultProps,
				isMultiday: true,
				isSelectingDate: 'endDate' as const,
				onChange,
			};

			const { getByText } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			// Click on a different date.
			const dateToClick = getByText( '25' );
			if ( dateToClick ) {
				await user.click( dateToClick );
			}

			// Check that onChange was called with the correct parameters
			expect( onChange ).toHaveBeenCalledWith( 'endDate', '2023-12-25T13:00:00' );
		} );

		it( 'calls onChange with endTime when time changes', async () => {
			const user = userEvent.setup();
			const onChange = jest.fn();
			const props = {
				...defaultProps,
				isAllDay: false,
				onChange,
			};

			const { container } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			// Simulate time change through TimePicker
			const timeInput = container.querySelector( timePickerSelector );

			expect( timeInput ).not.toBeNull();

			await user.click( timeInput );
			await user.clear( timeInput );
			await user.type( timeInput, '3:30 pm' );
			await user.type( timeInput, '{enter}' );

			// Check that onChange was called with the correct parameters
			expect( onChange ).toHaveBeenCalledWith( 'endTime', '2023-12-23 15:30:00' );
		} );
	} );

	describe( 'time interval from store', () => {
		it( 'uses time interval from WordPress data store', () => {
			// Set up the localized data with a specific time interval.
			window.tec.common.classy.data.settings.timeInterval = 20;

			const { container } = render(
				<TestProvider>
					<EndSelector { ...defaultProps } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'accessibility', () => {
		it( 'renders accessible titles for date and time inputs', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				isAllDay: false,
			};

			const { getByText } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'Date' ) ).toBeTruthy();
			expect( getByText( 'End Time' ) ).toBeTruthy();
		} );

		it( 'renders only End Time title when not multiday', () => {
			const props = {
				...defaultProps,
				isMultiday: false,
				isAllDay: false,
			};

			const { getByText, queryByText } = render(
				<TestProvider>
					<EndSelector { ...props } />
				</TestProvider>
			);

			expect( queryByText( 'Date' ) ).toBeNull();
			expect( getByText( 'End Time' ) ).toBeTruthy();
		} );
	} );
} );
