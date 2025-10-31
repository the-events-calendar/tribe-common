/**
 * Base URL for The Events Calendar API endpoints.
 *
 * @since TBD
 */
export const tecApiBaseUrl = '/tec/v1';

/**
 * Get the TEC API route for a given path.
 *
 * @since TBD
 *
 * @param {string} path The path to append to the base API URL.
 */
export function getTecApiUrl( path: string = '' ): string {
	if ( path && ! path.startsWith( '/' ) ) {
		path = '/' + path;
	}
	return `${ tecApiBaseUrl }${ path }`;
}
