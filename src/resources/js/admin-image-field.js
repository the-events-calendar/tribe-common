/* eslint-disable es5/no-arrow-functions */
/* eslint-disable es5/no-es6-methods */
jQuery( $ => {
	let frame;

	$('.tribe-field-image').each(( x, elm ) => {
		const fieldParent = $(elm),
			addImgLink    = fieldParent.find( '.tribe-admin-image_field-btn-add' ),
			removeImgLink = fieldParent.find( '.tribe-admin-image_field-btn-remove' ),
			imgContainer  = fieldParent.find( '.tribe-admin-image_field-image-container' ),
			imgIdInput    = fieldParent.find( '.tribe-admin-image_field-input' );

		const setHiddenElements = () => {
			const imageIsSet = imgIdInput.val() !== '';
			addImgLink.toggleClass( 'hidden', imageIsSet );
			removeImgLink.toggleClass( 'hidden', !imageIsSet );
			imgContainer.toggleClass( 'hidden', !imageIsSet );
		};

		addImgLink.on('click', e => {
			e.preventDefault();
			if ( frame ) {
				frame.open();
			}else{
				frame = wp.media({
					title: tribe_admin_image_field.select_image_text,
					button: {
						text: tribe_admin_image_field.use_image_text
					},
					multiple: false
				});
				frame.open();
			}

			frame.off('select').on( 'select', () => {
				var attachment = frame.state().get('selection').first().toJSON();
				if ( imgContainer.find('img').length > 0 ){
					imgContainer.find('img').attr('src', attachment.url);
				}else{
					imgContainer
					.html( '<img src="' + attachment.url + '" alt="" />' );
				}
				imgIdInput.val( attachment.url );
				setHiddenElements();
			} );
		});

		removeImgLink.on('click', e => {
			e.preventDefault();
			imgIdInput.val('');
			imgContainer.html('');
			setHiddenElements();
		})

		setHiddenElements();

	});
} );