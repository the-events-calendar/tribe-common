<?php
/**
 * View: Zapier Integration - Missing Dependency Message.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/dashboard/components/missing-dependency.php
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
 * @var array<string,array> $endpoint An array of the Zapier endpoint data.
 * @var Endpoints_Manager   $manager  The Endpoint Manager instance.
 * @var Url                 $url      The URLs handler for the integration.
 */

if ( empty( $endpoint['dependents'] ) ) {
	return;
}

$dependents = [];
foreach ( $endpoint['dependents'] as $dependent ) {
	if ( $dependent === 'tec') {
		$dependents[] = _x( 'The Events Calendar', 'The of the missing dependent plugin for a Zapier Endpoint.','event-automator' );
	}
	if ( $dependent === 'et') {
		$dependents[] = _x( 'Event Tickets', 'The of the missing dependent plugin for a Zapier Endpoint.','event-automator' );
	}
}
$tooltip      = [
		'classes_wrap'  => [ 'tec-settings-connection-endpoint-dashboard-details__tooltip' ],
		'message'   => sprintf( '%1s %2s',
			_x( 'Missing ', 'Missing dependency message in the settings.', 'event-automator' ),
			implode( ' and ', $dependents )
		),
	];
?>
	<div class="tec-settings-connection-endpoint-dashboard-details-actions__missing-dependency-wrap">
		<?php echo esc_html_x( 'Endpoint Disabled', 'Missing dependency label in the settings.', 'event-automator' ); ?>
		<?php $this->template( 'components/tooltip', $tooltip );
		?>
	</div>
<?php
