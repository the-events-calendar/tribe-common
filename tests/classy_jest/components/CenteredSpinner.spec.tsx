// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { CenteredSpinner } from '@tec/common/classy/components';

describe( 'CenteredSpinner', () => {
	it( 'renders with default class name', () => {
		const { container } = render( <CenteredSpinner /> );
		const spinnerWrapper = container.querySelector( '.classy-component__spinner' );

		expect( spinnerWrapper ).toBeInTheDocument();
		expect( spinnerWrapper ).toHaveClass( 'classy-component__spinner' );
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders with additional class name when provided', () => {
		const { container } = render( <CenteredSpinner className="custom-spinner" /> );
		const spinnerWrapper = container.querySelector( '.classy-component__spinner' );

		expect( spinnerWrapper ).toBeInTheDocument();
		expect( spinnerWrapper ).toHaveClass( 'classy-component__spinner', 'custom-spinner' );
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'does not add extra space when className is undefined', () => {
		const { container } = render( <CenteredSpinner className={ undefined } /> );
		const spinnerWrapper = container.querySelector( '.classy-component__spinner' );

		expect( spinnerWrapper?.className ).toBe( 'classy-component__spinner' );
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'handles empty string className correctly', () => {
		const { container } = render( <CenteredSpinner className="" /> );
		const spinnerWrapper = container.querySelector( '.classy-component__spinner' );

		expect( spinnerWrapper ).toBeInTheDocument();
		expect( spinnerWrapper?.className ).toBe( 'classy-component__spinner' );
		expect( container.firstChild ).toMatchSnapshot();
	} );
} );
