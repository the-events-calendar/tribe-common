import apiFetch from '@wordpress/api-fetch';
import { decodeEntities } from '@wordpress/html-entities';
import { Currency } from '@tec/common/classy/types/Currency';
import { getClassyApiUrl } from '../api';

export default {
	getCountryOptions:
		() =>
		async ( { dispatch } ): Promise< void > => {
			return apiFetch( {
				path: getClassyApiUrl( '/options/country' ),
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

	getUsStatesOptions:
		() =>
		async ( { dispatch } ): Promise< void > => {
			return apiFetch( {
				path: getClassyApiUrl( '/options/us-states' ),
				method: 'GET',
			} )
				.then( ( options: Object[] ) => {
					const usStatesOptions = Object.values( options )
						.filter( ( option: any ) => {
							return typeof option === 'object' && option?.value && option?.name;
						} )
						.map( ( option: { value: string | number; name: string | number } ) => ( {
							key: String( option.value ),
							value: String( option.value ),
							name: decodeEntities( String( option.name ) ),
						} ) );

					dispatch.setUsStateOptions( usStatesOptions );
				} )
				.catch( ( error: Error ) => {
					throw new Error( `Failed to fetch US states options: ${ error.message }` );
				} );
		},

	getCurrencyOptions:
		() =>
		async ( { dispatch } ): Promise< void > => {
			return apiFetch( {
				path: getClassyApiUrl( '/options/currencies' ),
				method: 'GET',
			} )
				.then( ( options: Currency[] ) => {
					const currencyOptions = Object.values( options ).filter( ( option: Currency ) => {
						return typeof option === 'object' && option?.code && option?.symbol && option?.position;
					} );

					dispatch.setCurrencyOptions( currencyOptions );
				} )
				.catch( ( error: Error ) => {
					throw new Error( `Failed to fetch currency options: ${ error.message }` );
				} );
		},
};
