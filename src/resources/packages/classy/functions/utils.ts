/**
 * Validates a URL string.
 *
 * @since TBD
 *
 * @param url - The URL string to validate.
 * @returns True if the URL is valid, false otherwise.
 */
export function isValidUrl( url: string ): boolean {
	if ( ! url || url.trim() === '' ) {
		return true; // Allow empty URLs
	}

	try {
		// Add protocol if missing for validation
		const urlToValidate = url.includes( '://' ) ? url : `https://${ url }`;
		const parsedUrl = new URL( urlToValidate );

		// Validate protocol for user-facing URLs
		const validProtocols = [ 'http:', 'https:' ];
		if ( url.includes( '://' ) && ! validProtocols.includes( parsedUrl.protocol ) ) {
			return false;
		}

		// Additional validation for real-world use cases
		const hostname = parsedUrl.hostname;

		// Reject URLs with spaces (not properly encoded)
		if ( url.includes( ' ' ) ) {
			return false;
		}

		// Reject hostnames that don't contain at least one dot (unless localhost or IP)
		if ( ! hostname.includes( '.' ) && hostname !== 'localhost' && ! /^\d+\.\d+\.\d+\.\d+$/.test( hostname ) ) {
			return false;
		}

		// Reject hostnames with consecutive dots
		if ( hostname.includes( '..' ) ) {
			return false;
		}

		// Reject hostnames that start or end with a dot
		if ( hostname.startsWith( '.' ) || hostname.endsWith( '.' ) ) {
			return false;
		}

		return true;
	} catch {
		return false;
	}
}
