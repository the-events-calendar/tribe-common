import apiFetch from '@wordpress/api-fetch';
import { APIFetchOptions } from '@wordpress/api-fetch/build-types/types';
import { tecApiBaseUrl } from './routes';

/**
 * Creates a middleware that adds the experimental header to TEC API requests.
 *
 * @since TBD
 *
 * @return {Function} The middleware function.
 */
function createTecExperimentalMiddleware(): {
	( options: APIFetchOptions, next: ( options: APIFetchOptions ) => Promise< any > ): Promise< any >;
} {
	return ( options: APIFetchOptions, next: ( options: APIFetchOptions ) => Promise< any > ) => {
		// Only add the header if this is a TEC API request
		if ( options.path && options.path.startsWith( tecApiBaseUrl ) ) {
			const modifiedOptions = {
				...options,
				headers: {
					...( options.headers || {} ),
					'X-TEC-EEA':
						'I understand that this endpoint is experimental and may change in a future release without maintaining backward compatibility. I also understand that I am using this endpoint at my own risk, while support is not provided for it.',
				},
			};
			return next( modifiedOptions );
		}
		return next( options );
	};
}

/**
 * Registers all middlewares for the TEC API.
 *
 * @since TBD
 */
export const registerMiddlewares = () => {
	apiFetch.use( createTecExperimentalMiddleware() );
};
