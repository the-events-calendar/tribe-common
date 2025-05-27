import * as React from 'react';
import { render } from '@testing-library/react';
import { beforeEach, afterEach, describe, expect, it, jest } from '@jest/globals';
import TinyMceEditor from '../../../src/resources/packages/classy/components/TinyMceEditor/TinyMceEditor';
import { userEvent } from '@testing-library/user-event';

declare global {
	interface Window {
		wp: {
			oldEditor: {
				initialize: jest.Mock;
				getContent: jest.Mock;
				remove: jest.Mock;
			};
		};
		tinymce: {
			get: ( id: string ) => {
				on: jest.Mock;
			};
		};
	}
}

describe( 'TinyMceEditor Component', () => {
	const defaultProps = {
		content: '<p>Initial content</p>',
		onChange: jest.fn(),
		id: 'editor-id',
	};

	beforeEach( () => {
		jest.resetAllMocks();
	} );

	afterEach( () => {
		jest.resetAllMocks();
	} );

	it( 'renders correctly with default props', () => {
		const { container } = render( <TinyMceEditor { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'initializes the editor on mount', () => {
		render( <TinyMceEditor { ...defaultProps } /> );

		expect( global.window.wp.oldEditor.initialize ).toHaveBeenCalledWith( defaultProps.id, expect.any( Object ) );
	} );

	it( 'calls onChange when content changes', async () => {
		const user = userEvent.setup();
		const { container } = render( <TinyMceEditor { ...defaultProps } /> );

		const newEditorContent = '<p>New content</p>';
		const editor = container.querySelector( 'textarea.classy-control-tinymce-editor' ) as Element;

		expect( editor ).not.toBeNull();

		await user.clear( editor );
		await user.type( editor, newEditorContent );

		expect( defaultProps.onChange ).toHaveBeenCalledWith( newEditorContent );
	} );
} );
