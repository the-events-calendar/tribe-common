<?php
/**
 * View: Zapier Integration endpoint dashboard intro text.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/dashboard/intro-text.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 */

?>
<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal tec-settings-form__header-block--no-border">
	<?php $this->template( '/components/loader' ); ?>
	<h3 id="tec-zapier-endpoint-dashboard" class="tec-settings-zapier-application__title tec-settings-form__section-header tec-settings-form__section-header--sub">
		<?php echo esc_html_x( 'Zapier Endpoint Dashboard', 'Zapier settings endpoint dashboard header', 'tribe-common' ); ?>
	</h3>
	<p class="tec-settings-zapier-application__description tec-settings-form__section-description">
		<?php
		$echo = sprintf(
			/* translators: %1$s: URL to the Zapier Endpoint Dashboard documentation */
			_x(
				'Monitor your Zapier endpoints (triggers and actions used by your connectors). <a href="%1$s" target="_blank">Read more about the Zapier Endpoint Dashboard.</a>',
				'Settings help text and link for Zapier Endpoint Dashboard.',
				'tribe-common'
			),
			esc_url( 'https://evnt.is/1bdl' ),
		);

		echo wp_kses_post( $echo );
		?>
	</p>
</div>
