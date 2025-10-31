import { addEditorTools } from '../../../src/resources/packages/classy/functions/addEditorTools';
import { afterEach, describe, it, expect, jest } from '@jest/globals';
import { registerPlugin } from '@wordpress/plugins';

jest.mock( '@wordpress/plugins', () => ( {
	registerPlugin: jest.fn( ( name: string, settings ) => null ),
} ) );

/**
 * Tests for the addEditorTools function.
 *
 * Note: In production, this function is only called when the `classy_enable_visual=1`
 * query parameter is present in the URL (checked in index.ts). These tests focus on
 * the function's behavior when called, not the conditional invocation logic.
 */
describe( 'addEditorTools', () => {
	afterEach( () => {
		jest.resetAllMocks();
		jest.restoreAllMocks();
	} );

	it( 'should register plugin with correct name and render function', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html><body>
                <div class="editor-document-tools">
                    <div class="editor-document-tools__left"></div>
                </div>
            </body></html>`,
			'text/html'
		);
		const onClick = jest.fn();

		addEditorTools( onClick, mockDocument );

		expect( registerPlugin ).toHaveBeenCalledWith( 'tec-editor-tools', {
			render: expect.any( Function ),
		} );
	} );

	it( 'should add editor tools button to the toolbar', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html><body>
                <div class="editor-document-tools">
                    <div class="editor-document-tools__left"></div>
                </div>
            </body></html>`,
			'text/html'
		);
		const onClick = jest.fn();

		addEditorTools( onClick, mockDocument );

		expect( onClick ).not.toHaveBeenCalled();
		expect( mockDocument.querySelectorAll( '.editor-document-tools__left .tec-editor-tool' ).length ).toBe( 0 );
		expect( registerPlugin ).toHaveBeenCalledWith( 'tec-editor-tools', {
			render: expect.any( Function ),
		} );

		const registerPluginMock = registerPlugin as jest.Mock;
		const registerPluginMockCallSettings = registerPluginMock.mock.calls[ 0 ][ 1 ] as { render: Function };
		const renderEditorTools = registerPluginMockCallSettings.render;

		const renderedEditorTools = renderEditorTools();

		expect( renderedEditorTools ).toBeNull();
		expect( mockDocument.querySelectorAll( '.editor-document-tools__left .tec-editor-tool' ).length ).toBe( 1 );
		expect(
			mockDocument.querySelector( '.editor-document-tools__left .tec-editor-tool' ).outerHTML
		).toMatchSnapshot();
	} );

	it( 'should not add duplicate buttons on multiple render calls', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html><body>
                <div class="editor-document-tools">
                    <div class="editor-document-tools__left"></div>
                </div>
            </body></html>`,
			'text/html'
		);
		const onClick = jest.fn();

		addEditorTools( onClick, mockDocument );

		const registerPluginMock = registerPlugin as jest.Mock;
		const registerPluginMockCallSettings = registerPluginMock.mock.calls[ 0 ][ 1 ] as { render: Function };
		const renderEditorTools = registerPluginMockCallSettings.render;

		renderEditorTools();

		expect( mockDocument.querySelectorAll( '.editor-document-tools__left .tec-editor-tool' ).length ).toBe( 1 );

		const secondRenderedEditorTools = renderEditorTools();

		expect( secondRenderedEditorTools ).toBeNull();
		expect( mockDocument.querySelectorAll( '.editor-document-tools__left .tec-editor-tool' ).length ).toBe( 1 );
	} );

	it( 'should call onClick handler when button is clicked', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html><body>
                <div class="editor-document-tools">
                    <div class="editor-document-tools__left"></div>
                </div>
            </body></html>`,
			'text/html'
		);
		const onClick = jest.fn();

		addEditorTools( onClick, mockDocument );

		const registerPluginMock = registerPlugin as jest.Mock;
		const registerPluginMockCallSettings = registerPluginMock.mock.calls[ 0 ][ 1 ] as { render: Function };
		const renderEditorTools = registerPluginMockCallSettings.render;

		renderEditorTools();

		const button = mockDocument.querySelector( '.editor-document-tools__left .tec-editor-tool' ) as HTMLElement;
		button.click();
		expect( onClick ).toHaveBeenCalledTimes( 1 );
	} );

	it( 'should not add any button if the document does not have the editor tools container', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html><body>
                <div class="editor-document-tools">
                </div>
            </body></html>`,
			'text/html'
		);
		const onClick = jest.fn();

		addEditorTools( onClick, mockDocument );

		expect( onClick ).not.toHaveBeenCalled();
		expect( mockDocument.querySelectorAll( '.editor-document-tools__left .tec-editor-tool' ).length ).toBe( 0 );
		expect( registerPlugin ).toHaveBeenCalledWith( 'tec-editor-tools', {
			render: expect.any( Function ),
		} );

		const registerPluginMock = registerPlugin as jest.Mock;
		const registerPluginMockCallSettings = registerPluginMock.mock.calls[ 0 ][ 1 ] as { render: Function };
		const renderEditorTools = registerPluginMockCallSettings.render;

		const renderedEditorTools = renderEditorTools();

		expect( renderedEditorTools ).toBeNull();
		expect( mockDocument.querySelectorAll( '.editor-document-tools .tec-editor-tool' ).length ).toBe( 0 );
	} );

	it( 'should use window.document when no document is provided', () => {
		const onClick = jest.fn();

		addEditorTools( onClick );

		expect( registerPlugin ).toHaveBeenCalledWith( 'tec-editor-tools', {
			render: expect.any( Function ),
		} );
	} );

	it( 'should add button with correct CSS classes', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html><body>
                <div class="editor-document-tools">
                    <div class="editor-document-tools__left"></div>
                </div>
            </body></html>`,
			'text/html'
		);
		const onClick = jest.fn();

		addEditorTools( onClick, mockDocument );

		const registerPluginMock = registerPlugin as jest.Mock;
		const registerPluginMockCallSettings = registerPluginMock.mock.calls[ 0 ][ 1 ] as { render: Function };
		const renderEditorTools = registerPluginMockCallSettings.render;

		renderEditorTools();

		const button = mockDocument.querySelector( '.editor-document-tools__left .tec-editor-tool' ) as HTMLElement;

		expect( button.classList.contains( 'tec-editor-tool' ) ).toBe( true );
		expect( button.classList.contains( 'tec-editor-tool--preview' ) ).toBe( true );
		expect( button.classList.contains( 'button' ) ).toBe( true );
		expect( button.getAttribute( 'type' ) ).toBe( 'button' );
		expect( button.getAttribute( 'data-toolbar-item' ) ).toBe( 'true' );
	} );

	it( 'should add button with visibility icon and text', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html><body>
                <div class="editor-document-tools">
                    <div class="editor-document-tools__left"></div>
                </div>
            </body></html>`,
			'text/html'
		);
		const onClick = jest.fn();

		addEditorTools( onClick, mockDocument );

		const registerPluginMock = registerPlugin as jest.Mock;
		const registerPluginMockCallSettings = registerPluginMock.mock.calls[ 0 ][ 1 ] as { render: Function };
		const renderEditorTools = registerPluginMockCallSettings.render;

		renderEditorTools();

		const button = mockDocument.querySelector( '.editor-document-tools__left .tec-editor-tool' ) as HTMLElement;

		expect( button.innerHTML ).toContain( 'dashicons-visibility' );
		expect( button.innerHTML ).toContain( 'Visual' );
	} );
} );
