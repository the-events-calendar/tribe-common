import { CurrencyParams, FormatCurrencyParams } from '@tec/common/classy/types/Currency';
import { getSettings } from '../localizedData';

/**
 * The default currency properties.
 */
let defaultCurrencyProps: CurrencyParams = {
	decimalSeparator: '.',
	thousandSeparator: ',',
	precision: 2,
	symbol: getSettings().defaultCurrency.symbol,
	position: getSettings().defaultCurrency.position,
};

/**
 * Validates that the separator is a single character.
 *
 * @since TBD
 *
 * @param {string} separator The separator to validate.
 * @throws {Error} If the separator is not a single character.
 */
const validateSeparatorLength = ( separator: string ): void => {
	if ( separator.length !== 1 ) {
		throw new Error( `Separator must be a single character. "${ separator }" is not valid.` );
	}
};

/**
 * Validates that the separator is not a number.
 *
 * @since TBD
 *
 * @param {string} separator The separator to validate.
 * @throws {Error} If the separator is a number.
 */
const validateSeparatorNotNumber = ( separator: string ): void => {
	if ( separator.match( /\d/ ) ) {
		throw new Error( `Separator cannot be a number. "${ separator }" is not valid.` );
	}
};

/**
 * Sets the default currency properties.
 *
 * @since TBD
 *
 * @param {Partial< CurrencyParams >} params The currency properties to set as defaults.
 * @throws {Error} If the decimal or thousand separator is not a single character.
 * @throws {Error} If the precision is not a number between 0 and 100.
 */
export const setDefaultCurrencyProps = ( params: Partial< CurrencyParams > ): void => {
	// Validate the separators if they are provided.
	if ( Object.hasOwn( params, 'decimalSeparator' ) ) {
		validateSeparatorLength( params.decimalSeparator as string );
		validateSeparatorNotNumber( params.decimalSeparator as string );
	}

	if ( Object.hasOwn( params, 'thousandSeparator' ) ) {
		validateSeparatorLength( params.thousandSeparator as string );
		validateSeparatorNotNumber( params.thousandSeparator as string );
	}

	// Validate that decimal and thousand separators are not the same if both are provided.
	if ( Object.hasOwn( params, 'decimalSeparator' ) && Object.hasOwn( params, 'thousandSeparator' ) ) {
		if ( params.decimalSeparator === params.thousandSeparator ) {
			throw new Error( 'Decimal and thousand separators cannot be the same.' );
		}
	}

	// Validate precision if provided (must be from 0 to 100).
	if ( Object.hasOwn( params, 'precision' ) ) {
		if ( ( typeof params.precision as any ) !== 'number' || isNaN( params.precision as number ) ) {
			throw new Error( 'Precision must be a valid number.' );
		}

		const precision = params.precision as number;
		if ( precision < 0 || precision > 100 ) {
			throw new Error( 'Precision must be between 0 and 100.' );
		}
	}

	defaultCurrencyProps = {
		...defaultCurrencyProps,
		...params,
	};
};

/**
 * Gets the current default currency properties.
 *
 * @since TBD
 *
 * @return {CurrencyParams} The current default currency properties.
 */
export const getDefaultCurrencyProps = (): CurrencyParams => ( { ...defaultCurrencyProps } );

/**
 * Formats a currency value based on the provided parameters.
 *
 * @since TBD
 *
 * @param {FormatCurrencyParams} params The parameters for formatting the currency.
 * @return {string} The formatted currency string.
 * @throws {Error} If the decimal or thousand separator is not a single character.
 * @throws {Error} If the decimal and thousand separators are the same.
 */
export const formatCurrency = ( params: FormatCurrencyParams ): string => {
	const {
		value,
		symbol = defaultCurrencyProps.symbol,
		position = defaultCurrencyProps.position,
		precision = defaultCurrencyProps.precision,
	} = params;

	const decimalSeparator: string = ( params.decimalSeparator as string ) ?? defaultCurrencyProps.decimalSeparator;
	const thousandSeparator: string = ( params.thousandSeparator as string ) ?? defaultCurrencyProps.thousandSeparator;

	// Validate the separators.
	validateSeparatorLength( decimalSeparator );
	validateSeparatorLength( thousandSeparator );
	validateSeparatorNotNumber( decimalSeparator );
	validateSeparatorNotNumber( thousandSeparator );
	if ( decimalSeparator === thousandSeparator ) {
		throw new Error( 'Decimal and thousand separators cannot be the same.' );
	}

	/*
	 * This takes the user-entered value and cleans it up to extract only the numeric parts,
	 * removing any non-numeric characters. It splits the value into pieces based on the decimal separator,
	 * ensuring that we only have the integer and decimal parts.
	 *
	 * This assumes that the user-entered value uses the same decimal and thousand separators as specified.
	 */
	const pieces = value
		.replaceAll( thousandSeparator, '' )
		.split( decimalSeparator )
		.map( ( piece ) => piece.replace( /[^0-9]/g, '' ) )
		.filter( ( piece ) => piece !== '' );

	// Ensure there are no more than two pieces; the integer and decimal parts.
	if ( pieces.length > 2 ) {
		throw new Error( 'Invalid value. Multiple decimal separators found.' );
	}

	// Build the formatted value using the pieces
	let integerPart = pieces[ 0 ] || '0';
	let decimalPart = pieces[ 1 ] || '';

	// Ensure decimal part has the correct precision
	if ( decimalPart.length < precision ) {
		decimalPart = decimalPart.padEnd( precision, '0' );
	} else if ( decimalPart.length > precision ) {
		decimalPart = decimalPart.substring( 0, precision );
	}

	// Add thousand separators to the integer part
	const integerWithThousands = integerPart.replace( /\B(?=(\d{3})+(?!\d))/g, thousandSeparator );

	// Combine integer and decimal parts with the appropriate decimal separator
	const formattedNumber = decimalPart
		? `${ integerWithThousands }${ decimalSeparator }${ decimalPart }`
		: integerWithThousands;

	return position === 'prefix' ? `${ symbol }${ formattedNumber }` : `${ formattedNumber }${ symbol }`;
};
