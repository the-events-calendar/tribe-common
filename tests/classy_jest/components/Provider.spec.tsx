// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { Provider, ProviderComponent } from '../../../src/resources/packages/classy/components/Provider';
import { createRegistry } from '@wordpress/data';
import { addAction, removeAction } from '@wordpress/hooks';
import { STORE_NAME, storeConfig } from '../../../src/resources/packages/classy/store';

describe( 'Provider', () => {
	let registry;
	let initActionFired;

	beforeEach( () => {
		jest.clearAllMocks();
		registry = createRegistry();
		initActionFired = false;

		// Add action listener to track initialization.
		addAction( 'tec.classy.initialized', 'test', () => {
			initActionFired = true;
		} );
	} );

	afterEach( () => {
		removeAction( 'tec.classy.initialized', 'test' );
	} );

	describe( 'ProviderComponent', () => {
		it( 'renders children wrapped in ErrorBoundary and RegistryProvider', () => {
			const { getByText } = render(
				<ProviderComponent registry={ registry }>
					<div>Test Content</div>
				</ProviderComponent>
			);

			expect( getByText( 'Test Content' ) ).toBeInTheDocument();
		} );

		it( 'registers the store if not already registered', () => {
			const registerStoreSpy = jest.spyOn( registry, 'registerStore' );

			render(
				<ProviderComponent registry={ registry }>
					<div>Test Content</div>
				</ProviderComponent>
			);

			expect( registerStoreSpy ).toHaveBeenCalledWith( 'tec/classy', expect.any( Object ) );
		} );

		it( 'fires tec.classy.initialized action when initializing', () => {
			expect( initActionFired ).toBe( false );

			render(
				<ProviderComponent registry={ registry }>
					<div>Test Content</div>
				</ProviderComponent>
			);

			expect( initActionFired ).toBe( true );
		} );

		it( 'does not re-register store if already registered', () => {
			// First render to register the store.
			render(
				<ProviderComponent registry={ registry }>
					<div>First Render</div>
				</ProviderComponent>
			);

			// Reset the flag and spy.
			initActionFired = false;
			const registerStoreSpy = jest.spyOn( registry, 'registerStore' );

			// Second render with same registry.
			const { getByText } = render(
				<ProviderComponent registry={ registry }>
					<div>Second Render</div>
				</ProviderComponent>
			);

			expect( getByText( 'Second Render' ) ).toBeInTheDocument();
			expect( registerStoreSpy ).not.toHaveBeenCalled();
			expect( initActionFired ).toBe( false );
		} );

		it( 'renders multiple children correctly', () => {
			const { getByText } = render(
				<ProviderComponent registry={ registry }>
					<div>First Child</div>
					<div>Second Child</div>
					<div>Third Child</div>
				</ProviderComponent>
			);

			expect( getByText( 'First Child' ) ).toBeInTheDocument();
			expect( getByText( 'Second Child' ) ).toBeInTheDocument();
			expect( getByText( 'Third Child' ) ).toBeInTheDocument();
		} );

		it( 'provides registry context to children', () => {
			const TestComponent = () => {
				const { useRegistry } = require( '@wordpress/data' );
				const currentRegistry = useRegistry();
				return <div>{ currentRegistry === registry ? 'Registry Matches' : 'Registry Mismatch' }</div>;
			};

			const { getByText } = render(
				<ProviderComponent registry={ registry }>
					<TestComponent />
				</ProviderComponent>
			);

			expect( getByText( 'Registry Matches' ) ).toBeInTheDocument();
		} );

		it( 'matches snapshot', () => {
			const { container } = render(
				<ProviderComponent registry={ registry }>
					<div className="test-child">Snapshot Test Content</div>
				</ProviderComponent>
			);

			expect( container.firstChild ).toMatchSnapshot();
		} );

		it( 'handles ErrorBoundary correctly', () => {
			const ThrowingComponent = () => {
				throw new Error( 'Test error' );
			};

			const { container } = render(
				<ProviderComponent registry={ registry }>
					<ThrowingComponent />
				</ProviderComponent>
			);

			// ErrorBoundary should catch the error and render fallback.
			expect( container.textContent ).toContain( 'An error occurred in the Classy application' );
		} );
	} );
} );
