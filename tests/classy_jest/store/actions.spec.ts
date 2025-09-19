// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import { describe, expect, it } from '@jest/globals';
import actions, {
	SET_COUNTRY_OPTIONS,
	SET_CURRENCY_OPTIONS,
	SET_US_STATE_OPTIONS,
} from '../../../src/resources/packages/classy/store/actions';

describe( 'Store Actions', () => {
	describe( 'setCountryOptions', () => {
		it( 'creates SET_COUNTRY_OPTIONS action with correct payload', () => {
			const options = [
				{ value: 'US', label: 'United States' },
				{ value: 'CA', label: 'Canada' },
				{ value: 'MX', label: 'Mexico' },
			];

			const action = actions.setCountryOptions( options );

			expect( action ).toEqual( {
				type: SET_COUNTRY_OPTIONS,
				options,
			} );
		} );

		it( 'handles empty options array', () => {
			const options = [];
			const action = actions.setCountryOptions( options );

			expect( action ).toEqual( {
				type: SET_COUNTRY_OPTIONS,
				options: [],
			} );
		} );

		it( 'handles null options', () => {
			const action = actions.setCountryOptions( null );

			expect( action ).toEqual( {
				type: SET_COUNTRY_OPTIONS,
				options: null,
			} );
		} );

		it( 'handles undefined options', () => {
			const action = actions.setCountryOptions( undefined );

			expect( action ).toEqual( {
				type: SET_COUNTRY_OPTIONS,
				options: undefined,
			} );
		} );
	} );

	describe( 'setUsStateOptions', () => {
		it( 'creates SET_US_STATE_OPTIONS action with correct payload', () => {
			const options = [
				{ value: 'CA', label: 'California' },
				{ value: 'TX', label: 'Texas' },
				{ value: 'NY', label: 'New York' },
			];

			const action = actions.setUsStateOptions( options );

			expect( action ).toEqual( {
				type: SET_US_STATE_OPTIONS,
				options,
			} );
		} );

		it( 'handles empty options array', () => {
			const options = [];
			const action = actions.setUsStateOptions( options );

			expect( action ).toEqual( {
				type: SET_US_STATE_OPTIONS,
				options: [],
			} );
		} );

		it( 'handles complex state data', () => {
			const options = [
				{ value: 'CA', label: 'California', abbreviation: 'CA', capital: 'Sacramento' },
				{ value: 'TX', label: 'Texas', abbreviation: 'TX', capital: 'Austin' },
			];

			const action = actions.setUsStateOptions( options );

			expect( action ).toEqual( {
				type: SET_US_STATE_OPTIONS,
				options,
			} );
		} );
	} );

	describe( 'setCurrencyOptions', () => {
		it( 'creates SET_CURRENCY_OPTIONS action with correct payload', () => {
			const options = [
				{ code: 'USD', symbol: '$', position: 'prefix', label: 'US Dollar' },
				{ code: 'EUR', symbol: '€', position: 'postfix', label: 'Euro' },
				{ code: 'GBP', symbol: '£', position: 'prefix', label: 'British Pound' },
			];

			const action = actions.setCurrencyOptions( options );

			expect( action ).toEqual( {
				type: SET_CURRENCY_OPTIONS,
				options,
			} );
		} );

		it( 'handles empty currency options', () => {
			const options = [];
			const action = actions.setCurrencyOptions( options );

			expect( action ).toEqual( {
				type: SET_CURRENCY_OPTIONS,
				options: [],
			} );
		} );

		it( 'handles currency options with additional properties', () => {
			const options = [
				{
					code: 'USD',
					symbol: '$',
					position: 'prefix',
					label: 'US Dollar',
					precision: 2,
					thousandSeparator: ',',
					decimalSeparator: '.',
				},
			];

			const action = actions.setCurrencyOptions( options );

			expect( action ).toEqual( {
				type: SET_CURRENCY_OPTIONS,
				options,
			} );
		} );

		it( 'handles any type of options (as per the type signature)', () => {
			const options = 'string-options';
			const action = actions.setCurrencyOptions( options );

			expect( action ).toEqual( {
				type: SET_CURRENCY_OPTIONS,
				options: 'string-options',
			} );
		} );
	} );
} );
