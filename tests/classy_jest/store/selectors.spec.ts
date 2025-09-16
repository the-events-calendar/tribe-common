// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import { describe, expect, it, jest, beforeEach, afterEach } from '@jest/globals';
import {
	getSettings,
	getTimeInterval,
	getCountryOptions,
	getUsStatesOptions,
	getCurrencyOptions,
	getDefaultCurrency,
} from '../../../src/resources/packages/classy/store/selectors';
import { StoreState } from '../../../src/resources/packages/classy/types/Store';
import { Currency } from '../../../src/resources/packages/classy/types/Currency';
import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';
import { LocalizedData } from '../../../src/resources/packages/classy/types/LocalizedData';

describe( 'Store Selectors', () => {
	let originalLocalizedData: LocalizedData;

	const mockLocalizedSettings = {
		defaultCurrency: {
			code: 'USD',
			symbol: '$',
			position: 'prefix' as const,
			label: 'US Dollar',
		},
		timezoneString: 'UTC',
		timezoneChoice: '<select><optgroup label="UTC"><option value="UTC">UTC</option></optgroup></select>',
		startOfWeek: 0 as const,
		endOfDayCutoff: {
			hours: 0,
			minutes: 0,
		},
		dateWithYearFormat: 'F j, Y',
		dateWithoutYearFormat: 'F j',
		monthAndYearFormat: 'F Y',
		compactDateFormat: 'n/j/Y',
		dataTimeSeparator: ' @ ',
		timeRangeSeparator: ' - ',
		timeFormat: 'g:i A',
		timeInterval: 30,
	};

	const mockCountryOptions: CustomSelectOption[] = [
		{ key: 'US', value: 'US', name: 'United States' },
		{ key: 'CA', value: 'CA', name: 'Canada' },
		{ key: 'MX', value: 'MX', name: 'Mexico' },
	];

	const mockUsStatesOptions: CustomSelectOption[] = [
		{ key: 'AL', value: 'AL', name: 'Alabama' },
		{ key: 'AK', value: 'AK', name: 'Alaska' },
		{ key: 'AZ', value: 'AZ', name: 'Arizona' },
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
		{
			code: 'GBP',
			symbol: '£',
			position: 'prefix' as const,
			label: 'British Pound',
		},
	];

	const mockState: StoreState = {
		settings: mockLocalizedSettings,
		options: {
			country: mockCountryOptions,
			usStates: mockUsStatesOptions,
			currencies: mockCurrencyOptions,
		},
	};

	const emptyState: StoreState = {
		settings: mockLocalizedSettings,
		options: {
			country: [],
			usStates: [],
			currencies: [],
		},
	};

	beforeEach( () => {
		jest.clearAllMocks();

		// Save original localized data
		originalLocalizedData = window.tec?.common?.classy?.data;

		// Set up the global window object with test data
		window.tec = window.tec || {};
		window.tec.common = window.tec.common || {};
		window.tec.common.classy = window.tec.common.classy || {};
		window.tec.common.classy.data = {
			settings: mockLocalizedSettings,
		};
	} );

	afterEach( () => {
		// Restore original localized data
		if ( originalLocalizedData ) {
			window.tec.common.classy.data = originalLocalizedData;
		}
	} );

	describe( 'getSettings', () => {
		it( 'returns the current Classy settings', () => {
			const settings = getSettings();

			// Test against actual values from jest.setup.ts
			expect( settings.timeInterval ).toBe( 15 );
			expect( settings.defaultCurrency ).toEqual( {
				code: 'USD',
				symbol: '$',
				position: 'prefix',
			} );
			expect( settings.startOfWeek ).toBe( 0 );
			expect( settings.dateWithYearFormat ).toBe( 'F j, Y' );
			expect( settings.timezoneString ).toBe( 'UTC' );
		} );
	} );

	describe( 'getTimeInterval', () => {
		it( 'returns the time interval in minutes', () => {
			const interval = getTimeInterval();
			// Test against actual value from jest.setup.ts
			expect( interval ).toBe( 15 );
		} );
	} );

	describe( 'getCountryOptions', () => {
		it( 'returns country options from state', () => {
			const options = getCountryOptions( mockState );
			expect( options ).toEqual( mockCountryOptions );
			expect( options ).toHaveLength( 3 );
			expect( options[ 0 ] ).toEqual( {
				key: 'US',
				value: 'US',
				name: 'United States',
			} );
		} );

		it( 'returns empty array when no country options in state', () => {
			const options = getCountryOptions( emptyState );
			expect( options ).toEqual( [] );
		} );

		it( 'returns empty array when state is undefined', () => {
			const options = getCountryOptions( undefined as unknown as StoreState );
			expect( options ).toEqual( [] );
		} );

		it( 'returns empty array when options is undefined', () => {
			const stateWithoutOptions = {
				settings: mockLocalizedSettings,
			} as StoreState;
			const options = getCountryOptions( stateWithoutOptions );
			expect( options ).toEqual( [] );
		} );
	} );

	describe( 'getUsStatesOptions', () => {
		it( 'returns US states options from state', () => {
			const options = getUsStatesOptions( mockState );
			expect( options ).toEqual( mockUsStatesOptions );
			expect( options ).toHaveLength( 3 );
			expect( options[ 0 ] ).toEqual( {
				key: 'AL',
				value: 'AL',
				name: 'Alabama',
			} );
		} );

		it( 'returns empty array when no US states options in state', () => {
			const options = getUsStatesOptions( emptyState );
			expect( options ).toEqual( [] );
		} );

		it( 'returns empty array when state is undefined', () => {
			const options = getUsStatesOptions( undefined as unknown as StoreState );
			expect( options ).toEqual( [] );
		} );

		it( 'returns empty array when options is undefined', () => {
			const stateWithoutOptions = {
				settings: mockLocalizedSettings,
			} as StoreState;
			const options = getUsStatesOptions( stateWithoutOptions );
			expect( options ).toEqual( [] );
		} );
	} );

	describe( 'getCurrencyOptions', () => {
		it( 'returns currency options from state', () => {
			const options = getCurrencyOptions( mockState );
			expect( options ).toEqual( mockCurrencyOptions );
			expect( options ).toHaveLength( 3 );
			expect( options[ 0 ] ).toEqual( {
				code: 'USD',
				symbol: '$',
				position: 'prefix',
				label: 'US Dollar',
			} );
		} );

		it( 'returns empty array when no currency options in state', () => {
			const options = getCurrencyOptions( emptyState );
			expect( options ).toEqual( [] );
		} );

		it( 'returns empty array when state is undefined', () => {
			const options = getCurrencyOptions( undefined as unknown as StoreState );
			expect( options ).toEqual( [] );
		} );

		it( 'returns empty array when options is undefined', () => {
			const stateWithoutOptions = {
				settings: mockLocalizedSettings,
			} as StoreState;
			const options = getCurrencyOptions( stateWithoutOptions );
			expect( options ).toEqual( [] );
		} );
	} );

	describe( 'getDefaultCurrency', () => {
		it( 'returns the default currency from settings', () => {
			const currency = getDefaultCurrency();

			// Test against actual values from jest.setup.ts (no label property)
			expect( currency ).toEqual( {
				code: 'USD',
				symbol: '$',
				position: 'prefix',
			} );
		} );
	} );
} );
