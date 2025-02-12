/**
 * External dependencies
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { noop } from 'lodash';

/**
 * Internal dependencies
 */
import CheckboxInput from '@moderntribe/common/elements/checkbox-input';

const Checkbox = ( {
	checked = false,
	className,
	disabled,
	id,
	label,
	onChange = noop,
	name,
	value,
} ) => {
	return (
		<div className={ classNames( 'tribe-editor__checkbox', className ) }>
			<CheckboxInput
				checked={ checked }
				className="tribe-editor__checkbox__input"
				disabled={ disabled }
				id={ id }
				name={ name }
				onChange={ onChange }
				value={ value }
			/>
			<label
				className="tribe-editor__checkbox__label"
				htmlFor={ id }
			>
				{ label }
			</label>
		</div>
	);
};

Checkbox.propTypes = {
	checked: PropTypes.bool.isRequired,
	className: PropTypes.string,
	disabled: PropTypes.bool,
	id: PropTypes.string,
	label: PropTypes.node,
	name: PropTypes.string,
	onChange: PropTypes.func,
	value: PropTypes.string,
};

export default Checkbox;
