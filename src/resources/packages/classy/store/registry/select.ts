import { AnyConfig, CurriedSelectorsOf, StoreDescriptor } from '@wordpress/data/build-types/types';
import { getRegistry } from './registry';

/**
 * Drop-in replacement for `select` from `@wordpress/data` to select from stores in
 * the Classy controlled registry.
 *
 * Given a store descriptor, returns an object of the store's selectors.
 * The selector functions are been pre-bound to pass the current state automatically.
 * As a consumer, you need only pass arguments of the selector, if applicable.
 *
 *
 * @param {string|StoreDescriptor} storeNameOrDescriptor The store descriptor.
 *
 * @example
 * ```js
 * import {select} from "@tec/common/classy/store";
 *
 * // Select from a custom store.
 * const attribute = select( 'tec/classy/my-store' ).getAttribute( 'linkedCount', 23 );
 *
 * // Select from the a WordPress core store.
 * const meta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
 * ```
 *
 * @return {Object} The store selectors.
 */
export function select< T extends StoreDescriptor< AnyConfig > >(
	storeNameOrDescriptor: string | T
): CurriedSelectorsOf< T > {
	return getRegistry().select( storeNameOrDescriptor );
}
