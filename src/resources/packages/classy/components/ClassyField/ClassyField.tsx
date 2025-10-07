import * as React from 'react';

type ClassyFieldProps = {
	/** The title of the field. */
	title: string;

	/** The child elements to be rendered within the field. */
	children: React.ReactNode;

	/**
	 * Optional additional class name for custom styling.
	 *
	 * If not provided, a default class name based on the title will be used. E.g., for a title "My Field",
	 * the class name will be "classy-field--my-field".
	 */
	className?: string;
};

/**
 * A wrapper component for fields in the Classy editor.
 *
 * @since TBD
 *
 * @param {ClassyFieldProps} props The component props.
 * @return {React.JSX.Element} The rendered component.
 */
export default function ClassyField( props: ClassyFieldProps ): React.JSX.Element {
	const { children, title } = props;
	const className = props.className || `classy-field--${ title.toLowerCase().replace( /\s+/g, '-' ) }`;

	return (
		<div className={ `classy-field ${ className }` }>
			<div className="classy-field__title">
				<h3>{ title }</h3>
			</div>

			{ children }
		</div>
	);
}
