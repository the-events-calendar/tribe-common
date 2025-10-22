import * as React from 'react';

type RootProps = {
	/**
	 * The child elements to be rendered within the modal.
	 */
	children: React.ReactNode;

	/**
	 * An optional icon to be displayed in the modal header. If not provided, no icon will be shown.
	 */
	headerIcon?: React.ReactNode;

	/**
	 * The type of the modal, used to apply specific class names for styling.
	 */
	type: string;

	/**
	 * The title text to be displayed in the modal header.
	 */
	title: string;
};

/**
 * Modal root component.
 *
 * Renders the content root of a modal with a header and content area.
 *
 * @since TBD
 *
 * @param {RootProps} props The component props.
 */
export default function Root( props: RootProps ) {
	const { children = null, headerIcon = null, type, title } = props;

	return (
		<div className="classy-root">
			<header className={ `classy-modal__header classy-modal__header--${ type }` }>
				{ headerIcon }
				<h4 className="classy-modal__header-title">{ title }</h4>
			</header>

			{ children }
		</div>
	);
}
