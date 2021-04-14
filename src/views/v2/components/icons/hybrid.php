<?php
/**
 * View: Hybrid Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/v2/components/icons/hybrid.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 * @version TBD
 */

$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--hybrid' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg <?php tribe_classes( $svg_classes ); ?> viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
	<circle cx="4.863" cy="12.026" r="2.619" transform="rotate(-45 4.863 12.026)" stroke="#0F0F30" stroke-width="1.103"/>
	<circle cx="9.651" cy="4.619" r="2.619" transform="rotate(-45 9.651 4.619)" stroke="#0F0F30" stroke-width="1.103"/>
	<path d="M5.948 10.068l2.424-3.491" stroke="#0F0F30"/>
	<circle r="2.619" transform="scale(-1 1) rotate(-45 7.288 23.463)" stroke="#0F0F30" stroke-width="1.103"/>
	<path d="M13.372 10.068l-2.425-3.492" stroke="#0F0F30"/>
</svg>