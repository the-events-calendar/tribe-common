/**
 * @todo: handle globals in a better way
 */
export const get = ( key, defaultValue ) => window[ key ] || defaultValue;
export const google = () => get( 'google' );
// Localized Config
export const config = () => get( 'tribe_editor_js_config', {} );
// Common
export const common = () => config().common || {};
export const adminUrl = () => common().admin_url || '';
export const rest = () => common().rest || {};
export const restNonce = () => rest().nonce || {};
export const dateSettings = () => common().date_settings || {};
export const editorConstants = () => common().constants || {};
export const list = () => ( {
	countries: common().countries || {},
	us_states: common().us_states || {},
} );

// TEC
export const tec = () => config().tec || {};
export const editor = () => tec().editor || {};
export const settings = () => tec().settings || {};
export const mapsAPI = () => tec().google_map || {};
export const priceSettings = () => tec().price_settings || {};
export const timezoneHtml = () => tec().timezone_html || '';
// PRO
export const pro = () => config().events_pro || {};
export const editorDefaults = () => pro().defaults || {};
// Tickets
export const tickets = () => config().tickets || {};