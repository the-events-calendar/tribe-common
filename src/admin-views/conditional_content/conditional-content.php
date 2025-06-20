<?php
/**
 * Generic Conditional Content template.
 *
 * @since TBD
 *
 * @var string $background_color The background color of the banner.
 * @var string $image_src         Where the image is located.
 * @var string $link              Where the image should link to.
 * @var string $nonce             The nonce for the dismiss button.
 * @var string $slug              The slug for the dismiss button.
 * @var string $year              The sale year.
 * @var string $sale_name         The name of the sale.
 * @var string $a11y_text         The accessibility text for the image.
 * @var bool   $is_narrow         Whether this is the narrow banner variant.
 * @var bool   $is_sidebar        Whether this is the sidebar banner variant.
 */

$classes = [
	$slug                               => true,
	'tribe-conditional-content-wrap'    => true,
	'tribe-conditional-content-narrow'  => $is_narrow,
	'tribe-conditional-content-wide'    => ! $is_narrow,
	'tribe-conditional-content-sidebar' => $is_sidebar,
];

?>
<div
	<?php tec_classes( $classes ); ?>
	style="background-color: <?php echo esc_attr( $background_color ); ?>;" <?php // This is intentionally inline, don't change it. ?>
>
	<a
		class="tribe-conditional-content-link"
		href="<?php echo esc_url( $link ); ?>"
		target="_blank"
		rel="noopener nofollow"
		title="<?php echo esc_attr( $a11y_text ); ?>"
		data-tec-conditional-content-dismiss-container
	>
		<img
			class="tribe-conditional-content-image"
			style="display: block; width: 100%; height: auto;" <?php // This is intentionally inline, don't add classes here. ?>
			src="<?php echo esc_url( $image_src ); ?>"
			alt="<?php echo esc_attr( $a11y_text ); ?>"
		/>
	</a>
	<button
		class="tribe-conditional-content-dismiss-button"
		data-tec-conditional-content-dismiss-button
		data-tec-conditional-content-dismiss-slug="<?php echo esc_attr( $slug ); ?>"
		data-tec-conditional-content-dismiss-nonce="<?php echo esc_attr( $nonce ); ?>"
	><i class="dashicons dashicons-dismiss"></i></button>
</div>
