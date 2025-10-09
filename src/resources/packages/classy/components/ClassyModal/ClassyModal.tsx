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
	 * Callback function to be invoked when the modal is requested to close.
	 */
	onClose: () => void;

	/**
	 * Optional additional class name for the modal overlay for custom styling.
	 */
	overlayClassName?: string;

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
	const { onClose, children, type } = props;
	const className = props.className || `classy-modal__${ type }`;
	const overlayClassName = props.overlayClassName || `classy-modal__overlay--${ type }`;

	return (
		<Modal
			__experimentalHideHeader={ true }
			className={ `classy-modal ${ className }` }
			onRequestClose={ onClose }
			overlayClassName={ `classy-modal__overlay ${ overlayClassName }` }
		>
			{ children }
		</Modal>
	);
}
