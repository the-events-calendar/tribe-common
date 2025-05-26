import * as React from 'react';
import {getByText, render} from '@testing-library/react';
import {userEvent} from '@testing-library/user-event';
import {beforeEach, describe, expect, it, jest} from '@jest/globals';
import {TimeZone} from '../../../src/resources/packages/classy/components';

describe('TimeZone Component', () => {
	const defaultProps = {
		onTimezoneChange: jest.fn(),
		timezone: 'Europe/Paris',
	};

	beforeEach(() => {
		jest.clearAllMocks()
	});

	it('renders correctly with default props', async () => {
		const user = userEvent.setup();
		const {container} = render(<TimeZone{...defaultProps} />);

		// To start, there should not be a timezone selection popover open.
		let popover = document.querySelector('.classy-component__popover--timezone');
		expect(popover).toBeNull();

		expect(container).toMatchSnapshot();

		const button = container.querySelector('.is-link.classy-field__timezone-value');
		expect(button).toBeDefined();

		// Click the timezone selection button to open the timezone selection popover.
		await user.click(button);

		// Grab the popover from the document.
		popover = document.querySelector('.classy-component__popover--timezone');

		expect(popover).toMatchSnapshot();
	});

	it('renders the UTC timezone choice if the timezone string is UTC', async ()=>{
		const user = userEvent.setup();
		const {container} = render(<TimeZone{...defaultProps} timezone='UTC+1' />);

		// To start, there should not be a timezone selection popover open.
		let popover = document.querySelector('.classy-component__popover--timezone');
		expect(popover).toBeNull();

		expect(container).toMatchSnapshot();

		const button = container.querySelector('.is-link.classy-field__timezone-value');
		expect(button).toBeDefined();

		// Click the timezone selection button to open the timezone selection popover.
		await user.click(button);

		// Grab the popover from the document.
		popover = document.querySelector('.classy-component__popover--timezone');

		expect(popover).toMatchSnapshot();
	})

	it('allows selecting a new timezone', async () => {
		const user = userEvent.setup();
		const {container, asFragment} = render(<TimeZone{...defaultProps} />);

		// To start, there should not be a timezone selection popover open.
		let popover = document.querySelector('.classy-component__popover--timezone');
		expect(popover).toBeNull();

		expect(container).toMatchSnapshot();

		const button = container.querySelector('.is-link.classy-field__timezone-value');
		expect(button).toBeDefined();

		// Click the timezone selection button to open the timezone selection popover.
		await user.click(button);

		// In the popover select Abidjan (Africa/Abidjan).
		popover = document.querySelector('.classy-component__popover--timezone');
		const timezoneOptions = popover.querySelector('.classy-component__popover-input--timezone select');
		await user.selectOptions(timezoneOptions, ['Africa/Abidjan']);

		// The popover should have closed and the new timezone should have been selected.
		expect(asFragment()).toMatchSnapshot();
	});
});
