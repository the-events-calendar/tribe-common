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
<svg <?php tribe_classes( $svg_classes ); ?> width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="4.86296" cy="12.0256" r="2.61862" transform="rotate(-45 4.86296 12.0256)" stroke="#0F0F30" stroke-width="1.1025"/>
<circle cx="9.65104" cy="4.61902" r="2.61862" transform="rotate(-45 9.65104 4.61902)" stroke="#0F0F30" stroke-width="1.1025"/>
<path d="M5.94775 10.0681L8.37239 6.57655" stroke="#0F0F30"/>
<circle r="2.61862" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 14.4564 12.0255)" stroke="#0F0F30" stroke-width="1.1025"/>
<path d="M13.3716 10.068L10.9469 6.57649" stroke="#0F0F30"/>
</svg>

