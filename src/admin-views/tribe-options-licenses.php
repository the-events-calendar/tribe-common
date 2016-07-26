<?php
$link = add_query_arg(
	array(
		'utm_campaign' => 'in-app',
		'utm_medium'   => 'plugin-tec',
		'utm_source'   => 'notice',
	), Tribe__Main::$tec_url . 'license-keys/'
);

$link = esc_url( $link );

// Explanatory text about license settings for the tab information box
$html = __( '<p>The license key you received when completing your purchase from %1$s will grant you access to support and updates until it expires. You do not need to enter the key below for the plugins to work, but you will need to enter it to get automatic updates. <strong>Find your license keys at <a href="%2$s" target="_blank">%3$s</a></strong>.</p> <p>Each paid add-on has its own unique license key. Simply paste the key into its appropriate field on below, and give it a moment to validate. You know you\'re set when a green expiration date appears alongside a "valid" message.</p> <p>If you\'re seeing a red message telling you that your key isn\'t valid or is out of installs, visit <a href="%4$s" target="_blank">%5$s</a> to manage your installs or renew / upgrade your license.</p><p>Not seeing an update but expecting one? In WordPress, go to <a href="%6$s">Dashboard > Updates</a> and click "Check Again".</p>', 'tribe-common' );

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

// Explanatory text about license settings for the tab information box
$support_html = '<p>' . __( 'The details of your calendar plugin and settings are often needed for you or our staff to help troubleshoot an issue. Please opt-in below to automatically share your system information with our support team. This will allow us to assist you faster if you post in our <a href="%1$s" target="_blank">forums</a>. You can see exactly what information you\'ll be sharing by viewing the System Info section on the <a href="%2$s" target="_blank">Help Tab</a>.', 'tribe-common' ) . '</p>';

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
		'html' => sprintf(
			$html,
			Tribe__Main::$tec_url,
			$link,
			Tribe__Main::$tec_url . 'license-keys/',
			$link,
			Tribe__Main::$tec_url . 'license-keys/',
			admin_url( '/update-core.php' )
		),
	),
	'info-end' => array(
		'type' => 'html',
		'html' => '</div>',
	),
	'tribe-form-content-start' => array(
		'type' => 'html',
		'html' => '<div class="tribe-settings-form-wrap">',
	),

	'sysinfo-box-title' => array(
		'type' => 'html',
		'html' => '<h3>' . esc_html__( 'Support', 'tribe-common' ) . '</h3>',
	),
	'sysinfo-box-description' => array(
		'type' => 'html',
		'html' => sprintf(
			$support_html,
			'http://m.tri.be/194m',
			Tribe__Settings::instance()->get_url( array( 'tab' => 'help' ) )
		),
	),
	'sysinfo-optin-checkbox' => array(
		'type' => 'html',
		'html' => Tribe__Support::opt_in(),
	),

	// TODO: Figure out how properly close this wrapper after the license content
	'tribe-form-content-end'   => array(
		'type' => 'html',
		'html' => '</div>',
	),
);
