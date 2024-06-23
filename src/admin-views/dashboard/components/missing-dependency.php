<?php
/**
 * View: Integration Endpoints - Missing Dependency Message.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/components/missing-dependency.php
 * -button.php
 *
 * See more documentation about our views templating system.
 *
 * @since   1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoint An array of the integration endpoint data.
 * @var Endpoints_Manager   $manager  The Endpoint Manager instance.
 * @var Url                 $url      The URLs handler for the integration.
 */

if ( empty( $endpoint['dependents'] ) ) {
	return;
}

switch ( $endpoint['dependents'][0] ) {
	case 'tec':
		$dependency = '<a href="https://wordpress.org/plugins/the-events-calendar/">' . _x( 'The Events Calendar', 'Name of missing dependency for Endpoint.', 'event-automator' ) . '</a>';
		break;
	case 'et':
		$dependency = '<a href="https://wordpress.org/plugins/event-tickets/">' . _x( 'Event Tickets', 'Name of missing dependency for Endpoint.', 'event-automator' ) . '</a>';
		break;
}

$tooltip = [
	'classes_wrap' => [ 'tec-settings-connection-endpoint-dashboard-details__tooltip' ],
	'message'      => sprintf(
		'%1s %2s %3s',
		_x( 'Missing ', 'Missing dependency message in the settings.', 'event-automator' ),
		$dependency,
		_x( ' plugin.', 'Missing dependency message in the settings.', 'event-automator' )
	),
];
?>
<div class="tec-settings-connection-endpoint-dashboard-details-actions__missing-dependency-wrap">
	<?php
	echo esc_html_x( 'Endpoint Disabled', 'Missing dependency label in the settings.', 'event-automator' );
	$this->template( 'components/tooltip', $tooltip );
	?>
</div>
<?php
