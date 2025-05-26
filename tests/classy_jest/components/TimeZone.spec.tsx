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

	it('renders correctly with default props', () => {
		const {container} = render(<TimeZone{...defaultProps} />);

		expect(container).toMatchSnapshot();
	});

	it('allows selecting a new timezone', async () => {
		const user = userEvent.setup();
		const {container, getByText, asFragment} = render(<TimeZone{...defaultProps} />);

		const timezoneButton = container.querySelector('.classy-field__timezone-value');

		expect(timezoneButton).not.toBeNull();

		// To start, the popover for the timezone selection should not be there.
		expect(container.querySelector('.classy-component__popover--timezone')).toBeNull();

		await userEvent.click(timezoneButton);

		const timezoneSelectionPopoverOpenRender = asFragment();

		expect(timezoneSelectionPopoverOpenRender).toMatchSnapshot('timezone selection popover open');

		// The timezone selection popover should be open now.
		const timezoneSelectionPopover = timezoneSelectionPopoverOpenRender.querySelector('.classy-component__popover--timezone');
		expect(timezoneSelectionPopover).not.toBeNull();

		// Pick Africa/Abidjan from the list of timezones.
		await user.click(getByText('Abidjan'));
	});
});
