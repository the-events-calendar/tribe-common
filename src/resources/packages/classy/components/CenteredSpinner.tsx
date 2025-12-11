import * as React from 'react';
import { Spinner } from '@wordpress/components';

interface CenteredSpinnerProps {
	className?: string;
}

/**
 * A component that displays a centered spinner wrapping the default WordPress spinner
 * in an element styled to center in the parent.
 *
 * @since TBD
 *
 * @param {CenteredSpinnerProps} props The component props.
 * @param {string}               props.className The class name to apply to the spinner.
 *
 * @returns React.JSX.Element The centered spinner component.
 */
export function CenteredSpinner( { className }: CenteredSpinnerProps ) {
	const fullClassName = className ? `classy-component__spinner ${ className }` : 'classy-component__spinner';
	return (
		<div className={ fullClassName }>
			<Spinner />
		</div>
	);
}
