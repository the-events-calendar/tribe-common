<?php
/**
 * View: Close Alt Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/close-alt.php
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
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--close-alt' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	viewBox="0 0 8 8"
	xmlns="http://www.w3.org/2000/svg"
>
	<title><?php echo esc_html__( 'Close', 'tribe-common' ); ?></title>
	<path d="M1 1l6 6M7 1L1 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
