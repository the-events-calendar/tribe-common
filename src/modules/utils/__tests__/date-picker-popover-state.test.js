/**
 * Internal dependencies
 */
import {
	closeAllDatePickers,
	getOpenDatePickerCount,
	isAnyDatePickerOpen,
	isDatePickerInteractionPending,
	markDatePickerInteractionPending,
	registerDatePickerClose,
	registerDatePickerCloseHandler,
	registerDatePickerOpen,
} from '../date-picker-popover-state';

describe( 'date-picker-popover-state', () => {
	beforeEach( () => {
		closeAllDatePickers();
	} );

	it( 'tracks open date pickers', () => {
		expect( isAnyDatePickerOpen() ).toBe( false );

		registerDatePickerOpen();
		expect( getOpenDatePickerCount() ).toBe( 1 );

		registerDatePickerClose();
		expect( isAnyDatePickerOpen() ).toBe( false );
	} );

	it( 'closes all registered date pickers', () => {
		const closeHandler = jest.fn();

		registerDatePickerOpen();
		registerDatePickerCloseHandler( closeHandler );

		closeAllDatePickers();

		expect( closeHandler ).toHaveBeenCalledTimes( 1 );
		expect( isAnyDatePickerOpen() ).toBe( false );
		expect( isDatePickerInteractionPending() ).toBe( false );
	} );

	it( 'unregisters close handlers', () => {
		const closeHandler = jest.fn();
		const unregister = registerDatePickerCloseHandler( closeHandler );

		unregister();
		closeAllDatePickers();

		expect( closeHandler ).not.toHaveBeenCalled();
	} );

	it( 'clears pending interaction state when closing all pickers', () => {
		markDatePickerInteractionPending();

		closeAllDatePickers();

		expect( isDatePickerInteractionPending() ).toBe( false );
	} );
} );
