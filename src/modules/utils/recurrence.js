/**
 * Internal dependencies
 */
import { plugins } from '../data';

/**
 * Returns whether the Event has at least one recurrence rule or not.
 *
 * @since 5.0.0
 * @param {Object} state The current container state.
 * @return {boolean} Whether the Event has at least one recurrence rule or not.
 */
export const hasRecurrenceRules = ( state ) => {
	try {
		const pluginOffset = plugins.constants.EVENTS_PRO_PLUGIN;
		const globalObject = window.tribe?.[ pluginOffset ] || window.tec?.[ pluginOffset ]?.app?.main;
		return globalObject.data.blocks.recurring.selectors.hasRules( state );
	} catch ( e ) {
		console.error( e );
		return false;
	}
};

/**
 * Returns whether tickets are allowed on Recurring events or not.
 *
 * @since 5.0.0
 *
 * @return {boolean} Whether tickets are allowed on Recurring events or not.
 */
export const noTicketsOnRecurring = () => {
	return document.body.classList.contains( 'tec-no-tickets-on-recurring' );
};

/**
 * Returns whether RSVPs are allowed on Recurring events or not.
 *
 * @since 5.8.0
 *
 * @return {boolean} Whether RSVPs are allowed on Recurring events or not.
 */
export const noRsvpsOnRecurring = () => {
	return document.body.classList.contains( 'tec-no-rsvp-on-recurring' );
};
