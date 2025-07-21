import { isValidUrl } from '../../../src/resources/packages/classy/functions/utils';
import { describe, expect, it } from '@jest/globals';

describe( 'utils', () => {
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
