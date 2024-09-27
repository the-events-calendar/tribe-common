<?php
/**
 * View: Zapier Integration Endpoint Dashboard Table.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/dashboard/table.php
 *
 * See more documentation about our views templating system.
 *
 * @since   1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoints An array of the Zapier endpoints.
 * @var Endpoints_Manager   $manager   The Endpoint Manager instance.
 * @var Url                 $url       The URLs handler for the integration.
 */

?>
<fieldset id="tec-field-zapier_token" class="tec-automator-endpoint-dashboard tribe-field tribe-field-text tribe-size-medium tec-settings-form__element--colspan-2">
	<legend class="tribe-field-label"><?php echo esc_html_x( 'Endpoint Dashboard', 'The legend for the Zapier endpoint dashboard.', 'tribe-common' ); ?></legend>
	<div class="tec-automator-settings-message__wrap tec-zapier-endpoint-dashboard-messages">
	</div>
	<div class="tec-automator-settings-items__wrap tec-zapier-endpoint-dashboard-wrap event-automator">
		<?php
		$this->template(
			'zapier/dashboard/endpoints/list',
			[
				'endpoints' => $endpoints,
				'manager'   => $manager,
				'url'       => $url,
			]
		);
		?>
	</div>
</fieldset>
