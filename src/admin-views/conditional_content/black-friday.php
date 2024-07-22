<?php
/**
 * Old Template for Black Friday Promo.
 *
 * @since 4.14.7
 * @var string $background_image - the url of the background image to use
 * @var string $branding_logo - the url of the TEC branding logo
 * @var string $button_link - the url the button links to
 */

?>

<div class="black-friday-promo">
	<div class="black-friday-promo__branding">
		<img
			src="<?php echo esc_url( $branding_logo ); ?>"
			alt="<?php echo esc_attr__( 'The Events Calendar brand logo', 'tribe-common' ); ?>"
			class="black-friday-promo__branding-image"
		/>
	</div>
	<div class="black-friday-promo__promo" style="background-image: url('<?php echo esc_attr( $background_image ); ?>')">
		<div class="black-friday-promo__content">
			<p class="black-friday-promo__text">
				<?php esc_html_e( 'Our biggest<br/> sale of the<br/> year ends<br/> soon</p>', 'tribe-common' ); ?>
				<a
					href="<?php echo esc_url( $button_link ); ?>"
					class="button black-friday-promo__button"
					rel="noreferrer noopener"
					target="_blank"
				>
					<?php esc_html_e( 'Save now', 'tribe-common' ); ?>
				</a>
			</p>
		</div>
	</div>
</div>
