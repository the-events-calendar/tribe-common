import { STORE_NAME, storeConfig } from '../store';
import { createRegistry as wpDataCreateRegistry } from '@wordpress/data';
import {
	StoreDescriptor,
	WPDataRegistry,
} from '@wordpress/data/build-types/registry';

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

type WPStoreSubscriptions = {
	[ key: string ]: {
		subscriber: Function;
		unsubscribe: Function;
	};
};

/**
 * The original select function.
 *
 * @since TBD
 *
 * @type {Function}
 */
let originalSelect: Function | null = null;

/**
 * The original dispatch function.
 *
 * @since TBD
 *
 * @type {Function}
 */
let originalDispatch: Function | null = null;

/**
 * The original subscribe function.
 *
 * @since TBD
 *
 * @type {Function}
 */
let originalSubscribe: Function | null = null;

/**
 * Debug flag to enable or disable debug logging.
 *
 * @since TBD
 *
 * @type {boolean}
 */
let debug: boolean = false;

/**
 * The unsubscribe function for the WordPress data subscribe hook.
 *
 * @since TBD
 *
 * @type {Function}
 */
let unsubscribeFromWP: Function = () => null;

/**
 * Array of WordPress store names the subscribe adapters will listen to.
 *
 * @since TBD
 *
 * @type {WPStoreSubscriptions}
 */
const wpSubscriptions: WPStoreSubscriptions = {};

/**
 * Helper function to extract the store name from a selector or descriptor.
 *
 * @since TBD
 *
 * @param {StoreDescriptor} storeSelector The store descriptor to extract the name from.
 *
 * @return {string} The name of the store.
 */
function getStoreNameFromDescriptor( storeSelector: string | StoreDescriptor ) {
	return typeof storeSelector === 'string'
		? storeSelector
		: storeSelector.name;
}

/**
 * Checks if a given store name is a Classy store.
 *
 * The check is very naive: it checks if the store name starts with "tec/classy".
 *
 * @since TBD
 *
 * @param {string} store The name of the store to check.
 *
 * @return {boolean} Whether the store is a Classy store.
 */
function isClassyStore( store: string ): boolean {
	return store.match( /^tec\/classy/ ) !== null;
}

/**
 * An adapter that will route data selections to the appropriate source.
 *
 * @since TBD
 *
 * @param {string|StoreDescriptor} storeDescriptor The name of the store to select from, or a StoreDescriptor.
 *
 * @returns {any} The selected data.
 */
function selectAdapter( storeDescriptor: string | StoreDescriptor ): any {
	const storeName = getStoreNameFromDescriptor( storeDescriptor );

	if ( isClassyStore( storeName ) ) {
		return originalSelect( STORE_NAME );
	}

	/**
	 * If the store exists on the `window.wp.data` object, then select from it.
	 */
	if ( window?.wp?.data?.select ) {
		const storeName = getStoreNameFromDescriptor( storeDescriptor );

		if ( ! wpSubscriptions.storeName ) {
			// We're not yet listening for updates from this WordPress store: subscribe to it now.
			const subscriber: Function = ( ...args ) => {
				console.log(
					`Getting updates from the ${ storeName } store of WP: ` +
						JSON.stringify( args )
				);
			};
			const unsubscribe: Function = window.wp.data.subscribe(
				subscriber,
				storeDescriptor
			);
			wpSubscriptions[ storeName ] = {
				subscriber,
				unsubscribe,
			};
		}

		// Return the value selected from the WordPress store.
		return window.wp.data.select( storeDescriptor );
	}

	console.error(
		`Classy select adapter: store ${ storeName } does not exist on window.wp.data or in Classy registry.`
	);

	return {}; // Return an empty object if the store cannot be selected.
}

/**
 * An adapter that will route data dispatches to the appropriate source.
 *
 * @since TBD
 *
 * @param {string|StoreDescriptor} storeDescriptor The name of the store to select from, or a StoreDescriptor.
 *
 * @returns {Object} The dispatched action.
 */
function dispatchAdapter( storeDescriptor: string | StoreDescriptor ): Object {
	const storeName = getStoreNameFromDescriptor( storeDescriptor );

	if ( storeName === STORE_NAME ) {
		return originalDispatch( STORE_NAME );
	}

	// If the store exists on the `window.wp.data` object, then dispatch from it.
	if ( window?.wp?.data?.dispatch ) {
		return window?.wp?.data.dispatch( storeName );
	}

	console.error(
		`Classy dispatch adapter: store ${ storeName } does not exist on window.wp.data or in Classy registry.`
	);

	return {};
}

/**
 * An adapter that will route subscriptions to the appropriate source.
 *
 * @since TBD
 *
 * @param {Function} subscriber The subscriber function.
 * @param {StoreDescriptor|null} storeDescriptor The name or description of the store to select from.
 *
 * @returns {Function} A function that will unsubscribe the subscriber.
 */
function subscribeAdapter(
	subscriber: Function,
	storeDescriptor: string | StoreDescriptor | null
): Function {
	const storeName = storeDescriptor
		? getStoreNameFromDescriptor( storeDescriptor )
		: null;

	if ( storeName ) {
		console.log( 'Subscribing to store:', storeName );
		if ( isClassyStore( storeName ) ) {
			// Subscribe to the store.
			return originalSubscribe( subscriber, storeDescriptor );
		}

		// Not a Classy store: assume it's trying to subscribe to the WP store.
		if ( window?.wp?.data?.subscribe ) {
			// The WP store exists: subscribe to it.
			return window.wp.data.subscribe( subscriber, storeDescriptor );
		} else {
			// The WP store does not exist: do nothing and return an empty unsubscribe function.
			return (): void => null;
		}
	}

	console.log( 'Subscribing to all stores.' );

	// A store is not specified: subscribe to all stores, WP and Classy.
	const unsubscribeFromClassy = originalSubscribe( subscriber );
	const unsubscribeFromWP = window?.wp?.data?.subscribe
		? window.wp.data.subscribe( subscriber )
		: () => {};

	return (): void => {
		unsubscribeFromWP();
		unsubscribeFromClassy();
	};
}

function wpSubscribe( ...args ): void {
	console.log( 'WP store did something: ' + JSON.stringify( args ) );
}

/**
 * Creates a registry, separated from the default WordPress one,
 * that will act as a router to handle requests coming from the Classy application components.
 * Requests for the Core stores will be handled by the WordPress registry if available.
 *
 * @since 1.0.0
 *
 * @return {WPDataRegistry} The created Classy registry.
 */
export function createRegistry(): WPDataRegistry {
	const classyRegistry: WPDataRegistry = wpDataCreateRegistry( {
		[ STORE_NAME ]: storeConfig,
	} );

	// Save a reference to the original `select` and `dispatch` functions.
	originalSelect = classyRegistry.select;
	originalDispatch = classyRegistry.dispatch;
	originalSubscribe = classyRegistry.subscribe;

	// Subscribe to the WordPress store if possible.
	if ( window?.wp?.data?.subscribe ) {
		unsubscribeFromWP = window?.wp.data.subscribe( wpSubscribe );
	}

	// Replace the default WordPress registry's select and dispatch functions with the adapters.
	classyRegistry.select = selectAdapter;
	classyRegistry.dispatch = dispatchAdapter;
	classyRegistry.subscribe = subscribeAdapter;

	return classyRegistry;
}
