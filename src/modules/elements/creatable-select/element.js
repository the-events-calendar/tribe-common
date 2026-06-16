/**
 * External dependencies
 */
import React, { useCallback, useMemo, useState } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { components } from 'react-select';
import Select from 'react-select';
import { CacheProvider } from '@emotion/react';
import createCache from '@emotion/cache';
import { Dashicon } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './style.pcss';

const DropdownIndicator = ( props ) =>
	components.DropdownIndicator && (
		<components.DropdownIndicator { ...props }>
			<Dashicon className="tribe-editor__creatable-select__dropdown-indicator" icon={ 'arrow-down' } />
		</components.DropdownIndicator>
	);

const IndicatorSeparator = () => null;

/**
 * There seems to be an issue with Creatable and a custom isValidNewOption
 * prop needs to be passed in for this to work.
 *
 * See:
 * - https://github.com/JedWatson/react-select/issues/2630
 * - https://github.com/JedWatson/react-select/issues/2944
 */

const CreatableSelect = ( { className, ...rest } ) => {
	// In the iframed block editor canvas (WP 6.x+/7), react-select's emotion styles are
	// injected into the wrong document by default. Bind an emotion cache to the owner
	// document's head so the styles land inside the iframe where the select renders.
	const [ ownerDocument, setOwnerDocument ] = useState( null );

	const refCallback = useCallback( ( node ) => {
		if ( node ) {
			setOwnerDocument( node.ownerDocument );
		}
	}, [] );

	const cache = useMemo(
		() =>
			ownerDocument
				? createCache( { key: 'tribe-editor-creatable-select', container: ownerDocument.head } )
				: null,
		[ ownerDocument ]
	);

	const select = (
		<Select
			className={ classNames( 'tribe-editor__creatable-select', className ) }
			classNamePrefix="tribe-editor__creatable-select"
			components={ { DropdownIndicator, IndicatorSeparator } }
			{ ...rest }
		/>
	);

	return (
		<div ref={ refCallback } className="tribe-editor__creatable-select-wrapper">
			{ cache ? <CacheProvider value={ cache }>{ select }</CacheProvider> : select }
		</div>
	);
};

CreatableSelect.propTypes = {
	className: PropTypes.string,
};

export default CreatableSelect;
