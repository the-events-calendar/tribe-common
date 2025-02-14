/**
 * External dependencies
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

/**
 * Internal dependencies
 */
import './style.pcss';

export const SIZES = {
	medium: 'medium',
	small: 'small',
};

const Paragraph = ( { children, size = SIZES.medium, className } ) => (
	<p
		className={
			classNames(
				'tribe-editor__paragraph',
				`tribe-editor__paragraph--${ size }`,
				className,
			)
		}
	>
		{ children }
	</p>
);

Paragraph.propTypes = {
	children: PropTypes.node.isRequired,
	size: PropTypes.oneOf( Object.keys( SIZES ) ),
	className: PropTypes.string,
};

export default Paragraph;
