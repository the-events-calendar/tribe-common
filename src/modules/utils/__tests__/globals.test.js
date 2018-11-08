/**
 * Internal dependencies
 */
import {
	google,
	mapsAPI,
	settings,
	list,
	get,
	config,
	rest,
	restNonce,
} from '@moderntribe/common/utils/globals';

describe( 'Tests for globals.js', () => {

	beforeAll( () => {
		window.tribe_blocks_editor_google_maps_api = {};
		window.tribe_blocks_editor_settings = {};
		window.tribe_data_countries = {};
		window.tribe_data_us_states = {};
		window.tribe_js_config = {
			rest: {
				namespaces: {
					core: 'wp/v2',
				},
				nonce: {
					wp_rest: 'cedcd6967b',
					add_ticket_nonce: '0878f40fb2',
				},
				url: 'http://gutenberg.local/wp-json/',
			},
			tickets: {
				providers: [ {
					class: 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main',
					currency: '$',
					currency_position: 'prefix',
					name: 'WooCommerce',
				} ],
				default_provider: 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main',
				default_currency: '$',
			},
		};
	} );

	test( 'Should match the default value for the globals values', () => {
		expect( get( 'random' ) ).toBe( undefined );
		expect( get( 'google' ) ).toBe( undefined );
		expect( google() ).toBe( undefined );
		expect( get( 'tribe_blocks_editor_settings' ) ).toMatchSnapshot();
		expect( settings() ).toEqual( {} );
		expect( mapsAPI() ).toEqual( {} );
		expect( list() ).toEqual( {
			countries: {},
			us_states: {},
		} );
		expect( config() ).toMatchSnapshot();
	} );

	test( 'get default value', () => {
		expect( get( 'UNKNOWN', 10 ) ).toBe( 10 );
		expect( get( 'tribe_js_config', [] ) ).toMatchSnapshot();
	} );

	test( 'rest value', () => {
		expect( rest() ).toMatchSnapshot();
		expect( restNonce() ).toMatchSnapshot();
	} );

	afterAll( () => {
		delete window.tribe_blocks_editor_google_maps_api;
		delete window.tribe_blocks_editor_settings;
		delete window.tribe_data_countries;
		delete window.tribe_data_us_states;
		delete window.tribe_js_config;
	} );
} );

describe( 'Test default values on globals', () => {
	test( 'rest default values', () => {
		expect( rest() ).toEqual( {} );
		expect( restNonce() ).toEqual( {} );
	} );
} );
