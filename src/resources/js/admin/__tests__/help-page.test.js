describe( 'help-page copy button', () => {
	const renderedText = 'Home URL\nhttps://example.test';
	let clipboardOptions;

	beforeEach( () => {
		jest.resetModules();

		document.body.innerHTML =
			'<dl class="support-stats"><dt>Home URL</dt><dd>https://example.test</dd></dl>' +
			'<button class="system-info-copy-btn" data-clipboard-action="copy">Copy</button>';

		// jsdom does not implement innerText, which a browser builds from the rendered layout.
		Object.defineProperty( document.querySelector( '.support-stats' ), 'innerText', {
			value: renderedText,
		} );

		global.tribe_system_info = {
			clipboard_btn_text: 'Copy to clipboard',
			clipboard_copied_text: 'System info copied',
			clipboard_fail_text: 'Press "Cmd + C" to copy',
		};

		global.ClipboardJS = jest.fn( function ( selector, options ) {
			clipboardOptions = options;
			this.on = jest.fn();
		} );

		global.jQuery = jest.fn( () => ( { on: jest.fn() } ) );

		require( '../help-page.js' );
		window.tribe.helpPage.setupCopyButton();
	} );

	it( 'should copy the system info as plain text', () => {
		expect( clipboardOptions.text() ).toBe( renderedText );
	} );

	it( 'should copy an empty string when the system info is missing', () => {
		document.querySelector( '.support-stats' ).remove();

		expect( clipboardOptions.text() ).toBe( '' );
	} );
} );
