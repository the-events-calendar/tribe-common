<?php

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
			'tribe-form-content-start'           => array(
				'type' => 'html',
				'html' => '<div class="tribe-settings-form-wrap">',
			),
			'tribeEventsDateFormatSettingsTitle' => array(
				'type' => 'html',
				'html' => '<h3>' . esc_html__( 'Date Format Settings', 'tribe-common' ) . '</h3>',
			),
			'tribeEventsDateFormatExplanation'   => array(
				'type' => 'html',
				'html' => __( '<p>The following three fields accept the date format options available to the php date() function. <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Learn how to make your own date format here</a>.</p>', 'tribe-common' ),
			),
			'dateWithYearFormat'                 => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Date with year', 'tribe-common' ),
				'tooltip'         => esc_html__( 'Enter the format to use for displaying dates with the year. Used when showing an event from a past or future year, also used for dates in view headers.', 'tribe-common' ),
				'default'         => get_option( 'date_format' ),
				'size'            => 'medium',
				'validation_type' => 'html',
			),
			'dateWithoutYearFormat'              => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Date without year', 'tribe-common' ),
				'tooltip'         => esc_html__( 'Enter the format to use for displaying dates without a year. Used when showing an event from the current year.', 'tribe-common' ),
				'default'         => 'F j',
				'size'            => 'medium',
				'validation_type' => 'html',
			),
			'monthAndYearFormat'                 => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Month and year format', 'tribe-common' ),
				'tooltip'         => esc_html__( 'Enter the format to use for dates that show a month and year only. Used on month view.', 'tribe-common' ),
				'default'         => 'F Y',
				'size'            => 'medium',
				'validation_type' => 'html',
			),
			'dateTimeSeparator'                  => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Date time separator', 'tribe-common' ),
				'tooltip'         => esc_html__( 'Enter the separator that will be placed between the date and time, when both are shown.', 'tribe-common' ),
				'default'         => ' @ ',
				'size'            => 'small',
				'validation_type' => 'html',
			),
			'timeRangeSeparator'                 => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Time range separator', 'tribe-common' ),
				'tooltip'         => esc_html__( 'Enter the separator that will be used between the start and end time of an event.', 'tribe-common' ),
				'default'         => ' - ',
				'size'            => 'small',
				'validation_type' => 'html',
			),
			'datepickerFormat'                   => array(
				'type'            => 'dropdown_select2',
				'label'           => esc_html__( 'Datepicker Date Format', 'tribe-common' ),
				'tooltip'         => esc_html__( 'Select the date format to use in datepickers', 'tribe-common' ),
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
			'tribe-form-content-end' => array(
				'type' => 'html',
				'html' => '</div>',
			),
		)
	),
);
