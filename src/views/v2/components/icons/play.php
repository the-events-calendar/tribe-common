<?php
/**
 * View: Play Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/play.php
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

$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--play' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	aria-hidden="true"
	viewBox="0 0 10 12"
	xmlns="http://www.w3.org/2000/svg"
>
	<path d="M10 6a1 1 0 01-.47.848l-8 5a.996.996 0 01-1.237-.14A.999.999 0 010 11V1A1 1 0 011.53.153l8 5A1 1 0 0110 6z" fill-rule="nonzero"/>
</svg>
