/**
 * Internal dependencies
 */
import * as actions from '@moderntribe/common/store/middlewares/request/actions';

describe( '[STORE] - Request actions', () => {
	test( 'WP Request action', () => {
		const meta = {
			path: 'tribe_organizer/1225',
			actions: {},
		};
		expect( actions.wpRequest( meta ) ).toMatchSnapshot();
	} );
} );
