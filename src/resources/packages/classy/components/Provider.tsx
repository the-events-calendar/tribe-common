import * as React from 'react';
import { WPDataRegistry } from '@wordpress/data/build-types/registry';
import { RegistryProvider, withRegistry } from '@wordpress/data';
import { doAction } from '@wordpress/hooks';
import { setRegistry as setRegistryInstance, STORE_NAME, storeConfig } from '../store';
import { ErrorBoundary } from './ErrorBoundary';

type ProviderComponentProps = {
	registry?: WPDataRegistry;
	children?: React.ReactNode;
};

/**
 * The component that, given a store registry, will initialize and render the Classy components in the context
 * of the given registry.
 *
 * This component is exported to allow its use in tests.
 * In production code the provider to use is the HOC component created usign the `withRegistry` function and
 * exported as `Provider`.
 *
 * @example
 * ```tsx
 * // Basic usage with default registry (automatically created by withRegistry).
 * import { ProviderComponent } from '@tec/common/classy/components/Provider';
 *
 * function App() {
 *   return (
 *     <ProviderComponent>
 *       <MyClassyComponents />
 *     </ProviderComponent>
 *   );
 * }
 *
 * // Advanced usage with custom registry.
 * import { createRegistry } from '@wordpress/data';
 * import { ProviderComponent } from '@tec/common/classy/components/Provider';
 *
 * const customRegistry = createRegistry();
 *
 * // Register custom stores before rendering.
 * customRegistry.registerStore('my-custom-store', {
 *   reducer: myReducer,
 *   actions: myActions,
 *   selectors: mySelectors,
 * });
 *
 * function AppWithCustomRegistry() {
 *   return (
 *     <ProviderComponent registry={customRegistry}>
 *       <MyClassyComponents />
 *     </ProviderComponent>
 *   );
 * }
 * ```
 *
 * @since TBD
 *
 * @param {ProviderComponentProps} props The component props.
 *
 * @returns {React.ReactElement} The rendered component.
 */
export function ProviderComponent( { registry, children }: ProviderComponentProps ): React.ReactElement {
	// Register the store and kick-start the initialization action if the store has not been registered yet.
	if ( ! registry.select( STORE_NAME ) ) {
		// Set the registry instances for selectors and dispatchers in stores.
		setRegistryInstance( registry );

		// Register the base Classy store.
		registry.registerStore( STORE_NAME, storeConfig );

		/**
		 * Fire an action when the Classy application has initialized.
		 * Initialized means the application got hold of the WordPress registry and registered the base store.
		 *
		 * @since TBD
		 */
		doAction( 'tec.classy.initialized' );
	}

	return (
		<ErrorBoundary>
			<RegistryProvider value={ registry }>{ children }</RegistryProvider>
		</ErrorBoundary>
	);
}

/**
 * The Classy Provider component.
 *
 * This provider should be used to wrap the Classy application, or, in the context of tests,
 * components that need to be tested in the context of a Classy-like context (in the React sense of the
 * word).
 * In production code this component must be used only once, by the Classy application.
 *
 * @example
 * ```
 * // MyClassyComponent.tsx
 * import {Provider as ClassyProvider} from "@tec/common/classy/components/Provider.tsx";
 * import {useSelect} from "@wordpress/data";
 *
 * function MyClassyComponent(){
 * 	 const {meta, settings}  = useSelect((select) => {
 * 	   const meta = select('core/editor').getEditedPostAttribute('meta');
 * 	   const settings = select('tec/classy').getSettings();
 *
 * 	   return {meta, settings};
 * 	 });
 *
 * 	 if(meta.hasZorps && settings.isGood){
 * 	   return <div>Has Zorps!</div>;
 * 	 }
 * }
 *
 * // MyClassyComponent.spec.tsx
 * import {render, findByText} from "@testing-library/react";
 * import {TestApplication} from "./TestApplication.tsx";
 *
 * describe('MyClasyApplication, ()=>{
 *   test('renders correctly', async () => {
 *     const {findByText} = render(<ClassyProvider><MyClassyComponent/></ClassyProvider>);
 *
 *     expect(await screen.findByText('Has Zorps!')).toBeVisible();
 *   }
 * });
 * ```
 *
 * @since TBD
 *
 * @returns {Element} The rendered component.
 */
export const Provider = withRegistry( ProviderComponent );
