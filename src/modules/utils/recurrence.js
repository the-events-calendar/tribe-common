/**
 * Recurrence-based global utils
 */
import { plugins } from '@moderntribe/common/data';

/**
 * Returns whether the Event has at least one recurrence rule or not.
 *
 * @since 5.0.0
 * @param {object} state The current container state.
 *
 * @returns {boolean} Whether the Event has at least one recurrence rule or not.
 */
export const hasRecurrenceRules = ( state ) => {
	let hasRules = false;
	try {
		hasRules = window.tribe[ plugins.constants.EVENTS_PRO_PLUGIN ]
			.data.blocks.recurring.selectors.hasRules( state );
	} catch ( e ) {
		// ¯\_(ツ)_/¯
	}
	return hasRules;
};

/**
 * Returns whether tickets are allowed on Recurring events or not.
 *
 * @since 5.0.0
 * @returns {boolean} Whether tickets are allowed on Recurring events or not.
 */
export const noTicketsOnRecurring = () => {
	return document.body.classList.contains( 'tec-no-tickets-on-recurring' );
};
