import * as React from 'react';
import { render, screen, waitFor } from '@testing-library/react';
import { userEvent } from '@testing-library/user-event';
import { beforeEach, describe, expect, it, jest } from '@jest/globals';
import { TimeZone } from '../../../src/resources/packages/classy/components';

describe( 'TimeZone Component', () => {
	const defaultProps = {
		onTimezoneChange: jest.fn(),
		timezone: 'Europe/Paris',
	};

	const popoverSelector = '.classy-component__popover--timezone';
	// The `is-positioned` class is added when the popover is positioned, showing and transitions are completed.
	const positionedPopoverSelector = `${ popoverSelector }.is-positioned`;
	const timezoneButtonSelector = '.is-link.classy-field__timezone-value';

	beforeEach( () => {
		jest.resetModules();
		jest.clearAllMocks();
	} );

	it( 'renders correctly with default props', async () => {
		const user = userEvent.setup();
		const { container } = render( <TimeZone { ...defaultProps } /> );

		// To start, there should not be a timezone selection popover open.
		let popover = document.querySelector( popoverSelector );
		expect( popover ).toBeNull();

		expect( container ).toMatchSnapshot();

		const button = container.querySelector( timezoneButtonSelector ) as Element;
		expect( button ).not.toBeNull();

		// Click the timezone selection button to open the timezone selection popover.
		await user.click( button );

		// Wait for the popover to be positioned.
		await waitFor( () => {
			expect( document.querySelector( positionedPopoverSelector ) ).not.toBeNull();
		} );

		popover = document.querySelector( positionedPopoverSelector ) as Element;
		expect( popover ).not.toBeNull();
		expect( popover ).toMatchSnapshot();
	} );

	it( 'renders the UTC timezone choice if the timezone string is UTC', async () => {
		const user = userEvent.setup();
		const { container } = render( <TimeZone { ...defaultProps } timezone="UTC+1" /> );

		// To start, there should not be a timezone selection popover open.
		let popover = document.querySelector( popoverSelector );
		expect( popover ).toBeNull();

		expect( container ).toMatchSnapshot();

		const button = container.querySelector( timezoneButtonSelector ) as Element;
		expect( button ).not.toBeNull();

		// Click the timezone selection button to open the timezone selection popover.
		await user.click( button );

		// Wait for the popover to be positioned.
		await waitFor( () => {
			expect( document.querySelector( positionedPopoverSelector ) ).not.toBeNull();
		} );

		popover = document.querySelector( positionedPopoverSelector ) as Element;
		expect( popover ).not.toBeNull();
		expect( popover ).toMatchSnapshot();
	} );

	it( 'allows selecting a new timezone', async () => {
		const user = userEvent.setup();
		const { container, asFragment } = render( <TimeZone { ...defaultProps } /> );

		// To start, there should not be a timezone selection popover open.
		let popover = document.querySelector( popoverSelector );
		expect( popover ).toBeNull();

		expect( container ).toMatchSnapshot();

		const button = container.querySelector( timezoneButtonSelector );
		expect( button ).toBeDefined();

		// Click the timezone selection button to open the timezone selection popover.
		await user.click( button as Element );

		// Wait for the popover to be positioned.
		await waitFor( () => {
			expect( document.querySelector( positionedPopoverSelector ) ).not.toBeNull();
		} );

		// In the popover select Abidjan (Africa/Abidjan).
		popover = document.querySelector( popoverSelector );
		const timezoneOptions = ( popover as Element ).querySelector(
			'.classy-component__popover-input--timezone select'
		) as HTMLSelectElement;

		expect( timezoneOptions ).toBeDefined();
		// There should be an Africa/Abidjan option in the select element.
		expect( timezoneOptions.querySelector( 'option[value="Africa/Abidjan"]' ) ).not.toBeNull();

		await user.selectOptions( timezoneOptions, [ 'Africa/Abidjan' ] );

		// The popover should have closed and the new timezone should have been selected.
		popover = document.querySelector( popoverSelector );
		expect( popover ).toBeNull();
		expect( defaultProps.onTimezoneChange ).toHaveBeenCalledWith( 'Africa/Abidjan' );
	} );
} );
