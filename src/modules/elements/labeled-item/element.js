/**
 * External dependencies
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

const LabeledItem = ( {
	className,
	forId,
	isLabel = false,
	label,
	children,
} ) => {
	const renderLabel = (
		isLabel
			? (
				<label className="tribe-editor__labeled-item__label" htmlFor={ forId }>
					{ label }
				</label>
			)
			: (
				<span className="tribe-editor__labeled-item__label">
					{ label }
				</span>
			)
	);

	return (
		<div className={ classNames(
			'tribe-editor__labeled-item',
			className,
		) }>
			{ renderLabel }
			{ children }
		</div>
	);
};

LabeledItem.propTypes = {
	className: PropTypes.string,
	isLabel: PropTypes.bool,
	forId: PropTypes.string,
	label: PropTypes.node,
	children: PropTypes.node,
};

export default LabeledItem;
