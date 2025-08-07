import * as React from 'react';
import { useEffect, useState } from 'react';
import { debounce } from 'lodash';
import '../../types/global.d.ts';

type TinyMceEditorProps = {
	content: string;
	onChange: ( value: string ) => void;
	id: string;
};

/**
 * Editor component that initializes and manages a TinyMCE editor.
 * @since TBD
 *
 * @param {TinyMceEditorProps} props - The component props.
 * @param {string} props.content The current content of the editor.
 * @param {(value: string) => void} props.onChange - Callback function to update the content.
 *
 * @return {JSX.Element} The rendered editor.
 */
export default function TinyMceEditor( { content, onChange, id }: TinyMceEditorProps ) {
	const [ value, setValue ] = useState( content );
	const [ , setEditor ] = useState< any >( null );

	useEffect( () => {
		// @ts-ignore - Defined by the `wp-tinymce` dependency required by the Classy package.
		if ( window.tinymce.get( id ) ) {
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

		// @ts-ignore - Defined by the `wp-tinymce` dependency required by the Classy package.
		const editor = window.tinymce.get( id );

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

		// Cleanup function to remove event listeners
		return () => {
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
