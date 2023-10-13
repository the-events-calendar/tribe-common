<?php
/**
 * The Black Friday admin notice.
 *
 * @since 4.12.14
 * @since 5.1.10 - reworked to make it more like the Stellar Sale banner in layout, can include an icon
 *
 * @var string $icon_url The local URL for the notice's image. Can be empty.
 * @var string $cta_url The short URL for black friday.
 * @var string $start_date - the end date of the sale.
 * @var string $end_date - the end date of the sale.
 */
?>
<div class="tribe-common tribe-marketing-notice">
	<div class="tribe-marketing-notice__content-wrapper">
		<h3 class="tribe-marketing-notice__header tribe-common-h3">
			<?php _e( 'Save 40% on The Events Calendar', 'tribe-common' ); ?>
		</h3>
		<p class="tribe-marketing-notice__content tribe-common-h4">
			<?php _e( 'Upgrade and purchase new products during the Black Friday Sale.', 'tribe-common' ); ?>
		</p>
		<p>
			<span class="tribe-marketing-notice__cta tribe-marketing-notice__cta-shop-now tribe-marketing-notice__cta-shop-now--desktop">
				<a target="_blank" href="<?php echo esc_url( $cta_url ); ?>">
					<?php echo esc_html_x( 'Shop now', 'Shop now link text', 'tribe-common' ) ?>
				</a>
			</span>
		</p>
	</div>
	<?php if ( ! empty( $icon_url ) ) : ?>
	<div class="tribe-marketing-notice__image-wrapper">
		<img
			class="tribe-marketing-notice__image"
			src="<?php echo esc_url( $icon_url ); ?>"
		/>
	</div>
	<?php endif; ?>
</div>
