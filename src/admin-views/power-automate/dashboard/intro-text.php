<?php
/**
 * View: Power Automate Integration endpoint dashboard intro text.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/power-automate/dashboard/intro-text.php
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
<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
	<?php $this->template( '/components/loader' ); ?>
	<h3 id="tec-power-automate-endpoint-dashboard" class="tec-settings-power-automate-application__title tec-settings-form__section-header tec-settings-form__section-header--sub">
		<?php echo esc_html_x( 'Power Automate Endpoint Dashboard', 'Power Automate settings endpoint dashboard header', 'tribe-common' ); ?>
	</h3>
	<p class="tec-settings-power-automate-application__description tec-settings-form__section-description">
		<?php
		$content = sprintf(
			/* Translators: %1$s: URL to the Power Automate Endpoint Dashboard documentation */
			_x(
				'Monitor your Power Automate endpoints (triggers and actions used by your connectors). <a href="%1$s" target="_blank">Read more about the Power Automate Endpoint Dashboard.</a>',
				'Settings help text for Power Automate Endpoint Dashboard.',
				'tribe-common'
			),
			'https://evnt.is/1bcx'
		);
		echo wp_kses_post( $content );
		?>
	</p>
</div>
