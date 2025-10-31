// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { fireEvent, render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';
import CurrencyInput from '../../../src/resources/packages/classy/components/CurrencyInput/CurrencyInput';

describe( 'CurrencyInput', () => {
	const mockDefaultCurrency = {
		code: 'USD',
		symbol: '$',
		position: 'prefix' as const,
		label: 'US Dollar',
	};

	const defaultProps = {
		value: '100',
		decimalPrecision: 2,
		decimalSeparator: '.',
		thousandSeparator: ',',
		defaultCurrency: mockDefaultCurrency,
	};

	it( 'renders with default label when no label provided', () => {
		render( <CurrencyInput { ...defaultProps } /> );
		expect( screen.getByLabelText( 'Price' ) ).toBeInTheDocument();
	} );

	it( 'renders with custom label', () => {
		render( <CurrencyInput { ...defaultProps } label="Ticket Cost" /> );
		expect( screen.getByLabelText( 'Ticket Cost' ) ).toBeInTheDocument();
	} );

	it( 'formats value when not focused', () => {
		render( <CurrencyInput { ...defaultProps } value="1000.50" /> );
		const input = screen.getByRole( 'textbox' );
		expect( input ).toHaveValue( '$1,000.50' );
	} );

	it( 'shows raw value when focused', () => {
		render( <CurrencyInput { ...defaultProps } value="1000.50" /> );
		const input = screen.getByRole( 'textbox' );

		fireEvent.focus( input );
		expect( input ).toHaveValue( '1000.50' );
	} );

	it( 'formats value when blurred', () => {
		render( <CurrencyInput { ...defaultProps } value="1000.50" /> );
		const input = screen.getByRole( 'textbox' );

		fireEvent.focus( input );
		expect( input ).toHaveValue( '1000.50' );

		fireEvent.blur( input );
		expect( input ).toHaveValue( '$1,000.50' );
	} );

	it( 'calls onChange when value changes', () => {
		const mockOnChange = jest.fn();
		render( <CurrencyInput { ...defaultProps } onChange={ mockOnChange } /> );
		const input = screen.getByRole( 'textbox' );

		fireEvent.focus( input );
		fireEvent.change( input, { target: { value: '250.75' } } );

		expect( mockOnChange ).toHaveBeenCalledWith( '250.75' );
	} );

	it( 'does not call onChange when value is the same', () => {
		const mockOnChange = jest.fn();
		render( <CurrencyInput { ...defaultProps } onChange={ mockOnChange } value="100" /> );
		const input = screen.getByRole( 'textbox' );

		fireEvent.focus( input );
		fireEvent.change( input, { target: { value: '100' } } );

		expect( mockOnChange ).not.toHaveBeenCalled();
	} );

	it( 'handles empty value correctly', () => {
		render( <CurrencyInput { ...defaultProps } value="" /> );
		const input = screen.getByRole( 'textbox' );
		expect( input ).toHaveValue( '' );
	} );

	it( 'sets required attribute when required prop is true', () => {
		render( <CurrencyInput { ...defaultProps } required={ true } /> );
		const input = screen.getByRole( 'textbox' );
		expect( input ).toBeRequired();
	} );

	it( 'does not set required attribute when required prop is false', () => {
		render( <CurrencyInput { ...defaultProps } required={ false } /> );
		const input = screen.getByRole( 'textbox' );
		expect( input ).not.toBeRequired();
	} );

	it( 'formats with different currency settings', () => {
		const euroProps = {
			...defaultProps,
			value: '1234,56', // Value already uses Euro format separators.
			decimalSeparator: ',',
			thousandSeparator: '.',
			defaultCurrency: {
				code: 'EUR',
				symbol: '€',
				position: 'postfix' as const,
				label: 'Euro',
			},
		};

		render( <CurrencyInput { ...euroProps } /> );
		const input = screen.getByRole( 'textbox' );
		expect( input ).toHaveValue( '1.234,56€' );
	} );

	it( 'handles decimal precision correctly', () => {
		render( <CurrencyInput { ...defaultProps } value="100.999" decimalPrecision={ 3 } /> );
		const input = screen.getByRole( 'textbox' );
		expect( input ).toHaveValue( '$100.999' );
	} );

	it( 'maintains focus state correctly', () => {
		render( <CurrencyInput { ...defaultProps } value="500" /> );
		const input = screen.getByRole( 'textbox' );

		// Initially formatted.
		expect( input ).toHaveValue( '$500.00' );

		// Focus shows raw value.
		fireEvent.focus( input );
		expect( input ).toHaveValue( '500' );

		// Change value while focused.
		fireEvent.change( input, { target: { value: '750' } } );
		expect( input ).toHaveValue( '750' );

		// Blur formats the new value.
		fireEvent.blur( input );
		expect( input ).toHaveValue( '$750.00' );
	} );
} );
