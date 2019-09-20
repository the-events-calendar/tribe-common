# Common PostCSS Styles

## Why common styles?

Historically, CSS for Modern Tribe plugins have not held up to the highest standards for structuring CSS and naming CSS classes. These common styles help to build a foundation for standardizing class naming as well as following the Modern Tribe products design system.

## Class naming consistency and BEM

One of the issues we've had previously with templates for Modern Tribe plugins was inconsistent class naming and the class naming structure. To deal with this, we've adopted the use of [BEM](http://getbem.com/naming/) for class naming, combined with the use of `tribe-common-` as a block prefix.

First is the use of [BEM](http://getbem.com/naming/) for class naming (see link for more details). BEM stands for Block Element Modifier. We've used BEM as a guide to help us name classes and maintain consistency. This helps us structure the CSS around the HTML that we are styling without running into class naming chaos.

Secondly, we've added prefixes to our classes. The first prefix we've used is `tribe-common-`. This is mainly to avoid style clashing with other theme styles. For example, if we used a class `h1`, a theme that the user may apply may also use a class `h1` and the theme styles may unintentionally affect the plugin styles. Instead, we use `tribe-common-h1`. The second prefix we've used is context-based prefixes. Some of these prefixes include `a11y-` for accessibility, `g-` for grid, `l-` for layout, and `c-` for component. These prefixes help determine the context of these reusable style classes. For example, the `tribe-common-a11y-hidden` can be applied to hide content from sighted users and screenreaders. The `tribe-common-c-btn` can be applied to a link or button to apply button styles.

## View/block wrapper class

Aside from classes that apply styles to elements, we also apply resets and base styles. In order to not override theme styles and elements outside of Modern Tribe plugins, we've added a wrapper class `tribe-common` around all of Modern Tribe plugins blocks and views. For example, the markup for a specific view or block might look like the following:

```
<div class="tribe-common">
	...
	<button class="tribe-common-c-btn">Test Button</button>>
	...
</div>
```

Given this markup, the PostCSS will look like the following:

```
.tribe-common {

	button {
		/* base button styles here */
	}

	.tribe-common-c-btn {
		/* component button styles here */
	}
}
```

This allows us to target only the buttons within the Modern Tribe plugin views.

## CSS specificity

Given the above structure of using a wrapper class, we've increased the [CSS specificity](https://developer.mozilla.org/en-US/docs/Web/CSS/Specificity) needed for theme developers to override our styles. For resets and base styles, the minimum specificity required is 1 class and 1 element. For class-based styles, the minimum specificity required is 2 classes. With some modifiers, the minimum specificity required may be 3 classes. For example:

```
.tribe-common {
	...

	.tribe-common-form-control-toggle--vertical {

		.tribe-common-form-control-toggle__label {
			/* toggle label styles */
		}
	}

	...
}
```

In this case, the label is an element of the toggle. However, the `--vertical` modifier is applied to the top level block. Given this structure, our minimum specificity becomes 3 classes.

For overriding styles, it is recommended to only use classes to keep overriding specificity consistent. All elements should have classes and should be targetted using those classes.

## Modifiers, pseudo-classes, and media queries

As you get into building upon these styles and creating new styles, the order of modifiers, pseudo-classes, and media queries comes into question. The general rule is to apply them in the following order: media queries, pseudo-classes, modifiers. See the examples below:

```
.tribe-common {
	...

	.tribe-common-form-control-toggle {
		/* toggle styles */

		@media (--viewport-medium) {
			/* viewport medium toggle styles */
		}

		&:after {
			/* :after pseudo-class styles */

			@media (--viewport-medium) {
				/* viewport medium :after pseudo-class styles */
			}
		}
	}

	.tribe-common-form-control-toggle--vertical {
		/* vertical toggle styles */

		@media (--viewport-medium) {
			/* viewport medium vertical toggle styles */
		}

		&:after {
			/* :after pseudo-class styles */

			@media (--viewport-medium) {
				/* viewport medium :after pseudo-class styles */
			}
		}
	}
}
```

In the case of an element, we might get the following scenario:

```
.tribe-common {
	...

	.tribe-common-form-control-toggle__input {
		/* toggle input styles */

		@media (--viewport-medium) {
			/* viewport medium toggle input styles */
		}

		&:after {
			/* :after pseudo-class styles */

			@media (--viewport-medium) {
				/* viewport medium :after pseudo-class styles */
			}
		}
	}

	.tribe-common-form-control-toggle--vertical {

		.tribe-common-form-control-toggle__input {
			/* vertical toggle input styles */

			@media (--viewport-medium) {
				/* viewport medium vertical toggle input styles */
			}

			&:after {
				/* :after pseudo-class styles */

				@media (--viewport-medium) {
					/* viewport medium :after pseudo-class styles */
				}
			}
		}
	}
}
```
