// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import * as React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it } from '@jest/globals';
import { ClassyModalRoot } from '@tec/common/classy/components';

describe( 'ClassyModalRoot Component', () => {
	const defaultProps = {
		type: 'test-root',
		title: 'Test Modal Title',
		children: <div>Test Content</div>,
	};

	it( 'renders correctly with required props', () => {
		const { getByText, container } = render( <ClassyModalRoot { ...defaultProps } /> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( getByText( 'Test Modal Title' ) ).toBeInTheDocument();
		expect( getByText( 'Test Content' ) ).toBeInTheDocument();
	} );

	it( 'applies correct className structure', () => {
		const { container } = render( <ClassyModalRoot { ...defaultProps } /> );

		const rootElement = container.firstChild as HTMLElement;
		expect( rootElement ).toHaveClass( 'classy-root' );
	} );

	it( 'renders header with correct structure', () => {
		const { getByRole, getByText } = render( <ClassyModalRoot { ...defaultProps } /> );

		const header = getByRole( 'banner' );
		expect( header ).toHaveClass( 'classy-modal__header' );
		expect( header ).toHaveClass( 'classy-modal__header--test-root' );

		const title = getByText( 'Test Modal Title' );
		expect( title ).toHaveClass( 'classy-modal__header-title' );
		expect( title.tagName ).toBe( 'H4' );
	} );

	it( 'applies correct header className based on type', () => {
		const { getByRole } = render( <ClassyModalRoot { ...defaultProps } type="confirmation-dialog" /> );

		const header = getByRole( 'banner' );
		expect( header ).toHaveClass( 'classy-modal__header--confirmation-dialog' );
	} );

	it( 'handles type with special characters', () => {
		const { getByRole } = render( <ClassyModalRoot { ...defaultProps } type="root-with-special_chars.123" /> );

		const header = getByRole( 'banner' );
		expect( header ).toHaveClass( 'classy-modal__header--root-with-special_chars.123' );
	} );

	it( 'handles empty type', () => {
		const { getByRole } = render( <ClassyModalRoot { ...defaultProps } type="" /> );

		const header = getByRole( 'banner' );
		expect( header ).toHaveClass( 'classy-modal__header' );
	} );

	it( 'renders header icon when provided', () => {
		const icon = <span data-testid="header-icon">🔧</span>;
		const { getByTestId } = render( <ClassyModalRoot { ...defaultProps } headerIcon={ icon } /> );

		expect( getByTestId( 'header-icon' ) ).toBeInTheDocument();
	} );

	it( 'handles null header icon gracefully', () => {
		const { container, getByText } = render( <ClassyModalRoot { ...defaultProps } headerIcon={ null } /> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( getByText( 'Test Modal Title' ) ).toBeInTheDocument();
	} );

	it( 'handles undefined header icon gracefully', () => {
		const { container, getByText } = render( <ClassyModalRoot { ...defaultProps } headerIcon={ undefined } /> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( getByText( 'Test Modal Title' ) ).toBeInTheDocument();
	} );

	it( 'renders children correctly', () => {
		const { getByText, getByRole } = render(
			<ClassyModalRoot { ...defaultProps }>
				<div>
					<h2>Section Title</h2>
					<p>Section content</p>
					<button>Action Button</button>
				</div>
			</ClassyModalRoot>
		);

		expect( getByText( 'Section Title' ) ).toBeInTheDocument();
		expect( getByText( 'Section content' ) ).toBeInTheDocument();
		expect( getByRole( 'button' ) ).toBeInTheDocument();
	} );

	it( 'renders multiple children correctly', () => {
		const { getByText } = render(
			<ClassyModalRoot { ...defaultProps }>
				<div>First Child</div>
				<span>Second Child</span>
				<p>Third Child</p>
			</ClassyModalRoot>
		);

		expect( getByText( 'First Child' ) ).toBeInTheDocument();
		expect( getByText( 'Second Child' ) ).toBeInTheDocument();
		expect( getByText( 'Third Child' ) ).toBeInTheDocument();
	} );

	it( 'handles empty children gracefully', () => {
		const { container, getByText } = render( <ClassyModalRoot { ...defaultProps }>{ null }</ClassyModalRoot> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( getByText( 'Test Modal Title' ) ).toBeInTheDocument();
	} );

	it( 'handles complex header icon', () => {
		const complexIcon = (
			<div data-testid="complex-icon">
				<span>Icon</span>
				<svg width="16" height="16">
					<circle cx="8" cy="8" r="4" />
				</svg>
			</div>
		);

		const { getByTestId } = render( <ClassyModalRoot { ...defaultProps } headerIcon={ complexIcon } /> );

		expect( getByTestId( 'complex-icon' ) ).toBeInTheDocument();
	} );

	it( 'handles different title values', () => {
		const { getByText } = render( <ClassyModalRoot { ...defaultProps } title="Different Title" /> );

		expect( getByText( 'Different Title' ) ).toBeInTheDocument();
	} );

	it( 'handles empty title', () => {
		const { container } = render( <ClassyModalRoot { ...defaultProps } title="" /> );

		expect( container.querySelector( '.classy-modal__header-title' ) ).not.toBeInTheDocument();
	} );

	it( 'handles title with special characters', () => {
		const specialTitle = 'Title with "quotes" & <tags>';
		const { getByText } = render( <ClassyModalRoot { ...defaultProps } title={ specialTitle } /> );

		expect( getByText( specialTitle ) ).toBeInTheDocument();
	} );

	it( 'matches snapshot with default props', () => {
		const { container } = render( <ClassyModalRoot { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with header icon', () => {
		const icon = <span data-testid="header-icon">🔧</span>;
		const { container } = render( <ClassyModalRoot { ...defaultProps } headerIcon={ icon } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with complex children', () => {
		const { container } = render(
			<ClassyModalRoot { ...defaultProps }>
				<div className="modal-content">
					<section>
						<h2>Complex Content</h2>
						<p>This is a complex modal root with multiple elements.</p>
						<form>
							<input type="text" placeholder="Enter text" />
							<button type="submit">Submit</button>
						</form>
					</section>
				</div>
			</ClassyModalRoot>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with different type', () => {
		const { container } = render( <ClassyModalRoot { ...defaultProps } type="confirmation-dialog" /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with special characters in type', () => {
		const { container } = render( <ClassyModalRoot { ...defaultProps } type="root-with-special_chars.123" /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );
} );
