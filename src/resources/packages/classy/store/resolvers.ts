import apiFetch from '@wordpress/api-fetch';
import { decodeEntities } from '@wordpress/html-entities';

export default {
	getCountryOptions:
		() =>
		async ( { dispatch } ): Promise< void > => {
			return apiFetch( {
				path: '/tec/classy/v1/options/country',
				method: 'GET',
			} )
				.then( ( options: Object[] ) => {
					const countryOptions = Object.values( options )
						.filter( ( option: any ) => {
							return typeof option === 'object' && option?.value && option?.name;
						} )
						.map( ( option: { value: string | number; name: string | number } ) => ( {
							key: String( option.value ),
							value: String( option.value ),
							name: decodeEntities( String( option.name ) ),
						} ) );

					dispatch.setCountryOptions( countryOptions );
				} )
				.catch( ( error: Error ) => {
					throw new Error( `Failed to fetch country options: ${ error.message }` );
				} );
		},
};
