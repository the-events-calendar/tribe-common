import React from 'react';
import { Slot, SlotFillProvider } from '@wordpress/components';
import { doAction } from '@wordpress/hooks';
import { _x } from '@wordpress/i18n';
import {
	EventDetails,
	EventTitle,
	EventDateTime,
	EventOrganizer,
	EventLocation,
} from './fields';
import { WPDataRegistry } from '@wordpress/data/build-types/registry';
import ErrorBoundary from './components/ErrorBoundary';
import ErrorDisplay from './components/ErrorDisplay';
import { RegistryProvider, useSelect } from '@wordpress/data';

function TestingComponent(){
	const postTitle = useSelect((select)=>{
		// @ts-ignore
		return select('core/editor').getEditedPostAttribute('title');
	}, []);

	return (
		<p>{postTitle}</p>
	)
}

function ClassyApplication() {
	return (
		<SlotFillProvider>
			{
				/**
				 * Filters the rendered JSX of the Classy component.
				 *
				 * This component is wrapped within a `SlotFillProvider` to allow dynamic content insertion
				 * via the `Slot/Fill` API. Use the `addFilter` hook to add Fills into the Classy application slots.
				 *
				 * @since TBD
				 */
				doAction( 'classy.render' )
			}

			<div className="classy-container">
				<TestingComponent/>

				<Slot name="classy.fields" />
			</div>
		</SlotFillProvider>
	);
}

export function Classy( { registry }: { registry: WPDataRegistry } ) {
	return (
		<ErrorBoundary
			fallback={ ( error: Error ) => <ErrorDisplay error={ error } /> }
		>
			<RegistryProvider value={ registry }>
				<ClassyApplication />
			</RegistryProvider>
		</ErrorBoundary>
	);
}
