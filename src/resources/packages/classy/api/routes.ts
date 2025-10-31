/**
 * Base URL for the Classy API endpoints.
 *
 * @since TBD
 */
export const classyApiUrl = '/tec/classy/v1';

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
