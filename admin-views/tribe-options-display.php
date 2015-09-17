<?php

$template_options = array(
	''        => __( 'Default Events Template', 'tribe-common' ),
	'default' => __( 'Default Page Template', 'tribe-common' ),
);
$templates        = get_page_templates();
ksort( $templates );
foreach ( array_keys( $templates ) as $template ) {
	$template_options[ $templates[ $template ] ] = $template;
}

/**
 * Filter the array of views that are registered for the tribe bar
 * @param array array() {
 *     Array of views, where each view is itself represented by an associative array consisting of these keys:
 *
 *     @type string $displaying         slug for the view
 *     @type string $anchor             display text (i.e. "List" or "Month")
 *     @type string $event_bar_hook     not used
 *     @type string $url                url to the view
 * }
 * @param boolean
 */
$views = apply_filters( 'tribe-events-bar-views', array(), false );

$views_options = array();
foreach ( $views as $view ) {
	$views_options[ $view['displaying'] ] = $view['anchor'];
}

$sample_date = strtotime( 'January 15 ' . date( 'Y' ) );

$displayTab = array(
	'priority' => 20,
	'fields'   =>
	/**
	 * Filter the fields available on the display settings tab
	 *
	 * @param array $fields a nested associative array of fields & field info passed to Tribe__Field
	 * @see Tribe__Field
	 */
		apply_filters(
		'tribe_display_settings_tab_fields', array(
			'info-start'                         => array(
				'type' => 'html',
				'html' => '<div id="modern-tribe-info">',
			),
			'info-box-title'                     => array(
				'type' => 'html',
				'html' => '<h2>' . __( 'Display Settings', 'tribe-common' ) . '</h2>',
			),
			'info-box-description'               => array(
				'type' => 'html',
				'html' => '<p>' . sprintf(
					__( 'The settings below control the display of your calendar. If things don\'t look right, try switching between the three style sheet options or pick a page template from your theme.</p><p>There are going to be situations where no out-of-the-box template is 100&#37; perfect. Check out our <a href="%s">our themer\'s guide</a> for instructions on custom modifications.', 'tribe-common' ),
					Tribe__Main::$tec_url . 'knowledgebase/themers-guide/?utm_medium=plugin-tec&utm_source=generaltab&utm_campaign=in-app'
				) . '</p>',
			),
			'info-end'                           => array(
				'type' => 'html',
				'html' => '</div>',
			),
			'tribe-form-content-start'           => array(
				'type' => 'html',
				'html' => '<div class="tribe-settings-form-wrap">',
			),
			'tribeEventsBasicSettingsTitle'      => array(
				'type' => 'html',
				'html' => '<h3>' . __( 'Basic Template Settings', 'tribe-common' ) . '</h3>',
			),
			'stylesheetOption'                   => array(
				'type'            => 'radio',
				'label'           => __( 'Default stylesheet used for events templates', 'tribe-common' ),
				'default'         => 'tribe',
				'options'         => array(
					'skeleton' => __( 'Skeleton Styles', 'tribe-common' ) .
								  '<p class=\'description tribe-style-selection\'>' .
								  __( 'Only includes enough css to achieve complex layouts like calendar and week view.', 'tribe-common' ) .
								  '</p>',
					'full'     => __( 'Full Styles', 'tribe-common' ) .
								  '<p class=\'description tribe-style-selection\'>' .
								  __( 'More detailed styling, tries to grab styles from your theme.', 'tribe-common' ) .
								  '</p>',
					'tribe'    => __( 'Tribe Events Styles', 'tribe-common' ) .
								  '<p class=\'description tribe-style-selection\'>' .
								  __( 'A fully designed and styled theme for your events pages.', 'tribe-common' ) .
								  '</p>',
				),
				'validation_type' => 'options',
			),
			'tribeEventsTemplate'                => array(
				'type'            => 'dropdown_select2',
				'label'           => __( 'Events template', 'tribe-common' ),
				'tooltip'         => __( 'Choose a page template to control the appearance of your calendar and event content.', 'tribe-common' ),
				'validation_type' => 'options',
				'size'            => 'large',
				'default'         => 'default',
				'options'         => $template_options,
			),
			'tribeEnableViews'                   => array(
				'type'            => 'checkbox_list',
				'label'           => __( 'Enable event views', 'tribe-common' ),
				'tooltip'         => __( 'You must select at least one view.', 'tribe-common' ),
				'default'         => array_keys( $views_options ),
				'options'         => $views_options,
				'validation_type' => 'options_multi',
			),
			'viewOption'                         => array(
				'type'            => 'dropdown_select2',
				'label'           => __( 'Default view', 'tribe-common' ),
				'validation_type' => 'options',
				'size'            => 'large',
				'default'         => 'month',
				'options'         => $views_options,
			),
			'tribeDisableTribeBar'               => array(
				'type'            => 'checkbox_bool',
				'label'           => __( 'Disable the Event Search Bar', 'tribe-common' ),
				'tooltip'         => __( 'Check this to use the classic header.', 'tribe-common' ),
				'default'         => false,
				'validation_type' => 'boolean',
			),
			'monthEventAmount'                   => array(
				'type'            => 'text',
				'label'           => __( 'Month view events per day', 'tribe-common' ),
				'tooltip'         => sprintf( __( 'Change the default 3 events per day in month view. Please note there may be performance issues if you set this too high. <a href="%s">Read more</a>.', 'tribe-common' ), 'http://m.tri.be/rh' ),
				'validation_type' => 'positive_int',
				'size'            => 'small',
				'default'         => '3',
			),
			'enable_month_view_cache' => array(
				'type'            => 'checkbox_bool',
				'label'           => __( 'Enable the Month View Cache', 'tribe-common' ),
				'tooltip'         => sprintf( __( 'Check this to cache your month view HTML in transients, which can help improve calendar speed on sites with many events. <a href="%s">Read more</a>.', 'tribe-common' ), 'http://m.tri.be/18di' ),
				'default'         => false,
				'validation_type' => 'boolean',
			),
			'tribeEventsDateFormatSettingsTitle' => array(
				'type' => 'html',
				'html' => '<h3>' . __( 'Date Format Settings', 'tribe-common' ) . '</h3>',
			),
			'tribeEventsDateFormatExplanation'   => array(
				'type' => 'html',
				'html' => __( '<p>The following three fields accept the date format options available to the php date() function. <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Learn how to make your own date format here</a>.</p>', 'tribe-common' ),
			),
			'dateWithYearFormat'                 => array(
				'type'            => 'text',
				'label'           => __( 'Date with year', 'tribe-common' ),
				'tooltip'         => __( 'Enter the format to use for displaying dates with the year. Used when showing an event from a past or future year, also used for dates in view headers.', 'tribe-common' ),
				'default'         => get_option( 'date_format' ),
				'size'            => 'medium',
				'validation_type' => 'html',
			),
			'dateWithoutYearFormat'              => array(
				'type'            => 'text',
				'label'           => __( 'Date without year', 'tribe-common' ),
				'tooltip'         => __( 'Enter the format to use for displaying dates without a year. Used when showing an event from the current year.', 'tribe-common' ),
				'default'         => 'F j',
				'size'            => 'medium',
				'validation_type' => 'html',
			),
			'monthAndYearFormat'                 => array(
				'type'            => 'text',
				'label'           => __( 'Month and year format', 'tribe-common' ),
				'tooltip'         => __( 'Enter the format to use for dates that show a month and year only. Used on month view.', 'tribe-common' ),
				'default'         => 'F Y',
				'size'            => 'medium',
				'validation_type' => 'html',
			),
			'dateTimeSeparator'                  => array(
				'type'            => 'text',
				'label'           => __( 'Date time separator', 'tribe-common' ),
				'tooltip'         => __( 'Enter the separator that will be placed between the date and time, when both are shown.', 'tribe-common' ),
				'default'         => ' @ ',
				'size'            => 'small',
				'validation_type' => 'html',
			),
			'timeRangeSeparator'                 => array(
				'type'            => 'text',
				'label'           => __( 'Time range separator', 'tribe-common' ),
				'tooltip'         => __( 'Enter the separator that will be used between the start and end time of an event.', 'tribe-common' ),
				'default'         => ' - ',
				'size'            => 'small',
				'validation_type' => 'html',
			),
			'datepickerFormat'                   => array(
				'type'            => 'dropdown_select2',
				'label'           => __( 'Datepicker Date Format', 'tribe-common' ),
				'tooltip'         => __( 'Select the date format to use in datepickers', 'tribe-common' ),
				'default'         => 'Y-m-d',
				'options'         => array(
					'0' => date( 'Y-m-d', $sample_date ),
					'1' => date( 'n/j/Y', $sample_date ),
					'2' => date( 'm/d/Y', $sample_date ),
					'3' => date( 'j/n/Y', $sample_date ),
					'4' => date( 'd/m/Y', $sample_date ),
					'5' => date( 'n-j-Y', $sample_date ),
					'6' => date( 'm-d-Y', $sample_date ),
					'7' => date( 'j-n-Y', $sample_date ),
					'8' => date( 'd-m-Y', $sample_date ),
				),
				'validation_type' => 'options',
			),
			'tribeEventsAdvancedSettingsTitle'   => array(
				'type' => 'html',
				'html' => '<h3>' . __( 'Advanced Template Settings', 'tribe-common' ) . '</h3>',
			),
			'tribeEventsBeforeHTML'              => array(
				'type'            => 'wysiwyg',
				'label'           => __( 'Add HTML before event content', 'tribe-common' ),
				'tooltip'         => __( 'If you are familiar with HTML, you can add additional code before the event template. Some themes may require this to help with styling or layout.', 'tribe-common' ),
				'validation_type' => 'html',
			),
			'tribeEventsAfterHTML'               => array(
				'type'            => 'wysiwyg',
				'label'           => __( 'Add HTML after event content', 'tribe-common' ),
				'tooltip'         => __( 'If you are familiar with HTML, you can add additional code after the event template. Some themes may require this to help with styling or layout.', 'tribe-common' ),
				'validation_type' => 'html',
			),
			'tribe-form-content-end'             => array(
				'type' => 'html',
				'html' => '</div>',
			),
		)
	),
);
