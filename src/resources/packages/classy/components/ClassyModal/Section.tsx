import * as React from 'react';

type SectionProps = {
	/**
	 * Whether the inputs within the section should be boxed. Adds --boxed modifier class.
	 */
	boxedInputs?: boolean;

	/**
	 * The child elements to be rendered within the section.
	 */
	children: React.ReactNode;

	/**
	 * Optional additional class name for custom styling.
	 */
	className?: string;

	/**
	 * Whether to include a separator line after the section. If true, will render a <hr> element.
	 */
	includeSeparator?: boolean;

	/**
	 * Whether the section contains input elements. If false, input-related classes will not be applied.
	 */
	hasInputs?: boolean;

	/**
	 * The title text to be displayed at the top of the section. If not provided, no title will be shown.
	 */
	title?: string;

	/**
	 * The type of the section, used to apply specific class names for styling.
	 */
	type?: string;
};

/**
 * A wrapper component for sections in the Classy modal.
 *
 * @since TBD
 *
 * ```tsx
 * import { Section } from '@tec/common/classy/components';
 *
 * function MyClassyComponent(): React.JSX.Element {
 *     return (
 *         <Section title="My Section" type="my-type" boxedInputs includeSeparator>
 *             <!-- Section content goes here -->
 *         </Section>
 *     );
 * }
 * ```
 *
 * @param {SectionProps} props The component props.
 * @return {React.JSX.Element} The rendered component.
 */
export default function Section( props: SectionProps ): React.JSX.Element {
	const { boxedInputs = false, children, className, includeSeparator = false, hasInputs = true, title, type } = props;
	const sectionClasses = [ 'classy-modal__section', 'classy-modal__content' ];

	if ( hasInputs ) {
		sectionClasses.push( 'classy-field__inputs' );
		sectionClasses.push( boxedInputs ? 'classy-field__inputs--boxed' : 'classy-field__inputs--unboxed' );
	}

	// Add type-based class if type is provided.
	if ( type ) {
		sectionClasses.push( `classy-modal__section--${ type }` );
	}

	// Add custom class if provided.
	if ( className ) {
		sectionClasses.push( className );
	}

	return (
		<React.Fragment>
			<section className={ sectionClasses.join( ' ' ) }>
				{ title && <div className="classy-field__input-title">{ title }</div> }
				{ children }
			</section>

			{ includeSeparator && <hr className="classy-modal__section-separator" /> }
		</React.Fragment>
	);
}
