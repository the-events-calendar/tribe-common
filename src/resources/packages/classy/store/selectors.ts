import { select } from '@wordpress/data';
import { StoreState } from '../types/StoreState';
import { EventDateTimeDetails } from '../types/EventDateTimeDetails';
import { EventMeta } from '../types/EventMeta';
import { getDate } from '@wordpress/date';
import { localizedData } from '../localizedData';
import { Settings } from '../types/LocalizedData';
import {
	METADATA_EVENT_ORGANIZER_ID,
	METADATA_EVENT_VENUE_ID,
} from '../constants';

export function getTimeInterval(): number {
	const settings = localizedData.settings;
	return settings.timeInterval;
}

// @todo move all below to TEC

/**
 * Returns the event date and time details, read from its meta. If the meta is not set
 * it will return default values
 *
 * @since TBD
 *
 * @param {StoreState} state The current store state.
 *
 * @returns {EventDateTimeDetails} The event date and time details.
 */
export function getEventDateTimeDetails(
	state: StoreState
): EventDateTimeDetails {
	// @todo update this to let the register handle the redirection.
	const coreEditor = select( 'core/editor' );
	let meta: EventMeta;

	if ( coreEditor ) {
		// @ts-ignore
		meta = coreEditor.getEditedPostAttribute( 'meta' ) ?? {};
	} else {
		meta = state?.meta || {};
	}

	const eventStartDateString = meta?._EventStartDate ?? '';
	const eventEndDateString = meta?._EventEndDate ?? '';

	let eventStart: Date;
	if ( eventStartDateString ) {
		eventStart = getDate( eventStartDateString );
	} else {
		eventStart = getDate( '' );
		eventStart.setHours( 8, 0, 0 );
	}

	let eventEnd: Date;
	if ( eventEndDateString ) {
		eventEnd = getDate( eventEndDateString );
	} else {
		eventEnd = getDate( '' );
		eventEnd.setHours( 17, 0, 0 );
	}
	const settings: Settings = localizedData.settings;
	const isMultiday =
		eventStart.getDate() !== eventEnd.getDate() ||
		eventStart.getMonth() !== eventEnd.getMonth() ||
		eventStart.getFullYear() !== eventEnd.getFullYear();
	const isAllDayStringValue = meta?._EventAllDay ?? '0';
	const isAllDay = isAllDayStringValue === '1';
	const eventTimezone = meta?._EventTimezone ?? settings.timezoneString;

	return {
		eventStart,
		eventEnd,
		isMultiday,
		isAllDay,
		eventTimezone,
		...settings,
	} as EventDateTimeDetails;
}

export function getEditedPostOrganizerIds( state: StoreState ): number[] {
	// @todo update this to let the register handle the redirection.
	const coreEditor = select( 'core/editor' );
	let meta: EventMeta;

	if ( coreEditor ) {
		// @ts-ignore
		meta = coreEditor.getEditedPostAttribute( 'meta' ) ?? {};
	} else {
		meta = state?.meta || {};
	}

	const ids = ( meta?.[ METADATA_EVENT_ORGANIZER_ID ] ?? [] ).map(
		( id: string | number ) =>
			typeof id === 'string' ? parseInt( id ) : id
	);

	return ids;
}

export function getEditedPostVenueIds( state: StoreState ): number[] {
	// @todo update this to let the register handle the redirection.
	const coreEditor = select( 'core/editor' );
	let meta: EventMeta;

	if ( coreEditor ) {
		// @ts-ignore
		meta = coreEditor.getEditedPostAttribute( 'meta' ) ?? {};
	} else {
		meta = state?.meta || {};
	}

	const ids = ( meta?.[ METADATA_EVENT_VENUE_ID ] ?? [] ).map(
		( id: string | number ) =>
			typeof id === 'string' ? parseInt( id ) : id
	);

	return ids;
}
