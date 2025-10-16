// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import * as React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it } from '@jest/globals';
import { ClassyModalSection } from '@tec/common/classy/components';

describe( 'ClassyModalSection Component', () => {
	const defaultProps = {
		type: 'test-section',
		children: <div>Section Content</div>,
	};

	it( 'renders correctly with required props', () => {
		const { getByText, container } = render( <ClassyModalSection { ...defaultProps } /> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( getByText( 'Section Content' ) ).toBeInTheDocument();
	});

	it( 'applies correct className structure by default', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-modal__section' );
		expect( sectionElement ).toHaveClass( 'classy-modal__content' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs--unboxed' );
		expect( sectionElement ).toHaveClass( 'classy-modal__section--test-section' );
	});

	it( 'applies boxed inputs className when boxedInputs is true', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } boxedInputs={ true } /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-field__inputs--boxed' );
		expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--unboxed' );
	});

	it( 'applies unboxed inputs className when boxedInputs is false', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } boxedInputs={ false } /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-field__inputs--unboxed' );
		expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--boxed' );
	});

	it( 'does not apply input classes when hasInputs is false', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } hasInputs={ false } /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-modal__section' );
		expect( sectionElement ).toHaveClass( 'classy-modal__content' );
		expect( sectionElement ).not.toHaveClass( 'classy-field__inputs' );
		expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--boxed' );
		expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--unboxed' );
	});

	it( 'applies custom className when provided', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } className="custom-section-class" /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'custom-section-class' );
	});

	it( 'applies correct className based on type', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } type="confirmation-section" /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-modal__section--confirmation-section' );
	});

	it( 'handles type with special characters', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } type="section-with-special_chars.123" /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-modal__section--section-with-special_chars.123' );
	});

	it( 'handles empty type', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } type="" /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-modal__section' );
		expect( sectionElement ).toHaveClass( 'classy-modal__content' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs--unboxed' );
		expect( sectionElement ).not.toHaveClass( 'classy-modal__section--' );
	});

	it( 'handles undefined type', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } type={ undefined } /> );

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-modal__section' );
		expect( sectionElement ).toHaveClass( 'classy-modal__content' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs--unboxed' );
	});

	it( 'renders title when provided', () => {
		const { getByText } = render( <ClassyModalSection { ...defaultProps } title="Section Title" /> );

		expect( getByText( 'Section Title' ) ).toBeInTheDocument();
	});

	it( 'renders title with correct className', () => {
		const { getByText } = render( <ClassyModalSection { ...defaultProps } title="Section Title" /> );

		const titleElement = getByText( 'Section Title' );
		expect( titleElement ).toHaveClass( 'classy-field__input-title' );
	});

	it( 'does not render title when not provided', () => {
		const { queryByText } = render( <ClassyModalSection { ...defaultProps } /> );

		expect( queryByText( 'Section Title' ) ).not.toBeInTheDocument();
	});

	it( 'handles empty title', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } title="" /> );

		const titleElement = container.querySelector( '.classy-field__input-title' );
		expect( titleElement ).not.toBeInTheDocument();
	});

	it( 'handles title with special characters', () => {
		const specialTitle = 'Title with "quotes" & <tags>';
		const { getByText } = render( <ClassyModalSection { ...defaultProps } title={ specialTitle } /> );

		expect( getByText( specialTitle ) ).toBeInTheDocument();
	});

	it( 'renders separator when includeSeparator is true', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } includeSeparator={ true } /> );

		const separator = container.querySelector( 'hr' );
		expect( separator ).toBeInTheDocument();
		expect( separator ).toHaveClass( 'classy-modal__section-separator' );
	});

	it( 'does not render separator when includeSeparator is false', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } includeSeparator={ false } /> );

		const separator = container.querySelector( 'hr' );
		expect( separator ).not.toBeInTheDocument();
	});

	it( 'does not render separator by default', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } /> );

		const separator = container.querySelector( 'hr' );
		expect( separator ).not.toBeInTheDocument();
	});

	it( 'renders children correctly', () => {
		const { getByText, getByRole } = render(
			<ClassyModalSection { ...defaultProps }>
				<div>
					<h3>Section Title</h3>
					<p>Section description</p>
					<button>Section Button</button>
				</div>
			</ClassyModalSection>
		);

		expect( getByText( 'Section Title' ) ).toBeInTheDocument();
		expect( getByText( 'Section description' ) ).toBeInTheDocument();
		expect( getByRole( 'button' ) ).toBeInTheDocument();
	});

	it( 'renders multiple children correctly', () => {
		const { getByText } = render(
			<ClassyModalSection { ...defaultProps }>
				<div>First Child</div>
				<span>Second Child</span>
				<p>Third Child</p>
			</ClassyModalSection>
		);

		expect( getByText( 'First Child' ) ).toBeInTheDocument();
		expect( getByText( 'Second Child' ) ).toBeInTheDocument();
		expect( getByText( 'Third Child' ) ).toBeInTheDocument();
	});

	it( 'handles empty children gracefully', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps }>{ null }</ClassyModalSection> );

		expect( container.firstChild ).toBeInTheDocument();
	});

	it( 'handles undefined children gracefully', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps }>{ undefined }</ClassyModalSection> );

		expect( container.firstChild ).toBeInTheDocument();
	});

	it( 'handles false children gracefully', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps }>{ false }</ClassyModalSection> );

		expect( container.firstChild ).toBeInTheDocument();
	});

	it( 'handles mixed valid and invalid children', () => {
		const { getByText } = render(
			<ClassyModalSection { ...defaultProps }>
				<div>Valid Child</div>
				{ null }
				<span>Another Valid Child</span>
				{ false }
				{ undefined }
			</ClassyModalSection>
		);

		expect( getByText( 'Valid Child' ) ).toBeInTheDocument();
		expect( getByText( 'Another Valid Child' ) ).toBeInTheDocument();
	});

	it( 'renders children with form elements', () => {
		const { getByRole, getByText } = render(
			<ClassyModalSection { ...defaultProps }>
				<form>
					<input type="text" placeholder="Enter text" />
					<button type="submit">Submit</button>
					<button type="button">Cancel</button>
				</form>
			</ClassyModalSection>
		);

		expect( getByRole( 'textbox' ) ).toBeInTheDocument();
		expect( getByText( 'Submit' ) ).toBeInTheDocument();
		expect( getByText( 'Cancel' ) ).toBeInTheDocument();
	});

	it( 'combines all className options correctly', () => {
		const { container } = render(
			<ClassyModalSection
				{ ...defaultProps }
				className="custom-class"
				type="confirmation-section"
				boxedInputs={ true }
			/>
		);

		const sectionElement = container.firstChild as HTMLElement;
		expect( sectionElement ).toHaveClass( 'classy-modal__section' );
		expect( sectionElement ).toHaveClass( 'classy-modal__content' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs' );
		expect( sectionElement ).toHaveClass( 'classy-field__inputs--boxed' );
		expect( sectionElement ).toHaveClass( 'classy-modal__section--confirmation-section' );
		expect( sectionElement ).toHaveClass( 'custom-class' );
	});

	it( 'handles all boolean combinations correctly', () => {
		const combinations = [
			{ boxedInputs: true, hasInputs: true, includeSeparator: true },
			{ boxedInputs: false, hasInputs: true, includeSeparator: false },
			{ boxedInputs: true, hasInputs: false, includeSeparator: true },
			{ boxedInputs: false, hasInputs: false, includeSeparator: false },
		];

		combinations.forEach( ( { boxedInputs, hasInputs, includeSeparator } ) => {
			const { container } = render(
				<ClassyModalSection
					{ ...defaultProps }
					boxedInputs={ boxedInputs }
					hasInputs={ hasInputs }
					includeSeparator={ includeSeparator }
				/>
			);

			const sectionElement = container.firstChild as HTMLElement;
			const separator = container.querySelector( 'hr' );

			expect( sectionElement ).toBeInTheDocument();

			if ( hasInputs ) {
				expect( sectionElement ).toHaveClass( 'classy-field__inputs' );
				if ( boxedInputs ) {
					expect( sectionElement ).toHaveClass( 'classy-field__inputs--boxed' );
					expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--unboxed' );
				} else {
					expect( sectionElement ).toHaveClass( 'classy-field__inputs--unboxed' );
					expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--boxed' );
				}
			} else {
				expect( sectionElement ).not.toHaveClass( 'classy-field__inputs' );
				expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--boxed' );
				expect( sectionElement ).not.toHaveClass( 'classy-field__inputs--unboxed' );
			}

			if ( includeSeparator ) {
				expect( separator ).toBeInTheDocument();
			} else {
				expect( separator ).not.toBeInTheDocument();
			}
		});
	});

	it( 'matches snapshot with default props', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with title', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } title="Section Title" /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with separator', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } includeSeparator={ true } /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with boxed inputs', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } boxedInputs={ true } /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot without inputs', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } hasInputs={ false } /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with custom className', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } className="custom-section-class" /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with different type', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } type="confirmation-section" /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with complex children', () => {
		const { container } = render(
			<ClassyModalSection { ...defaultProps } title="Complex Section" includeSeparator={ true }>
				<div className="section-content">
					<header>
						<h3>Complex Section</h3>
					</header>
					<main>
						<p>This is a complex section with multiple elements.</p>
						<form>
							<input type="text" placeholder="Enter text" />
							<button type="submit">Submit</button>
						</form>
					</main>
				</div>
			</ClassyModalSection>
		);

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with all options enabled', () => {
		const { container } = render(
			<ClassyModalSection
				{ ...defaultProps }
				title="Full Options Section"
				className="custom-class"
				type="confirmation-section"
				boxedInputs={ true }
				includeSeparator={ true }
			/>
		);

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with special characters in type', () => {
		const { container } = render( <ClassyModalSection { ...defaultProps } type="section-with-special_chars.123" /> );

		expect( container.firstChild ).toMatchSnapshot();
	});
});
