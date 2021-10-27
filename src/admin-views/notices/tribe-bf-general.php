<?php
/**
 * The Black Friday admin notice.
 *
 * @since 4.12.14
 *
 * @var string $icon_url The local URL for the notice's image.
 * @var string $cta_url The short URL for black friday.
 * @var string $end_date - the end date of the sale.
 */
?>
<div class="tribe-marketing-notice">
	<div class="tribe-marketing-notice__icon">
		<img src="<?php echo esc_url( $icon_url ); ?>"/>
	</div>
	<div class="tribe-marketing-notice__content">
		<h3>Save 40% on every single plugin.</h3>
		<p>
			Black Friday Sale now through <?php echo esc_html( $end_date ); ?>.
			<span class="tribe-marketing-notice__cta"><a target="_blank" href="<?php echo esc_url( $cta_url ); ?>">Shop now</a></span>
		</p>
	</div>
</div>