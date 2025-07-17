import * as React from 'react';
import { VirtualElement } from '@wordpress/components/build-types/popover/types';
import { Popover, SelectControl, Button } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { _x } from '@wordpress/i18n';
import { IconClose } from '../Icons';
import { localizedData } from '../../localizedData';
import { parse as hpqParse } from 'hpq';

// @todo pull this from the store.
// @see `wp_timezone_choice`.
const timezoneChoice = localizedData.settings.timezoneChoice;

export default function TimezoneSelectionPopover( props: {
	anchor: Element | VirtualElement | null;
	onClose: () => void;
	onTimezoneChange: ( timezone: string ) => void;
	timezone: string;
} ) {
	const { anchor, onClose, onTimezoneChange, timezone } = props;
	const eventUsesUtc = timezone.startsWith( 'UTC' );

	const timezoneOptions = useMemo( () => {
		const parsedOptions: HTMLCollection = hpqParse( timezoneChoice, ( h ) => h ).children;

		return ( Array.from( parsedOptions ) as HTMLOptGroupElement[] ).map(
			( optgroup: HTMLOptGroupElement, index ) => {
				const options = Array.from( optgroup.children ) as HTMLOptionElement[];

				if ( options.length === 0 ) {
					return null;
				}

				if ( options[ 0 ].value.startsWith( 'UTC' ) && ! eventUsesUtc ) {
					// If the event does not use a UTC timezone, then do not show UTC timezone options.
					return null;
				}

				return (
					<optgroup key={ index } label={ optgroup.label }>
						{ options.map( ( option: HTMLOptionElement, optionIndex ) => (
							<option key={ optionIndex } value={ option.value }>
								{ option.label }
							</option>
						) ) }
					</optgroup>
				);
			}
		);
	}, [ timezoneChoice ] );

	return (
		<Popover
			anchor={ anchor }
			className="classy-component__popover classy-component__popover--choice classy-component__popover--timezone"
			expandOnMobile={ true }
			placement="bottom-end"
			noArrow={ true }
			offset={ 4 }
			onClose={ onClose }
		>
			<div className="classy-component__popover-content">
				<Button variant="link" onClick={ onClose } className="classy-component__popover-close">
					<IconClose />
				</Button>

				<h4 className="classy-component__popover-title">
					{ _x( 'Event Time Zone', 'Timezone selector popover title', 'tribe-common' ) }
				</h4>

				<p className="classy-component__popover-description">
					{ _x(
						'Choose a different time zone than your default for this event.',
						'Timezone selector popover description',
						'tribe-common'
					) }
				</p>

				<SelectControl
					className="classy-component__popover-input classy-component__popover-input--select classy-component__popover-input--timezone"
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					value={ timezone }
					onChange={ onTimezoneChange }
					autoFocus
				>
					{ timezoneOptions }
				</SelectControl>
			</div>
		</Popover>
	);
}
