// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import * as React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it } from '@jest/globals';
import { ClassyModalFooter } from '@tec/common/classy/components';

describe( 'ClassyModalFooter Component', () => {
	const defaultProps = {
		type: 'test-footer',
		children: <div>Footer Content</div>,
	};

	it( 'renders correctly with required props', () => {
		const { getByText, container } = render( <ClassyModalFooter { ...defaultProps } /> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( getByText( 'Footer Content' ) ).toBeInTheDocument();
	} );

	it( 'applies correct className structure', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } /> );

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'classy-modal__footer' );
		expect( footerElement ).toHaveClass( 'classy-modal__footer--test-footer' );
	} );

	it( 'applies correct className based on type', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } type="confirmation-footer" /> );

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'classy-modal__footer' );
		expect( footerElement ).toHaveClass( 'classy-modal__footer--confirmation-footer' );
	} );

	it( 'applies custom className when provided', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } className="custom-footer-class" /> );

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'classy-modal__footer' );
		expect( footerElement ).toHaveClass( 'custom-footer-class' );
		expect( footerElement ).not.toHaveClass( 'classy-modal__footer--test-footer' );
	} );

	it( 'uses custom className instead of type-based className when provided', () => {
		const { container } = render(
			<ClassyModalFooter { ...defaultProps } className="custom-footer-class" type="confirmation-footer" />
		);

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'classy-modal__footer' );
		expect( footerElement ).toHaveClass( 'custom-footer-class' );
		expect( footerElement ).not.toHaveClass( 'classy-modal__footer--confirmation-footer' );
	} );

	it( 'handles type with special characters', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } type="footer-with-special_chars.123" /> );

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'classy-modal__footer--footer-with-special_chars.123' );
	} );

	it( 'handles empty type', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } type="" /> );

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'classy-modal__footer--' );
	} );

	it( 'renders children correctly', () => {
		const { getByText, getByRole } = render(
			<ClassyModalFooter { ...defaultProps }>
				<div>
					<h3>Footer Title</h3>
					<p>Footer description</p>
					<button>Footer Button</button>
				</div>
			</ClassyModalFooter>
		);

		expect( getByText( 'Footer Title' ) ).toBeInTheDocument();
		expect( getByText( 'Footer description' ) ).toBeInTheDocument();
		expect( getByRole( 'button' ) ).toBeInTheDocument();
	} );

	it( 'renders multiple children correctly', () => {
		const { getByText } = render(
			<ClassyModalFooter { ...defaultProps }>
				<div>First Child</div>
				<span>Second Child</span>
				<p>Third Child</p>
			</ClassyModalFooter>
		);

		expect( getByText( 'First Child' ) ).toBeInTheDocument();
		expect( getByText( 'Second Child' ) ).toBeInTheDocument();
		expect( getByText( 'Third Child' ) ).toBeInTheDocument();
	} );

	it( 'handles empty children gracefully', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps }>{ null }</ClassyModalFooter> );

		expect( container.firstChild ).toBeInTheDocument();
	} );

	it( 'handles undefined children gracefully', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps }>{ undefined }</ClassyModalFooter> );

		expect( container.firstChild ).toBeInTheDocument();
	} );

	it( 'handles false children gracefully', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps }>{ false }</ClassyModalFooter> );

		expect( container.firstChild ).toBeInTheDocument();
	} );

	it( 'handles mixed valid and invalid children', () => {
		const { getByText } = render(
			<ClassyModalFooter { ...defaultProps }>
				<div>Valid Child</div>
				{ null }
				<span>Another Valid Child</span>
				{ false }
				{ undefined }
			</ClassyModalFooter>
		);

		expect( getByText( 'Valid Child' ) ).toBeInTheDocument();
		expect( getByText( 'Another Valid Child' ) ).toBeInTheDocument();
	} );

	it( 'renders children with click handlers', () => {
		const handleClick = jest.fn();

		const { getByText } = render(
			<ClassyModalFooter { ...defaultProps }>
				<button onClick={ handleClick }>Clickable Button</button>
			</ClassyModalFooter>
		);

		expect( getByText( 'Clickable Button' ) ).toBeInTheDocument();
	} );

	it( 'renders children with form elements', () => {
		const { getByRole, getByText } = render(
			<ClassyModalFooter { ...defaultProps }>
				<form>
					<input type="text" placeholder="Enter text" />
					<button type="submit">Submit</button>
					<button type="button">Cancel</button>
				</form>
			</ClassyModalFooter>
		);

		expect( getByRole( 'textbox' ) ).toBeInTheDocument();
		expect( getByText( 'Submit' ) ).toBeInTheDocument();
		expect( getByText( 'Cancel' ) ).toBeInTheDocument();
	} );

	it( 'handles different type values', () => {
		const types = [ 'confirmation', 'warning', 'error', 'info', 'success' ];

		types.forEach( ( type ) => {
			const { container } = render( <ClassyModalFooter { ...defaultProps } type={ type } /> );

			const footerElement = container.firstChild as HTMLElement;
			expect( footerElement ).toHaveClass( `classy-modal__footer--${ type }` );
		} );
	} );

	it( 'handles className with special characters', () => {
		const { container } = render(
			<ClassyModalFooter { ...defaultProps } className="custom-class_with.special-chars" />
		);

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'custom-class_with.special-chars' );
	} );

	it( 'handles multiple custom classNames', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } className="class1 class2 class3" /> );

		const footerElement = container.firstChild as HTMLElement;
		expect( footerElement ).toHaveClass( 'class1' );
		expect( footerElement ).toHaveClass( 'class2' );
		expect( footerElement ).toHaveClass( 'class3' );
	} );

	it( 'matches snapshot with default props', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with custom className', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } className="custom-footer-class" /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with different type', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } type="confirmation-footer" /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with complex children', () => {
		const { container } = render(
			<ClassyModalFooter { ...defaultProps }>
				<div className="footer-content">
					<header>
						<h3>Complex Footer</h3>
					</header>
					<main>
						<p>This is a complex footer with multiple elements.</p>
						<form>
							<input type="text" placeholder="Enter text" />
							<button type="submit">Submit</button>
						</form>
					</main>
				</div>
			</ClassyModalFooter>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with mixed children', () => {
		const { container } = render(
			<ClassyModalFooter { ...defaultProps }>
				<div>Valid Child</div>
				{ null }
				<span>Another Valid Child</span>
				{ false }
			</ClassyModalFooter>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with special characters in type', () => {
		const { container } = render( <ClassyModalFooter { ...defaultProps } type="footer-with-special_chars.123" /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );
} );
