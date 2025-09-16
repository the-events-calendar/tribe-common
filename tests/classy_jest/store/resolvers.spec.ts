// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import { describe, expect, it, jest, beforeEach } from '@jest/globals';
import apiFetch from '@wordpress/api-fetch';
import resolvers from '../../../src/resources/packages/classy/store/resolvers';
import { Currency } from '../../../src/resources/packages/classy/types/Currency';

// Mock the @wordpress/api-fetch module
jest.mock( '@wordpress/api-fetch' );

describe( 'Store Resolvers', () => {
	const mockDispatch = {
		setCountryOptions: jest.fn(),
		setUsStateOptions: jest.fn(),
		setCurrencyOptions: jest.fn(),
	};

	beforeEach( () => {
		jest.clearAllMocks();
		( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockClear();
	} );

	describe( 'getCountryOptions', () => {
		it( 'fetches and dispatches country options successfully', async () => {
			const mockCountryData = [
				{ value: 'US', name: 'United States' },
				{ value: 'CA', name: 'Canada' },
				{ value: 'MX', name: 'Mexico' },
			];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockCountryData );

			const resolver = resolvers.getCountryOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( apiFetch ).toHaveBeenCalledWith( {
				path: '/tec/classy/v1/options/country',
				method: 'GET',
			} );

			expect( mockDispatch.setCountryOptions ).toHaveBeenCalledWith( [
				{ key: 'US', value: 'US', name: 'United States' },
				{ key: 'CA', value: 'CA', name: 'Canada' },
				{ key: 'MX', value: 'MX', name: 'Mexico' },
			] );
		} );

		it( 'filters out invalid country options', async () => {
			const mockCountryData = [
				{ value: 'US', name: 'United States' },
				{ invalid: 'data' }, // Invalid option
				{ value: null, name: 'Invalid' }, // Invalid value
				{ value: 'CA', name: null }, // Invalid name
				{ value: 'MX', name: 'Mexico' },
			];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockCountryData );

			const resolver = resolvers.getCountryOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( mockDispatch.setCountryOptions ).toHaveBeenCalledWith( [
				{ key: 'US', value: 'US', name: 'United States' },
				{ key: 'MX', value: 'MX', name: 'Mexico' },
			] );
		} );

		it( 'handles API errors gracefully', async () => {
			const mockError = new Error( 'Network error' );
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockRejectedValue( mockError );

			const resolver = resolvers.getCountryOptions();
			await expect( resolver( { dispatch: mockDispatch } ) ).rejects.toThrow(
				'Failed to fetch country options: Network error'
			);

			expect( mockDispatch.setCountryOptions ).not.toHaveBeenCalled();
		} );

		it( 'handles empty response', async () => {
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( [] );

			const resolver = resolvers.getCountryOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( mockDispatch.setCountryOptions ).toHaveBeenCalledWith( [] );
		} );

		it( 'converts non-string values to strings', async () => {
			const mockCountryData = [
				{ value: 1, name: 100 },
				{ value: 'US', name: 'United States' },
			];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockCountryData );

			const resolver = resolvers.getCountryOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( mockDispatch.setCountryOptions ).toHaveBeenCalledWith( [
				{ key: '1', value: '1', name: '100' },
				{ key: 'US', value: 'US', name: 'United States' },
			] );
		} );
	} );

	describe( 'getUsStatesOptions', () => {
		it( 'fetches and dispatches US states options successfully', async () => {
			const mockStatesData = [
				{ value: 'AL', name: 'Alabama' },
				{ value: 'AK', name: 'Alaska' },
				{ value: 'AZ', name: 'Arizona' },
			];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockStatesData );

			const resolver = resolvers.getUsStatesOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( apiFetch ).toHaveBeenCalledWith( {
				path: '/tec/classy/v1/options/us-states',
				method: 'GET',
			} );

			expect( mockDispatch.setUsStateOptions ).toHaveBeenCalledWith( [
				{ key: 'AL', value: 'AL', name: 'Alabama' },
				{ key: 'AK', value: 'AK', name: 'Alaska' },
				{ key: 'AZ', value: 'AZ', name: 'Arizona' },
			] );
		} );

		it( 'filters out invalid US states options', async () => {
			const mockStatesData = [
				{ value: 'AL', name: 'Alabama' },
				{}, // Invalid option
				{ value: '', name: 'Empty' }, // Empty value
				{ value: 'AK', name: '' }, // Empty name
				{ value: 'AZ', name: 'Arizona' },
			];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockStatesData );

			const resolver = resolvers.getUsStatesOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( mockDispatch.setUsStateOptions ).toHaveBeenCalledWith( [
				{ key: 'AL', value: 'AL', name: 'Alabama' },
				{ key: 'AZ', value: 'AZ', name: 'Arizona' },
			] );
		} );

		it( 'handles API errors gracefully', async () => {
			const mockError = new Error( 'Server error' );
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockRejectedValue( mockError );

			const resolver = resolvers.getUsStatesOptions();
			await expect( resolver( { dispatch: mockDispatch } ) ).rejects.toThrow(
				'Failed to fetch US states options: Server error'
			);

			expect( mockDispatch.setUsStateOptions ).not.toHaveBeenCalled();
		} );

		it( 'handles empty response', async () => {
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( [] );

			const resolver = resolvers.getUsStatesOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( mockDispatch.setUsStateOptions ).toHaveBeenCalledWith( [] );
		} );
	} );

	describe( 'getCurrencyOptions', () => {
		it( 'fetches and dispatches currency options successfully', async () => {
			const mockCurrencyData: Currency[] = [
				{ code: 'USD', symbol: '$', position: 'prefix', label: 'US Dollar' },
				{ code: 'EUR', symbol: '€', position: 'postfix', label: 'Euro' },
				{ code: 'GBP', symbol: '£', position: 'prefix', label: 'British Pound' },
			];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockCurrencyData );

			const resolver = resolvers.getCurrencyOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( apiFetch ).toHaveBeenCalledWith( {
				path: '/tec/classy/v1/options/currencies',
				method: 'GET',
			} );

			expect( mockDispatch.setCurrencyOptions ).toHaveBeenCalledWith( mockCurrencyData );
		} );

		it( 'filters out invalid currency options', async () => {
			const mockCurrencyData = [
				{ code: 'USD', symbol: '$', position: 'prefix', label: 'US Dollar' },
				{ code: 'EUR', symbol: '€' }, // Missing position
				{ code: 'GBP', position: 'prefix', label: 'British Pound' }, // Missing symbol
				{ symbol: '¥', position: 'prefix', label: 'Yen' }, // Missing code
				{}, // Empty object
				{ code: '', symbol: '$', position: 'prefix', label: 'Invalid' }, // Empty code
				{ code: 'CAD', symbol: '', position: 'prefix', label: 'Canadian' }, // Empty symbol
				{ code: 'AUD', symbol: 'A$', position: '', label: 'Australian' }, // Empty position
				{ code: 'JPY', symbol: '¥', position: 'prefix', label: 'Japanese Yen' },
			] as any[];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockCurrencyData );

			const resolver = resolvers.getCurrencyOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( mockDispatch.setCurrencyOptions ).toHaveBeenCalledWith( [
				{ code: 'USD', symbol: '$', position: 'prefix', label: 'US Dollar' },
				{ code: 'JPY', symbol: '¥', position: 'prefix', label: 'Japanese Yen' },
			] );
		} );

		it( 'handles API errors gracefully', async () => {
			const mockError = new Error( 'API timeout' );
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockRejectedValue( mockError );

			const resolver = resolvers.getCurrencyOptions();
			await expect( resolver( { dispatch: mockDispatch } ) ).rejects.toThrow(
				'Failed to fetch currency options: API timeout'
			);

			expect( mockDispatch.setCurrencyOptions ).not.toHaveBeenCalled();
		} );

		it( 'handles empty response', async () => {
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( [] );

			const resolver = resolvers.getCurrencyOptions();
			await resolver( { dispatch: mockDispatch } );

			expect( mockDispatch.setCurrencyOptions ).toHaveBeenCalledWith( [] );
		} );

		it( 'validates currency position values', async () => {
			const mockCurrencyData: Currency[] = [
				{ code: 'USD', symbol: '$', position: 'prefix', label: 'US Dollar' },
				{ code: 'EUR', symbol: '€', position: 'postfix', label: 'Euro' },
				{ code: 'JPY', symbol: '¥', position: 'invalid' as any, label: 'Japanese Yen' }, // Invalid position
			];

			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockResolvedValue( mockCurrencyData );

			const resolver = resolvers.getCurrencyOptions();
			await resolver( { dispatch: mockDispatch } );

			// The resolver doesn't validate position values, it just checks for existence
			// So all three should be included
			expect( mockDispatch.setCurrencyOptions ).toHaveBeenCalledWith( mockCurrencyData );
		} );
	} );

	describe( 'Error Messages', () => {
		it( 'provides descriptive error messages for country options', async () => {
			const mockError = new Error( 'Custom error' );
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockRejectedValue( mockError );

			const resolver = resolvers.getCountryOptions();
			try {
				await resolver( { dispatch: mockDispatch } );
			} catch ( error: any ) {
				expect( error.message ).toBe( 'Failed to fetch country options: Custom error' );
			}
		} );

		it( 'provides descriptive error messages for US states options', async () => {
			const mockError = new Error( 'State fetch failed' );
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockRejectedValue( mockError );

			const resolver = resolvers.getUsStatesOptions();
			try {
				await resolver( { dispatch: mockDispatch } );
			} catch ( error: any ) {
				expect( error.message ).toBe( 'Failed to fetch US states options: State fetch failed' );
			}
		} );

		it( 'provides descriptive error messages for currency options', async () => {
			const mockError = new Error( 'Currency API down' );
			( apiFetch as jest.MockedFunction< typeof apiFetch > ).mockRejectedValue( mockError );

			const resolver = resolvers.getCurrencyOptions();
			try {
				await resolver( { dispatch: mockDispatch } );
			} catch ( error: any ) {
				expect( error.message ).toBe( 'Failed to fetch currency options: Currency API down' );
			}
		} );
	} );
} );
