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
<?php $this->template( '/components/loader' ); ?>

<h3 id="tec-zapier-endpoint-dashboard" class="tec-settings-zapier-application__title">
	<?php echo esc_html_x( 'Zapier Endpoint Dashboard', 'Zapier settings endpoint dashboard header', 'tribe-common' ); ?>
</h3>
<p class="tec-settings-zapier-application__description">
	<?php
	printf(
		'%1$s',
		esc_html_x(
			'Monitor your Zapier endpoints (triggers and actions used by your connectors).',
			'Settings help text for Zapier Endpoint Dashboard.',
			'tribe-common'
		),
	);
	?>
</p>
<p class="tec-settings-zapier-application__description">
	<?php
	$url = 'https://evnt.is/1bdl';
	printf(
		'<a href="%1$s" target="_blank">%2$s</a>',
		esc_url( $url ),
		esc_html_x(
			'Read more about the Zapier Endpoint Dashboard.',
			'Settings link text for Zapier endpoint dashboard.',
			'tribe-common'
		)
	);
	?>
</p>
