<?php
/**
 * Black Friday Conditional template.
 *
 * @since TBD
 *
 * @var string $image_src Where the image is located.
 * @var string $link Where the image should link to.
 */

$year = date_i18n( 'Y' );
/* translators: %1$s: Black Friday year */
$a11y_text = _x( '%1$s Black Friday Sale for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Black Friday Ad', 'tribe-common' );
?>

<a
	href="<?php echo esc_url( $link ); ?>"
	target="_blank"
	rel="noopener nofollow"
	style="display: block; margin: 25px 0; <?php echo $this->get( 'is_narrow' ) ? 'max-width: 1000px' : ''; ?>"
	title="<?php printf( esc_attr( $a11y_text ), esc_attr( $year ) ); ?>"
>
	<img
		style="display: block; width: 100%; height: auto;" <?php // This is intentionally inline, don't add classes here. ?>
		src="<?php echo esc_url( $image_src ); ?>"
		alt="<?php echo printf( esc_attr( $a11y_text ), esc_attr( $year ) ); ?>"
	/>
</a>
