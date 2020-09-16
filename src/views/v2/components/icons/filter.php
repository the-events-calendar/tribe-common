<?php
/**
 * View: Filter Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/v2/components/icons/filter.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @var array $classes Additional classes to add to the svg icon.
 *
 * @version TBD
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--filter' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg <?php tribe_classes( $svg_classes ); ?> viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.541 1a.875.875 0 00-1.75 0v1.791H1a.875.875 0 100 1.75h1.791v1.792a.875.875 0 101.75 0V4.541h12.458a.875.875 0 100-1.75H4.541V1zM.125 14.334c0-.483.392-.875.875-.875h12.459v-1.793a.875.875 0 111.75 0v1.793H17a.875.875 0 110 1.75h-1.791v1.79a.875.875 0 11-1.75 0v-1.79H1a.875.875 0 01-.875-.875z"/></svg>
