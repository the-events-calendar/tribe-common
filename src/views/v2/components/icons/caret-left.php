<?php
/**
 * View: Caret Left Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/caret-left.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 * @since 4.12.10 Initial template.
 *
 * @since TBD Updated for accessibility changes.
 *
 * @version TBD
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--caret-left' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	viewBox="0 0 10 16"
	xmlns="http://www.w3.org/2000/svg"
>
	<title><?php echo esc_html__( 'Previous', 'tribe-common' ); ?></title>
	<path d="M9.7 14.4l-1.5 1.5L.3 8 8.2.1l1.5 1.5L3.3 8l6.4 6.4z"/>
</svg>
