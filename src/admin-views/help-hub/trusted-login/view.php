<?php
/**
 * Template for the TrustedLogin integration.
 *
 * @since TBD
 */

?>
	<div class="tribe-settings-form form">
		<div class="tec-settings-form">
			<div class="tec-settings-form__content-section">
				<div>
					<?php do_action( 'trustedlogin/the-events-calendar/auth_screen' ); ?>
				</div>
			</div>
		</div>
	</div>
<?php $this->template( "help-hub/support/sidebar/{$template_variant}" ); ?>
