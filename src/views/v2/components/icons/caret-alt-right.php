<?php
/**
 * View: Caret Alt Right Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/v2/components/icons/caret-alt-right.php
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
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--caret-alt-right' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg <?php tribe_classes( $svg_classes ); ?> viewBox="0 0 9 15" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M.07 13.155l5.553-5.588L.07 1.978 1.403.637l6.886 6.93-6.886 6.93L.07 13.154z" class="tribe-common-c-svgicon__svg-fill"/></svg>
