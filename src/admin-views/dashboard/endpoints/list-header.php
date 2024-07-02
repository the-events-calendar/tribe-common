<?php
/**
 * View: Integration Endpoint Dashboard Table Header.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/endpoints/list-header.php
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
<div class="tec-automator-settings-details__container tec-settings-connection-endpoint-dashboard-details__container tec-settings-connection-endpoint-dashboard-details__container-header tec-automator-grid tec-automator-endpoint-dashboard-grid tec-automator-grid-header">
	<div class="tec-automator-grid-item tec-automator-settings-details__row tec-settings-connection-endpoint-dashboard-details__name-wrap">
		<?php echo esc_html_x( 'Name', 'Name header label for the settings listing of the integration endpoints.', 'tribe-common' ); ?>
	</div>
	<div class="tec-automator-grid-item tec-settings-connection-endpoint-dashboard-details__last-access-wrap">
		<?php echo esc_html_x( 'Last Access', 'Last Access header label for the settings listing of the integration endpoints.', 'tribe-common' ); ?>
	</div>
	<div class="tec-automator-grid-item tec-settings-connection-endpoint-dashboard-details__queue-wrap">
		<?php echo esc_html_x( 'Queue', 'Last Access header label for the settings listing of the integration endpoints.', 'tribe-common' ); ?>
	</div>
	<div class="tec-automator-grid-item tec-settings-connection-endpoint-dashboard-details__actions-wrap tec-common-integration-endpoint-details__actions-wrap">
		<?php echo esc_html_x( 'Actions', 'Actions header label for the settings listing of the integration endpoints.', 'tribe-common' ); ?>
	</div>
</div>
