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
tribe.settings.fields.image = {};

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
	var $document = $( document );

	/**
	 * Store the frame object globally.
	 * 
	 * @since TBD
	 * 
	 * @type {PlainObject}
	 */
	obj.frame = false;

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since TBD
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		imageFieldContainer: '.tribe-field-image',
		addImgLink: '.tec-admin__settings-image-field-btn-add',
		removeImgLink: '.tec-admin__settings-image-field-btn-remove',
		imgContainer: '.tec-admin__settings-image-field-image-container',
		imgIdInput: '.tec-admin__settings-image-field-input',
	};

	/**
	 * Method to hide/show elements based on the image input field.
	 * 
	 * @since TBD
	 * 
	 * @param {jQuery} $fieldParent jQuery object of the field container.
	 *
	 * @return {void}
	 */
	obj.hideElements = function( $fieldParent ) {
		const imageIsSet = $fieldParent.find( obj.selectors.imgIdInput ).val() !== '';
		$fieldParent.find( obj.selectors.addImgLink ).toggleClass( 'hidden', imageIsSet );
		$fieldParent.find( obj.selectors.removeImgLink ).toggleClass( 'hidden', !imageIsSet );
		$fieldParent.find( obj.selectors.imgContainer ).toggleClass( 'hidden', !imageIsSet );
	};

	/**
	 * Method to handle when image is selected from WP Media feature.
	 * 
	 * @since TBD
	 * 
	 * @param {jQuery} $fieldParent jQuery object of the field container.
	 *
	 * @return {void}
	 */
	obj.onImageSelect = function( $fieldParent ) {
		const attachment = obj.frame.state().get('selection').first().toJSON(),
			$imgContainer = $fieldParent.find( obj.selectors.imgContainer );
		if ( $imgContainer.find( 'img' ).length > 0 ) {
			$imgContainer.find( 'img' ).attr( 'src', attachment.url );
		} else {
			$imgContainer.html( '<img src="' + attachment.url + '" />' );
		}
		$fieldParent.find( obj.selectors.imgIdInput ).val( attachment.url );
		obj.hideElements( $fieldParent );
	};

	/**
	 * Method that handles 'Add Image' button click event.
	 * 
	 * @since TBD
	 * 
	 * @param {Event} e Click event.
	 *
	 * @return {void}
	 */
	obj.addImage = function( e ) {
		e.preventDefault();
		const $target = $( e.target );
		const $fieldParent = $target.closest( obj.selectors.imageFieldContainer );

		if ( obj.frame ) {
			obj.frame.open();
		} else {
			obj.frame = wp.media({ // eslint-disable-line no-undef
				title: $fieldParent.data( 'select-image-text' ),
				button: {
					text: $fieldParent.data( 'use-image-text' )
				},
				multiple: false
			});
			obj.frame.open();
		}

		obj.frame.off( 'select' ).on( 'select', function() {
			obj.onImageSelect( $fieldParent );
		} );
	};

	/**
	 * Method that handles 'Remove Image' button click event.
	 * 
	 * @since TBD
	 * 
	 * @param {Event} e Click event.
	 *
	 * @return {void}
	 */
	obj.removeImage = function( e ) {
		e.preventDefault();
		const $target = $( e.target );
		const $fieldParent = $target.closest( obj.selectors.imageFieldContainer );
		$fieldParent.find( obj.selectors.imgIdInput ).val( '' );
		$fieldParent.find( obj.selectors.imgContainer ).html( '' );
		obj.hideElements( $fieldParent );
	};

	/**
	 * Bind events for events bar
	 *
	 * @since TBD
	 *
	 * @return {void}
	 */
	obj.bindEvents = function() {
		$document.on( 'click', obj.selectors.addImgLink, obj.addImage );
		$document.on( 'click', obj.selectors.removeImgLink, obj.removeImage );
	};

	/**
	 * Handles the initialization of image fields when Document is ready.
	 *
	 * @since TBD
	 *
	 * @return {void}
	 */
	obj.init = function() {
		$( obj.selectors.imageFieldContainer ).each( function( x, elm ) {
			obj.hideElements( $( elm ) );
		} );
		obj.bindEvents();
	};

	$( obj.init );

} )( jQuery, tribe.settings.fields.image );