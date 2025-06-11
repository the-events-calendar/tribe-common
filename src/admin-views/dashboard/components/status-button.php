<?php
/**
 * View: Integration Endpoints - Status Button to enable or disable endpoint.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/components/status
 * -button.php
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
 * @var Endpoints_Manager   $manager  The Endpoint Manager instance.
 * @var Url                 $url      The URLs handler for the integration.
 */

if ( $endpoint['missing_dependency'] ) {
	return;
}

$action_link  = $url->to_enable_endpoint_queue( $endpoint['id'] );
$label        = _x( 'Enable', 'Enables a integration endpoint.', 'tribe-common' );
$confirmation = $manager->get_confirmation_to_enable_endpoint();
$end_type     = 'enable';
if ( $endpoint['enabled'] ) {
	$action_link  = $url->to_disable_endpoint_queue( $endpoint['id'] );
	$label        = _x( 'Disable', 'Disables a integration endpoint queue.', 'tribe-common' );
	$confirmation = $manager->get_confirmation_to_disable_endpoint( $endpoint['type'] );
	$end_type     = 'disable';
}
?>
	<div class="tec-settings-connection-endpoint-dashboard-details-actions__<?php echo esc_html( $end_type ); ?>-wrap">
		<button
			class="tec-settings-connection-endpoint-dashboard-details-action__button tec-settings-connection-endpoint-dashboard-details-actions__<?php echo esc_html( $end_type ); ?> tec-common-integration-details-actions__<?php echo esc_html( $end_type ); ?>"
			type="button"
			data-ajax-action-url="<?php echo esc_url( $action_link ); ?>"
			data-confirmation="<?php echo esc_attr( $confirmation ); ?>"
		>
			<?php echo esc_html( $label ); ?>
		</button>
	</div>
<?php
