import * as React from 'react';
import { Spinner } from '@wordpress/components';

/**
 * A component that displays a centered spinner wrapping the default WordPress spinner
 * in an element styled to center in the parent.
 *
 * @since TBD
 *
 * @returns JSX.Element The centered spinner component.
 */
export function CenteredSpinner() {
	return (
		<div className="classy-component__spinner">
			<Spinner />
		</div>
	);
}
