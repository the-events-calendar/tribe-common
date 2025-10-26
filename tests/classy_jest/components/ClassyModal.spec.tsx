// @ts-nocheck
import * as React from 'react';
import { render, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it, jest } from '@jest/globals';
import { ClassyModal } from '@tec/common/classy/components';

// Mock the WordPress Modal component
jest.mock( '@wordpress/components', () => ( {
	Modal: ( { children, className, onRequestClose, overlayClassName } ) => (
		<div
			data-testid="modal"
			className={ className }
			onClick={ onRequestClose }
			data-overlay-class={ overlayClassName }
		>
			{ children }
		</div>
	),
} ) );

describe( 'ClassyModal Component', () => {
	const defaultProps = {
		onClose: jest.fn(),
		type: 'test-modal',
		children: <div>Test Modal Content</div>,
	};

	beforeEach( () => {
		jest.clearAllMocks();
	} );

	it( 'renders correctly with required props', () => {
		const { getByTestId, getByText } = render( <ClassyModal { ...defaultProps } /> );

		expect( getByTestId( 'modal' ) ).toBeInTheDocument();
		expect( getByText( 'Test Modal Content' ) ).toBeInTheDocument();
	} );

	it( 'applies correct className based on type', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps } /> );

		const modal = getByTestId( 'modal' );
		expect( modal ).toHaveClass( 'classy-modal' );
		expect( modal ).toHaveClass( 'classy-modal__test-modal' );
	} );

	it( 'applies custom className when provided', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps } className="custom-modal-class" /> );

		const modal = getByTestId( 'modal' );
		expect( modal ).toHaveClass( 'classy-modal' );
		expect( modal ).toHaveClass( 'custom-modal-class' );
		expect( modal ).not.toHaveClass( 'classy-modal__test-modal' );
	} );

	it( 'applies correct overlay className based on type', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps } /> );

		const modal = getByTestId( 'modal' );
		expect( modal ).toHaveAttribute(
			'data-overlay-class',
			'classy-modal__overlay classy-modal__overlay--test-modal'
		);
	} );

	it( 'applies custom overlay className when provided', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps } overlayClassName="custom-overlay-class" /> );

		const modal = getByTestId( 'modal' );
		expect( modal ).toHaveAttribute( 'data-overlay-class', 'classy-modal__overlay custom-overlay-class' );
	} );

	it( 'calls onClose when modal is clicked', () => {
		const onClose = jest.fn();
		const { getByTestId } = render( <ClassyModal { ...defaultProps } onClose={ onClose } /> );

		fireEvent.click( getByTestId( 'modal' ) );
		expect( onClose ).toHaveBeenCalledTimes( 1 );
	} );

	it( 'renders children correctly', () => {
		const { getByText, getByRole } = render(
			<ClassyModal { ...defaultProps }>
				<div>
					<h2>Modal Title</h2>
					<p>Modal description</p>
					<button>Action Button</button>
				</div>
			</ClassyModal>
		);

		expect( getByText( 'Modal Title' ) ).toBeInTheDocument();
		expect( getByText( 'Modal description' ) ).toBeInTheDocument();
		expect( getByRole( 'button' ) ).toBeInTheDocument();
	} );

	it( 'renders multiple children correctly', () => {
		const { getByText } = render(
			<ClassyModal { ...defaultProps }>
				<div>First Child</div>
				<span>Second Child</span>
				<p>Third Child</p>
			</ClassyModal>
		);

		expect( getByText( 'First Child' ) ).toBeInTheDocument();
		expect( getByText( 'Second Child' ) ).toBeInTheDocument();
		expect( getByText( 'Third Child' ) ).toBeInTheDocument();
	} );

	it( 'handles empty children gracefully', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps }>{ null }</ClassyModal> );

		expect( getByTestId( 'modal' ) ).toBeInTheDocument();
	} );

	it( 'handles different type values', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps } type="confirmation-dialog" /> );

		const modal = getByTestId( 'modal' );
		expect( modal ).toHaveClass( 'classy-modal__confirmation-dialog' );
		expect( modal ).toHaveAttribute(
			'data-overlay-class',
			'classy-modal__overlay classy-modal__overlay--confirmation-dialog'
		);
	} );

	it( 'handles type with special characters', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps } type="modal-with-special_chars.123" /> );

		const modal = getByTestId( 'modal' );
		expect( modal ).toHaveClass( 'classy-modal__modal-with-special_chars.123' );
		expect( modal ).toHaveAttribute(
			'data-overlay-class',
			'classy-modal__overlay classy-modal__overlay--modal-with-special_chars.123'
		);
	} );

	it( 'handles empty type', () => {
		const { getByTestId } = render( <ClassyModal { ...defaultProps } type="" /> );

		const modal = getByTestId( 'modal' );
		expect( modal ).toHaveClass( 'classy-modal__' );
		expect( modal ).toHaveAttribute( 'data-overlay-class', 'classy-modal__overlay classy-modal__overlay--' );
	} );

	it( 'matches snapshot with default props', () => {
		const { container } = render( <ClassyModal { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with custom className', () => {
		const { container } = render( <ClassyModal { ...defaultProps } className="custom-class" /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with custom overlay className', () => {
		const { container } = render( <ClassyModal { ...defaultProps } overlayClassName="custom-overlay" /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with complex children', () => {
		const { container } = render(
			<ClassyModal { ...defaultProps }>
				<div className="modal-content">
					<header>
						<h1>Complex Modal</h1>
					</header>
					<main>
						<p>This is a complex modal with multiple elements.</p>
						<form>
							<input type="text" placeholder="Enter text" />
							<button type="submit">Submit</button>
						</form>
					</main>
				</div>
			</ClassyModal>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );
} );
