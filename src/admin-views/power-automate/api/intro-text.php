<?php
/**
 * View: Power Automate Integration API Keys intro text.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/power-automate/api/intro-text.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 * @since 1.4.0 - Add loader template.
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array $allowed_html Which HTML elements are used for wp_kses.
 */

?>
<?php $this->template( '/components/loader' ); ?>

<h3 id="tec-power-automate-application-credentials" class="tec-settings-power-automate-application__title">
	<?php echo esc_html_x( 'Power Automate', 'API connection header', 'tribe-common' ); ?>
</h3>
<p class="tec-settings-power-automate-application__description">
	<?php
	printf(
		'%1$s',
		esc_html_x(
			'Please generate a connection for each of our applications you are using with Power Automate to enable its integrations. i.e.: one connection for The Events Calendar and one connection for Event Tickets.',
			'Settings help text for Power Automate API.',
			'tribe-common'
		),
	);
	?>
</p>
<p class="tec-settings-power-automate-application__description">
	<?php
	$url = 'https://evnt.is/1bc8';
	printf(
		'<a href="%1$s" target="_blank">%2$s</a>',
		esc_url( $url ),
		esc_html_x(
			'Read more about adding and managing access.',
			'Settings link text for Power Automate API.',
			'tribe-common'
		)
	);
	?>
</p>
