<?php
/**
 * View: Photo Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/icons/photo.php
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
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--photo' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg
	<?php tec_classes( $svg_classes ); ?>
	viewBox="0 0 18 18"
	xmlns="http://www.w3.org/2000/svg"
>
	<title><?php echo esc_html__( 'Photo', 'tribe-common' ); ?></title>
	<path d="M1 1h16v16H1z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="tribe-common-c-svgicon__svg-stroke"/>
	<path d="M1 12l4-4 4 4 4-4 4 4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="tribe-common-c-svgicon__svg-stroke"/>
	<circle cx="6" cy="6" r="1.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="tribe-common-c-svgicon__svg-stroke"/>
</svg>
