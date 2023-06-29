<?php
/**
 * The Stellar Sale admin notice.
 *
 * @since 4.14.2
 *
 * @var string $icon_url The local URL for the notice's image.
 * @var string $cta_url The short URL for the Stellar Sale.
 */
?>
<div class="tribe-marketing-notice">
	<div class="tribe-marketing-notice__content-wrapper">
		<div class="tribe-marketing-notice__col--md">
			<h3>
				<?php echo _e( 'Make it yours.', 'tribe-common' ); ?>
			</h3>
			<h4>
				<?php echo _e( 'Save 30% on all Events Calendar products.', 'tribe-common' ); ?>
			</h4>
			<p>
				<span class="tribe-marketing-notice__cta-shop-now">
					<a target="_blank" href="<?php echo esc_url( $cta_url ); ?>">
						<?php echo esc_html_x( 'Shop now', 'Shop now link text', 'tribe-common' ) ?>
					</a>
				</span>
			</p>
		</div>
		
		<div class="tribe-marketing-notice__col--lg">
			<p>
				<?php echo __( 'Purchase any StellarWP product during the sale and get <b>100%</b> off WP Business Reviews and take <b>40%</b> off all other brands.', 'tribe-common' ); ?>
			</p>
			<p>
				<span class="tribe-marketing-notice__cta-stellar-deals">
					<a target="_blank" href="<?php echo esc_url( $stellar_url ); ?>">
						<?php echo esc_html_x( 'View all StellarWP Deals', 'View all StellarWP Deals link text', 'tribe-common' ); ?>
					</a>
				</span>
			</p>
		</div>

		<div class="tribe-marketing-notice__col--sm"></div>
	</div>
</div>
