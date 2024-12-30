<?php
/**
 * View: Troubleshooting - Initial Troubleshooting Steps
 *
 * @since 4.14.2
 */

?>
<div class="tribe-events-admin-section-header">
	<h3>
		<?php esc_html_e( 'First Steps', 'tribe-common' ); ?>
	</h3>
</div>

<div class="tribe-events-admin-step tribe-events-admin-2col-grid">
	<div class="tribe-events-admin-step-card">
		<div class="tribe-events-admin-step-card__icon">
			<img
				src="<?php echo esc_url( tribe_resource_url( 'images/help/1.png', false, null, $main ) ); ?>"
				alt="<?php esc_attr_e( 'lightbulb icon', 'tribe-common' ); ?>"
			/>
		</div>
		<div class="tribe-events-admin-step-card__content">
			<div class="tribe-events-admin-step__title">
				<?php esc_html_e( 'Test for conflicts', 'tribe-common' ); ?>
			</div>
			<div class="tribe-events-admin-step__description">
				<?php
					$url = '<br /> <a href="https://evnt.is/1apu" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View article', 'tribe-common' ) . '</a>';
					/* translators: %s: link to the article */
					printf( esc_html__( 'Most issues are caused by conflicts with the theme or other plugins. Follow these steps as a first point of action. %s', 'tribe-common' ), $url ); // phpcs:ignore WordPress.Security.EscapeOutput,StellarWP.XSS.EscapeOutput
				?>
			</div>
		</div>
	</div>

	<div class="tribe-events-admin-step-card">
		<div class="tribe-events-admin-step-card__icon">
			<img
				src="<?php echo esc_url( tribe_resource_url( 'images/help/2.png', false, null, $main ) ); ?>"
				alt="<?php esc_attr_e( 'lightbulb icon', 'tribe-common' ); ?>"
			/>
		</div>
		<div class="tribe-events-admin-step-card__content">
			<div class="tribe-events-admin-step__title">
				<?php esc_html_e( 'Share your system info', 'tribe-common' ); ?>
			</div>
			<div class="tribe-events-admin-step__description">
				<?php
					$url = '<br /> <a href="https://evnt.is/1aqd" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View article', 'tribe-common' ) . '</a>';
					/* translators: %s: link to the article */
					printf( esc_html__( 'Providing the details of your calendar plugin and settings (located below) helps our support team troubleshoot an issue faster. %s', 'tribe-common' ), $url ); // phpcs:ignore WordPress.Security.EscapeOutput,StellarWP.XSS.EscapeOutput
				?>
			</div>
		</div>
	</div>
</div>
