import { describe, expect, it, beforeEach, afterEach } from '@jest/globals';
import {
	setDefaultCurrencyProps,
	formatCurrency,
	getDefaultCurrencyProps,
} from '@tec/common/classy/functions/currencyUtils';
import { CurrencyPosition } from '@tec/common/classy/types/CurrencyPosition';

describe( 'currencyUtils', () => {
	beforeEach( () => {
		// Reset to default currency props before each test
		setDefaultCurrencyProps( {
			decimalSeparator: '.',
			thousandSeparator: ',',
			precision: 2,
			symbol: '$',
			position: 'prefix',
		} );
	} );

	afterEach( () => {
		// Reset to default currency props after each test
		setDefaultCurrencyProps( {
			decimalSeparator: '.',
			thousandSeparator: ',',
			precision: 2,
			symbol: '$',
			position: 'prefix',
		} );
	} );

	describe( 'getDefaultCurrencyProps', () => {
		it( 'should return the current default currency properties', () => {
			const defaultProps = getDefaultCurrencyProps();

			expect( defaultProps ).toEqual( {
				decimalSeparator: '.',
				thousandSeparator: ',',
				precision: 2,
				symbol: '$',
				position: 'prefix',
			} );
		} );

		it( 'should return updated properties after setting new defaults', () => {
			setDefaultCurrencyProps( {
				symbol: '€',
				position: 'postfix' as CurrencyPosition,
				precision: 3,
			} );

			const defaultProps = getDefaultCurrencyProps();

			expect( defaultProps ).toEqual( {
				decimalSeparator: '.',
				thousandSeparator: ',',
				precision: 3,
				symbol: '€',
				position: 'postfix',
			} );
		} );

		it( 'should return a copy of the properties, not a reference', () => {
			const defaultProps1 = getDefaultCurrencyProps();
			const defaultProps2 = getDefaultCurrencyProps();

			expect( defaultProps1 ).not.toBe( defaultProps2 );
			expect( defaultProps1 ).toEqual( defaultProps2 );
		} );
	} );

	describe( 'setDefaultCurrencyProps', () => {
		it( 'should set default currency properties correctly', () => {
			const newProps = {
				symbol: '€',
				position: 'postfix' as CurrencyPosition,
				precision: 3,
			};

			expect( () => setDefaultCurrencyProps( newProps ) ).not.toThrow();

			const defaultProps = getDefaultCurrencyProps();
			expect( defaultProps.symbol ).toBe( '€' );
			expect( defaultProps.position ).toBe( 'postfix' );
			expect( defaultProps.precision ).toBe( 3 );
		} );

		it( 'should set decimal separator correctly', () => {
			expect( () => setDefaultCurrencyProps( { decimalSeparator: ',' } ) ).not.toThrow();

			const defaultProps = getDefaultCurrencyProps();
			expect( defaultProps.decimalSeparator ).toBe( ',' );
		} );

		it( 'should set thousand separator correctly', () => {
			expect( () => setDefaultCurrencyProps( { thousandSeparator: '.' } ) ).not.toThrow();

			const defaultProps = getDefaultCurrencyProps();
			expect( defaultProps.thousandSeparator ).toBe( '.' );

			expect( () => setDefaultCurrencyProps( { thousandSeparator: '_' } ) ).not.toThrow();
			expect( getDefaultCurrencyProps().thousandSeparator ).toBe( '_' );
		} );

		it( 'should throw error for invalid decimal separator length', () => {
			expect( () => setDefaultCurrencyProps( { decimalSeparator: '..' } ) ).toThrow(
				'Separator must be a single character. ".." is not valid.'
			);
		} );

		it( 'should throw error for invalid thousand separator length', () => {
			expect( () => setDefaultCurrencyProps( { thousandSeparator: ',,' } ) ).toThrow(
				'Separator must be a single character. ",," is not valid.'
			);
		} );

		it( 'should throw error for empty decimal separator', () => {
			const newProps = {
				decimalSeparator: '',
			};

			expect( () => setDefaultCurrencyProps( newProps ) ).toThrow(
				'Separator must be a single character. "" is not valid.'
			);
		} );

		it( 'should throw error for empty thousand separator', () => {
			const newProps = {
				thousandSeparator: '',
			};

			expect( () => setDefaultCurrencyProps( newProps ) ).toThrow(
				'Separator must be a single character. "" is not valid.'
			);
		} );

		it( 'should throw error for numeric decimal separator', () => {
			expect( () => setDefaultCurrencyProps( { decimalSeparator: '1' } ) ).toThrow(
				'Separator cannot be a number. "1" is not valid.'
			);
		} );

		it( 'should throw error for numeric thousand separator', () => {
			expect( () => setDefaultCurrencyProps( { thousandSeparator: '2' } ) ).toThrow(
				'Separator cannot be a number. "2" is not valid.'
			);
		} );

		it( 'should throw error when decimal and thousand separators are the same', () => {
			expect( () =>
				setDefaultCurrencyProps( {
					decimalSeparator: ',',
					thousandSeparator: ',',
				} )
			).toThrow( 'Decimal and thousand separators cannot be the same.' );
		} );

		it( 'should merge partial properties with existing defaults', () => {
			// First set some defaults
			setDefaultCurrencyProps( {
				symbol: '€',
				precision: 3,
			} );

			let defaultProps = getDefaultCurrencyProps();
			expect( defaultProps.symbol ).toBe( '€' );
			expect( defaultProps.precision ).toBe( 3 );

			// Then set additional properties
			setDefaultCurrencyProps( {
				position: 'postfix',
			} );

			defaultProps = getDefaultCurrencyProps();
			expect( defaultProps.symbol ).toBe( '€' ); // Should maintain previous setting
			expect( defaultProps.precision ).toBe( 3 ); // Should maintain previous setting
			expect( defaultProps.position ).toBe( 'postfix' ); // Should have new setting

			// Should not throw and should maintain previous settings
			expect( () => setDefaultCurrencyProps( { decimalSeparator: ',' } ) ).not.toThrow();

			defaultProps = getDefaultCurrencyProps();
			expect( defaultProps.symbol ).toBe( '€' ); // Should still maintain all previous settings
			expect( defaultProps.precision ).toBe( 3 );
			expect( defaultProps.position ).toBe( 'postfix' );
			expect( defaultProps.decimalSeparator ).toBe( ',' );
		} );
	} );

	describe( 'formatCurrency', () => {
		describe( 'formatCurrency error handling', () => {
			it( 'should throw error for invalid decimal separator length', () => {
				const params = {
					value: '123.45',
					decimalSeparator: '..',
				};

				expect( () => formatCurrency( params ) ).toThrow(
					'Separator must be a single character. ".." is not valid.'
				);
			} );

			it( 'should throw error for invalid thousand separator length', () => {
				const params = {
					value: '1,234.56',
					thousandSeparator: ',,',
				};

				expect( () => formatCurrency( params ) ).toThrow(
					'Separator must be a single character. ",," is not valid.'
				);
			} );

			it( 'should throw error for empty decimal separator', () => {
				const params = {
					value: '123.45',
					decimalSeparator: '',
				};

				expect( () => formatCurrency( params ) ).toThrow(
					'Separator must be a single character. "" is not valid.'
				);
			} );

			it( 'should throw error for empty thousand separator', () => {
				const params = {
					value: '1,234.56',
					thousandSeparator: '',
				};

				expect( () => formatCurrency( params ) ).toThrow(
					'Separator must be a single character. "" is not valid.'
				);
			} );

			it( 'should throw error for numeric decimal separator', () => {
				const params = {
					value: '123.45',
					decimalSeparator: '1',
				};

				expect( () => formatCurrency( params ) ).toThrow( 'Separator cannot be a number. "1" is not valid.' );
			} );

			it( 'should throw error for numeric thousand separator', () => {
				const params = {
					value: '1,234.56',
					thousandSeparator: '2',
				};

				expect( () => formatCurrency( params ) ).toThrow( 'Separator cannot be a number. "2" is not valid.' );
			} );

			it( 'should throw error when decimal and thousand separators are the same', () => {
				const params = {
					value: '123.45',
					decimalSeparator: ',',
					thousandSeparator: ',',
				};

				expect( () => formatCurrency( params ) ).toThrow(
					'Decimal and thousand separators cannot be the same.'
				);
			} );

			it( 'should handle values with multiple decimal separators', () => {
				const params = {
					value: '123.45.67',
				};

				expect( () => formatCurrency( params ) ).toThrow( 'Invalid value. Multiple decimal separators found.' );
			} );
		} );

		describe( 'formatCurrency currency symbol and position', () => {
			it( 'should format with prefix symbol by default', () => {
				const params = {
					value: '123.45',
				};

				expect( formatCurrency( params ) ).toEqual( '$123.45' );
			} );

			it( 'should format with custom prefix symbol', () => {
				const params = {
					value: '123.45',
					symbol: '€',
					position: 'prefix' as CurrencyPosition,
				};

				expect( formatCurrency( params ) ).toEqual( '€123.45' );
			} );

			it( 'should format with postfix symbol', () => {
				const params = {
					value: '123.45',
					symbol: '€',
					position: 'postfix' as CurrencyPosition,
				};

				expect( formatCurrency( params ) ).toEqual( '123.45€' );
			} );

			it( 'should format with postfix symbol and custom separators', () => {
				const params = {
					value: '1234,56',
					symbol: '€',
					position: 'postfix' as CurrencyPosition,
					thousandSeparator: ' ',
					decimalSeparator: ',',
				};

				expect( formatCurrency( params ) ).toEqual( '1 234,56€' );
			} );

			it( 'should use default symbol and position when not specified', () => {
				setDefaultCurrencyProps( {
					symbol: '¥',
					position: 'postfix' as CurrencyPosition,
				} );

				const defaultProps = getDefaultCurrencyProps();
				expect( defaultProps.symbol ).toBe( '¥' );
				expect( defaultProps.position ).toBe( 'postfix' );

				const params = {
					value: '123.45',
				};

				expect( formatCurrency( params ) ).toEqual( '123.45¥' );
			} );
		} );

		describe( 'formatCurrency edge cases', () => {
			it( 'should handle very large numbers', () => {
				const params = {
					value: '999999999.99',
				};

				expect( () => formatCurrency( params ) ).not.toThrow();
				expect( formatCurrency( params ) ).toEqual( '$999,999,999.99' );
			} );

			it( 'should handle very small decimal numbers', () => {
				const params = {
					value: '0.001',
					precision: 3,
				};

				expect( () => formatCurrency( params ) ).not.toThrow();
				expect( formatCurrency( params ) ).toEqual( '$0.001' );
			} );

			it( 'should handle zero with precision', () => {
				const params = {
					value: '0',
					precision: 4,
				};

				expect( formatCurrency( params ) ).toEqual( '$0.0000' );
			} );

			it( 'should handle large numbers with different separators', () => {
				const params = {
					value: '1234567,89',
					thousandSeparator: ' ',
					decimalSeparator: ',',
				};

				expect( formatCurrency( params ) ).toEqual( '$1 234 567,89' );
			} );
		} );

		describe( 'formatCurrency typical cases', () => {
			it( 'should handle simple numeric values', () => {
				const params = {
					value: '123.45',
				};

				expect( formatCurrency( params ) ).toEqual( '$123.45' );
			} );

			it( 'should handle values with thousand separators', () => {
				const params = {
					value: '1,234.56',
				};

				expect( formatCurrency( params ) ).toEqual( '$1,234.56' );
			} );

			it( 'should handle values with custom separators', () => {
				const params = {
					value: '123.123,45',
					decimalSeparator: ',',
					thousandSeparator: '.',
				};

				expect( () => formatCurrency( params ) ).not.toThrow();
				expect( formatCurrency( params ) ).toEqual( '$123.123,45' );
			} );

			it( 'should handle values with currency symbol and position', () => {
				const params = {
					value: '123.45',
					symbol: '€',
					position: 'postfix' as CurrencyPosition,
				};

				expect( formatCurrency( params ) ).toEqual( '123.45€' );
			} );

			it( 'should handle values with custom precision', () => {
				const params = {
					value: '123.456789',
					precision: 4,
				};

				expect( formatCurrency( params ) ).toEqual( '$123.4567' );
			} );

			it( 'should handle empty string values', () => {
				const params = {
					value: '',
				};

				expect( formatCurrency( params ) ).toEqual( '$0.00' );
			} );

			it( 'should handle non-numeric string values', () => {
				const params = {
					value: 'abc',
				};

				expect( formatCurrency( params ) ).toEqual( '$0.00' );
			} );

			it( 'should handle values with mixed separators', () => {
				const params = {
					value: '1,234.56',
					decimalSeparator: '.',
					thousandSeparator: ',',
				};

				expect( formatCurrency( params ) ).toEqual( '$1,234.56' );
			} );

			it( 'should handle values with European number format', () => {
				const params = {
					value: '1.234,56',
					decimalSeparator: ',',
					thousandSeparator: '.',
				};

				expect( formatCurrency( params ) ).toEqual( '$1.234,56' );
			} );

			it( 'should handle values with spaces as thousand separators', () => {
				const params = {
					value: '1 234 567.89',
					thousandSeparator: ' ',
				};

				expect( formatCurrency( params ) ).toEqual( '$1 234 567.89' );
			} );

			it( 'should handle values with special characters', () => {
				const params = {
					value: '$123.45',
				};

				expect( () => formatCurrency( params ) ).not.toThrow();
				expect( formatCurrency( params ) ).toEqual( '$123.45' );
				expect( formatCurrency( { ...params, symbol: '€' } ) ).toEqual( '€123.45' );
			} );

			it( 'should use default values when parameters are not provided', () => {
				const params = {
					value: '123.45',
				};

				expect( () => formatCurrency( params ) ).not.toThrow();
				expect( formatCurrency( params ) ).toEqual( '$123.45' );

				setDefaultCurrencyProps( { symbol: '€' } );

				const defaultProps = getDefaultCurrencyProps();
				expect( defaultProps.symbol ).toBe( '€' );

				expect( formatCurrency( params ) ).toEqual( '€123.45' );
			} );

			it( 'should handle zero values', () => {
				const params = {
					value: '0',
				};

				expect( () => formatCurrency( params ) ).not.toThrow();
				expect( formatCurrency( params ) ).toEqual( '$0.00' );
			} );

			it( 'should handle negative values', () => {
				const params = {
					value: '-123.45',
				};

				expect( formatCurrency( params ) ).toEqual( '$123.45' );
			} );
		} );
	} );
} );
