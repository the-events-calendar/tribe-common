/**
 * External dependencies
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import ReactSelect, { components } from 'react-select';
import { Dashicon } from '@wordpress/components';

/**
 * Internal dependencies
 */
import EmotionStylesProvider from '../emotion-styles-provider';
import './style.pcss';

const DropdownIndicator = ( props ) =>
	components.DropdownIndicator && (
		<components.DropdownIndicator { ...props }>
			<Dashicon className="tribe-editor__select__dropdown-indicator" icon={ 'arrow-down' } />
		</components.DropdownIndicator>
	);

const IndicatorSeparator = () => null;

const Select = ( { className, ...rest } ) => (
	<EmotionStylesProvider cacheKey="tribe-editor-select" className="tribe-editor__select-wrapper">
		<ReactSelect
			className={ classNames( 'tribe-editor__select', className ) }
			classNamePrefix="tribe-editor__select"
			components={ { DropdownIndicator, IndicatorSeparator } }
			{ ...rest }
		/>
	</EmotionStylesProvider>
);

Select.propTypes = {
	className: PropTypes.string,
};

export default Select;
