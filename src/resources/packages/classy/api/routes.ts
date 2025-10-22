/**
 * Base URL for The Events Calendar API endpoints.
 *
 * @since TBD
 */
export const tecApiBaseUrl = '/tec/v1';

/**
 * Base URL for the Classy API endpoints.
 *
 * @since TBD
 */
export const classyApiUrl = '/tec/classy/v1';

/**
 * Get the TEC API route for a given path.
 *
 * @since TBD
 *
 * @param {string} path The path to append to the base API URL.
 */
export function getTecApiUrl( path: string ): string {
	return `${ tecApiBaseUrl }${ path }`;
}

/**
 * Get the Classy API route for a given path.
 *
 * @since TBD
 *
 * @param {string} path The path to append to the base API URL.
 */
export function getClassyApiUrl( path: string ): string {
	return `${ classyApiUrl }${ path }`;
}
