<?php
/**
 * View: Caret Down Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/caret-down.php
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

$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--caret-down' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	aria-hidden="true"
	viewBox="0 0 10 7"
	xmlns="http://www.w3.org/2000/svg"
>
	<path fill-rule="evenodd" clip-rule="evenodd" d="M1.008.609L5 4.6 8.992.61l.958.958L5 6.517.05 1.566l.958-.958z" class="tribe-common-c-svgicon__svg-fill"/>
</svg>
