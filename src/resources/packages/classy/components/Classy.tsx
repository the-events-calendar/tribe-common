import * as React from 'react';
import { Slot, SlotFillProvider } from '@wordpress/components';
import { applyFilters } from '@wordpress/hooks';
import { Provider as ClassyProvider } from './Provider';

/**
 * The main Classy application component.
 * By default, tha Classy application will render nothing, but it can be extended by plugins to
 * render their own components using the Slot/Fill API.
 *
 * @since TBD
 */
function ClassyApplication() {
	return (
		<SlotFillProvider>
			{
				/**
				 * Starts the rendering of the Classy application component.
				 *
				 * Extending plugins should hook on this filter to render their own components using the Slot/Fill API.
				 *
				 * Why is this a filter application instead of being an action? Because we're calling this inside `{}`
				 * which means the returned values (React nodes) will be rendered here.
				 * See notes on each Slot for more information and usage examples.
				 *
				 * @see https://developer.wordpress.org/block-editor/reference-guides/components/slot-fill/
				 *
				 * Incorrect usage of this filter is to render something outside a named Slot as it will render here.
				 * Bad example:
				 * ```
				 * addFilter(
				 * 	'tec.classy.render',
				 * 	'tec.classy.my-plugin',
				 * 	() => (
				 * 		<Fragment>
				 *          <p>HELLO FROM MY PLUGIN</p>
				 * 		</Fragment>
				 * 	)
				 * );
				 * ```
				 * This will render the paragraph before any other field. That is likely not the desired behavior.
				 *
				 * Correct usage is to add to this filter and then render in a named Slot(s).
				 *
				 * Good example:
				 * ```
				 * addFilter(
				 * 	'tec.classy.render',
				 * 	'tec.classy.my-plugin',
				 * 	(fields: React.ReactNode | null) => (
				 * 		<Fragment>
				 * 			{fields}
				 * 			<Fill name='tec.classy.fields'>
				 * 				<p>HELLO FROM MY PLUGIN</p>
				 * 			</Fill>
				 * 		</Fragment>
				 * 	)
				 * );
				 * ```
				 * In this example the rendered component (a paragraph) will be rendered among the Classy fields.
				 *
				 * Developers: do not add more filters, add more named Slots to control where things are rendered.
				 *
				 * @since TBD
				 *
				 * @param {React.ReactNode|null} The nodes previously rendered, starting with no nodes (null).
				 */
				applyFilters( 'tec.classy.render', null ) as React.ReactNode
			}

			{
				/**
				 * Renders before the Classy container.
				 *
				 * Extending plugins must hook on the `tec.classy.render` filter and render components in this Slot.
				 *
				 * Example:
				 * ```
				 * addFilter(
				 * 	'tec.classy.render',
				 * 	'tec.classy.my-plugin',
				 * 	(fields: React.ReactNode | null) => (
				 * 		<Fragment>
				 * 			{fields}
				 * 			<Fill name='tec.classy.before'>
				 * 				<p>HELLO FROM MY PLUGIN</p>
				 * 			</Fill>
				 * 		</Fragment>
				 * 	)
				 * );
				 * ```
				 *
				 * Note that, as in any filter, it's up to the extending plugin to manage priority in the filter
				 * or whether previous nodes will be rendered or not.
				 */
				<Slot name="tec.classy.before" />
			}

			<div className="classy-container">
				{
					/**
					 * Renders in the Classy container, before the fields.
					 *
					 * Extending plugins must hook on the `tec.classy.render` filter and render components in this Slot.
					 *
					 * Example:
					 * ```
					 * addFilter(
					 * 	'tec.classy.render',
					 * 	'tec.classy.my-plugin',
					 * 	(fields: React.ReactNode | null) => (
					 * 		<Fragment>
					 * 			{fields}
					 * 			<Fill name='tec.classy.fields.before'>
					 * 				<p>HELLO FROM MY PLUGIN</p>
					 * 			</Fill>
					 * 		</Fragment>
					 * 	)
					 * );
					 * ```
					 *
					 * Note that, as in any filter, it's up to the extending plugin to manage priority in the filter
					 * or whether previous nodes will be rendered or not.
					 */
					<Slot name="tec.classy.fields.before" />
				}

				{
					/**
					 * Renders in the Classy fields.
					 *
					 * Extending plugins must hook on the `tec.classy.render` filter and render components in this Slot.
					 *
					 * Example:
					 * ```
					 * addFilter(
					 * 	'tec.classy.render',
					 * 	'tec.classy.my-plugin',
					 * 	(fields: React.ReactNode | null) => (
					 * 		<Fragment>
					 * 			{fields}
					 * 			<Fill name='tec.classy.fields'>
					 * 				<p>HELLO FROM MY PLUGIN</p>
					 * 			</Fill>
					 * 		</Fragment>
					 * 	)
					 * );
					 * ```
					 *
					 * Note that, as in any filter, it's up to the extending plugin to manage priority in the filter
					 * or whether previous nodes will be rendered or not.
					 */
					<Slot name="tec.classy.fields" />
				}

				{
					/**
					 * Renders in the Classy container, after the fields.
					 *
					 * Extending plugins must hook on the `tec.classy.render` filter and render components in this Slot.
					 *
					 * Example:
					 * ```
					 * addFilter(
					 * 	'tec.classy.render',
					 * 	'tec.classy.my-plugin',
					 * 	(fields: React.ReactNode | null) => (
					 * 		<Fragment>
					 * 			{fields}
					 * 			<Fill name='tec.classy.fields.after'>
					 * 				<p>HELLO FROM MY PLUGIN</p>
					 * 			</Fill>
					 * 		</Fragment>
					 * 	)
					 * );
					 * ```
					 *
					 * Note that, as in any filter, it's up to the extending plugin to manage priority in the filter
					 * or whether previous nodes will be rendered or not.
					 */
					<Slot name="tec.classy.fields.after" />
				}
			</div>

			{
				/**
				 * Renders outside of the Classy container, after the fields.
				 *
				 * Extending plugins must hook on the `tec.classy.render` filter and render components in this Slot.
				 *
				 * Example:
				 * ```
				 * addFilter(
				 * 	'tec.classy.render',
				 * 	'tec.classy.my-plugin',
				 * 	(fields: React.ReactNode | null) => (
				 * 		<Fragment>
				 * 			{fields}
				 * 			<Fill name='tec.classy.after'>
				 * 				<p>HELLO FROM MY PLUGIN</p>
				 * 			</Fill>
				 * 		</Fragment>
				 * 	)
				 * );
				 * ```
				 *
				 * Note that, as in any filter, it's up to the extending plugin to manage priority in the filter
				 * or whether previous nodes will be rendered or not.
				 */
				<Slot name="tec.classy.after" />
			}
		</SlotFillProvider>
	);
}

/**
 * The Classy application rendered in the context of the Classy provider that will provide the application
 * with access to the WordPress default registry.
 *
 * @since TBD
 */
export function Classy() {
	return (
		<ClassyProvider>
			<ClassyApplication />
		</ClassyProvider>
	);
}
