import {STORE_NAME, storeConfig} from '../store';
import {createRegistry as wpDataCreateRegistry} from '@wordpress/data';
import {WPDataRegistry,} from '@wordpress/data/build-types/registry';
import getDefaultRegistry from "../../functions/getDefaultRegistry";

/**
 * Extend the global to let TypeScript know about the global window object.
 */
declare global {
	interface Window {
		wp?: {
			data?: {
				select: Function;
				dispatch: Function;
				subscribe: Function;
			};
		};
	}
}

/**
 * Creates the Classy registry.
 *
 * Depending on the running context (in Block Editor or not) the registry will behave
 * differently. In Block Editor context, the registry will have the WordPress default
 * registry as its parent; stores that are not defined in the Classy registry will be
 * searched in the WordPress registry. Outside the Block Editor context, the registry
 * will register the default core Stores itself and will not have any parent registry.
 *
 * @since TBD
 *
 * @return {Promise<WPDataRegistry>} A promise that resolves to the built Classy registry.
 */
export async function createRegistry(): Promise<WPDataRegistry> {
	let classyRegistry: WPDataRegistry;

	// Try and select from the WordPress data component a Block Editor store.
	if (window?.wp?.data?.select('core/block-editor')) {
		// We're in Blocks Editor context.
		const defaultRegistry = await getDefaultRegistry();
		classyRegistry = wpDataCreateRegistry(
			{[STORE_NAME]: storeConfig},
			defaultRegistry
		);
	} else {
		// Not in Block Editor context.
		classyRegistry = wpDataCreateRegistry({[STORE_NAME]: storeConfig});
	}

	return classyRegistry;
}
