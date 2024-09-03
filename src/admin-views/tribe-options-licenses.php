<?php
/**
 * The licenses tab for the Tribe Options page.
 */

// Explanatory text about license settings for the tab information box.
use TEC\Common\Admin\Entities\Div;
use TEC\Common\Admin\Entities\Heading;
use TEC\Common\Admin\Entities\Paragraph;
use TEC\Common\Admin\Entities\Plain_Text;
use Tribe\Utils\Element_Classes;

$html = '<p>' .
		esc_html__( 'If you\'ve purchased a premium add-on, you\'ll need to enter your license key here in order to have access to automatic updates when new versions are available.', 'tribe-common' ) .
		'</p>';

$html .= '<p>' .
		sprintf(
			/* translators: %1$s and %2$s are placeholders for the opening and closing <a> tags, %3$s and %4$s are placeholders for the opening and closing <a> tags */
			esc_html__( 'In order to register a plugin license, you\'ll first need to %1$sdownload and install%2$s the plugin you purchased. You can download the latest version of your plugin(s) from %3$syour account\'s downloads page%4$s. Once the plugin is installed and activated on this site, the license key field will appear below.', 'tribe-common' ),
			'<a href="http://evnt.is/1acu" target="_blank">',
			'</a>',
			'<a href="http://evnt.is/1act" target="_blank">',
			'</a>'
		) .
		'</p>';

$html .= '<p>' .
		esc_html__( 'Each paid add-on has its own unique license key. Paste the key into its appropriate field below, and give it a moment to validate. You know you\'re set when a green expiration date appears alongside a "valid" message. Then click Save Changes.', 'tribe-common' ) .
		'</p>';

$html .= '<p>' .
		esc_html__( 'Helpful Links:', 'tribe-common' ) .
		'</p>';

$html .= '<ul>';
$html .= '<li><a href="http://evnt.is/1acv" target="_blank">' .
			esc_html__( 'Why am I being told my license key is out of installs?', 'tribe-common' ) .
		'</a></li>';
$html .= '<li><a href="http://evnt.is/1ad1" target="_blank">' .
			esc_html__( 'View and manage your license keys', 'tribe-common' ) .
		'</a></li>';
$html .= '<li><a href="http://evnt.is/1acw" target="_blank">' .
			esc_html__( 'Moving your license keys', 'tribe-common' ) .
		'</a></li>';
$html .= '<li><a href="http://evnt.is/1acx" target="_blank">' .
			esc_html__( 'Expired license keys and subscriptions', 'tribe-common' ) .
		'</a></li>';

// Expand with extra information for multisite users.
if ( is_multisite() ) {
	$html .= '<li><a href="http://evnt.is/1ad0" target="_blank">' .
			esc_html__( 'Licenses for Multisites', 'tribe-common' ) .
		'</a></li>';
}

$html .= '</ul>';


$old_license_field_info_box = [
	'info-start'           => [
		'type' => 'html',
		'html' => '<div id="modern-tribe-info">',
	],
	'info-box-title'       => [
		'type' => 'html',
		'html' => '<h2>' . esc_html__( 'Licenses', 'tribe-common' ) . '</h2>',
	],
	'info-box-description' => [
		'type' => 'html',
		'html' => $html,
	],
	'info-end'             => [
		'type' => 'html',
		'html' => '</div>',
	],
];

$license_title = new Div( new Element_Classes( [ 'tec-settings-form__header-block', 'tec-settings-form__header-block--horizontal' ] ) );
$license_title->add_child(
	new Heading(
		_x( 'Licenses', 'Licenses section header', 'tribe-common' ),
		3,
		new Element_Classes( 'tec-settings-form__section-header' )
	)
);
$license_title->add_child(
	( new Paragraph( new Element_Classes( 'tec-settings-form__section-description' ) ) )->add_children(
		[
			new Plain_Text( __( "If you've purchased a premium add-on, you'll need to enter your license key here in order to have access to automatic updates when new versions are available.", 'tribe-common' ) ),
		]
	)
);

$license_fields = [
	'tec-events-pro-defaults-licenses-title' => $license_title,
];


/**
 * Allows the fields displayed in the licenses tab to be modified.
 *
 * @var array<string,mixed> $license_fields Array of fields used to setup the Licenses Tab.
 */
$license_fields = apply_filters( 'tribe_license_fields', $license_fields );


$licenses_tab = new Tribe__Settings_Tab(
	'licenses',
	esc_html__( 'Licenses', 'tribe-common' ),
	[
		'priority'      => 40,
		'fields'        => $license_fields,
		'network_admin' => is_network_admin(),
	]
);

do_action( 'tec_settings_tab_licenses', $licenses_tab );
