<?php
/**
 * View: Caret Alt Left Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/v2/components/icons/caret-alt-left.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 * @version TBD
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--caret-alt-left' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg <?php tribe_classes( $svg_classes ); ?> viewBox="0 0 9 15" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.289 1.978L2.735 7.567l5.554 5.588-1.333 1.341L.07 7.566 6.956.638l1.333 1.341z"/></svg>
