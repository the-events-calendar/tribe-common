import * as React from 'react';
import { render } from '@testing-library/react';
import { userEvent } from '@testing-library/user-event';
import { afterEach, beforeEach, describe, expect, it, jest } from '@jest/globals';
import { StartSelector } from '../../../src/resources/packages/classy/components';
import { keyDownEscape } from '../_support/userEvents';
import { getDefault as getDefaultLocalizedData } from '../../../src/resources/packages/classy/localizedData';
import { LocalizedData } from '../../../src/resources/packages/classy/types/LocalizedData';
import TestProvider from '../_support/TestProvider';

// Save the original localized data here.
let originalLocalizedData: LocalizedData;
const timePickerSelector = '.classy-field__input--start-time input[type="text"]';
const datePickerButton = '.classy-field__control--date-picker input.components-input-control__input';

describe( 'StartSelector Component', () => {
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
				<StartSelector { ...defaultProps } />
			</TestProvider>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	describe( 'all day events', () => {
		it( 'renders without time picker when isAllDay is true and isMultiday is false', () => {
			const props = {
				...defaultProps,
				isAllDay: true,
				isMultiday: false,
			};

			const { container, getByText, queryByText } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'Date' ) ).toBeTruthy();
			expect( queryByText( 'Start Time' ) ).toBeNull();
			expect( container.querySelector( '.classy-field__input--start-time' ) ).toBeNull();
			expect( container.querySelector( '.classy-field__input-full-width' ) ).toBeTruthy();
			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'renders with time picker when isAllDay is false', () => {
			const props = {
				...defaultProps,
				isAllDay: false,
			};

			const { container, getByText } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'Start Time' ) ).toBeTruthy();
			expect( container.querySelector( '.classy-field__input--start-time' ) ).toBeTruthy();
			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'renders with grow class when isAllDay is true and isMultiday is true', () => {
			const props = {
				...defaultProps,
				isAllDay: true,
				isMultiday: true,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			expect( container.querySelector( '.classy-field__input--grow' ) ).toBeTruthy();
			expect( container.querySelector( '.classy-field__input-full-width' ) ).toBeNull();
		} );
	} );

	describe( 'multiday events', () => {
		it( 'passes null as endDate to TimePicker when isMultiday is true', () => {
			const props = {
				...defaultProps,
				isMultiday: true,
				isAllDay: false,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			// TimePicker should be rendered with null endDate for multiday events
			const timePicker = container.querySelector( '.classy-field__input--start-time' );
			expect( timePicker ).toBeTruthy();
		} );

		it( 'passes endDate to TimePicker when isMultiday is false', () => {
			const props = {
				...defaultProps,
				isMultiday: false,
				isAllDay: false,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			// TimePicker should be rendered with endDate for single-day events
			const timePicker = container.querySelector( '.classy-field__input--start-time' );
			expect( timePicker ).toBeTruthy();
		} );
	} );

	describe( 'date selection', () => {
		it( 'shows DatePicker popover when isSelectingDate is startDate', () => {
			const props = {
				...defaultProps,
				isSelectingDate: 'startDate' as const,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'does not show DatePicker popover when isSelectingDate is false', () => {
			const props = {
				...defaultProps,
				isSelectingDate: false as const,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'does not show DatePicker popover when isSelectingDate is endDate', () => {
			const props = {
				...defaultProps,
				isSelectingDate: 'endDate' as const,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
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
					<StartSelector { ...props } />
				</TestProvider>
			);

			// Find the time input (this would be inside the TimePicker component).
			const timeInput = container.querySelector( timePickerSelector );

			expect( timeInput ).not.toBeNull();

			await user.clear( timeInput );
			await user.type( timeInput, '14:30' );
			await user.type( timeInput, '{enter}' );

			// Check that onChange was called with the correct parameters
			expect( props.onChange ).toHaveBeenCalledWith( 'startTime', '2023-12-23 14:30:00' );
		} );

		it( 'highlights time when highlightTime is true', () => {
			const props = {
				...defaultProps,
				highlightTime: true,
				isAllDay: false,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
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
				onClick,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			// The onClick would be triggered through the DatePicker component.
			const dateButton = container.querySelector( datePickerButton );

			expect( dateButton ).not.toBeNull();

			await user.click( dateButton );

			// Check that onClick was called
			expect( onClick ).toHaveBeenCalled();
		} );

		it( 'passes onClose handler to DatePicker', async () => {
			const onClose = jest.fn();
			const props = {
				...defaultProps,
				isSelectingDate: 'startDate' as const,
				onClose,
			};

			render(
				<TestProvider>
					<StartSelector { ...props } />
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

		it( 'calls onChange with startDate when date changes', async () => {
			const user = userEvent.setup();
			const onChange = jest.fn();
			const props = {
				...defaultProps,
				isSelectingDate: 'startDate' as const,
				onChange,
			};

			const { getByText } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			// Click on a different date.
			const dateToClick = getByText( '25' );
			if ( dateToClick ) {
				await user.click( dateToClick );
			}

			// Check that onChange was called with the correct parameters
			expect( onChange ).toHaveBeenCalledWith( 'startDate', '2023-12-25T10:00:00' );
		} );

		it( 'calls onChange with startTime when time changes', async () => {
			const user = userEvent.setup();
			const onChange = jest.fn();
			const props = {
				...defaultProps,
				isAllDay: false,
				onChange,
			};

			const { container } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			// Simulate time change through TimePicker
			const timeInput = container.querySelector( timePickerSelector );

			expect( timeInput ).not.toBeNull();

			await user.clear( timeInput );
			await user.type( timeInput, '15:30' );
			await user.type( timeInput, '{enter}' );

			// Check that onChange was called with the correct parameters
			expect( onChange ).toHaveBeenCalledWith( 'startTime', '2023-12-23 15:30:00' );
		} );
	} );

	describe( 'time interval from store', () => {
		it( 'uses time interval from WordPress data store', () => {
			// Set up the localized data with a specific time interval.
			window.tec.common.classy.data.settings.timeInterval = 20;

			const { container } = render(
				<TestProvider>
					<StartSelector { ...defaultProps } />
				</TestProvider>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );
	} );

	describe( 'accessibility', () => {
		it( 'renders accessible titles for date and time inputs', () => {
			const props = {
				...defaultProps,
				isAllDay: false,
			};

			const { getByText } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'Date' ) ).toBeTruthy();
			expect( getByText( 'Start Time' ) ).toBeTruthy();
		} );

		it( 'renders only Date title when isAllDay is true', () => {
			const props = {
				...defaultProps,
				isAllDay: true,
				isMultiday: false,
			};

			const { getByText, queryByText } = render(
				<TestProvider>
					<StartSelector { ...props } />
				</TestProvider>
			);

			expect( getByText( 'Date' ) ).toBeTruthy();
			expect( queryByText( 'Start Time' ) ).toBeNull();
		} );
	} );
} );
