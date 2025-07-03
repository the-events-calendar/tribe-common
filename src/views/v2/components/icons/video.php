<?php
/**
 * View: Video Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/video.php
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

$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--video' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	aria-hidden="true"
	viewBox="0 0 16 12"
	xmlns="http://www.w3.org/2000/svg"
>
	<path d="M11 4V1c0-.6-.4-1-1-1H1C.4 0 0 .4 0 1v10c0 .6.4 1 1 1h9c.6 0 1-.4 1-1V8l5 2V2l-5 2z" fill-rule="nonzero" class="tribe-common-c-svgicon__svg-fill"/>
</svg>
