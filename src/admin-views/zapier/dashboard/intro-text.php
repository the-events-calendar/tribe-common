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
<div class="tec_settings__header-block tec_settings__header-block--horizontal">
	<?php $this->template( '/components/loader' ); ?>
	<h3 id="tec-zapier-endpoint-dashboard" class="tec-settings-zapier-application__title tec-settings__section-header tec-settings__section-header--sub">
		<?php echo esc_html_x( 'Zapier Endpoint Dashboard', 'Zapier settings endpoint dashboard header', 'tribe-common' ); ?>
	</h3>
	<p class="tec-settings-zapier-application__description tec_settings__section-description">
		<?php
		printf(
			/* translators: %1$s: URL to the Zapier Endpoint Dashboard documentation */
			_x(
				'Monitor your Zapier endpoints (triggers and actions used by your connectors). <a href="%1$s" target="_blank">Read more about the Zapier Endpoint Dashboard.</a>',
				'Settings help text and link for Zapier Endpoint Dashboard.',
				'tribe-common'
			),
			'https://evnt.is/1bdl',
		);
		?>
	</p>
</div>
