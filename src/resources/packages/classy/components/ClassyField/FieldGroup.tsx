import * as React from 'react';

type FieldGroupProps = {
	/**
	 * Optional additional class name for custom styling.
	 */
	className?: string;

	/**
	 * The child elements to be rendered within the field group.
	 */
	children?: React.ReactNode;
};

/**
 * A wrapper component for grouping fields in the Classy editor.
 *
 * @since TBD
 *
 * ```tsx
 * import { ClassyFieldGroup } from '@tec/common/classy/components';
 *
 * function MyClassyComponent(): React.JSX.Element {
 *     return (
 *         <ClassyFieldGroup>
 *             <!-- Grouped field content goes here -->
 *         </ClassyFieldGroup>
 *     );
 * }
 * ```
 *
 * @param {FieldGroupProps} props The component props.
 * @return {React.JSX.Element} The rendered component.
 */
export default function FieldGroup( props: FieldGroupProps ): React.JSX.Element {
	const { children } = props;
	const className = `classy-field__group${ props.className ? ` ${ props.className }` : '' }`;

	return <div className={ className }>{ children }</div>;
}
