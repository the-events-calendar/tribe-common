/**
 * Internal dependencies
 */
import { constants } from '@moderntribe/common/data/plugins';

describe( 'Plugin constants', () => {
	test( 'Events plugin', () => {
		expect( constants.EVENTS_PLUGIN ).toEqual( 'events' );
	} );
	test( 'Events pro plugin', () => {
		expect( constants.EVENTS_PRO_PLUGIN ).toEqual( 'eventsPro' );
	} );
	test( 'Tickets plugin', () => {
		expect( constants.TICKETS ).toEqual( 'tickets' );
	} );
	test( 'Tickets plus plugin', () => {
		expect( constants.TICKETS_PLUS ).toEqual( 'ticketsPlus' );
	} );
} );
