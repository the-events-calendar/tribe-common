// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import * as React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it } from '@jest/globals';
import { ClassyFieldGroup } from '@tec/common/classy/components';

describe( 'ClassyFieldGroup Component', () => {
	const defaultProps = {
		children: <div>Group Content</div>,
	};

	it( 'renders correctly with required props', () => {
		const { getByText, container } = render( <ClassyFieldGroup { ...defaultProps } /> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( getByText( 'Group Content' ) ).toBeInTheDocument();
	});

	it( 'applies correct className structure', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } /> );

		const groupElement = container.firstChild as HTMLElement;
		expect( groupElement ).toHaveClass( 'classy-field__group' );
	});

	it( 'applies custom className when provided', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className="custom-group-class" /> );

		const groupElement = container.firstChild as HTMLElement;
		expect( groupElement ).toHaveClass( 'classy-field__group' );
		expect( groupElement ).toHaveClass( 'custom-group-class' );
	});

	it( 'handles className with special characters', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className="custom-class_with.special-chars" /> );

		const groupElement = container.firstChild as HTMLElement;
		expect( groupElement ).toHaveClass( 'custom-class_with.special-chars' );
	});

	it( 'handles multiple custom classNames', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className="class1 class2 class3" /> );

		const groupElement = container.firstChild as HTMLElement;
		expect( groupElement ).toHaveClass( 'class1' );
		expect( groupElement ).toHaveClass( 'class2' );
		expect( groupElement ).toHaveClass( 'class3' );
	});

	it( 'renders children correctly', () => {
		const { getByText, getByRole } = render(
			<ClassyFieldGroup>
				<div>
					<h4>Group Title</h4>
					<p>Group description</p>
					<button>Group Button</button>
				</div>
			</ClassyFieldGroup>
		);

		expect( getByText( 'Group Title' ) ).toBeInTheDocument();
		expect( getByText( 'Group description' ) ).toBeInTheDocument();
		expect( getByRole( 'button' ) ).toBeInTheDocument();
	});

	it( 'renders multiple children correctly', () => {
		const { getByText } = render(
			<ClassyFieldGroup>
				<div>First Child</div>
				<span>Second Child</span>
				<p>Third Child</p>
			</ClassyFieldGroup>
		);

		expect( getByText( 'First Child' ) ).toBeInTheDocument();
		expect( getByText( 'Second Child' ) ).toBeInTheDocument();
		expect( getByText( 'Third Child' ) ).toBeInTheDocument();
	});

	it( 'handles empty children gracefully', () => {
		const { container } = render( <ClassyFieldGroup>{ null }</ClassyFieldGroup> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field__group' );
	});

	it( 'handles undefined children gracefully', () => {
		const { container } = render( <ClassyFieldGroup>{ undefined }</ClassyFieldGroup> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field__group' );
	});

	it( 'handles false children gracefully', () => {
		const { container } = render( <ClassyFieldGroup>{ false }</ClassyFieldGroup> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-field__group' );
	});

	it( 'handles mixed valid and invalid children', () => {
		const { getByText } = render(
			<ClassyFieldGroup>
				<div>Valid Child</div>
				{ null }
				<span>Another Valid Child</span>
				{ false }
				{ undefined }
			</ClassyFieldGroup>
		);

		expect( getByText( 'Valid Child' ) ).toBeInTheDocument();
		expect( getByText( 'Another Valid Child' ) ).toBeInTheDocument();
	});

	it( 'renders children with click handlers', () => {
		const handleClick = jest.fn();

		const { getByText } = render(
			<ClassyFieldGroup>
				<button onClick={ handleClick }>Clickable Button</button>
			</ClassyFieldGroup>
		);

		expect( getByText( 'Clickable Button' ) ).toBeInTheDocument();
	});

	it( 'renders children with form elements', () => {
		const { getByRole, getByText } = render(
			<ClassyFieldGroup>
				<form>
					<input type="text" placeholder="Enter text" />
					<button type="submit">Submit</button>
					<button type="button">Cancel</button>
				</form>
			</ClassyFieldGroup>
		);

		expect( getByRole( 'textbox' ) ).toBeInTheDocument();
		expect( getByText( 'Submit' ) ).toBeInTheDocument();
		expect( getByText( 'Cancel' ) ).toBeInTheDocument();
	});

	it( 'renders children with complex nested structure', () => {
		const { getByText, getByRole } = render(
			<ClassyFieldGroup>
				<div className="field-group-content">
					<header>
						<h3>Complex Group</h3>
					</header>
					<main>
						<p>This is a complex field group with multiple elements.</p>
						<section>
							<label htmlFor="input1">Input 1:</label>
							<input id="input1" type="text" />
						</section>
						<section>
							<label htmlFor="input2">Input 2:</label>
							<input id="input2" type="email" />
						</section>
					</main>
					<footer>
						<button type="submit">Save</button>
						<button type="button">Reset</button>
					</footer>
				</div>
			</ClassyFieldGroup>
		);

		expect( getByText( 'Complex Group' ) ).toBeInTheDocument();
		expect( getByText( 'This is a complex field group with multiple elements.' ) ).toBeInTheDocument();
		expect( getByText( 'Input 1:' ) ).toBeInTheDocument();
		expect( getByText( 'Input 2:' ) ).toBeInTheDocument();
		expect( getByText( 'Save' ) ).toBeInTheDocument();
		expect( getByText( 'Reset' ) ).toBeInTheDocument();
	});

	it( 'handles different className combinations', () => {
		const combinations = [
			{ className: 'single-class' },
			{ className: 'multiple classes here' },
			{ className: 'class-with-dashes' },
			{ className: 'class_with_underscores' },
			{ className: 'class.with.dots' },
		];

		combinations.forEach( ( { className } ) => {
			const { container } = render( <ClassyFieldGroup { ...defaultProps } className={ className } /> );

			const groupElement = container.firstChild as HTMLElement;
			expect( groupElement ).toHaveClass( 'classy-field__group' );
			expect( groupElement ).toHaveClass( className );
		});
	});

	it( 'handles empty className', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className="" /> );

		const groupElement = container.firstChild as HTMLElement;
		expect( groupElement ).toHaveClass( 'classy-field__group' );
		// Empty string className should not add any additional classes
		expect( groupElement.className ).toBe( 'classy-field__group' );
	});

	it( 'handles undefined className', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className={ undefined } /> );

		const groupElement = container.firstChild as HTMLElement;
		expect( groupElement ).toHaveClass( 'classy-field__group' );
	});

	it( 'handles null className', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className={ null } /> );

		const groupElement = container.firstChild as HTMLElement;
		expect( groupElement ).toHaveClass( 'classy-field__group' );
	});

	it( 'matches snapshot with default props', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with custom className', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className="custom-group-class" /> );

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with complex children', () => {
		const { container } = render(
			<ClassyFieldGroup>
				<div className="field-group-content">
					<header>
						<h3>Complex Group</h3>
					</header>
					<main>
						<p>This is a complex field group with multiple elements.</p>
						<form>
							<input type="text" placeholder="Enter text" />
							<button type="submit">Submit</button>
						</form>
					</main>
				</div>
			</ClassyFieldGroup>
		);

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with mixed children', () => {
		const { container } = render(
			<ClassyFieldGroup>
				<div>Valid Child</div>
				{ null }
				<span>Another Valid Child</span>
				{ false }
			</ClassyFieldGroup>
		);

		expect( container.firstChild ).toMatchSnapshot();
	});

	it( 'matches snapshot with special characters in className', () => {
		const { container } = render( <ClassyFieldGroup { ...defaultProps } className="custom-class_with.special-chars" /> );

		expect( container.firstChild ).toMatchSnapshot();
	});
});
