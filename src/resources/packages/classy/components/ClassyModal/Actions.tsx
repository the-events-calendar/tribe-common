import * as React from 'react';
import type { Button } from '@wordpress/components';

type ButtonElement = React.ReactElement< typeof Button > | null | false | undefined;

type ClassyModalActionsProps = {
	/**
	 * The action buttons to be rendered within the actions container.
	 * This can be a single button element or an array of button elements.
	 * It can also include null, false, or undefined values to facilitate
	 * conditional rendering of buttons.
	 */
	children?: ButtonElement | ButtonElement[];

	/**
	 * The type of actions, used to apply specific class names for styling.
	 */
	type: string;
};

/**
 * Classy Actions component.
 *
 * Renders a container for action buttons with a specific type for styling. The children
 * are expected to be button elements or null/undefined values (to facilitate conditional rendering).
 *
 * @since TBD
 *
 * ```tsx
 * import * as React from 'react';
 * import { ClassyModalActions } from '@tec/common/classy/components';
 * import { Button } from '@wordpress/components';
 *
 * function MyClassyComponent(): React.JSX.Element {
 *     return (
 *         <ClassyModalActions type="my-type">
 *             <Button variant="primary" onClick={ () => {} }>Save</Button>
 *             <Button variant="secondary" onClick={ () => {} }>Cancel</Button>
 *             <Button variant="link" onClick={ () => {} }>Delete</Button>
 *         </ClassyModalActions>
 *     );
 * }
 * ```
 *
 * @param {ClassyModalActionsProps} props The component props.
 * @return {React.JSX.Element} The rendered actions container.
 */
export default function Actions( props: ClassyModalActionsProps ): React.JSX.Element {
	const { children = null, type } = props;

	return <div className={ `classy-modal__actions classy-modal__actions--${ type }` }>{ children }</div>;
}
