/**
 * @todo: handle globals in a better way
 */
export const get = ( key, defaultValue ) => window[ key ] || defaultValue;
export const google = () => get( 'google' );
export const dateSettings = () => get( 'tribe_date_settings' );
export const editor = () => get( 'tribe_blocks_editor' );
export const editorConstants = () => get( 'tribe_blocks_editor_constants' );
export const mapsAPI = () => get( 'tribe_blocks_editor_google_maps_api' );
export const priceSettings = () => get( 'tribe_blocks_editor_price_settings' );
export const settings = () => get( 'tribe_blocks_editor_settings' );
export const timezoneHtml = () => get( 'tribe_blocks_editor_timezone_html', '' );
export const config = () => get( 'tribe_js_config', {} );
export const rest = () => config().rest || {};
export const restNonce = () => rest().nonce || {};
export const editorDefaults = () => config().editor_defaults || {};
export const list = () => ( {
	countries: get( 'tribe_data_countries' ),
	us_states: get( 'tribe_data_us_states' ),
} );
