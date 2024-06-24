<?php
/**
 * View: Integration Endpoint Dashboard Endpoint Fields.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/endpoints/endpoint.php
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
 * @var Endpoints_Manager   $manager   The Endpoint Manager instance.
 * @var Url                 $url       The URLs handler for the integration.
 */

?>

<div class="tec-automator-grid tec-automator-grid-row tec-automator-endpoint-dashboard-grid tec-automator-settings-details__container tec-settings-connection-endpoint-dashboard-details__container" data-endpoint-id="<?php echo esc_attr( $endpoint['id'] ); ?>">
	<?php
	$this->template(
		'components/read-only',
		[
			'classes_wrap'  => [ 'tec-automator-grid-item', 'tec-settings-connection-endpoint-dashboard-details__name-wrap', ! $endpoint['enabled'] || $endpoint['missing_dependency'] ? 'disabled' : '' ],
			'label'         => _x( 'Name', 'Label for the integration endpoint dashboard endpoint name.', 'tribe-common' ),
			'screen_reader' => _x( 'The name for the integration endpoint.', 'The screen reader text of the label for the integration endpoint Dashboard endpoint name.', 'tribe-common' ),
			'id'            => 'tec_automator_integration_endpoint_name_' . $endpoint['id'],
			'name'          => "tec_automator_integration[]['endpoint_name']",
			'value'         => $endpoint['display_name'],
		]
	);
	?>
	<?php
	$last_access_classes = [ 'tec-automator-grid-item', 'tec-settings-connection-endpoint-dashboard-details__last-access-wrap' ];
	$last_access_label   = _x( 'Last Access', 'Label for the integration endpoint Dashboards endpoint last access.', 'tribe-common' );
	if ( $endpoint['enabled'] && ! $endpoint['missing_dependency'] ) {
		$this->template(
			'components/read-only',
			[
				'classes_wrap'  => $last_access_classes,
				'label'         => $last_access_label,
				'screen_reader' => _x( 'The last access for the integration endpoint.', 'The screen reader text of the label for the integration endpoint Dashboard endpoint last access.', 'tribe-common' ),
				'id'            => 'tec_automator_integration_endpoint_last_access_' . $endpoint['id'],
				'name'          => "tec_automator_integration[]['endpoint_last_access']",
				'value'         => str_replace( '|', ' - ', $endpoint['last_access'] ),
			]
		);
	} else {
		$this->template(
			'dashboard/components/disabled',
			[
				'classes_wrap'  => $last_access_classes,
				'label'         => $last_access_label,
				'screen_reader' => _x( 'The last access is disabled as this endpoint is disabled.', 'The screen reader text of the label for the integration endpoint Dashboard endpoint last access when disabled.', 'tribe-common' ),
			]
		);
	}
	?>
	<?php
	$queue_classes = [ 'tec-automator-grid-item', 'tec-settings-connection-endpoint-dashboard-details__queue-wrap' ];
	$queue_label   = _x( 'Queue', 'Label for the integration endpoint Dashboards endpoint queue.', 'tribe-common' );
	$queue_status  = _x( 'none', 'Label for the integration endpoint Dashboards endpoint queue status.', 'tribe-common' );
	if ( $endpoint['count'] > 0 ) {
		$queue_status = _x( 'ready', 'Label for the integration endpoint Dashboards endpoint queue status.', 'tribe-common' );
	}
	if ( $manager::$api_id === 'power-automate' ) {
		$queue_status = $endpoint['count'];
	}

	if ( $endpoint['type'] === 'queue' && $endpoint['enabled'] ) {
		$this->template(
			'components/read-only',
			[
				'classes_wrap'  => $queue_classes,
				'label'         => $queue_label,
				'screen_reader' => _x( 'The Queue for the integration endpoint.', 'The screen reader text of the label for the integration endpoint Dashboard endpoint queue.', 'tribe-common' ),
				'id'            => 'tec_automator_integration_endpoint_queue_' . $endpoint['id'],
				'name'          => "tec_automator_integration[]['endpoint_queue']",
				'value'         => $queue_status,
			]
		);
	} else {
		$this->template(
			'dashboard/components/disabled',
			[
				'classes_wrap'  => $queue_classes,
				'label'         => $queue_label,
				'screen_reader' => _x( 'The Queue is disabled for this endpoint.', 'The screen reader text of the label for the integration endpoint Dashboard endpoint queue when disabled.', 'tribe-common' ),
			]
		);
	}
	?>
	<div class="tec-automator-grid-item tec-settings-connection-endpoint-dashboard-details__actions-wrap tec-common-integration-endpoint-details__actions-wrap">
		<?php
		if ( $endpoint['missing_dependency'] ) {
			$this->template(
				'dashboard/components/missing-dependency',
				[
					'endpoint' => $endpoint,
					'manager'  => $manager,
					'url'      => $url,
				]
			);
		} else {
			$this->template(
				'dashboard/components/clear-button',
				[
					'endpoint' => $endpoint,
					'manager'  => $manager,
					'url'      => $url,
				]
			);
			$this->template(
				'dashboard/components/status-button',
				[
					'endpoint' => $endpoint,
					'manager'  => $manager,
					'url'      => $url,
				]
			);
		}
		?>
	</div>
</div>
