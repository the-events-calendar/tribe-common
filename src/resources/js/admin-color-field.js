/* eslint-disable es5/no-arrow-functions */
/* eslint-disable linebreak-style */
/**
 * Makes sure we have all the required levels on the Tribe Object.
 *
 * @since 5.0.0
 *
 * @type {PlainObject}
 */
tribe.settings = tribe.settings || {};
tribe.settings.fields = tribe.settings.fields || {};

/**
 * Configure image field for settings in global Tribe variable.
 *
 * @since 5.0.0
 *
 * @type {PlainObject}
 */
tribe.settings.fields.color = {};

/**
 * Initializes the color field for settings.
 *
 * @since 5.0.0
 *
 * @param {PlainObject} $   jQuery
 * @param {PlainObject} obj tribe.settings.fields.color
 */
( function( $, obj ) {
	'use strict';

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since 5.0.0
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		colorFieldInput: '.tec-admin__settings-color-field-input',
	};

	/**
	 * Handles the initialization of color fields when Document is ready.
	 *
	 * @since 5.0.0
	 *
	 * @return {void}
	 */
	obj.init = () => {
		$( obj.selectors.colorFieldInput ).wpColorPicker();
	};

	$( obj.init );

} )( jQuery, tribe.settings.fields.color );