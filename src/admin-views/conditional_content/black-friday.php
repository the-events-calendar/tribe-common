<?php
/**
 * Black Friday Conditional template.
 *
 * @since 6.3.0
 *
 * @var string $image_src Where the image is located.
 * @var string $link      Where the image should link to.
 * @var string $nonce     The nonce for the dismiss button.
 * @var string $slug      The slug for the dismiss button.
 */

$sale_year = date_i18n( 'Y' );
/* translators: %1$s: Black Friday sale year (numeric) */
$a11y_text = _x( '%1$s Black Friday Sale for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Black Friday Ad', 'tribe-common' );
?>

<a
	href="<?php echo esc_url( $link ); ?>"
	target="_blank"
	rel="noopener nofollow"
	style="display: block; margin: 25px 0; position: relative; <?php echo $this->get( 'is_narrow' ) ? 'max-width: 1000px' : ''; ?>"
	title="<?php printf( esc_attr( $a11y_text ), esc_attr( $sale_year ) ); ?>"
	data-tec-conditional-content-dismiss-container
>
	<button
		data-tec-conditional-content-dismiss-button
		data-tec-conditional-content-dismiss-slug="<?php echo esc_attr( $slug ); ?>"
		data-tec-conditional-content-dismiss-nonce="<?php echo esc_attr( $nonce ); ?>"
		style="position: absolute; top: 0; right: 0; background: transparent; border: 0; color: #fff; padding: 0.5em; cursor: pointer;"
	><i class="dashicons dashicons-dismiss"></i></button>
	<img
		style="display: block; width: 100%; height: auto;" <?php // This is intentionally inline, don't add classes here. ?>
		src="<?php echo esc_url( $image_src ); ?>"
		alt="<?php printf( esc_attr( $a11y_text ), esc_attr( $sale_year ) ); ?>"
	/>
</a>
