<?php
/**
 * View: Integration Endpoints - Clear Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/components/clear-button.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoint An array of the integration endpoint data.
 * @var Endpoints_Manager   $manager   The Endpoint Manager instance.
 * @var Url                 $url       The URLs handler for the integration.
 */

// Only show for queue endpoints.
if ( $endpoint['type'] !== 'queue' || ! $endpoint['enabled'] || $endpoint['missing_dependency'] ) {
	return;
}

$clear_link  = $url->to_clear_endpoint_queue( $endpoint['id'] );
$clear_label = _x( 'Clear Queue', 'Clears a integration endpoint queue.', 'tribe-common' )
?>
<div class="tec-settings-connection-endpoint-dashboard-details-actions__clear-wrap ">
	<button
		class="tec-settings-connection-endpoint-dashboard-details-action__button tec-settings-connection-endpoint-dashboard-details-actions__clear tec-common-integration-endpoint-details-actions__clear"
		type="button"
		data-ajax-action-url="<?php echo esc_url( $clear_link ); ?>"
		data-confirmation="<?php echo esc_attr( $manager->get_confirmation_to_clear_endpoint_queue() ); ?>"
	>
		<?php echo esc_html( $clear_label ); ?>
	</button>
</div>
