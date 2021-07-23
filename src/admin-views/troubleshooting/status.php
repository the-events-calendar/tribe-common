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
        $ea_active = false;
        if ( tribe( 'events-aggregator.main' )->is_service_active() ) {
            $icon = 'success';
            $text      = __( 'Your license is valid', 'the-events-calendar' );
            $ea_active = true;
        } else {
            $service_status = tribe( 'events-aggregator.service' )->api()->get_error_code();

            $icon = 'error';
            if ( 'core:aggregator:invalid-service-key' == $service_status ) {
                $text   = __( 'You do not have a license', 'the-events-calendar' );
                $notes  = '<a href="https://theeventscalendar.com/wordpress-event-aggregator/?utm_source=importsettings&utm_medium=plugin-tec&utm_campaign=in-app">';
                $notes .= esc_html__( 'Buy Event Aggregator to access more event sources and automatic imports!', 'the-events-calendar' );
                $notes .= '</a>';
            } else {
                $text  = __( 'Your license is invalid', 'the-events-calendar' );
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
                alt="<?php esc_attr_e('success-icon', 'tribe-common'); ?>"
            />
            <?php echo esc_html( $text ); ?>
        </td>
        <td><?php echo $notes; // Escaping handled above. ?></td>
    </tr>










    <tr>
        <td>
            <?php esc_html_e('Current usage	', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/success-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('success-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('100 imports used ouf of 1000 available today', 'tribe-common'); ?>
        </td>
    </tr>

    <?php //Import Services?>
    <tr>
        <th>
            <?php esc_html_e('Import Services', 'tribe-common'); ?>
        </th>
    </tr>

    <tr class="tribe-events-admin__ea-status-table-dark">
        <td>
            <?php esc_html_e('Server Connection', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/error-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('error-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('Connected to https://ea.theeventscalendar.com', 'tribe-common'); ?></td>
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