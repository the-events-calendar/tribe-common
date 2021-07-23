<?php 
/**
 * View: Troubleshooting - EA Status
 * 
 * @since TBD
 * 
 */
$status_icons = [
	'success' => 'images/help/success-icon.svg',
	'warning' => 'images/help/warning-icon.svg',
	'error'   => 'images/help/error-icon.svg',
];

$show_third_party_accounts = ! is_network_admin();
?>
<h3 class="tribe-events-admin__troubleshooting-title tribe-events-admin__ea-status">
    <?php esc_html_e('Event Aggregator system status ', 'tribe-common'); ?>
</h3>

<table class="tribe-events-admin__ea-status-table">
    <?php //License & Usage
        $message = '&nbsp;';
        $ea_active = true; // temporarily set to true to allow development of the rest of the table's features
        if ( tribe( 'events-aggregator.main' )->is_service_active() ) {
            $icon = 'success';
            $message      = __( 'Your license is valid', 'the-events-calendar' );
            $ea_active = true;
        } else {
            $service_status = tribe( 'events-aggregator.service' )->api()->get_error_code();

            $icon = 'error';
            if ( 'core:aggregator:invalid-service-key' == $service_status ) {
                $message   = __( 'You do not have a license', 'the-events-calendar' );
                $notes  = '<a href="https://theeventscalendar.com/wordpress-event-aggregator/?utm_source=importsettings&utm_medium=plugin-tec&utm_campaign=in-app">';
                $notes .= esc_html__( 'Buy Event Aggregator to access more event sources and automatic imports!', 'the-events-calendar' );
                $notes .= '</a>';
            } else {
                $message  = __( 'Your license is invalid', 'the-events-calendar' );
                $notes = '<a href="' . esc_url( Tribe__Settings::instance()->get_url( [ 'tab' => 'licenses' ] ) ) . '">' . esc_html__( 'Check your license key', 'the-events-calendar' ) . '</a>';
            }
        }
    ?>
    <tr>
        <th>
            <?php esc_html_e('License & Usage', 'tribe-common'); ?>
        </th>
    </tr>

    <tr class="tribe-events-admin__ea-status-table-dark">
        <td>
            <?php esc_html_e('License Key', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url($status_icons[ $icon ], false, null, $main)); ?>"
                alt=""
            />
            <?php echo esc_html( $message ); ?>
        </td>
        <td><?php echo $notes; // Escaping handled above. ?></td>
    </tr>

    <?php
		// if EA is not active, bail out of the rest of this
		if ( ! $ea_active ) {
			echo '</table>';
			return;
		}

		$service          = tribe( 'events-aggregator.service' );
		$import_limit     = $service->get_limit( 'import' );
		$import_available = $service->get_limit_remaining();
		$import_count     = $service->get_limit_usage();

		$icon = 'success';
		$notes     = '&nbsp;';

		if ( 0 === $import_limit || $import_count >= $import_limit ) {
			$icon = 'error';
			$notes     = esc_html__( 'You have reached your daily import limit. Scheduled imports will be paused until tomorrow.', 'the-events-calendar' );
		} elseif ( $import_count / $import_limit >= 0.8 ) {
			$icon = 'warning';
			$notes     = esc_html__( 'You are approaching your daily import limit. You may want to adjust your Scheduled Import frequencies.', 'the-events-calendar' );
		}

		$message = sprintf( // import count and limit
			_n( '%1$d import used out of %2$d available today', '%1$d imports used out of %2$d available today', $import_count, 'the-events-calendar' ),
			intval( $import_count ),
			intval( $import_limit )
		);

		?>
    <tr>
        <td>
            <?php esc_html_e('Current usage	', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url($status_icons[ $icon ], false, null, $main)); ?>"
                alt=""
            />
            <?php echo esc_html( $message ); ?>
        </td>
        <td><?php echo $notes;  // Escaping handled above. ?></td>
    </tr>


<?php //to be continued ?>


    <?php //Import Services?>
    <tr>
        <th>
            <?php esc_html_e('Import Services', 'tribe-common'); ?>
        </th>
    </tr>

    <?php
		$icon = 'success';
		$notes     = '&nbsp;';

		$ea_server = tribe( 'events-aggregator.service' )->api()->domain;
		$up        = tribe( 'events-aggregator.service' )->get( 'status/up' );

		if ( ! $up || is_wp_error( $up ) ) {
			$icon = 'error';
			/* translators: %s: Event Aggregator Server URL */
			$message  = sprintf( __( 'Not connected to %s', 'the-events-calendar' ), $ea_server );
			$notes = esc_html__( 'The server is not currently responding', 'the-events-calendar' );
		} elseif ( is_object( $up ) && is_object( $up->data ) && isset( $up->data->status ) && 400 <= $up->data->status ) {
			// this is a rare condition that should never happen
			// An example case: the route is not defined on the EA server
			$icon = 'warning';

			/* translators: %s: Event Aggregator Server URL */
			$message = sprintf( __( 'Not connected to %s', 'the-events-calendar' ), $ea_server );

			$notes  = __( 'The server is responding with an error:', 'the-events-calendar' );
			$notes .= '<pre>';
			$notes .= esc_html( $up->message );
			$notes .= '</pre>';
		} else {
			/* translators: %s: Event Aggregator Server URL */
			$message = sprintf( __( 'Connected to %s', 'the-events-calendar' ), $ea_server );
		}
    ?>

    <tr class="tribe-events-admin__ea-status-table-dark">
        <td>
            <?php esc_html_e('Server Connection', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url($status_icons[ $icon ], false, null, $main)); ?>"
                alt=""
            />
            <?php echo esc_html( $message ); ?>
        </td>
        <td><?php echo $notes;  // Escaping handled above. ?></td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e( 'Scheduler Status', 'tribe-common' ); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url( tribe_resource_url( 'images/help/success-icon.svg', false, null, $main ) ); ?>"
                alt="<?php esc_attr_e( 'success-icon', 'tribe-common' ); ?>"
            />
            <?php esc_html_e( 'WP Cron enabled', 'tribe-common' ); ?>
        </td>
    </tr>

    <?php //Third Party Accounts?>
    <tr>
        <th>
            <?php esc_html_e( 'Third Party Accounts', 'tribe-common' ); ?>
        </th>
    </tr>

    <tr class="tribe-events-admin__ea-status-table-dark">
        <td class="tribe-events-admin__ea-status-table-dark">
            <?php esc_html_e( 'Eventbrite', 'tribe-common' ); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/warning-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('warning-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('Event Aggregator is not connected to Eventbrite', 'tribe-common'); ?>
        </td>
        <td>
            <a href="" target="_blank" rel="noreferrer">
                <?php esc_html_e('Connect to Eventbrite', 'tribe-common'); ?>
            </a>
        </td>
    </tr>

    <tr>
        <td>
            <?php esc_html_e('Meetup', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/warning-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('warning-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('Event Aggregator is not connected to Meetup', 'tribe-common'); ?>
        </td>
        <td>
            <a href="" target="_blank" rel="noreferrer">
                <?php esc_html_e('Connect to Meetup', 'tribe-common'); ?>
            </a>
        </td>
    </tr>
</table>