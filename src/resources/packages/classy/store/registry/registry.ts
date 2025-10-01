import { WPDataRegistry } from '@wordpress/data/build-types/registry';
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
