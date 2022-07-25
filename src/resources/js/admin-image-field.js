/* eslint-disable linebreak-style */
/* eslint-disable es5/no-arrow-functions */
jQuery( $ => {
	let frame;
	$(document).on('click', '.tribe-admin-image_field-btn-add', e => {
		e.preventDefault();
		const fieldParent = $( e.target ).closest( '.tribe-field' ),
			delImgLink    = fieldParent.find( '.tribe-admin-image_field-btn-remove' ),
			imgContainer  = fieldParent.find( '.tribe-admin-image_field-image-container' ),
			imgIdInput    = fieldParent.find( '.tribe-admin-image_field-input' );

		if ( frame ) {
			frame.open();
		} else {
			frame = wp.media({
				title: 'Select an image to use in your email headers',
				button: {
					text: 'Use this image'
				},
				multiple: false
			});
			frame.open();
		}

		frame.off('select').on( 'select', () => {
			var attachment = frame.state().get('selection').first().toJSON();
			imgContainer
				.html( '<img src="' + attachment.url + '" alt="" style="max-width:100%;" />' );
			imgIdInput.val( attachment.url );
			delImgLink.removeClass( 'hidden' );
		} );

	}).on('click', '.tribe-admin-image_field-btn-remove', e => {
		e.preventDefault();
		const fieldParent = $( e.target ).closest( '.tribe-field' ),
			delImgLink    = fieldParent.find( '.tribe-admin-image_field-btn-remove' ),
			imgContainer  = fieldParent.find( '.tribe-admin-image_field-image-container' ),
			imgIdInput    = fieldParent.find( '.tribe-admin-image_field-input' );

		imgIdInput.val('');
		imgContainer.html('');
		delImgLink.addClass( 'hidden' );
	});
} );