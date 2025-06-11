import * as React from 'react';
import { InputLabel } from './InputLabel';

export function LabeledInput( { label, children }: { label: string; children: React.ReactNode } ) {
	return (
		<span className="classy-component__labeled-input">
			<InputLabel label={ label } />
			{ children }
		</span>
	);
}
