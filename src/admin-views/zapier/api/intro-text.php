<?php
/**
 * View: Zapier Integration API Keys intro text.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/intro-text.php
 *
 * See more documentation about our views templating system.
 *
 * @since TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array $allowed_html Which HTML elements are used for wp_kses.
 */

?>
<h3 id="tec-zapier-application-credentials" class="tec-settings-zapier-application__title">
	<?php echo esc_html_x( 'Zapier', 'API connection header', 'tribe-common' ); ?>
</h3>
<p>
	<?php
	$url = 'https://evnt.is/1b9c';
	echo sprintf(
		'%1$s. <a href="%2$s" target="_blank">%3$s</a>',
		esc_html_x(
			'Please generate at least one consumer id and secret to enable the Zapier integrations.',
		'Settings help text for Zapier API.',
		'tribe-common'
		),
		esc_url( $url ),
		esc_html_x(
			'Read more about adding and managing access.',
			'Settings link text for Zapier API.',
			'tribe-common'
		)
	);
	?>
</p>
