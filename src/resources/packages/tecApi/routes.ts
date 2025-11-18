/**
 * Base URL for The Events Calendar API endpoints.
 *
 * @since 6.10.0
 */
export const tecApiBaseUrl = '/tec/v1';

/**
 * Get the TEC API route for a given path.
 *
 * @since 6.10.0
 *
 * @param {string} path The path to append to the base API URL.
 */
export function getTecApiUrl( path: string = '' ): string {
	if ( path && ! path.startsWith( '/' ) ) {
		path = '/' + path;
	}
	return `${ tecApiBaseUrl }${ path }`;
}
