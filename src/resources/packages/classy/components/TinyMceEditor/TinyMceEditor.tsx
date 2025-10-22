import * as React from 'react';
import { useEffect, useState } from 'react';
import { debounce } from 'lodash';
import '../../types/global.d.ts';

type TinyMceEditorProps = {
	content: string;
	onChange: ( value: string ) => void;
	id: string;
};

type TinyMceEditorInstance = {
	initialized: boolean;
	on: ( event: string, callback: () => void ) => void;
	off: ( event: string, callback: () => void ) => void;
};

/**
 * Get the TinyMCE editor instance by its ID.
 *
 * @since TBD
 *
 * @param {string} id The ID of the TinyMCE editor.
 * @return {TinyMceEditorProps|null} The TinyMCE editor instance or null if not found.
 */
const getEditor = ( id: string ): TinyMceEditorInstance | null => {
	// @ts-ignore - Defined by the `wp-tinymce` dependency required by the Classy package.
	return window.tinymce.get( id );
};

/**
 * Initialize the TinyMCE editor for the given textarea ID.
 *
 * @since TBD
 * @param {string} id The ID of the textarea to convert into a TinyMCE editor.
 */
const initializeEditor = ( id: string ): void => {
	if ( getEditor( id ) ) {
		// @ts-ignore - Defined by the `wp-tinymce` dependency required by the Classy package.
		window.wp.oldEditor.remove( id );
	}

	// @ts-ignore - Defined by the `wp-tinymce` dependency required by the Classy package.
	window.wp.oldEditor.initialize( id, {
		tinymce: {
			wpautop: true,
			toolbar1:
				'formatselect bold italic | bullist numlist blockquote | alignleft aligncenter alignright | link wp_more wp_adv',
			toolbar2:
				'strikethrough hr forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help',
		},
		quicktags: true, // Show the "Visual / Text" tabs.
		mediaButtons: true, // Show the "Add media" button.
	} );
};

/**
 * Editor component that initializes and manages a TinyMCE editor.
 * @since TBD
 *
 * @param {TinyMceEditorProps} props - The component props.
 * @param {string} props.content The current content of the editor.
 * @param {(value: string) => void} props.onChange - Callback function to update the content.
 *
 * @return {React.JSX.Element} The rendered editor.
 */
export default function TinyMceEditor( { content, onChange, id }: TinyMceEditorProps ): React.JSX.Element {
	const [ value, setValue ] = useState( content );
	const [ , setEditor ] = useState< any >( null );

	useEffect( () => {
		// Initialize the editor immediately.
		initializeEditor( id );

		// Use a timeout to ensure the editor is fully initialized before setting up event listeners.
		setTimeout( () => {
			const editor = getEditor( id );

			// Verify we got an editor instance before trying to work with it.
			if ( null === editor ) {
				console.warn( `TinyMCE editor not found for id: ${ id }` );
				return;
			}

			// Handle initialization of the editor.
			const onInit = () => {
				editor.on( 'NodeChange', debounce( triggerChangeIfDirty, 250 ) );
				setEditor( editor );
			};

			if ( editor.initialized ) {
				onInit();
			} else {
				editor.on( 'init', onInit );
			}
		}, 500 );

		// Cleanup function to remove event listeners.
		return () => {
			const editor = getEditor( id );
			if ( editor ) {
				editor.off( 'NodeChange', debounce( triggerChangeIfDirty, 250 ) );
			}
		};
	}, [ id ] );

	const triggerChangeIfDirty = () => {
		// @ts-ignore - Defined by the `wp-tinymce` dependency required by the Classy package.
		updateValues( window.wp.oldEditor.getContent( id ) );
	};

	const updateValues = ( newValue: string ) => {
		setValue( newValue );
		onChange( newValue );
	};

	return (
		<textarea
			className="classy-control-tinymce-editor wp-editor-area"
			id={ id }
			value={ value }
			onChange={ ( e ) => updateValues( e.target.value ) }
		/>
	);
}
