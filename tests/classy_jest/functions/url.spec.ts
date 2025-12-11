import { hasQueryParam, isValidUrl } from '@tec/common/classy/functions';
import { describe, expect, it, beforeEach, afterEach } from '@jest/globals';

describe( 'url', () => {
	describe( 'hasQueryParam', () => {
		let originalLocation: Location;

		beforeEach( () => {
			// Save original location
			originalLocation = window.location;
		} );

		afterEach( () => {
			// Restore original location
			Object.defineProperty( window, 'location', {
				value: originalLocation,
				writable: true,
			} );
		} );

		it( 'should return true when parameter exists with correct value', () => {
			// Mock window.location.search
			Object.defineProperty( window, 'location', {
				value: {
					search: '?classy_enable_visual=1',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'classy_enable_visual', '1' ) ).toBe( true );
		} );

		it( 'should return false when parameter exists with wrong value', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?classy_enable_visual=0',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'classy_enable_visual', '1' ) ).toBe( false );
		} );

		it( 'should return true when parameter exists and no value is specified', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?classy_enable_visual=1',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'classy_enable_visual' ) ).toBe( true );
		} );

		it( 'should return false when parameter does not exist', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?other_param=value',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'classy_enable_visual', '1' ) ).toBe( false );
			expect( hasQueryParam( 'classy_enable_visual' ) ).toBe( false );
		} );

		it( 'should return false when search string is empty', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'classy_enable_visual', '1' ) ).toBe( false );
			expect( hasQueryParam( 'classy_enable_visual' ) ).toBe( false );
		} );

		it( 'should handle multiple query parameters', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?foo=bar&classy_enable_visual=1&baz=qux',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'classy_enable_visual', '1' ) ).toBe( true );
			expect( hasQueryParam( 'foo', 'bar' ) ).toBe( true );
			expect( hasQueryParam( 'baz', 'qux' ) ).toBe( true );
			expect( hasQueryParam( 'missing' ) ).toBe( false );
		} );

		it( 'should handle parameter with empty value', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?classy_enable_visual=',
				},
				writable: true,
			} );

			// Parameter exists but has empty value
			expect( hasQueryParam( 'classy_enable_visual' ) ).toBe( true );
			expect( hasQueryParam( 'classy_enable_visual', '' ) ).toBe( true );
			expect( hasQueryParam( 'classy_enable_visual', '1' ) ).toBe( false );
		} );

		it( 'should handle URL encoded values', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?message=hello%20world&special=%26%3D',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'message', 'hello world' ) ).toBe( true );
			expect( hasQueryParam( 'special', '&=' ) ).toBe( true );
		} );

		it( 'should be case-sensitive for parameter names', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?classy_enable_visual=1',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'classy_enable_visual', '1' ) ).toBe( true );
			expect( hasQueryParam( 'CLASSY_ENABLE_VISUAL', '1' ) ).toBe( false );
			expect( hasQueryParam( 'Classy_Enable_Visual', '1' ) ).toBe( false );
		} );

		it( 'should be case-sensitive for parameter values', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?mode=DEBUG',
				},
				writable: true,
			} );

			expect( hasQueryParam( 'mode', 'DEBUG' ) ).toBe( true );
			expect( hasQueryParam( 'mode', 'debug' ) ).toBe( false );
			expect( hasQueryParam( 'mode', 'Debug' ) ).toBe( false );
		} );

		it( 'should handle parameter appearing multiple times (uses first value)', () => {
			Object.defineProperty( window, 'location', {
				value: {
					search: '?param=first&param=second',
				},
				writable: true,
			} );

			// URLSearchParams.get() returns the first value
			expect( hasQueryParam( 'param', 'first' ) ).toBe( true );
			expect( hasQueryParam( 'param', 'second' ) ).toBe( false );
		} );

		it( 'should return false on error', () => {
			// Create a scenario that might cause an error
			Object.defineProperty( window, 'location', {
				value: {
					get search() {
						throw new Error( 'Test error' );
					},
				},
				writable: true,
			} );

			expect( hasQueryParam( 'any_param' ) ).toBe( false );
		} );
	} );

	describe( 'isValidUrl', () => {
		it( 'should return true for valid URLs with protocols', () => {
			expect( isValidUrl( 'https://www.example.com' ) ).toBe( true );
			expect( isValidUrl( 'http://www.example.com' ) ).toBe( true );
		} );

		it( 'should return true for valid URLs without protocols', () => {
			expect( isValidUrl( 'www.example.com' ) ).toBe( true );
			expect( isValidUrl( 'example.com' ) ).toBe( true );
			expect( isValidUrl( 'subdomain.example.com' ) ).toBe( true );
			expect( isValidUrl( 'my-site.co.uk' ) ).toBe( true );
			expect( isValidUrl( 'localhost:3000' ) ).toBe( true );
			expect( isValidUrl( 'localhost' ) ).toBe( true );
			expect( isValidUrl( '192.168.1.1' ) ).toBe( true );
		} );

		it( 'should return true for complex valid URLs without protocols', () => {
			expect( isValidUrl( 'example.com/path/to/page' ) ).toBe( true );
			expect( isValidUrl( 'www.example.com/path?query=value&another=param' ) ).toBe( true );
			expect( isValidUrl( 'example.com:8080/api/v1' ) ).toBe( true );
		} );

		it( 'should return true for empty or whitespace-only URLs', () => {
			expect( isValidUrl( '' ) ).toBe( true );
			expect( isValidUrl( '   ' ) ).toBe( true );
		} );

		it( 'should return false for invalid URLs', () => {
			expect( isValidUrl( 'invalid-url' ) ).toBe( false );
			expect( isValidUrl( 'just-text' ) ).toBe( false );
			expect( isValidUrl( 'www.space in url.com' ) ).toBe( false );
			expect( isValidUrl( 'htp://wrong-protocol.com' ) ).toBe( false );
			expect( isValidUrl( '://missing-protocol.com' ) ).toBe( false );
		} );

		it( 'should return false for malformed URLs', () => {
			expect( isValidUrl( 'http://example..com' ) ).toBe( false );
			expect( isValidUrl( 'http://.example.com' ) ).toBe( false );
			expect( isValidUrl( 'http://example.com.' ) ).toBe( false );
		} );

		it( 'should handle international domain names', () => {
			// Unicode domains now supported with Unicode property escapes
			expect( isValidUrl( 'https://例え.テスト' ) ).toBe( true );
			expect( isValidUrl( 'xn--r8jz45g.xn--zckzah' ) ).toBe( true );
		} );

		it( 'should handle edge cases', () => {
			// Very long domain names should be handled by URL constructor
			const longDomain = 'a'.repeat( 60 ) + '.com';
			expect( isValidUrl( `https://${ longDomain }` ) ).toBe( true );

			// URL with authentication not supported by simple regex
			expect( isValidUrl( 'https://user:pass@example.com' ) ).toBe( true );
			expect( isValidUrl( 'user:pass@example.com' ) ).toBe( true );
		} );
	} );
} );
