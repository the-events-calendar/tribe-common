import * as React from 'react';
import { Component } from 'react';
import ErrorDisplay from './ErrorDisplay';
import { _x } from '@wordpress/i18n';

type ErrorBoundaryProps = {
	children: React.ReactNode;
	errorMessage?: string;
};

type ErrorBoundaryState = {
	error: Error | null;
};

const defaultErrorMessage = _x(
	'Classy has encountered an error:',
	'Error message displayed when an error occurs in the Classy editor. An error message will follow.',
	'tribe-common'
);

/**
 * A boundary component that catches JavaScript errors anywhere in its child component tree,
 * logs those errors, and displays a fallback UI.
 *
 * @since TBD
 */
export default class ErrorBoundary extends Component< ErrorBoundaryProps, ErrorBoundaryState > {
	/**
	 * Constructs the ErrorBoundary instance with the initial state.
	 *
	 * @since TBD
	 *
	 * @param {ErrorBoundaryProps} props The properties passed to this component.
	 */
	constructor( props: ErrorBoundaryProps ) {
		super( props );
		this.state = { error: null };
	}

	/**
	 * Returns the error message to be displayed when an error occurs.
	 *
	 * @since TBD
	 *
	 * @return {string} The error message to display.
	 */
	getErrorMessage(): string {
		const { errorMessage = defaultErrorMessage } = this.props;

		return errorMessage;
	}

	/**
	 * Updates the state with the error caught by the nearest descendant error boundary.
	 *
	 * @since TBD
	 *
	 * @param {Error} error The error object that was thrown.
	 *
	 * @return {ErrorBoundaryState} The new state of the component.
	 */
	static getDerivedStateFromError( error: Error ): ErrorBoundaryState {
		return { error };
	}

	/**
	 * Logs the error and its information to the console.
	 *
	 * @since TBD
	 *
	 * @param {any} error The error object that was thrown.
	 * @param {any} errorInfo Information about which component threw the error.
	 */
	componentDidCatch( error: any, errorInfo: any ) {
		console.error( this.getErrorMessage(), error, errorInfo );
	}

	/**
	 * Renders either the fallback UI if there is an error in the state,
	 * or the children components otherwise.
	 *
	 * @since TBD
	 *
	 * @return {React.ReactNode} The component to render.
	 */
	render(): React.ReactNode {
		if ( this.state.error !== null ) {
			return <ErrorDisplay error={ this.state.error } />;
		}

		return this.props.children;
	}
}
