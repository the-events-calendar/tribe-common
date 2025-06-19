import { AnyConfig, DispatchReturn, StoreDescriptor } from '@wordpress/data/build-types/types';
import { getRegistry } from './registry';

/**
 * Drop-in replacement for `dispatch` from `@wordpress/data` to dispatch to stores in
 * the Classy controlled registry.
 *
 * Given a store descriptor, returns an object of the store's action creators.
 * Calling an action creator will cause it to be dispatched, updating the state value accordingly.
 *
 * Note: Action creators returned by the dispatch will return a promise when
 * they are called.
 *
 * @param storeNameOrDescriptor The store descriptor. The legacy calling convention of passing
 *                              the store name is also supported.
 *
 * @example
 * ```js
 * import { dispatch } from '@tec/common/classy/store';
 *
 * // Dispatch to a custom store.
 * dispatch( 'tec/classy/my-store' ).setAttribute( 'linkedCount', 23 );j
 *
 * // Dispatch to the a WordPress core store.
 * dispatch( 'core/editor' ).editPost( { meta: { foo: 'bar' } } );
 * ```
 * @return {Object} The store action creators.
 */
export function dispatch< StoreNameOrDescriptor extends StoreDescriptor< AnyConfig > | string >(
	storeNameOrDescriptor: StoreNameOrDescriptor
): DispatchReturn< StoreNameOrDescriptor > {
	return getRegistry().dispatch( storeNameOrDescriptor );
}
