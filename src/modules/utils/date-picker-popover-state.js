/**
 * Tracks open nested date-picker popovers so parent popovers can ignore
 * dismiss events triggered by calendar interactions.
 */
let openDatePickerCount = 0;
let interactionPending = false;
let clearInteractionPendingTimerId = null;
const datePickerCloseHandlers = new Set();

/**
 * @return {number} The number of open date-picker popovers.
 */
export const getOpenDatePickerCount = () => openDatePickerCount;

/**
 * @return {boolean} Whether any date-picker popover is open.
 */
export const isAnyDatePickerOpen = () => openDatePickerCount > 0;

/**
 * @return {boolean} Whether a date-picker interaction is in progress.
 */
export const isDatePickerInteractionPending = () => interactionPending;

/**
 * Marks that focus is about to move into a date-picker field.
 *
 * Parent popovers should ignore the focus-outside event that fires on mousedown,
 * before the calendar open state has been committed.
 */
export const markDatePickerInteractionPending = () => {
	interactionPending = true;
	clearTimeout( clearInteractionPendingTimerId );
	clearInteractionPendingTimerId = setTimeout( () => {
		interactionPending = false;
		clearInteractionPendingTimerId = null;
	}, 100 );
};

/**
 * Registers a date-picker popover as open.
 */
export const registerDatePickerOpen = () => {
	openDatePickerCount += 1;
};

/**
 * Registers a date-picker popover as closed.
 */
export const registerDatePickerClose = () => {
	openDatePickerCount = Math.max( 0, openDatePickerCount - 1 );
};

/**
 * Registers a handler that closes a date-picker popover instance.
 *
 * @param {Function} handler Callback that closes the picker.
 * @return {Function} Unregister function.
 */
export const registerDatePickerCloseHandler = ( handler ) => {
	datePickerCloseHandlers.add( handler );

	return () => {
		datePickerCloseHandlers.delete( handler );
	};
};

/**
 * Closes every registered date-picker popover.
 */
export const closeAllDatePickers = () => {
	datePickerCloseHandlers.forEach( ( handler ) => {
		handler();
	} );

	openDatePickerCount = 0;
	interactionPending = false;

	if ( clearInteractionPendingTimerId ) {
		clearTimeout( clearInteractionPendingTimerId );
		clearInteractionPendingTimerId = null;
	}
};
