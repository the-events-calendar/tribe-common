import React, { useCallback, useState } from 'react';
import { __experimentalInputControl as InputControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { LabeledInput } from '../LabeledInput';
import { Currency } from '../../types/Currency';

type CurrencyInputProps = {
	required?: boolean;
	label?: string;
	onChange?: ( value: string ) => void;
	value: string;
	decimalPrecision: number;
	decimalSeparator: string;
	thousandSeparator: string;
	defaultCurrency: Currency;
};

const defaultLabel = __( 'Price', 'tribe-common' );

/**
 * Renders a currency input field in the Classy editor.
 *
 * @since TBD
 *
 * @param {CurrencyInputProps} props
 * @return {JSX.Element} The rendered ticket price field.
 */
export default function CurrencyInput( props: CurrencyInputProps ): JSX.Element{
	const {
		label,
		onChange,
		value,
		required,
		decimalPrecision,
		decimalSeparator,
		thousandSeparator,
		defaultCurrency,
	} = props;
	const displayLabel = label || defaultLabel;

	const [ hasFocus, setHasFocus ] = useState< boolean >( false );

	/**
	 * Renders the value of the input field, applying formatting based on the currency settings.
	 *
	 * This function formats the value to display it in a user-friendly way, taking into account
	 * the provided settings for decimal precision, decimal separator, and thousand separator. When
	 * the input field is focused or the value is empty, it returns the raw value. Otherwise,
	 * it formats the value to a string representation of the currency amount.
	 *
	 * todo: correctly handle the decimal and thousand separators in the rendered version.
	 *
	 * @since TBD
	 *
	 * @param {string} value The raw value of the input field.
	 * @return {string} The formatted value to be displayed in the input field.
	 */
	const renderValue = useCallback( ( value: string ): string => {
		if ( hasFocus || value === '' ) {
			return value;
		}

		const pieces = value
			.replaceAll( thousandSeparator, '' )
			.split( decimalSeparator )
			.map( ( piece ) => piece.replace( /[^0-9]/g, '' ) )
			.filter( ( piece ) => piece !== '' );

		// The cleaned value should always use a period as the decimal separator.
		let cleanedValue = parseFloat( pieces.join( '.' ) );
		if ( isNaN( cleanedValue ) ) {
			cleanedValue = 0;
		}

		const formattedValue = cleanedValue.toFixed( decimalPrecision );

		return defaultCurrency.position === 'prefix'
			? `${ defaultCurrency.symbol }${ formattedValue }`
			: `${ formattedValue }${ defaultCurrency.symbol }`;
	}, [
		hasFocus,
		defaultCurrency,
		decimalPrecision,
		decimalSeparator,
		thousandSeparator,
	] );

	return (
		<LabeledInput label={ displayLabel }>
			<InputControl
				className="classy-field__control classy-field__control--input"
				label={ displayLabel }
				hideLabelFromVision={ true }
				value={ renderValue( value ) }
				onChange={ onChange }
				required={ required || false }
				onFocus={ (): void => setHasFocus( true ) }
				onBlur={ (): void => setHasFocus( false ) }
			/>
		</LabeledInput>
	);
}
