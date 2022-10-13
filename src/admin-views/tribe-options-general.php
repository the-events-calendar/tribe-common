<?php

$general_tab_fields = [
	'info-start'                    => [
		'type' => 'html',
		'html' => '<div id="modern-tribe-info">
					<img
						src="' . plugins_url( 'resources/images/logo/logoblock.svg', dirname( __FILE__ ) ) . '"
						alt="' . esc_attr( 'The Events Calendar brand logo', 'tribe-common' ) . '"
					/>',
	],
	'event-tickets-info' => [
		'type'        => 'html',
		'html'        => '<p>' . sprintf( esc_html__( 'Thank you for using Event Tickets! All of us at The Events Calendar sincerely appreciate your support and we\'re excited to see you using our plugins. Check out our handy %1$sNew User Primer%2$s to get started.', 'tribe-common' ), '<a target="_blank" rel="noopener noreferrer" href="http://evnt.is/18nd">', '</a>' ) . '</p>',
		'conditional' => ! class_exists( 'Tribe__Events__Main' ),
	],
	'event-tickets-upsell-info' => [
		'type'        => 'html',
		'html'        => '<p>' . sprintf( esc_html__( 'Optimize your site\'s event listings with %1$sThe Events Calendar%2$s, our free calendar plugin. Looking for additional functionality including recurring events, user-submission, advanced ticket sales and more? Check out our %3$spremium add-ons%4$s.', 'tribe-common' ), '<a target="_blank" rel="noopener noreferrer" href="http://evnt.is/18x6">', '</a>', '<a target="_blank" rel="noopener noreferrer" href="http://evnt.is/18x5">', '</a>' ) . '</p>',
		'conditional' => ! class_exists( 'Tribe__Events__Main' ),
	],
	'calendar-title' => [
		'type' => 'html',
		'html' => '<h3 class=".tribe-common-h2">The Events Calendar</h3>'
	],
	'calendar-description' => [
		'type' => 'html',
		'html' => '<p class="tribe-common-b2 tribe-common-b1--min-medium">A free and endlessly extensible calendaring, event, and ticketing suite from Modern Tribe.</p>'
	],
	'view-calendar-link'            => [
		'type' => 'html',
		'html' => '<a class="tribe-common-c-btn-border" href="' . esc_url( tribe( 'tec.main' )->getLink() ) . '">' . esc_html__( 'View My Calendar', 'the-events-calendar' ) . '</a>',
	],
	'upsell-info'                   => [
		'type'        => 'html',
		'html'        => '<a class="tribe-common-c-btn-border" target="_blank" rel="noopener noreferrer" href="' . Tribe__Main::$tec_url . 'products/?utm_source=generaltab&utm_medium=plugin-tec&utm_campaign=in-app">' . esc_html__( 'Explore Add-Ons', 'tribe-common' ) . '</a>',
		'conditional' => ( ! tec_should_hide_upsell() ) && class_exists( 'Tribe__Events__Main' ),
	],
	'info-end'                      => [
		'type' => 'html',
		'html' => '</div>',
	],
	'tribe-form-content-start'      => [
		'type' => 'html',
		'html' => '<div class="tribe-settings-form-wrap">',
	],
];

$fields = apply_filters( 'tribe_general_settings_tab_fields', $general_tab_fields );

// Closes form
$fields['tribe-form-content-end'] = [
	'type' => 'html',
	'html' => '</div>',
];


$generalTab = [
	'priority' => 10,
	'fields'   => $fields,
];
