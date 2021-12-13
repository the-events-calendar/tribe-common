/**
 * Internal dependencies
 */
import { PREFIX_COMMON_STORE } from '@moderntribe/common/data/utils';
import * as types from '@moderntribe/common/store/middlewares/request/types';

describe( '[STORE] - Request types', () => {
	it( 'Should return the types values', () => {
		expect( types.WP_REQUEST ).toBe( `${ PREFIX_COMMON_STORE }/WP_REQUEST` );
	} );
} );
