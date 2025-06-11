// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { fireEvent, render, screen } from '@testing-library/react';
import { useDispatch, useSelect } from '@wordpress/data';
import '@testing-library/jest-dom';
import CurrencySelector from '../../../src/resources/packages/classy/components/CurrencySelector/CurrencySelector';

// Mock the WordPress data hooks
jest.mock( '@wordpress/data', () => ( {
	useDispatch: jest.fn(),
	useSelect: jest.fn(),
} ) );

// Mock the WordPress components
jest.mock( '@wordpress/components', () => ( {
	Button: ( { children, onClick, ...props } ) => (
		<button onClick={ onClick } { ...props }>
			{ children }
		</button>
	),
	Popover: ( { children } ) => <div data-testid="popover">{ children }</div>,
	SelectControl: ( { value, onChange, options } ) => (
		<select value={ value } onChange={ ( e ) => onChange( e.target.value ) } data-testid="currency-select">
			{ options.map( ( option ) => (
				<option key={ option.value } value={ option.value }>
					{ option.label }
				</option>
			) ) }
		</select>
	),
	ToggleControl: ( { checked, onChange } ) => (
		<input
			type="checkbox"
			checked={ checked }
			onChange={ () => onChange( ! checked ) }
			data-testid="currency-position-toggle"
		/>
	),
} ) );

// Mock the CenteredSpinner component
jest.mock( '../../../src/resources/packages/classy/components/CenteredSpinner', () => ( {
	CenteredSpinner: () => <div data-testid="centered-spinner">Loading...</div>,
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
		const button = screen.getByText( /\$ USD|EUR €/ );
		fireEvent.click( button );
	}

	it( 'renders with default currency initially', () => {
		render( <CurrencySelector { ...mockProps } /> );
		expect( screen.getByText( '$ USD' ) ).toBeInTheDocument();
	} );

	it( 'updates currency when selection changes', () => {
		render( <CurrencySelector { ...mockProps } /> );
		openCurrencyPopover();

		const select = screen.getByTestId( 'currency-select' );
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

		const select = screen.getByTestId( 'currency-select' );
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

		const toggle = screen.getByTestId( 'currency-position-toggle' );
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

		expect( screen.getByTestId( 'centered-spinner' ) ).toBeInTheDocument();
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
		const select = screen.getByTestId( 'currency-select' );
		const toggle = screen.getByTestId( 'currency-position-toggle' );
		expect( select ).toHaveValue( 'EUR' );
		expect( toggle ).not.toBeChecked();
	} );
} );
