import * as React from 'react';

export function InputLabel( { label }: { label: string } ) {
	return (
		<span className="classy-component__input-label" aria-hidden={ true }>
			{ label }
		</span>
	);
}
