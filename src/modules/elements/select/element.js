/**
 * External dependencies
 */
import React, { useCallback, useMemo, useState } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import ReactSelect, { components } from 'react-select';
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
			<Dashicon className="tribe-editor__select__dropdown-indicator" icon={ 'arrow-down' } />
		</components.DropdownIndicator>
	);

const IndicatorSeparator = () => null;

const Select = ( { className, ...rest } ) => {
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
				? createCache( { key: 'tribe-editor-select', container: ownerDocument.head } )
				: null,
		[ ownerDocument ]
	);

	const select = (
		<ReactSelect
			className={ classNames( 'tribe-editor__select', className ) }
			classNamePrefix="tribe-editor__select"
			components={ { DropdownIndicator, IndicatorSeparator } }
			{ ...rest }
		/>
	);

	return (
		<div ref={ refCallback } className="tribe-editor__select-wrapper">
			{ cache ? <CacheProvider value={ cache }>{ select }</CacheProvider> : select }
		</div>
	);
};

Select.propTypes = {
	className: PropTypes.string,
};

export default Select;
