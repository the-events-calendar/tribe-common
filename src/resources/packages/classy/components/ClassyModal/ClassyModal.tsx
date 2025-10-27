import * as React from 'react';
import { Modal } from '@wordpress/components';

type ClassyModalProps = {
	/**
	 * Content to be displayed inside the modal.
	 */
	children: React.ReactNode;

	/**
	 * Optional additional class name for custom styling.
	 */
	className?: string;

	/**
	 * Optional icon to display before the title in the modal header.
	 */
	icon?: JSX.Element;

	/**
	 * Callback function to be invoked when the modal is requested to close.
	 */
	onClose: () => void;

	/**
	 * Optional additional class name for the modal overlay for custom styling.
	 */
	overlayClassName?: string;

	/**
	 * Optional title to display in the modal header.
	 */
	title?: string;

	/**
	 * Type of the modal, used to add specific class names for styling.
	 */
	type: string;
};

/**
 * Classy Modal component.
 *
 * Renders a modal with customizable type and content. Automatically applies class names based
 * on the provided type for consistent styling.
 *
 * @since TBD
 *
 * ```tsx
 * import * as React from 'react';
 * import { ClassyModal } from '@tec/common/classy/components';
 *
 * function MyClassyComponent(): React.JSX.Element {
 *     return (
 *         <ClassyModal onClose={ () => {} } type="my-type">
 *             <!-- Modal content goes here -->
 *         </ClassyModal>
 *     );
 * }
 * ```
 *
 * @param {ClassyModalProps} props The component props.
 * @return {React.JSX.Element} The rendered modal component.
 */
export default function ClassyModal( props: ClassyModalProps ): React.JSX.Element {
	const { onClose, children, type, title, icon } = props;
	const className = props.className || `classy-modal__${ type }`;
	const overlayClassName = props.overlayClassName || `classy-modal__overlay--${ type }`;

	return (
		<Modal
			className={ `classy-modal ${ className }` }
			icon={ icon }
			onRequestClose={ onClose }
			overlayClassName={ `classy-modal__overlay ${ overlayClassName }` }
			title={ title }
		>
			{ children }
		</Modal>
	);
}
