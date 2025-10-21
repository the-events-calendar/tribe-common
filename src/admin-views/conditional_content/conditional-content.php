<?php
/**
 * Generic Conditional Content template.
 *
 * @since 6.8.2
 * @since 6.9.8 Modified to use the presence of the nonce to determine if the dismiss button should be displayed.
 *
 * @var bool   $is_narrow         Whether this is the narrow banner variant (legacy).
 * @var bool   $is_responsive     Whether this is the responsive banner variant.
 * @var bool   $is_sidebar        Whether this is the sidebar banner variant.
 * @var string $a11y_text         The accessibility text for the image.
 * @var string $background_color  The background color of the banner.
 * @var string $image_src         Where the image is located (legacy).
 * @var string $link              Where the image should link to.
 * @var string $narrow_image_src  Where the narrow image is located (responsive).
 * @var string $sale_name         The name of the sale.
 * @var string $slug              The slug for the sale.
 * @var string $wide_image_src    Where the wide image is located (responsive).
 * @var string $year              The sale year.
 * @var string $nonce             Optional. The nonce for the dismiss button.
 *                                    If we don't get a nonce, we don't display the button.
 */

// No direct access.
defined( 'ABSPATH' ) || exit;

// Handle both legacy and responsive modes.
$is_responsive_mode = isset( $is_responsive ) && $is_responsive;
$use_legacy_mode    = ! $is_responsive_mode && isset( $image_src );
$use_sidebar_mode   = isset( $is_sidebar ) && $is_sidebar;

$classes = [
	$slug                                  => true,
	'tribe-conditional-content-wrap'       => true,
	'tribe-conditional-content-narrow'     => $use_legacy_mode && isset( $is_narrow ) && $is_narrow && ! $is_sidebar,
	'tribe-conditional-content-wide'       => $use_legacy_mode && isset( $is_narrow ) && ! $is_narrow && ! $is_sidebar,
	'tribe-conditional-content-responsive' => $is_responsive_mode && ! $is_sidebar,
	'tribe-conditional-content-sidebar'    => $use_sidebar_mode,
];

?>
<div
	<?php tec_classes( $classes ); ?>
	style="background-color: <?php echo esc_attr( $background_color ); ?>;" <?php // This is intentionally inline, don't change it. ?>
	data-tec-conditional-content-dismiss-container
>
	<a
		class="tribe-conditional-content-link"
		href="<?php echo esc_url( $link ); ?>"
		target="_blank"
		rel="noopener nofollow"
		title="<?php echo esc_attr( $a11y_text ); ?>"
	>
		<?php if ( $is_responsive_mode && ! $use_sidebar_mode ) : ?>
			<img
				class="tribe-conditional-content-image tribe-conditional-content-image-wide"
				src="<?php echo esc_url( $wide_image_src ); ?>"
				role="presentation"
			/>
			<img
				class="tribe-conditional-content-image tribe-conditional-content-image-narrow"
				src="<?php echo esc_url( $narrow_image_src ); ?>"
				role="presentation"
			/>
		<?php else : ?>
			<img
				class="tribe-conditional-content-image"
				style="display: block; width: 100%; height: auto;" <?php // This is intentionally inline, don't change it. ?>
				src="<?php echo esc_url( $image_src ); ?>"
				role="presentation"
			/>
		<?php endif; ?>
		<span class="screen-reader-text"><?php echo esc_html( $a11y_text ); ?></span>
	</a>
	<?php if ( ! empty( $nonce ) ) : ?>
		<button
			class="tribe-conditional-content-dismiss-button"
			data-tec-conditional-content-dismiss-button
			data-tec-conditional-content-dismiss-slug="<?php echo esc_attr( $slug ); ?>"
			data-tec-conditional-content-dismiss-nonce="<?php echo esc_attr( $nonce ); ?>"
		><span class="dashicons dashicons-dismiss"></span></button>
	<?php endif; ?>
</div>
