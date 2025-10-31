<?php
/**
 * Template for the TrustedLogin integration.
 *
 * @since 6.9.5
 *
 * @var string $template_variant The template variant, determining which template to display.
 */

?>
<div class="tribe-settings-form form">
	<div class="tec-settings-form">
		<div class="tec-settings-form__content-section">
			<div>
				<?php
				// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
				do_action( 'trustedlogin/the-events-calendar/auth_screen' );
				?>
			</div>
		</div>
	</div>
</div>
<?php $this->template( "help-hub/support/sidebar/{$template_variant}" ); ?>
