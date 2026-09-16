/**
 * Internal dependencies
 */
import DayPickerInput from '../element.js';

describe( 'DayPickerInput element', () => {
	it( 'Should render the component', () => {
		const seriesEndsOnDate = 'September 7, 2019';
		const seriesEndsOnDateObj = new Date( seriesEndsOnDate );
		const component = renderer.create(
			<DayPickerInput
				value={ seriesEndsOnDate }
				format={ 'LL' }
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				dayPickerProps={ {
					modifiers: {
						start: seriesEndsOnDateObj,
						end: seriesEndsOnDateObj,
					},
				} }
				onDayChange={ jest.fn() }
			/>,
		);
		expect( component.toJSON() ).toMatchSnapshot();
	} );

	it( 'Should re-sync the input value when the value prop changes', () => {
		const firstValue = 'September 7, 2019';
		const secondValue = 'October 10, 2019';

		const component = renderer.create(
			<DayPickerInput
				value={ firstValue }
				format={ 'LL' }
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ jest.fn() }
			/>,
		);

		expect( component.toJSON().props.value ).toBe( firstValue );

		component.update(
			<DayPickerInput
				value={ secondValue }
				format={ 'LL' }
				formatDate={ jest.fn() }
				parseDate={ jest.fn() }
				onDayChange={ jest.fn() }
			/>,
		);

		expect( component.toJSON().props.value ).toBe( secondValue );
	} );
} );
