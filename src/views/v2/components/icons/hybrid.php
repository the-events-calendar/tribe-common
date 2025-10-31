<?php
/**
 * View: Hybrid Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/hybrid.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @since 4.13.2
 * @since 6.8.2 Add aria-hidden="true" to the svg element as this is a decorative element. Remove the `$icon_title` var, it is not longer used.
 *
 * @version 6.8.2
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 */

$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--hybrid' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}

?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	aria-hidden="true"
	viewBox="0 0 15 13"
	fill="none"
	xmlns="http://www.w3.org/2000/svg"
>
	<circle cx="3.661" cy="9.515" r="2.121" transform="rotate(-45 3.661 9.515)" stroke="#0F0F30" stroke-width="1.103"/>
	<circle cx="7.54" cy="3.515" r="2.121" transform="rotate(-45 7.54 3.515)" stroke="#0F0F30" stroke-width="1.103"/>
	<path d="M4.54 7.929l1.964-2.828" stroke="#0F0F30"/>
	<circle r="2.121" transform="scale(-1 1) rotate(-45 5.769 18.558)" stroke="#0F0F30" stroke-width="1.103"/>
	<path d="M10.554 7.929L8.59 5.1" stroke="#0F0F30"/>
</svg>
