// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { fireEvent, render, screen } from '@testing-library/react';
import { createRegistry, RegistryProvider } from '@wordpress/data';
import '@testing-library/jest-dom';
import CurrencySelector from '../../../src/resources/packages/classy/components/CurrencySelector/CurrencySelector';
import { STORE_NAME, storeConfig } from '@tec/common/classy/store';

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
			label: 'EUR',
		},
	];

	const mockProps = {
		currencyCodeMeta: '_EventCurrency',
		currencySymbolMeta: '_EventCurrencySymbol',
		currencyPositionMeta: '_EventCurrencyPosition',
	};

	let registry;
	let mockEditPost;

	function setupRegistry( meta = {}, currencies = mockCurrencies ) {
		registry = createRegistry();
		mockEditPost = jest.fn();

		// Register the tec/classy store
		registry.registerStore( STORE_NAME, {
			...storeConfig,
			selectors: {
				...storeConfig.selectors,
				getDefaultCurrency: () => mockDefaultCurrency,
				getCurrencyOptions: () => currencies,
			},
		} );

		// Register core/editor store for meta handling
		registry.registerStore( 'core/editor', {
			reducer: ( state = { meta }, action ) => {
				if ( action.type === 'EDIT_POST' ) {
					return {
						...state,
						meta: { ...state.meta, ...action.edits.meta },
					};
				}
				return state;
			},
			selectors: {
				getEditedPostAttribute: ( state, attribute ) => {
					if ( attribute === 'meta' ) {
						return state.meta;
					}
					return null;
				},
			},
			actions: {
				editPost: ( edits ) => {
					mockEditPost( edits );
					return { type: 'EDIT_POST', edits };
				},
			},
		} );
	}

	beforeEach( () => {
		jest.clearAllMocks();
		setupRegistry();
	} );

	function openCurrencyPopover() {
		const button = screen.getByRole( 'button', { name: /\$ USD|EUR €/ } );
		fireEvent.click( button );
	}

	it( 'renders with default currency initially', () => {
		render(
			<RegistryProvider value={ registry }>
				<CurrencySelector { ...mockProps } />
			</RegistryProvider>
		);
		expect( screen.getByText( '$ USD' ) ).toBeInTheDocument();
	} );

	it( 'updates currency when selection changes', () => {
		render(
			<RegistryProvider value={ registry }>
				<CurrencySelector { ...mockProps } />
			</RegistryProvider>
		);
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
		render(
			<RegistryProvider value={ registry }>
				<CurrencySelector { ...mockProps } />
			</RegistryProvider>
		);
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
		render(
			<RegistryProvider value={ registry }>
				<CurrencySelector { ...mockProps } />
			</RegistryProvider>
		);
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
		setupRegistry( {}, [] );
		render(
			<RegistryProvider value={ registry }>
				<CurrencySelector { ...mockProps } />
			</RegistryProvider>
		);
		openCurrencyPopover();

		// The CenteredSpinner renders a div with class 'classy-component__spinner'
		const spinner = document.querySelector( '.classy-component__spinner' );
		expect( spinner ).toBeInTheDocument();
	} );

	it( 'initializes with custom meta values', () => {
		setupRegistry( {
			_EventCurrency: 'EUR',
			_EventCurrencySymbol: '€',
			_EventCurrencyPosition: 'postfix',
		} );

		render(
			<RegistryProvider value={ registry }>
				<CurrencySelector { ...mockProps } />
			</RegistryProvider>
		);

		// Check the button text shows the correct currency
		// EUR is displayed with the code on the left and symbol on the right when position is postfix
		expect( screen.getByText( 'EUR €' ) ).toBeInTheDocument();

		// Open popover and check controls
		openCurrencyPopover();
		const select = screen.getByRole( 'combobox' );
		const toggle = screen.getByRole( 'checkbox' );
		expect( select ).toHaveValue( 'EUR' );
		expect( toggle ).not.toBeChecked();
	} );
} );
