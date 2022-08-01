/* eslint-disable linebreak-style */
/**
 * Makes sure we have all the required levels on the Tribe Object.
 * 
 * @since TBD
 * 
 * @type {PlainObject}
 */
tribe.settings = tribe.settings || {};
tribe.settings.fields = tribe.settings.fields || {};

/**
 * Configure image field for settings in global Tribe variable.
 * 
 * @since TBD
 * 
 * @type {PlainObject}
 */
tribe.settings.fields.color = {};

/**
 * Intializes the image field for settings.
 * 
 * @since TBD
 * 
 * @param {PlainObject} $   jQuery
 * @param {PlainObject} obj tribe.settings.fields.image
 */
( function( $, obj ) {
	'use strict';

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since TBD
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		colorFieldInput: '.tec-admin__settings-color-field-input',
	};

	/**
	 * Handles the initialization of color fields when Document is ready.
	 *
	 * @since TBD
	 *
	 * @return {void}
	 */
	obj.init = function() {
		$( obj.selectors.colorFieldInput ).wpColorPicker();
	};
 
	$( obj.init );

} )( jQuery, tribe.settings.fields.color );