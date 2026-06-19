/**
 * External dependencies
 */
import React, { useCallback, useMemo, useState } from 'react';
import { CacheProvider } from '@emotion/react';
import createCache from '@emotion/cache';

/**
 * EmotionStylesProvider
 *
 * In WordPress 6.x+, the block editor renders inside an iframe. This component
 * binds an Emotion cache to the owner document's <head>, so react-select styles
 * land inside the iframe rather than the parent document.
 *
 * @see https://github.com/JedWatson/react-select/issues/5281
 *
 * @param {Object} props
 * @param {React.ReactNode} props.children
 * @param {string} props.cacheKey  Emotion cache key — must be unique per select instance.
 * @param {string} [props.className] Class name applied to the wrapper element.
 */
const EmotionStylesProvider = ( { children, cacheKey, className } ) => {
	const [ ownerDocument, setOwnerDocument ] = useState( null );

	const refCallback = useCallback( ( node ) => {
		if ( node ) {
			setOwnerDocument( node.ownerDocument );
		}
	}, [] );

	const cache = useMemo(
		() =>
			ownerDocument
				? createCache( { key: cacheKey, container: ownerDocument.head } )
				: null,
		[ ownerDocument, cacheKey ]
	);

	return (
		<div ref={ refCallback } className={ className }>
			{ cache ? <CacheProvider value={ cache }>{ children }</CacheProvider> : children }
		</div>
	);
};

export default EmotionStylesProvider;
