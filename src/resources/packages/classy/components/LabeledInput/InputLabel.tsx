import * as React from 'react';

export function InputLabel( { label }: { label: string } ) {
	return (
		<span
			className="classy-component__input-label"
			style={ {
				textTransform: 'capitalize',
				fontSize: 'var(--classy-font-size-15)',
				fontWeight: 'var(--tec-font-weight-regular)',
			} }
		>
			{ label }
		</span>
	);
}
