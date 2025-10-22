// @ts-nocheck
import * as React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it } from '@jest/globals';
import { ClassyField } from '@tec/common/classy/components';

describe( 'ClassyField Component', () => {
	const defaultProps = {
		title: 'Test Field',
		children: <div>Test Content</div>,
	};

	it( 'renders correctly with required props', () => {
		const { container, getByText } = render( <ClassyField { ...defaultProps } /> );

		expect( getByText( 'Test Field' ) ).toBeInTheDocument();
		expect( getByText( 'Test Content' ) ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field' );
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field--test-field' );
	} );

	it( 'renders children correctly', () => {
		const { getByPlaceholderText, getByText } = render(
			<ClassyField title="Form Field">
				<div>
					<input type="text" placeholder="Test input" />
					<button>Test Button</button>
				</div>
			</ClassyField>
		);

		expect( getByPlaceholderText( 'Test input' ) ).toBeInTheDocument();
		expect( getByText( 'Test Button' ) ).toBeInTheDocument();
	} );

	it( 'generates className from title when no custom className provided', () => {
		const { container } = render(
			<ClassyField title="My Custom Field">
				<div>Content</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field--my-custom-field' );
	} );

	it( 'handles title with spaces in className generation', () => {
		const { container } = render(
			<ClassyField title="Field With Spaces">
				<div>Content</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field--field-with-spaces' );
	} );

	it( 'handles title with multiple spaces in className generation', () => {
		const { container } = render(
			<ClassyField title="Field   With   Multiple   Spaces">
				<div>Content</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field--field-with-multiple-spaces' );
	} );

	it( 'uses custom className when provided', () => {
		const { container } = render(
			<ClassyField title="Test Field" className="custom-field-class">
				<div>Content</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field' );
		expect( container.firstChild as HTMLElement ).toHaveClass( 'custom-field-class' );
		expect( container.firstChild as HTMLElement ).not.toHaveClass( 'classy-field--test-field' );
	} );

	it( 'renders title in h3 element', () => {
		const { getByRole } = render( <ClassyField { ...defaultProps } /> );

		const heading = getByRole( 'heading', { level: 3 } );
		expect( heading ).toBeInTheDocument();
		expect( heading ).toHaveTextContent( 'Test Field' );
	} );

	it( 'has correct structure with title and children', () => {
		const { container } = render( <ClassyField { ...defaultProps } /> );

		const fieldElement = container.firstChild as HTMLElement;
		expect( fieldElement ).toHaveClass( 'classy-field' );

		const titleElement = fieldElement.querySelector( '.classy-field__title' );
		expect( titleElement ).toBeInTheDocument();
		expect( titleElement ).toContainHTML( '<h3>Test Field</h3>' );
	} );

	it( 'renders multiple children correctly', () => {
		const { getByText } = render(
			<ClassyField title="Multi Child Field">
				<div>First Child</div>
				<div>Second Child</div>
				<span>Third Child</span>
			</ClassyField>
		);

		expect( getByText( 'First Child' ) ).toBeInTheDocument();
		expect( getByText( 'Second Child' ) ).toBeInTheDocument();
		expect( getByText( 'Third Child' ) ).toBeInTheDocument();
	} );

	it( 'handles empty children gracefully', () => {
		const { container, getByText } = render( <ClassyField title="Empty Field">{ null }</ClassyField> );

		expect( container.firstChild as HTMLElement ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field' );
		expect( getByText( 'Empty Field' ) ).toBeInTheDocument();
	} );

	it( 'handles special characters in title for className', () => {
		const { container } = render(
			<ClassyField title="Field!@#$%^&*()">
				<div>Content</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field--field!@#$%^&*()' );
	} );

	it( 'handles empty title', () => {
		const { container } = render(
			<ClassyField title="">
				<div>Content</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field--' );
	} );

	it( 'matches snapshot', () => {
		const { container } = render( <ClassyField { ...defaultProps } /> );

		expect( container.firstChild as HTMLElement ).toMatchSnapshot();
	} );

	it( 'matches snapshot with custom className', () => {
		const { container } = render(
			<ClassyField title="Custom Field" className="custom-class">
				<div>Custom Content</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toMatchSnapshot();
	} );

	it( 'matches snapshot with complex children', () => {
		const { container } = render(
			<ClassyField title="Complex Form Field">
				<div>
					<label htmlFor="test-input">Test Label</label>
					<input id="test-input" type="text" />
					<button type="submit">Submit</button>
				</div>
			</ClassyField>
		);

		expect( container.firstChild as HTMLElement ).toMatchSnapshot();
	} );
} );
