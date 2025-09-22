// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import { describe, expect, it, jest, beforeEach } from '@jest/globals';
import { reducer } from '@tec/common/classy/store/reducer';
import { SET_COUNTRY_OPTIONS, SET_CURRENCY_OPTIONS, SET_US_STATE_OPTIONS } from '@tec/common/classy/store/actions';
import { StoreState } from '@tec/common/classy/types/Store';
import { Currency } from '@tec/common/classy/types/Currency';
import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';
import { getDefault } from '@tec/common/classy/localizedData';

describe( 'Store Reducer', () => {
	// Get default settings from the actual getDefault function
	const defaultLocalizedData = getDefault();
	const defaultSettings = defaultLocalizedData.settings;

	const mockCountryOptions: CustomSelectOption[] = [
		{ key: 'US', value: 'US', name: 'United States' },
		{ key: 'CA', value: 'CA', name: 'Canada' },
	];

	const mockUsStatesOptions: CustomSelectOption[] = [
		{ key: 'AL', value: 'AL', name: 'Alabama' },
		{ key: 'AK', value: 'AK', name: 'Alaska' },
	];

	const mockCurrencyOptions: Currency[] = [
		{
			code: 'USD',
			symbol: '$',
			position: 'prefix' as const,
			label: 'US Dollar',
		},
		{
			code: 'EUR',
			symbol: '€',
			position: 'postfix' as const,
			label: 'Euro',
		},
	];

	const defaultState: StoreState = {
		settings: defaultSettings,
		options: {
			country: [],
			currencies: [],
			usStates: [],
		},
	};

	beforeEach( () => {
		jest.clearAllMocks();
	} );

	describe( 'Default State', () => {
		it( 'returns default state when state is undefined', () => {
			const result = reducer( undefined, { type: 'UNKNOWN_ACTION' } as any );
			// Change the `timezoneChoice` from the comparison as it's mocked in the tests.
			result.settings.timezoneChoice = '';
			expect( result ).toEqual( defaultState );
		} );

		it( 'returns default state with correct structure', () => {
			const result = reducer( undefined, { type: 'UNKNOWN_ACTION' } as any );
			expect( result.settings ).toBeDefined();
			expect( result.settings.timeInterval ).toBe( 15 ); // From getDefault() in localizedData.ts
			expect( result.settings.defaultCurrency ).toEqual( {
				code: 'USD',
				symbol: '$',
				position: 'prefix',
			} );
			expect( result.settings.timezoneString ).toBe( 'UTC' );
			expect( result.settings.startOfWeek ).toBe( 0 );
			expect( result.options ).toBeDefined();
			expect( result.options.country ).toEqual( [] );
			expect( result.options.currencies ).toEqual( [] );
			expect( result.options.usStates ).toEqual( [] );
		} );
	} );

	describe( 'SET_COUNTRY_OPTIONS', () => {
		it( 'sets country options correctly', () => {
			const action = {
				type: SET_COUNTRY_OPTIONS,
				options: mockCountryOptions,
			};

			const result = reducer( defaultState, action );
			expect( result.options.country ).toEqual( mockCountryOptions );
			expect( result.options.currencies ).toEqual( [] );
			expect( result.options.usStates ).toEqual( [] );
			expect( result.settings ).toEqual( defaultSettings );
		} );

		it( 'replaces existing country options', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					...defaultState.options,
					country: [ { key: 'MX', value: 'MX', name: 'Mexico' } ],
				},
			};

			const action = {
				type: SET_COUNTRY_OPTIONS,
				options: mockCountryOptions,
			};

			const result = reducer( initialState, action );
			expect( result.options.country ).toEqual( mockCountryOptions );
			expect( result.options.country ).not.toContainEqual( {
				key: 'MX',
				value: 'MX',
				name: 'Mexico',
			} );
		} );

		it( 'preserves other options when setting country options', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					country: [],
					currencies: mockCurrencyOptions,
					usStates: mockUsStatesOptions,
				},
			};

			const action = {
				type: SET_COUNTRY_OPTIONS,
				options: mockCountryOptions,
			};

			const result = reducer( initialState, action );
			expect( result.options.country ).toEqual( mockCountryOptions );
			expect( result.options.currencies ).toEqual( mockCurrencyOptions );
			expect( result.options.usStates ).toEqual( mockUsStatesOptions );
		} );
	} );

	describe( 'SET_US_STATE_OPTIONS', () => {
		it( 'sets US state options correctly', () => {
			const action = {
				type: SET_US_STATE_OPTIONS,
				options: mockUsStatesOptions,
			};

			const result = reducer( defaultState, action );
			expect( result.options.usStates ).toEqual( mockUsStatesOptions );
			expect( result.options.country ).toEqual( [] );
			expect( result.options.currencies ).toEqual( [] );
			expect( result.settings ).toEqual( defaultSettings );
		} );

		it( 'replaces existing US state options', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					...defaultState.options,
					usStates: [ { key: 'CA', value: 'CA', name: 'California' } ],
				},
			};

			const action = {
				type: SET_US_STATE_OPTIONS,
				options: mockUsStatesOptions,
			};

			const result = reducer( initialState, action );
			expect( result.options.usStates ).toEqual( mockUsStatesOptions );
			expect( result.options.usStates ).not.toContainEqual( {
				key: 'CA',
				value: 'CA',
				name: 'California',
			} );
		} );

		it( 'preserves other options when setting US state options', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					country: mockCountryOptions,
					currencies: mockCurrencyOptions,
					usStates: [],
				},
			};

			const action = {
				type: SET_US_STATE_OPTIONS,
				options: mockUsStatesOptions,
			};

			const result = reducer( initialState, action );
			expect( result.options.usStates ).toEqual( mockUsStatesOptions );
			expect( result.options.country ).toEqual( mockCountryOptions );
			expect( result.options.currencies ).toEqual( mockCurrencyOptions );
		} );
	} );

	describe( 'SET_CURRENCY_OPTIONS', () => {
		it( 'sets currency options correctly', () => {
			const action = {
				type: SET_CURRENCY_OPTIONS,
				options: mockCurrencyOptions,
			};

			const result = reducer( defaultState, action );
			expect( result.options.currencies ).toEqual( mockCurrencyOptions );
			expect( result.options.country ).toEqual( [] );
			expect( result.options.usStates ).toEqual( [] );
			expect( result.settings ).toEqual( defaultSettings );
		} );

		it( 'replaces existing currency options', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					...defaultState.options,
					currencies: [
						{
							code: 'CAD',
							symbol: 'C$',
							position: 'prefix' as const,
							label: 'Canadian Dollar',
						},
					],
				},
			};

			const action = {
				type: SET_CURRENCY_OPTIONS,
				options: mockCurrencyOptions,
			};

			const result = reducer( initialState, action );
			expect( result.options.currencies ).toEqual( mockCurrencyOptions );
			expect( result.options.currencies ).not.toContainEqual( {
				code: 'CAD',
				symbol: 'C$',
				position: 'prefix',
				label: 'Canadian Dollar',
			} );
		} );

		it( 'preserves other options when setting currency options', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					country: mockCountryOptions,
					currencies: [],
					usStates: mockUsStatesOptions,
				},
			};

			const action = {
				type: SET_CURRENCY_OPTIONS,
				options: mockCurrencyOptions,
			};

			const result = reducer( initialState, action );
			expect( result.options.currencies ).toEqual( mockCurrencyOptions );
			expect( result.options.country ).toEqual( mockCountryOptions );
			expect( result.options.usStates ).toEqual( mockUsStatesOptions );
		} );
	} );

	describe( 'Unknown Actions', () => {
		it( 'returns current state for unknown action types', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					country: mockCountryOptions,
					currencies: mockCurrencyOptions,
					usStates: mockUsStatesOptions,
				},
			};

			const action = {
				type: 'UNKNOWN_ACTION_TYPE',
				payload: 'some data',
			} as any;

			const result = reducer( initialState, action );
			expect( result ).toEqual( initialState );
			expect( result ).toBe( initialState ); // Should return the same reference
		} );
	} );

	describe( 'State Immutability', () => {
		it( 'does not mutate the original state', () => {
			const initialState: StoreState = {
				...defaultState,
				options: {
					country: [],
					currencies: [],
					usStates: [],
				},
			};

			const originalState = JSON.parse( JSON.stringify( initialState ) );

			const action = {
				type: SET_COUNTRY_OPTIONS,
				options: mockCountryOptions,
			};

			reducer( initialState, action );
			expect( initialState ).toEqual( originalState );
		} );

		it( 'creates new state object on changes', () => {
			const initialState: StoreState = {
				...defaultState,
			};

			const action = {
				type: SET_COUNTRY_OPTIONS,
				options: mockCountryOptions,
			};

			const result = reducer( initialState, action );
			expect( result ).not.toBe( initialState );
			expect( result.options ).not.toBe( initialState.options );
		} );
	} );
} );
