<?php
/**
 * The Stellar Sale admin notice.
 *
 * @since 4.14.2
 * @since 5.1.3 - Made updates to the notice for the 2023 sale.
 *
 * @var string $icon_url The local URL for the notice's image.
 * @var string $cta_url The short URL for the Stellar Sale.
 */

?>
<div class="tribe-marketing-notice">
	<div class="tribe-marketing-notice__content-wrapper">
		<div class="tribe-marketing-notice__col--md">
			<h3>
				<?php echo esc_html( $heading ); ?>
			</h3>
			<h4>
			<?php echo esc_html( $sub_heading ); ?>
			</h4>
			<p>
				<span class="tribe-marketing-notice__cta-shop-now tribe-marketing-notice__cta-shop-now--desktop">
					<a target="_blank" href="<?php echo esc_url( $cta_url ); ?>">
						<?php echo esc_html( $cta_link_text ); ?>
					</a>
				</span>
			</p>
		</div>

		<div class="tribe-marketing-notice__col--lg">
			<p class="tribe-marketing-notice__info">
				<?php echo wp_kses_post( $content ); ?>
			</p>
			<div class="tribe-marketing-notice__col--inner">
				<p>
					<span class="tribe-marketing-notice__cta-shop-now tribe-marketing-notice__cta-shop-now--mobile">
						<a target="_blank" href="<?php echo esc_url( $cta_url ); ?>">
							<?php echo esc_html( $cta_link_text ); ?>
						</a>
					</span>
				</p>

				<p>
					<span class="tribe-marketing-notice__cta-stellar-deals">
						<a target="_blank" href="<?php echo esc_url( $stellar_url ); ?>">
							<?php echo esc_html( $stellar_link_text ); ?>
						</a>
					</span>
				</p>
			</div>
		</div>

		<div class="tribe-marketing-notice__col--sm">
			<img
				class="tribe-marketing-notice__col--sm-bg"
				src="<?php echo esc_url( tribe_resource_url( 'images/marketing/stellar-sale-banner-bg.svg', false, null, Tribe__Main::instance() ) ); ?>"
			/>
		</div>
	</div>
</div>
