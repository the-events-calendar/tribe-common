// @ts-nocheck
import * as React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it } from '@jest/globals';
import { ClassyModalActions } from '@tec/common/classy/components';

// Mock the WordPress Button component
jest.mock( '@wordpress/components', () => ( {
	Button: ( { children, variant, onClick, ...props } ) => (
		<button
			data-testid="wp-button"
			data-variant={ variant }
			onClick={ onClick }
			{ ...props }
		>
			{ children }
		</button>
	),
} ) );

describe( 'ClassyModalActions Component', () => {
	const defaultProps = {
		type: 'test-actions',
	};

	it( 'renders correctly with required props', () => {
		const { container } = render( <ClassyModalActions { ...defaultProps } /> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions' );
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions--test-actions' );
	} );

	it( 'applies correct className based on type', () => {
		const { container } = render( <ClassyModalActions { ...defaultProps } /> );

		const actionsElement = container.firstChild as HTMLElement;
		expect( actionsElement ).toHaveClass( 'classy-modal__actions' );
		expect( actionsElement ).toHaveClass( 'classy-modal__actions--test-actions' );
	} );

	it( 'handles different type values', () => {
		const { container } = render(
			<ClassyModalActions { ...defaultProps } type="confirmation-actions" />
		);

		const actionsElement = container.firstChild as HTMLElement;
		expect( actionsElement ).toHaveClass( 'classy-modal__actions--confirmation-actions' );
	} );

	it( 'handles type with special characters', () => {
		const { container } = render(
			<ClassyModalActions { ...defaultProps } type="actions-with-special_chars.123" />
		);

		const actionsElement = container.firstChild as HTMLElement;
		expect( actionsElement ).toHaveClass( 'classy-modal__actions--actions-with-special_chars.123' );
	} );

	it( 'handles empty type', () => {
		const { container } = render( <ClassyModalActions { ...defaultProps } type="" /> );

		const actionsElement = container.firstChild as HTMLElement;
		expect( actionsElement ).toHaveClass( 'classy-modal__actions--' );
	} );

	it( 'renders single button child correctly', () => {
		const { getByTestId, getByText } = render(
			<ClassyModalActions { ...defaultProps }>
				<button data-testid="wp-button" variant="primary">
					Save
				</button>
			</ClassyModalActions>
		);

		expect( getByTestId( 'wp-button' ) ).toBeInTheDocument();
		expect( getByText( 'Save' ) ).toBeInTheDocument();
	} );

	it( 'renders multiple button children correctly', () => {
		const { getAllByTestId, getByText } = render(
			<ClassyModalActions { ...defaultProps }>
				<button data-testid="wp-button" variant="primary">
					Save
				</button>
				<button data-testid="wp-button" variant="secondary">
					Cancel
				</button>
				<button data-testid="wp-button" variant="link">
					Delete
				</button>
			</ClassyModalActions>
		);

		const buttons = getAllByTestId( 'wp-button' );
		expect( buttons ).toHaveLength( 3 );
		expect( getByText( 'Save' ) ).toBeInTheDocument();
		expect( getByText( 'Cancel' ) ).toBeInTheDocument();
		expect( getByText( 'Delete' ) ).toBeInTheDocument();
	} );

	it( 'renders array of button children correctly', () => {
		const buttons = [
			<button key="save" data-testid="wp-button" variant="primary">
				Save
			</button>,
			<button key="cancel" data-testid="wp-button" variant="secondary">
				Cancel
			</button>,
		];

		const { getAllByTestId, getByText } = render(
			<ClassyModalActions { ...defaultProps }>{ buttons }</ClassyModalActions>
		);

		const renderedButtons = getAllByTestId( 'wp-button' );
		expect( renderedButtons ).toHaveLength( 2 );
		expect( getByText( 'Save' ) ).toBeInTheDocument();
		expect( getByText( 'Cancel' ) ).toBeInTheDocument();
	} );

	it( 'handles null children gracefully', () => {
		const { container } = render( <ClassyModalActions { ...defaultProps }>{ null }</ClassyModalActions> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions' );
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions--test-actions' );
	} );

	it( 'handles undefined children gracefully', () => {
		const { container } = render( <ClassyModalActions { ...defaultProps }>{ undefined }</ClassyModalActions> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions' );
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions--test-actions' );
	} );

	it( 'handles false children gracefully', () => {
		const { container } = render( <ClassyModalActions { ...defaultProps }>{ false }</ClassyModalActions> );

		expect( container.firstChild ).toBeInTheDocument();
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions' );
		expect( container.firstChild as HTMLElement ).toHaveClass( 'classy-modal__actions--test-actions' );
	} );

	it( 'handles mixed valid and invalid children', () => {
		const { getAllByTestId, getByText } = render(
			<ClassyModalActions { ...defaultProps }>
				<button data-testid="wp-button" variant="primary">
					Save
				</button>
				{ null }
				<button data-testid="wp-button" variant="secondary">
					Cancel
				</button>
				{ false }
				{ undefined }
			</ClassyModalActions>
		);

		const buttons = getAllByTestId( 'wp-button' );
		expect( buttons ).toHaveLength( 2 );
		expect( getByText( 'Save' ) ).toBeInTheDocument();
		expect( getByText( 'Cancel' ) ).toBeInTheDocument();
	} );

	it( 'renders children with click handlers', () => {
		const handleSave = jest.fn();
		const handleCancel = jest.fn();

		const { getByText } = render(
			<ClassyModalActions { ...defaultProps }>
				<button data-testid="wp-button" variant="primary" onClick={ handleSave }>
					Save
				</button>
				<button data-testid="wp-button" variant="secondary" onClick={ handleCancel }>
					Cancel
				</button>
			</ClassyModalActions>
		);

		const saveButton = getByText( 'Save' );
		const cancelButton = getByText( 'Cancel' );

		expect( saveButton ).toBeInTheDocument();
		expect( cancelButton ).toBeInTheDocument();
	} );

	it( 'renders children with different button variants', () => {
		const { Button } = require( '@wordpress/components' );
		const { getAllByTestId } = render(
			<ClassyModalActions { ...defaultProps }>
				<Button variant="primary">Primary</Button>
				<Button variant="secondary">Secondary</Button>
				<Button variant="tertiary">Tertiary</Button>
				<Button variant="link">Link</Button>
			</ClassyModalActions>
		);

		const buttons = getAllByTestId( 'wp-button' );
		expect( buttons ).toHaveLength( 4 );
		expect( buttons[ 0 ] ).toHaveAttribute( 'data-variant', 'primary' );
		expect( buttons[ 1 ] ).toHaveAttribute( 'data-variant', 'secondary' );
		expect( buttons[ 2 ] ).toHaveAttribute( 'data-variant', 'tertiary' );
		expect( buttons[ 3 ] ).toHaveAttribute( 'data-variant', 'link' );
	} );

	it( 'renders children with additional props', () => {
		const { getByTestId } = render(
			<ClassyModalActions { ...defaultProps }>
				<button
					data-testid="wp-button"
					variant="primary"
					disabled
					data-custom="test-value"
				>
					Disabled Button
				</button>
			</ClassyModalActions>
		);

		const button = getByTestId( 'wp-button' );
		expect( button ).toBeDisabled();
		expect( button ).toHaveAttribute( 'data-custom', 'test-value' );
	} );

	it( 'matches snapshot with default props', () => {
		const { container } = render( <ClassyModalActions { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with single button', () => {
		const { container } = render(
			<ClassyModalActions { ...defaultProps }>
				<button data-testid="wp-button" variant="primary">
					Save
				</button>
			</ClassyModalActions>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with multiple buttons', () => {
		const { container } = render(
			<ClassyModalActions { ...defaultProps }>
				<button data-testid="wp-button" variant="primary">
					Save
				</button>
				<button data-testid="wp-button" variant="secondary">
					Cancel
				</button>
				<button data-testid="wp-button" variant="link">
					Delete
				</button>
			</ClassyModalActions>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with mixed children', () => {
		const { container } = render(
			<ClassyModalActions { ...defaultProps }>
				<button data-testid="wp-button" variant="primary">
					Save
				</button>
				{ null }
				<button data-testid="wp-button" variant="secondary">
					Cancel
				</button>
				{ false }
			</ClassyModalActions>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'matches snapshot with different type', () => {
		const { container } = render(
			<ClassyModalActions { ...defaultProps } type="confirmation-dialog">
				<button data-testid="wp-button" variant="primary">
					Confirm
				</button>
				<button data-testid="wp-button" variant="secondary">
					Cancel
				</button>
			</ClassyModalActions>
		);

		expect( container.firstChild ).toMatchSnapshot();
	} );
} );
