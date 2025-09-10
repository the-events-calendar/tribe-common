// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { fireEvent, render, screen } from '@testing-library/react';
import { useDispatch, useSelect } from '@wordpress/data';
import '@testing-library/jest-dom';
import CurrencySelector from '../../../src/resources/packages/classy/components/CurrencySelector/CurrencySelector';

// Mock the `@wordpress/data` package to intercept the `useDispatch` and `useSelect` hooks.
jest.mock( '@wordpress/data', () => ( {
	...jest.requireActual( '@wordpress/data' ),
	useDispatch: jest.fn(),
	useSelect: jest.fn(),
} ) );

describe( 'CurrencySelector', () => {
	const mockDefaultCurrency = {
		code: 'USD',
		symbol: '$',
		position: 'prefix' as const,
		label: 'US Dollar',
	};

	const mockCurrencies = [
		mockDefaultCurrency,
		{
			code: 'EUR',
			symbol: '€',
			position: 'postfix' as const,
			label: 'Euro',
		},
	];

	const mockProps = {
		currencyCodeMeta: '_EventCurrency',
		currencySymbolMeta: '_EventCurrencySymbol',
		currencyPositionMeta: '_EventCurrencyPosition',
	};

	const mockEditPost = jest.fn();

	function setupUseSelect( meta = {}, currencies = mockCurrencies ) {
		( useSelect as unknown as jest.Mock ).mockImplementation( ( selector ) => {
			const select = ( storeName: string ) => {
				if ( storeName === 'tec/classy' ) {
					return {
						getDefaultCurrency: () => mockDefaultCurrency,
						getCurrencyOptions: () => currencies,
					};
				}
				if ( storeName === 'core/editor' ) {
					return {
						getEditedPostAttribute: ( attribute: string ) => {
							if ( attribute === 'meta' ) {
								return meta;
							}
							return null;
						},
					};
				}
				return {};
			};
			return selector( select );
		} );
	}

	beforeEach( () => {
		jest.clearAllMocks();
		setupUseSelect();
		( useDispatch as unknown as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
		} );
	} );

	function openCurrencyPopover() {
		const button = screen.getByRole( 'button', { name: /\$ USD|EUR €/ } );
		fireEvent.click( button );
	}

	it( 'renders with default currency initially', () => {
		render( <CurrencySelector { ...mockProps } /> );
		expect( screen.getByText( '$ USD' ) ).toBeInTheDocument();
	} );

	it( 'updates currency when selection changes', () => {
		render( <CurrencySelector { ...mockProps } /> );
		openCurrencyPopover();

		const select = screen.getByRole( 'combobox' );
		fireEvent.change( select, { target: { value: 'EUR' } } );

		expect( mockEditPost ).toHaveBeenCalledWith( {
			meta: {
				_EventCurrency: 'EUR',
				_EventCurrencySymbol: '€',
			},
		} );
	} );

	it( 'resets to default currency when "default" is selected', () => {
		render( <CurrencySelector { ...mockProps } /> );
		openCurrencyPopover();

		const select = screen.getByRole( 'combobox' );
		fireEvent.change( select, { target: { value: 'default' } } );

		expect( mockEditPost ).toHaveBeenCalledWith( {
			meta: {
				_EventCurrency: '',
				_EventCurrencySymbol: mockDefaultCurrency.symbol,
				_EventCurrencyPosition: mockDefaultCurrency.position,
			},
		} );
	} );

	it( 'toggles currency position correctly', () => {
		render( <CurrencySelector { ...mockProps } /> );
		openCurrencyPopover();

		const toggle = screen.getByRole( 'checkbox' );
		fireEvent.click( toggle );

		expect( mockEditPost ).toHaveBeenCalledWith( {
			meta: {
				_EventCurrencyPosition: 'postfix',
			},
		} );
	} );

	it( 'shows loading spinner when currencies are not loaded', () => {
		setupUseSelect( {}, [] );
		render( <CurrencySelector { ...mockProps } /> );
		openCurrencyPopover();

		// The CenteredSpinner renders a div with class 'classy-component__spinner'
		const spinner = document.querySelector( '.classy-component__spinner' );
		expect( spinner ).toBeInTheDocument();
	} );

	it( 'initializes with custom meta values', () => {
		setupUseSelect( {
			_EventCurrency: 'EUR',
			_EventCurrencySymbol: '€',
			_EventCurrencyPosition: 'postfix',
		} );
		render( <CurrencySelector { ...mockProps } /> );

		// Check the button text shows the correct currency
		expect( screen.getByText( 'EUR €' ) ).toBeInTheDocument();

		// Open popover and check controls
		openCurrencyPopover();
		const select = screen.getByRole( 'combobox' );
		const toggle = screen.getByRole( 'checkbox' );
		expect( select ).toHaveValue( 'EUR' );
		expect( toggle ).not.toBeChecked();
	} );
} );
