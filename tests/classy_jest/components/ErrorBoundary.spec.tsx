import * as React from 'react';
import { render } from '@testing-library/react';
import { describe, expect, it, beforeAll, afterAll, jest } from '@jest/globals';
import ErrorBoundary from '../../../src/resources/packages/classy/components/ErrorBoundary/ErrorBoundary';

// Mock console.error to suppress output in tests
const originalError = console.error;

describe( 'ErrorBoundary Component', () => {
	beforeAll( () => {
		console.error = jest.fn();
	} );
	afterAll( () => {
		console.error = originalError;
	} );

	it( 'renders children when there is no error', () => {
		const { container } = render(
			<ErrorBoundary>
				<div>Test Child</div>
			</ErrorBoundary>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'catches an error and displays the ErrorDisplay', async () => {
		const ThrowingComponent = () => {
			const error = new Error( 'for reasons' );
			error.stack = '__ERROR_CALL_STACK__';
			throw error;
		};

		const { container } = render(
			<ErrorBoundary>
				<ThrowingComponent />
			</ErrorBoundary>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );
} );
