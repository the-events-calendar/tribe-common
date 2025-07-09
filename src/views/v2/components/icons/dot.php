<?php
/**
 * View: Dot Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/dot.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @since 4.12.14
 * @since 6.8.2 Add aria-hidden="true" to the svg element as this is a decorative element.
 *
 * @version 6.8.2
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--dot' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	aria-hidden="true"
	viewBox="0 0 15 15"
	xmlns="http://www.w3.org/2000/svg"
>
	<circle cx="7.5" cy="7.5" r="7.5"/>
</svg>
