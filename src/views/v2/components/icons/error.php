<?php
/**
 * View: Error Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/error.php
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
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--error' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	aria-hidden="true"
	viewBox="0 0 18 18"
	xmlns="http://www.w3.org/2000/svg"
>
	<g fill-rule="evenodd" transform="translate(1 1)">
		<circle cx="8" cy="8" r="7.467" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" class="tribe-common-c-svgicon__svg-stroke"/>
		<circle cx="8" cy="11.733" r="1.067" fill-rule="nonzero" class="tribe-common-c-svgicon__svg-fill"/>
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 3.733v4.8" class="tribe-common-c-svgicon__svg-stroke"/>
	</g>
</svg>
