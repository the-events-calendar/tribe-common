<?php
/**
 * View: Stellar Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/stellar-icon.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @since 4.15.4
 * @since 6.8.2 Add aria-hidden="true" to the svg element as this is a decorative element.
 *
 * @version 6.8.2
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--stellar-icon' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	aria-hidden="true"
	viewBox="0 0 47 71"
	xmlns="http://www.w3.org/2000/svg"
>
	<circle cx="23.5342" cy="47.3893" r="21.5342" stroke-width="3.32802"/>
	<circle cx="23.5342" cy="35.4618" r="21.5342" stroke-width="3.32802"/>
	<circle cx="23.5342" cy="23.5342" r="21.5342" stroke-width="3.32802"/>
</svg>
