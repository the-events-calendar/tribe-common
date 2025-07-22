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
