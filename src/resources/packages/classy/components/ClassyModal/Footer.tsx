import * as React from 'react';

type FooterProps = {
	/**
	 * The child elements to be rendered within the footer.
	 */
	children: React.ReactNode;

	/**
	 * Optional additional class name for custom styling.
	 */
	className?: string;

	/**
	 * The type of the footer, used to apply specific class names for styling.
	 */
	type: string;
};


/**
 * Classy Footer component.
 *
 * Renders a container for footer content with a specific type for styling.
 *
 * @since TBD
 *
 * ```tsx
 * import * as React from 'react';
 * import { ClassyModalFooter } from '@tec/common/classy/components';
 *
 * function MyClassyComponent(): React.JSX.Element {
 *     return (
 *         <ClassyModalFooter type="my-type">
 *             <!-- Footer content goes here -->
 *         </ClassyModalFooter>
 *     );
 * }
 * ```
 *
 * @param {FooterProps} props The component props.
 * @return {React.JSX.Element} The rendered footer container.
 */
export default function Footer( props: FooterProps ): React.JSX.Element {
	const { children, type } = props;
	const className = props.className || `classy-modal__footer--${ type }`;

	return <div className={ `classy-modal__footer ${ className }` }>{ children }</div>;
}

