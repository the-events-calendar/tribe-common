import * as React from 'react';

export default function ( { className = '' }: { className?: string } ) {
	const fullClassName = 'classy-icon classy-icon--add' + ( className ? ` ${ className }` : '' );

	return (
		<span className={ fullClassName }>
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<path d="M11 12.5V17.5H12.5V12.5H17.5V11H12.5V6H11V11H6V12.5H11Z" />
			</svg>
		</span>
	);
}
