import * as React from 'react';
import { IconProps } from '../../types/ElementProps';

/**
 * Renders a "Video Camera" icon.
 *
 * @since TBD
 *
 * @param {IconProps} props The component props.
 * @return {JSX.Element} The rendered "Video Camera" icon.
 */
export default function ( { className = '' }: IconProps ): JSX.Element {
	const fullClassName = `classy-icon classy-icon--video-camera${ className ? ` ${ className }` : '' }`;

	return (
		<span className={ fullClassName }>
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M2 7C2 5.89543 2.89543 5 4 5H14C15.1046 5 16 5.89543 16 7V17C16 18.1046 15.1046 19 14 19H4C2.89543 19 2 18.1046 2 17V7ZM4 6.5H14C14.2761 6.5 14.5 6.72386 14.5 7V17C14.5 17.2761 14.2761 17.5 14 17.5H4C3.72386 17.5 3.5 17.2761 3.5 17V7C3.5 6.72386 3.72386 6.5 4 6.5Z"
				/>
				<path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M17 10L22 7V17L17 14V10ZM18.5 13.1507V10.8493L20.5 9.64929V14.3507L18.5 13.1507Z"
				/>
			</svg>
		</span>
	);
}
