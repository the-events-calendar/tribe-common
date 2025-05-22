import * as React from 'react';
import { __experimentalInputControl as InputControl } from '@wordpress/components';
import { useState, useEffect } from 'react';
import { useSelect, useDispatch } from '@wordpress/data';
import { FieldProps } from '../../../types/FieldProps';

/**
 * PostTitle component for rendering and handling the post title input field.
 *
 * @since TBD
 *
 * @param {FieldProps} props Component properties including title.
 *
 * @return {JSX.Element} Rendered PostTitle component.
 */
export default function PostTitle( props: FieldProps ) {
	// Fetch the post title from the core/editor store.
	// This will also subscribe to changes in the core/editor store.
	const postTitle = useSelect( ( select ) => {
		const { getEditedPostAttribute }: { getEditedPostAttribute: Function } = select( 'core/editor' );
		return getEditedPostAttribute( 'title' );
	}, [] );

	// Dispatch to the core/editor store when the title is updated.
	const { editPost }: { editPost: Function } = useDispatch( 'core/editor' );

	// Set the initial value of the field to the post title selected from the store.
	const [ value, setValue ] = useState< string >( postTitle || '' );

	// On a change to the post title, coming from the core/editor store, rerender.
	useEffect( () => {
		setValue( postTitle );
	}, [ postTitle ] );

	//Handles changes to the input field by setting the new state and dispatching the new title to the store.
	const onChange = ( nextValue: string | undefined ): void => {
		setValue( nextValue ?? '' );
		editPost( { title: nextValue } );
	};

	return (
		<div className="classy-field classy-field--post-title">
			<div className="classy-field__title">
				<h3>{ props.title }</h3>
			</div>

			<div className="classy-field__inputs">
				<InputControl
					className="classy-field__control classy-field__control--input"
					__next40pxDefaultSize
					value={ value }
					onChange={ onChange }
				/>
			</div>
		</div>
	);
}
