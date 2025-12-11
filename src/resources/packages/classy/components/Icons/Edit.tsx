import * as React from 'react';
import { IconWithAriaProps } from '../../types/ElementProps';

/**
 * Renders an "Edit" icon.
 *
 * @since TBD
 *
 * @param {IconWithAriaProps} props The component props.
 * @return {JSX.Element} The rendered "Edit" icon.
 */
export default function ( { className = '', 'aria-hidden': ariaHidden = true }: IconWithAriaProps ): JSX.Element {
	const fullClassName = `classy-icon classy-icon--edit${ className ? ` ${ className }` : '' }`;

	return (
		<span className={ fullClassName } aria-hidden={ ariaHidden }>
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<path d="M19 7L16 4L7.5 12.5L6.5 16.5L10.5 15.5L19 7Z" />
				<path d="M12 18.5H5V20H12V18.5Z" />
			</svg>
		</span>
	);
}
