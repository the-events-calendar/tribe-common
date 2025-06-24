<?php
/**
 * View: Close Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/close.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 * @since 4.12.14 Initial template.
 *
 * @since TBD Updated for accessibility changes.
 *
 * @version TBD
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--close' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	viewBox="0 0 12 12"
	xmlns="http://www.w3.org/2000/svg"
>
	<title><?php echo esc_html__( 'Close', 'tribe-common' ); ?></title>
	<path d="M11 1L1 11M1 1l10 10" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
