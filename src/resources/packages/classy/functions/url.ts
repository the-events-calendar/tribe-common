/**
 * Checks if a query parameter exists in the current URL and optionally matches a specific value.
 *
 * @since TBD
 *
 * @param {string} param - The name of the query parameter to check for.
 * @param {string} [value] - Optional. The expected value of the query parameter. If provided, the function
 *     will return true only if the parameter exists AND matches this value.
 *
 * @return {boolean} True if the parameter exists (and matches the value if provided), false otherwise.
 *
 * @example
 * // URL: https://example.com/editor?classy_enable_visual=1
 * hasQueryParam('classy_enable_visual'); // returns true
 * hasQueryParam('classy_enable_visual', '1'); // returns true
 * hasQueryParam('classy_enable_visual', '0'); // returns false
 * hasQueryParam('other_param'); // returns false
 */
export function hasQueryParam( param: string, value?: string ): boolean {
	try {
		const searchParams = new URLSearchParams( window.location.search );
		const paramValue = searchParams.get( param );

		if ( paramValue === null ) {
			return false;
		}

		// If no specific value is required, just check if the parameter exists
		if ( value === undefined ) {
			return true;
		}

		// Check if the parameter value matches the expected value
		return paramValue === value;
	} catch {
		return false;
	}
}

/**
 * Validates a URL string.
 *
 * @since TBD
 *
 * @param url - The URL string to validate.
 * @returns True if the URL is valid, false otherwise.
 */
export function isValidUrl( url: string ): boolean {
	// Allow empty values.
	if ( ! url || url.trim() === '' ) {
		return true;
	}

	try {
		// Add protocol if missing for validation.
		const urlToValidate = url.includes( '://' ) ? url : `https://${ url }`;
		const parsedUrl = new URL( urlToValidate );

		// Validate protocol for user-facing URLs.
		const validProtocols = [ 'http:', 'https:' ];
		if ( url.includes( '://' ) && ! validProtocols.includes( parsedUrl.protocol ) ) {
			return false;
		}

		// Additional validation for real-world use cases.
		const hostname = parsedUrl.hostname;

		// Reject URLs with spaces (not properly encoded).
		if ( url.includes( ' ' ) ) {
			return false;
		}

		// Reject hostnames that don't contain at least one dot (unless localhost or IP).
		if ( ! hostname.includes( '.' ) && hostname !== 'localhost' && ! /^\d+\.\d+\.\d+\.\d+$/.test( hostname ) ) {
			return false;
		}

		if ( hostname.includes( '..' ) ) {
			return false;
		}

		if ( hostname.startsWith( '.' ) || hostname.endsWith( '.' ) ) {
			return false;
		}

		return true;
	} catch {
		return false;
	}
}
