import * as React from 'react';

export function InputLabel( { label }: { label: string } ) {
	return (
		<span
			className="classy-component__inlabel"
			style={ { textTransform: 'capitalize', fontSize: 'var(--classy-font-size-15)' } }
		>
			{ label }
		</span>
	);
}
