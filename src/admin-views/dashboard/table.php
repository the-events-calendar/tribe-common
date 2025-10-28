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
 *
 * @version 5.26.7
 *
 * @link http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoints An array of intergration endpoints.
 * @var Endpoints_Manager   $manager   The Endpoint Manager instance.
 * @var Url                 $url       The URLs handler for the integration.
 */

?>
<fieldset id="tec-field-integration_token" class="tec-automator-endpoint-dashboard tribe-field tribe-field-text tribe-size-medium">
	<legend class="tribe-field-label"><?php echo esc_html_x( 'Endpoint Dashboard', 'The legend for the integration endpoint dashboard.', 'tribe-common' ); ?></legend>
	<?php if ( $manager::$api_id === 'zapier' ) { ?>
		<p class="tec-settings-zapier-application__description">
			<?php
				printf(
					/* Translators: 1: Opening anchor tag, 2: Closing anchor tag. */
					esc_html_x(
						'The Zapier queue is currently limited to 15 items for each endpoint on your site. To increase that limit, check out the %1$sIncreasing the Zapier Queue Limit knowledgebase article%2$s.',
						'The Zapier endpoint dashboard description.',
						'tribe-common'
					),
					'<a href="https://evnt.is/zapier-endpoints" target="_blank" rel="noopener noreferrer">',
					'</a>',
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
