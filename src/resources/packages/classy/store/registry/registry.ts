import { STORE_NAME, storeConfig } from '../store';
import { createRegistry as wpDataCreateRegistry, StoreDescriptor } from '@wordpress/data';
import { WPDataRegistry } from '@wordpress/data/build-types/registry';
import getDefaultRegistry from '../../functions/getDefaultRegistry';
import '../../types/global.d.ts';

/**
 * The module Classy registry instance.
 *
 * @since TBD
 *
 * @type {WPDataRegistry}
 */
let registry: WPDataRegistry;

/**
 * Creates the Classy registry.
 *
 * Depending on the running context (in Block Editor or not), the registry will behave
 * differently. In Block Editor context, the registry will have the WordPress default
 * registry as its parent; stores that are not defined in the Classy registry will be
 * searched in the WordPress registry. Outside the Block Editor context, the registry
 * will register the default core Stores itself and will not have any parent registry.
 *
 * @since TBD
 *
 * @return {Promise<WPDataRegistry>} A promise that resolves to the built Classy registry.
 */
export async function createRegistry(): Promise< WPDataRegistry > {
	let classyRegistry: WPDataRegistry;

	// Try and select from the WordPress data component a Block Editor store.
	if ( window?.wp?.data?.select( 'core/block-editor' ) ) {
		// We're in Blocks Editor context.
		const defaultRegistry = await getDefaultRegistry();
		classyRegistry = wpDataCreateRegistry( { [ STORE_NAME ]: storeConfig }, defaultRegistry );
	} else {
		// Not in Block Editor context.
		// @todo here register the core stores.
		classyRegistry = wpDataCreateRegistry( {
			[ STORE_NAME ]: storeConfig,
		} );
	}

	return classyRegistry;
}

/**
 * Sets the Classy registry instance in the module.
 *
 * @since TBD
 *
 * @param {WPDataRegistry} registryInstance The Classy registry.
 *
 * @return {void} The registry instance is set.
 */
export function setRegistry( registryInstance: WPDataRegistry ): void {
	registry = registryInstance;
}

/**
 * Returns the module Classy registry instance.
 *
 * @since TBD
 *
 * @return {WPDataRegistry} The module Classy registry instance.
 */
export function getRegistry(): WPDataRegistry {
	return registry;
}

/**
 * Returns a registered store instance selectors.
 *
 * This is the function that must be used when needing to select from the Classy registry.
 * Components must use the `useSelect` hook that will already select from the Classy registry.
 * This function main use is in stores.
 *
 * Example:
 * ```
 * import {select} from '@tec/common/classy/store';
 *
 * const value = select('tec/classy').getValue();
 * const title = select('core/editor').getEditedPostAttribute('title');
 * ```
 *
 * @since TBD
 *
 * @param {StoreDescriptor} storeDescriptor The descriptor of the store to select from.
 *
 * @return {Object|undefined} The registered store instance selectors; undefined if the store is not registered.
 */
export function select( storeDescriptor: StoreDescriptor ): Object | undefined {
	return registry.select( storeDescriptor );
}

/**
 * Returns a registered store instance action creators.
 *
 * This is the function that must be used when needing to dispatch from the Classy registry.
 * Components must use the `useDispatch` hook that will already dispatch from the Classy registry.
 * This function main use is in stores.
 *
 * Example:
 * ```
 * import {dispatch} from '@tec/common/classy/store';
 *
 * const {updateValue} = dispatch('tec/classy');
 * const {editPost} = dispatch('core/editor');
 * ```
 *
 * @since TBD
 *
 * @param {StoreDescriptor} storeDescriptor The descriptor of the store to select from.
 *
 * @return {Object|undefined} The registered store instance action creators; undefined if the store is not registered.
 */
export function dispatch( storeDescriptor: StoreDescriptor ): Object | undefined {
	return registry.dispatch( storeDescriptor );
}
