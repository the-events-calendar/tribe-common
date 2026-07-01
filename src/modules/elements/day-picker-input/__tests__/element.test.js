/**
 * Internal dependencies
 */
import React from 'react';
import renderer from 'react-test-renderer';
import DayPickerInput from '../element.js';

const buildDate = ( dateString ) => new Date( dateString );

describe( 'DayPickerInput element', () => {
	const july1 = buildDate( '2026-07-01T00:00:00Z' );
	const august15 = buildDate( '2026-08-15T00:00:00Z' );
	const september7 = buildDate( '2019-09-07T00:00:00Z' );

	it( 'Should render the component', () => {
		const component = renderer.create(
			<DayPickerInput
				value="September 7, 2019"
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ {
					modifiers: {
						start: september7,
						end: september7,
					},
				} }
				onDayChange={ jest.fn() }
			/>,
		);
		expect( component.toJSON() ).toMatchSnapshot();
	} );

	it( 'renders with empty value and no selected date highlighted', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ jest.fn() }
			/>,
		);
		const tree = component.toJSON();
		// Input should show empty value.
		const input = tree.children[0];
		expect( input.props.value ).toBe( '' );
	} );

	it( 'renders with a valid date value and displays it in the input', () => {
		const component = renderer.create(
			<DayPickerInput
				value="September 7, 2019"
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ jest.fn() }
			/>,
		);
		const tree = component.toJSON();
		// Input should show the formatted value.
		const input = tree.children[0];
		expect( input.props.value ).toBe( 'September 7, 2019' );
	} );

	it( 'passes dayPickerProps.disabledDays to DayPicker as disabled', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ {
					disabledDays: { before: july1 },
				} }
				onDayChange={ jest.fn() }
			/>,
		);

		// Open the calendar.
		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		expect( dayPicker ).toBeDefined();

		const disabledEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-disabled'
		)[0];

		expect( disabledEl ).toBeDefined();
		expect( disabledEl.children[0] ).toContain( '2026-07-01' );
	} );

	it( 'passes dayPickerProps.modifiers to DayPicker', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ {
					modifiers: { start: july1, end: august15 },
				} }
				onDayChange={ jest.fn() }
			/>,
		);

		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const modifiersEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-modifiers'
		)[0];

		expect( modifiersEl ).toBeDefined();
		expect( modifiersEl.children[0] ).toContain( 'start' );
		expect( modifiersEl.children[0] ).toContain( 'end' );
	} );

	it( 'passes dayPickerProps.fromMonth to DayPicker as startMonth', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ { fromMonth: july1 } }
				onDayChange={ jest.fn() }
			/>,
		);

		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const startMonthEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-startMonth'
		)[0];

		expect( startMonthEl ).toBeDefined();
		expect( startMonthEl.children[0] ).toContain( '2026-07-01' );
	} );

	it( 'passes dayPickerProps.toMonth to DayPicker as endMonth', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ { toMonth: august15 } }
				onDayChange={ jest.fn() }
			/>,
		);

		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const endMonthEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-endMonth'
		)[0];

		expect( endMonthEl ).toBeDefined();
		expect( endMonthEl.children[0] ).toContain( '2026-08-15' );
	} );

	it( 'uses dayPickerProps.month when no date is selected', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ { month: july1 } }
				onDayChange={ jest.fn() }
			/>,
		);

		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const monthEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-month'
		)[0];

		expect( monthEl ).toBeDefined();
		expect( monthEl.children[0] ).toContain( '2026-07-01' );
	} );

	it( 'prefers selectedDate over dayPickerProps.month for the displayed month', () => {
		const component = renderer.create(
			<DayPickerInput
				value="August 15, 2026"
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ { month: july1 } }
				onDayChange={ jest.fn() }
			/>,
		);

		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const monthEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-month'
		)[0];

		const selectedEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-selected'
		)[0];

		expect( monthEl ).toBeDefined();
		expect( selectedEl ).toBeDefined();
		// Month should reflect the selected date (August), not dayPickerProps.month (July).
		expect( monthEl.children[0] ).toContain( '2026-08-15' );
	} );

	it( 'falls back to current date for month when neither selectedDate nor dayPickerProps.month is set', () => {
		const before = new Date();
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ jest.fn() }
			/>,
		);
		const after = new Date();

		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const monthEl = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-month'
		)[0];

		expect( monthEl ).toBeDefined();
		// The month should be a timestamp near "now".
		const monthTimestamp = new Date( monthEl.children[0] ).getTime();
		expect( monthTimestamp ).toBeGreaterThanOrEqual( before.getTime() - 1000 );
		expect( monthTimestamp ).toBeLessThanOrEqual( after.getTime() + 1000 );
	} );

	it( 'toggles calendar visibility on input click', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ jest.fn() }
			/>,
		);

		// Calendar should not be visible initially.
		let tree = component.toJSON();
		expect( JSON.stringify( tree ) ).not.toContain( 'DayPicker-mock' );

		// Click to open.
		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		tree = component.toJSON();
		expect( JSON.stringify( tree ) ).toContain( 'DayPicker-mock' );

		// Click to close.
		renderer.act( () => {
			input.props.onClick();
		} );

		tree = component.toJSON();
		expect( JSON.stringify( tree ) ).not.toContain( 'DayPicker-mock' );
	} );

	it( 'calls onDayChange when a day is selected', () => {
		const onDayChange = jest.fn();
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ onDayChange }
			/>,
		);

		// Open the calendar.
		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		// Find the "Select" button in the mock and click it.
		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const selectBtn = dayPicker.findByType( 'button' );
		renderer.act( () => {
			selectBtn.props.onClick();
		} );

		expect( onDayChange ).toHaveBeenCalledTimes( 1 );
		expect( onDayChange.mock.calls[0][0] ).toBeInstanceOf( Date );
	} );

	it( 'syncs selectedDate when value prop changes after mount (React 17)', () => {
		const onDayChange = jest.fn();

		// Mount with empty value.
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ onDayChange }
			/>,
		);

		// Open the calendar.
		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		let dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		// No selected date when value is empty.
		let selectedEls = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-selected'
		);
		expect( selectedEls ).toHaveLength( 0 );

		// Update the value prop — simulates Redux arriving after mount.
		renderer.act( () => {
			component.update(
				<DayPickerInput
					value="September 7, 2019"
					format="LL"
					formatDate={ jest.fn() }
					parseDate={ jest.fn() }
					onDayChange={ onDayChange }
				/>,
			);
		} );

		dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		selectedEls = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-selected'
		);

		expect( selectedEls ).toHaveLength( 1 );
		expect( selectedEls[0].children[0] ).toContain( '2019-09-07' );
	} );

	it( 'clears selectedDate when value prop becomes empty', () => {
		const onDayChange = jest.fn();

		// Mount with a valid date value.
		const component = renderer.create(
			<DayPickerInput
				value="September 7, 2019"
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ onDayChange }
			/>,
		);

		// Open the calendar.
		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		let dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		// Should have selected date initially.
		let selectedEls = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-selected'
		);
		expect( selectedEls ).toHaveLength( 1 );

		// Clear the value prop.
		renderer.act( () => {
			component.update(
				<DayPickerInput
					value=""
					format="LL"
					formatDate={ jest.fn() }
					parseDate={ jest.fn() }
					onDayChange={ onDayChange }
				/>,
			);
		} );

		dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		selectedEls = dayPicker.findAll(
			( node ) => node.props.className === 'DayPicker-selected'
		);

		// Selected date should be cleared.
		expect( selectedEls ).toHaveLength( 0 );
	} );

	it( 'calls onDayChange with formatted date string when day selected', () => {
		const onDayChange = jest.fn();
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ onDayChange }
			/>,
		);

		const input = component.root.findByType( 'input' );
		renderer.act( () => {
			input.props.onClick();
		} );

		const dayPicker = component.root.findAll(
			( node ) => node.props?.['data-testid'] === 'day-picker'
		)[0];

		const selectBtn = dayPicker.findByType( 'button' );
		renderer.act( () => {
			selectBtn.props.onClick();
		} );

		// The third argument should be the formatted date string.
		const formattedValue = onDayChange.mock.calls[0][2];
		expect( typeof formattedValue ).toBe( 'string' );
		expect( formattedValue.length ).toBeGreaterThan( 0 );
	} );

	it( 'ignores undefined or null dayPickerProps gracefully', () => {
		const component = renderer.create(
			<DayPickerInput
				value=""
				format="LL"
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ null }
				onDayChange={ jest.fn() }
			/>,
		);

		// Should not throw.
		const input = component.root.findByType( 'input' );
		expect( () => {
			renderer.act( () => {
				input.props.onClick();
			} );
		} ).not.toThrow();

		const tree = component.toJSON();
		expect( JSON.stringify( tree ) ).toContain( 'DayPicker-mock' );
	} );
} );
