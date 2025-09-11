import apiFetch from '@wordpress/api-fetch';
import { APIFetchOptions } from '@wordpress/api-fetch/build-types/types';

/**
 * Base URL for The Events Calendar API endpoints.
 *
 * @since TBD
 */
export const tecApiBaseUrl = '/tec/v1';

/**
 * Fetch data from the API.
 *
 * Wraps the `apiFetch` function to include a custom header indicating that this endpoint is experimental.
 *
 * @since TBD
 * @param {APIFetchOptions} params The parameters for the API fetch request, including headers, path, method, and data.
 * @return {Promise<any>} A promise that resolves to the response data from the API.
 */
export const fetch = ( params: APIFetchOptions ): Promise< any > => {
	const { headers = {} } = params;
	const requestParams = {
		...params,
		headers: {
			...headers,
			'X-TEC-EEA':
				'I understand that this endpoint is experimental and may change in a future release without maintaining backward compatibility. I also understand that I am using this endpoint at my own risk, while support is not provided for it.',
		},
	};

	return apiFetch( requestParams );
};

/**
 * Get the API route for a given path.
 *
 * @since TBD
 *
 * @param {string} path The path to append to the base API URL.
 */
export const getRoute = ( path: string ): string => {
	return `${ tecApiBaseUrl }${ path }`;
}
