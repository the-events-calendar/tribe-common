/**
 * Internal dependencies
 */
import { constants } from '@moderntribe/common/data/plugins';

describe( 'Plugin constants', () => {
	test( 'Events plugin', () => {
		expect( constants.EVENTS_PLUGIN ).toMatchSnapshot();
	} );
	test( 'Events pro plugin', () => {
		expect( constants.EVENTS_PRO_PLUGIN ).toMatchSnapshot();
	} );
	test( 'Tickets plugin', () => {
		expect( constants.TICKETS ).toMatchSnapshot();
	} );
	test( 'Tickets plus plugin', () => {
		expect( constants.TICKETS_PLUS ).toMatchSnapshot();
	} );
} );
