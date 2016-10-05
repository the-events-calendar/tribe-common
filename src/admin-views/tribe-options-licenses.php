<?php
$utm_link = add_query_arg(
	array(
		'utm_campaign' => 'in-app',
		'utm_medium'   => 'plugin-tec',
		'utm_source'   => 'notice',
	), Tribe__Main::$tec_url . 'license-keys/'
);

$utm_link = esc_url( $utm_link );
$license_link = '<a href="' . $utm_link . '" target="_blank">' . Tribe__Main::$tec_url . '<span class="screen-reader-text">' . __( ' (opens in new window)', 'tribe-common' ) . '</span></a>';

// Explanatory text about license settings for the tab information box
$html = '<p>' . sprintf(
		esc_html__( 'The license key you received when completing your purchase from %1$s will grant you access to support and updates until it expires. You do not need to enter the key below for the plugins to work, but you will need to enter it to get automatic updates. %3$sFind your license keys at %2$s%4$s.', 'tribe-common' ),
		'<a href="' . Tribe__Main::$tec_url . '" target="_blank">' . Tribe__Main::$tec_url . '<span class="screen-reader-text">' . __( ' (opens in new window)', 'tribe-common' ) . '</span></a>',
		$license_link,
		'<strong>',
		'</strong>'
	) . '</p>';

$html .= '<p>' . esc_html__( 'Each paid add-on has its own unique license key. Simply paste the key into its appropriate field below, and give it a moment to validate. You know you\'re set when a green expiration date appears alongside a "valid" message.', 'tribe-common' ) . '</p>';

$html .= '<p>' . sprintf(
		esc_html__( 'If you\'re seeing a red message telling you that your key isn\'t valid or is out of installs, visit %1$s to manage your installs or renew / upgrade your license.', 'tribe-common' ),
		$license_link
	) . '</p>';

$html .= '<p>' . sprintf(
		esc_html__( 'Not seeing an update but expecting one? In WordPress, go to %1$sDashboard > Updates%2$s and click "Check Again".', 'tribe-common' ),
		'<a href="' . admin_url( '/update-core.php' ) . '">',
		'</a>'
	) . '</p>';

// Expand with extra information for mu network users
if ( is_multisite() ) {
	$network_all_sites_text = sprintf(
		esc_html__( '%1$s Using our plugins in a multisite network? %2$s Please note that your license key will be applied to the entire network, not just this site.', 'tribe-common' ),
		'<strong>',
		'</strong>'
	);

	$network_admin_only = '';

	if ( is_network_admin() ) {
		$network_admin_only = sprintf(
			esc_html__(
				'Only license fields for %1$snetwork activated%2$s plugins will be listed on this screen. ',
				'tribe-common'
			),
			'<strong>',
			'</strong>'
		);
	}

	$html .= "<p> $network_all_sites_text $network_admin_only </p>";
}

$licenses_tab = array(
	'info-start' => array(
		'type' => 'html',
		'html' => '<div id="modern-tribe-info">',
	),
	'info-box-title' => array(
		'type' => 'html',
		'html' => '<h2>' . esc_html__( 'Licenses', 'tribe-common' ) . '</h2>',
	),
	'info-box-description' => array(
		'type' => 'html',
		'html' => $html,
	),
	'info-end' => array(
		'type' => 'html',
		'html' => '</div>',
	),
);
