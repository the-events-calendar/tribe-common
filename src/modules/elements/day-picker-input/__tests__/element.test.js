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
} );
