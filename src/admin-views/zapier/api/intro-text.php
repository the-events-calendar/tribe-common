<?php
/**
 * View: Zapier Integration API Keys intro text.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/intro-text.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 * @since 1.4.0 - Add loader template.
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array $allowed_html Which HTML elements are used for wp_kses.
 */

?>
<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal tec-settings-form__header-block--no-border">
	<?php $this->template( '/components/loader' ); ?>
	<h3 id="tec-zapier-application-credentials" class="tec-settings-zapier-application__title tec-settings-form__section-header tec-settings-form__section-header--sub">
		<?php echo esc_html_x( 'Zapier', 'API connection header', 'tribe-common' ); ?>
	</h3>
	<p class="tec-settings-zapier-application__description tec-settings-form__section-description">
		<?php
		$content = sprintf(
			/* Translators: %1$s: URL to the Zapier API documentation */
			_x(
				'Please generate a consumer id and secret for each of our applications you are using with Zapier to enable its integrations. i.e.: one consumer id and secret for The Events Calendar and one consumer id and secret for Event Tickets. <a href="%1$s" target="_blank">Read more about adding and managing access.</a>',
				'Settings help text and link for Zapier API.',
				'tribe-common'
			),
			'https://evnt.is/1bc8'
		);
		echo wp_kses_post( $content );
		?>
	</p>
</div>
