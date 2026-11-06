<?php
/**
 * View: Integration Endpoint Dashboard Table.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/table.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 * @since 5.26.7 Updated Zapier Knowledgebase URL.
 * @since TBD Removed the Zapier queue limit knowledgebase link.
 *
 * @version TBD
 *
 * @link http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoints An array of intergration endpoints.
 * @var Endpoints_Manager   $manager   The Endpoint Manager instance.
 * @var Url                 $url       The URLs handler for the integration.
 */

defined( 'ABSPATH' ) || exit;

?>
<fieldset id="tec-field-integration_token" class="tec-automator-endpoint-dashboard tribe-field tribe-field-text tribe-size-medium">
	<legend class="tribe-field-label"><?php echo esc_html_x( 'Endpoint Dashboard', 'The legend for the integration endpoint dashboard.', 'tribe-common' ); ?></legend>
	<?php if ( $manager::$api_id === 'zapier' ) { ?>
		<p class="tec-settings-zapier-application__description">
			<?php
				echo esc_html_x(
					'The Zapier queue is currently limited to 15 items for each endpoint on your site.',
					'The Zapier endpoint dashboard description.',
					'tribe-common'
				);
			?>
		</p>
	<?php } ?>
	<div class="tec-automator-settings-message__wrap tec-integration-endpoint-dashboard-messages">
	</div>
	<div class="tec-automator-settings-items__wrap tec-integration-endpoint-dashboard-wrap event-automator">
		<?php
		$this->template(
			'dashboard/endpoints/list',
			[
				'endpoints' => $endpoints,
				'manager'   => $manager,
				'url'       => $url,
			]
		);
		?>
	</div>
</fieldset>
