import * as React from 'react';

type ErrorDisplayProps = {
	error: Error;
};

/**
 * A pure function component that displays an error message and stack trace.
 *
 * @since TBD
 *
 * @param {Error} error The error object to display.
 */
export default function ErrorDisplay( { error }: ErrorDisplayProps ): React.ReactNode {
	return (
		<div
			className="classy-root classy-root--error"
			style={ {
				alignItems: 'center',
				backgroundColor: '#fdd',
				border: '1px solid #d6e9c6',
				color: '#a94442',
				display: 'flex',
				flexDirection: 'column',
				gap: '20px',
				justifyContent: 'center',
				overflow: 'scroll',
				padding: '20px',
			} }
		>
			<h2>An error occurred in the Classy application:</h2>
			<pre
				style={ {
					backgroundColor: '#eee',
					borderRadius: '4px',
					marginTop: '20px',
					maxWidth: '100%',
					padding: '15px',
					textWrap: 'pretty',
				} }
			>
				{ error.stack }
			</pre>
		</div>
	);
}
