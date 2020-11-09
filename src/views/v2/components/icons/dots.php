<?php
/**
 * View: Dots Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/v2/components/icons/dots.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 * @version 4.12.12
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--dots' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg <?php tribe_classes( $svg_classes ); ?> viewBox="0 0 61 15" xmlns="http://www.w3.org/2000/svg"><circle cx="7.5" cy="7.5" r="7.5" class="tribe-common-c-loader-dots__svg-dot tribe-common-c-loader-dots__svg-dot-first"/><circle cx="7.5" cy="7.5" r="7.5" transform="translate(23)" class="tribe-common-c-loader-dots__svg-dot tribe-common-c-loader-dots__svg-dot-second"/><circle cx="7.5" cy="7.5" r="7.5" transform="translate(46)" class="tribe-common-c-loader-dots__svg-dot tribe-common-c-loader-dots__svg-dot-third"/></svg>
