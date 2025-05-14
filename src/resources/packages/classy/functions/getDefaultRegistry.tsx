import {WPDataRegistry} from "@wordpress/data/build-types/registry";
import {withRegistry} from "@wordpress/data";
import {createRoot} from "@wordpress/element";

/**
 * A reference to the default WordPress registry.
 *
 * @since TBD
 *
 * @type {WPDataRegistry|null}
 */
let defaultRegistry: WPDataRegistry | null = null;

/**
 * Returns the deafult WordPress registry.
 *
 * This is a hack. The Block Editor API does not expose the default registry object directly,
 * but provides access to it using the `withRegistry` higher-order component.
 *
 * @since TBD
 *
 * @return {Promise<WPDataRegistry>} A promise that will resolve to the default WordPress registry.
 */
export default async function getDefaultRegistry(): Promise<WPDataRegistry> {
	if(defaultRegistry){
		// Already resolved, return it.
		return defaultRegistry;
	}

	// Create a promise and capture its resolve function.
	let resolvePromise: (value: WPDataRegistry) => void;
	const promise = new Promise<WPDataRegistry>((resolve) => {
		resolvePromise = resolve;
	});

	// Create a React component that will capture the registry and resolve the promise.
	const RegistryFetcher = function ( { registry }:{registry: WPDataRegistry} ) {
		if(registry){
			// Store the reference to avoid running this code twice.
			defaultRegistry = registry;
			// Resolve the promise for this first time providing the default registry as value.
			resolvePromise((registry as  WPDataRegistry));
		}
		return null;
	};

	// Create a HOC that will pass the `registry` property to the wrapped component when rendered.
	const RegisterFetcherWithRegistry = withRegistry( RegistryFetcher );

	// Create a new div element to act as this utility React DOM root.
	// Do not attach the element to the DOM.
	const rootElement = createRoot(document.createElement('div'));

	// Render the `RegisterFetcherWithRegistry` component.
	// This will, in turn, render `<RegistryFetcher registry={registry} />` passing the default registry.
	rootElement.render(<RegisterFetcherWithRegistry />);

	// Return the promise to allow thenable chaining.
	return promise;
}
