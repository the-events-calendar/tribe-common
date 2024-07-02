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
<?php $this->template( '/components/loader' ); ?>

<h3 id="tec-power-automate-endpoint-dashboard" class="tec-settings-power-automate-application__title">
	<?php echo esc_html_x( 'Power Automate Endpoint Dashboard', 'Power Automate settings endpoint dashboard header', 'tribe-common' ); ?>
</h3>
<p class="tec-settings-power-automate-application__description">
	<?php
	printf(
		'%1$s',
		esc_html_x(
			'Monitor your Power Automate endpoints (triggers and actions used by your connectors).',
			'Settings help text for Power Automate Endpoint Dashboard.',
			'tribe-common'
		),
	);
	?>
</p>
<p class="tec-settings-power-automate-application__description">
	<?php
	$url = 'https://evnt.is/1bcx';
	printf(
		'<a href="%1$s" target="_blank">%2$s</a>',
		esc_url( $url ),
		esc_html_x(
			'Read more about the Power Automate Endpoint Dashboard.',
			'Settings link text for Power Automate endpoint dashboard.',
			'tribe-common'
		)
	);
	?>
</p>
