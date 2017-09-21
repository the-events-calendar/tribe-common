<?php


class Tribe__Events__Aggregator_Mocker__Service_Options implements Tribe__Events__Aggregator_Mocker__Option_Provider_Interface {

	/**
	 * @var string The string itself is of no particular relevance if not as a placeholder.
	 */
	protected $import_id = '7cb60ba64ad6f3f807e90b561d62de02b6e2306525472c0e8ba6867ed4b6d38e';

	protected $examples = array(
		'all_active'   => array(
			'origin' => array(
				0 => array(
					'id'       => 'csv',
					'name'     => 'csv file',
					'disabled' => false,
				),
				1 => array(
					'id'       => 'facebook',
					'name'     => 'facebook',
					'disabled' => false,
				),
				2 => array(
					'id'       => 'gcal',
					'name'     => 'google calendar',
					'disabled' => false,
				),
				3 => array(
					'id'       => 'ical',
					'name'     => 'icalendar',
					'disabled' => false,
				),
				4 => array(
					'id'       => 'ics',
					'name'     => 'ics file',
					'disabled' => false,
				),
				5 => array(
					'id'       => 'meetup',
					'name'     => 'meetup',
					'disabled' => false,
				),
				6 => array(
					'id'       => 'url',
					'name'     => 'other url',
					'disabled' => false,
				),
			),
		),
		'all_inactive' => array(
			'origin' => array(
				0 => array(
					'id'       => 'csv',
					'name'     => 'csv file',
					'disabled' => true,
				),
				1 => array(
					'id'       => 'facebook',
					'name'     => 'facebook',
					'disabled' => true,
				),
				2 => array(
					'id'       => 'gcal',
					'name'     => 'google calendar',
					'disabled' => true,
				),
				3 => array(
					'id'       => 'ical',
					'name'     => 'icalendar',
					'disabled' => true,
				),
				4 => array(
					'id'       => 'ics',
					'name'     => 'ics file',
					'disabled' => true,
				),
				5 => array(
					'id'       => 'meetup',
					'name'     => 'meetup',
					'disabled' => true,
				),
				6 => array(
					'id'       => 'url',
					'name'     => 'other url',
					'disabled' => true,
				),
			),
		),
		'one_active'   => array(
			'origin' => array(
				0 => array(
					'id'       => 'some-source',
					'name'     => 'Some Source',
					'disabled' => false,
				),
			),
		),
	);

	public function __construct() {
		$this->examples['fetching'] = set_object_state( array(
			'status'       => 'fetching',
			'message_code' => 'fetching',
			'message'      => 'The import is in progress.',
			'data'         => set_object_state( array(
				'import_id' => $this->import_id,
			) ),
		) );

		$this->examples['no_events'] = set_object_state( array(
			'status'       => 'success',
			'message_code' => 'success:import-complete',
			'message'      => 'Import is complete',
			'data'         => set_object_state( array(
				'import_id'   => $this->import_id,
				'source_name' => 'Test calendar',
				'events'      => array(),
				'origin'      => '{{origin}}',
			) ),
		) );

		$next_month                             = date( 'Y-m', strtotime( '+1 month' ) );
		$this->examples['ical']['three_events'] = ea_mocker_template( set_object_state( array(
			'status'       => 'success',
			'message_code' => 'success:import-complete',
			'message'      => 'Import is complete',
			'data'         => set_object_state( array(
				'import_id'   => $this->import_id,
				'source_name' => 'Test calendar',
				'origin'      => 'ical',
				'events'      => array(
					0 => set_object_state( array(
						'title'          => 'Event 001',
						'description'    => '',
						'start_date'     => '{{nextMonth}}-12',
						'end_date'       => '{{nextMonth}}-12',
						'start_hour'     => '09',
						'end_hour'       => '12',
						'start_minute'   => '00',
						'end_minute'     => '00',
						'timezone'       => 'Europe/Rome',
						'url'            => '',
						'venue'          => set_object_state( array(
							'venue' => '',
						) ),
						'uid'            => 'qepjt972ptir73oc5d7oi6drgg@google.com0',
						'start_date_utc' => '{{nextMonth}}-12 08:00:00',
						'end_date_utc'   => '{{nextMonth}}-12 11:00:00',
					) ),
					1 => set_object_state( array(
						'title'          => 'Event 002',
						'description'    => '',
						'start_date'     => '{{nextMonth}}-13',
						'end_date'       => '{{nextMonth}}-13',
						'start_hour'     => '09',
						'end_hour'       => '14',
						'start_minute'   => '00',
						'end_minute'     => '30',
						'timezone'       => 'Europe/Rome',
						'url'            => '',
						'venue'          => set_object_state( array(
							'venue' => '',
						) ),
						'uid'            => 'ujhsklpa0mo32q1421n5lh707s@google.com0',
						'start_date_utc' => '{{nextMonth}}-13 08:00:00',
						'end_date_utc'   => '{{nextMonth}}-13 13:30:00',
					) ),
					2 => set_object_state( array(
						'title'          => 'Event 003',
						'description'    => '',
						'start_date'     => '{{nextMonth}}-14',
						'end_date'       => '{{nextMonth}}-14',
						'start_hour'     => '09',
						'end_hour'       => '16',
						'start_minute'   => '00',
						'end_minute'     => '00',
						'timezone'       => 'Europe/Rome',
						'url'            => '',
						'venue'          => set_object_state( array(
							'venue' => '',
						) ),
						'uid'            => 'gbj09k3es51a50u59jidccsag8@google.com0',
						'start_date_utc' => '{{nextMonth}}-14 08:00:00',
						'end_date_utc'   => '{{nextMonth}}-14 15:00:00',
					) ),
				),
			) ),
		) ), array( 'nextMonth' => $next_month ) );

		$this->examples['url']['three_events'] = ea_mocker_template( set_object_state( array(
			'status'       => 'success',
			'message_code' => 'success:import-complete',
			'message'      => 'Import is complete',
			'data'         => set_object_state( array(
				'import_id'   => $this->import_id,
				'source_name' => 'http://example.com',
				'origin'      => 'url',
				'events'      => array(
					0 => set_object_state( array(
						'id'             => '23',
						'title'          => 'Event 001',
						'description'    => '',
						'start_date'     => '{{nextMonth}}-12',
						'end_date'       => '{{nextMonth}}-12',
						'start_hour'     => '09',
						'end_hour'       => '12',
						'start_minute'   => '00',
						'end_minute'     => '00',
						'timezone'       => 'Europe/Rome',
						'url'            => '',
						'venue'          => set_object_state( array(
							'venue' => '',
						) ),
						'start_date_utc' => '{{nextMonth}}-12 08:00:00',
						'end_date_utc'   => '{{nextMonth}}-12 11:00:00',
					) ),
					1 => set_object_state( array(
						'id'             => '2389',
						'title'          => 'Event 002',
						'description'    => '',
						'start_date'     => '{{nextMonth}}-13',
						'end_date'       => '{{nextMonth}}-13',
						'start_hour'     => '09',
						'end_hour'       => '14',
						'start_minute'   => '00',
						'end_minute'     => '30',
						'timezone'       => 'Europe/Rome',
						'url'            => '',
						'venue'          => set_object_state( array(
							'venue' => '',
						) ),
						'start_date_utc' => '{{nextMonth}}-13 08:00:00',
						'end_date_utc'   => '{{nextMonth}}-13 13:30:00',
					) ),
					2 => set_object_state( array(
						'id'             => '89',
						'title'          => 'Event 003',
						'description'    => '',
						'start_date'     => '{{nextMonth}}-14',
						'end_date'       => '{{nextMonth}}-14',
						'start_hour'     => '09',
						'end_hour'       => '16',
						'start_minute'   => '00',
						'end_minute'     => '00',
						'timezone'       => 'Europe/Rome',
						'url'            => '',
						'venue'          => set_object_state( array(
							'venue' => '',
						) ),
						'uid'            => 'gbj09k3es51a50u59jidccsag8@google.com0',
						'start_date_utc' => '{{nextMonth}}-14 08:00:00',
						'end_date_utc'   => '{{nextMonth}}-14 15:00:00',
					) ),
				),
			) ),
		) ), array( 'nextMonth' => $next_month ) );

		$this->examples['post_import'] = array();

		$this->examples['post_import']['queued'] = set_object_state( array(
			'status'       => 'queued',
			'message_code' => 'queued',
			'message'      => 'The import will be starting soon.',
			'data'         => set_object_state( array(
				'import_id' => $this->import_id,
				'position'  => 1,
			) ),
		) );

		$this->examples['errors']['ical-invalid-url'] = set_object_state( array(
			'status'       => 'error',
			'message_code' => 'error:invalid-ical-url',
			'message'      => 'The URL provided did not have events in the proper format.',
			'data'         =>
				set_object_state( array(
					'import_id'        => $this->import_id,
					'iCalParsingError' => 10,
					'iCalContents'     => 'Not what we expected',
					'origin'           => 'ical',
				) ),
		) );

		$this->examples['errors']['rest-error'] = set_object_state( array(
			'status'       => 'error',
			'message_code' => 'error:some-rest-error',
			'message'      => 'A REST error happened',
			'data'         =>
				set_object_state( array(
					'import_id' => $this->import_id,
					'origin'    => 'url',
				) ),
		) );
	}

	/**
	 * Returns an array of options the class uses.
	 */
	public static function provides_options() {
		return array(
			'ea_mocker-origins-mock_response',
			'ea_mocker-import-mock_response',
			'ea_mocker-import-post_import_mock_response',
			'ea_mocker-service_domain',
		);
	}

	public function hook() {
		add_filter( 'ea_mocker-settings', array( $this, 'settings' ) );
		add_action( 'ea_mocker-options_form', array( $this, 'fields' ) );
	}

	public function settings( array $settings = array() ) {
		return array_merge( $settings, array(
			'ea_mocker-origins-mock_response',
			'ea_mocker-import-mock_response',
			'ea_mocker-import-post_import_mock_response',
			'ea_mocker-service_domain',
		) );
	}

	public function fields() {
		$recorder_enabled = get_option( 'ea_mocker-recorder-enabled' );
		?>
		<?php if ( ! $recorder_enabled ) : ?>
			<tr valign="top">
				<th scope="row">Import ID generator</th>
				<td>
					<label for="ea_mocker-import_id">
						Enter or modify an import ID to replace any occurrence of import ids in the current mock values.
						<div class="inline">
							<input type="text" value="<?php echo $this->import_id ?>" name="ea_mocker-import_id"
							       id="ea_mocker-import_id">
							<input type="button"
							       class="button-secondary"
							       id="ea_mocker-replace_import_id"
							       value="Replace import id"
							       data-placeholder="<?php echo $this->import_id ?>">
						</div>
					</label>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Origins Mock response</th>
				<td>
					<label for="ea_mocker-origins-mock_response">
						Paste a JSON representation of the origins response array here to mock it; if blank the origins
						response will not be mocked.
					</label>
					<textarea name="ea_mocker-origins-mock_response"
					          id="ea_mocker-origins-mock_response"
					          class="json"
					          cols="30"
					          rows="10"
					><?php echo get_option( 'ea_mocker-origins-mock_response' ); ?></textarea>
					<button class="button-secondary insert-default" data-slug="all_active">All active</button>
					<div class="default" data-slug="all_active"><?php echo json_encode( $this->examples['all_active'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="all_inactive">All inactive</button>
					<div class="default" data-slug="all_inactive"><?php echo json_encode( $this->examples['all_inactive'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="one_active">One active</button>
					<div class="default" data-slug="one_active"><?php echo json_encode( $this->examples['one_active'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="throttled">Throttled</button>
					<div class="default" data-slug="throttled">throttled</div>

					<button class="button-secondary insert-default" data-slug="not_found">Not found</button>
					<div class="default" data-slug="not_found">not-found</div>

					<button class="button-secondary insert-default" data-slug="error">Error</button>
					<div class="default" data-slug="error">error</div>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Post Import Mock response</th>
				<td>
					<label for="ea_mocker-import-post_import_mock_response">
						Paste a JSON representation of the post import request response array here to mock it; if blank the
						import response will not be mocked. </label>
					<textarea name="ea_mocker-import-post_import_mock_response"
					          id="ea_mocker-import-post_import_mock_response"
					          class="json"
					          cols="30"
					          rows="20"
					><?php echo get_option( 'ea_mocker-import-post_import_mock_response' ); ?></textarea>
					<button class="button-secondary insert-default" data-slug="queued">Queued (success)</button>
					<div class="default"
					     data-slug="queued"><?php echo json_encode( $this->examples['post_import']['queued'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="throttled">Throttled</button>
					<div class="default" data-slug="throttled">throttled</div>

					<button class="button-secondary insert-default" data-slug="not_found">Not found</button>
					<div class="default" data-slug="not_found">not-found</div>

					<button class="button-secondary insert-default" data-slug="error">Error</button>
					<div class="default" data-slug="error">error</div>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Import Mock response</th>
				<td>
					<label for="ea_mocker-import-mock_response">
						Paste a JSON representation of the import request response array here to mock it; if blank the
						import response will not be mocked. </label>
					<textarea name="ea_mocker-import-mock_response"
					          id="ea_mocker-import-mock_response"
					          class="json"
					          cols="30"
					          rows="20"
					><?php echo get_option( 'ea_mocker-import-mock_response' ); ?></textarea>
					<button class="button-secondary insert-default" data-slug="fetching">Fetching</button>
					<div class="default"
					     data-slug="fetching"><?php echo json_encode( $this->examples['fetching'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="no_events">No Events</button>
					<div class="default"
					     data-slug="no_events"><?php echo json_encode( ea_mocker_template( $this->examples['no_events'],
							array( 'origin' => 'ical' ) ) ); ?></div>

					<button class="button-secondary insert-default" data-slug="three_ical_events">Three iCal-like Events
					</button>
					<div class="default"
					     data-slug="three_ical_events"><?php echo json_encode( $this->examples['ical']['three_events'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="three_url_events">Three URL (REST) Events
					</button>
					<div class="default"
					     data-slug="three_url_events"><?php echo json_encode( $this->examples['url']['three_events'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="rest_no_events">No URL (REST) Events</button>
					<div class="default"
					     data-slug="rest_no_events"><?php echo json_encode( ea_mocker_template( $this->examples['no_events'],
							array( 'origin' => 'url' ) ) ); ?></div>

					<button class="button-secondary insert-default" data-slug="ical_error_invalid_url">Invalid iCal URL
						error
					</button>
					<div class="default"
					     data-slug="ical_error_invalid_url"><?php echo json_encode( $this->examples['errors']['ical-invalid-url'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="rest_error">URL (REST) error</button>
					<div class="default"
					     data-slug="rest_error"><?php echo json_encode( $this->examples['errors']['rest-error'] ); ?></div>

					<button class="button-secondary insert-default" data-slug="throttled">Throttled</button>
					<div class="default" data-slug="throttled">throttled</div>

					<button class="button-secondary insert-default" data-slug="not_found">Not found</button>
					<div class="default" data-slug="not_found">not-found</div>

					<button class="button-secondary insert-default" data-slug="error">Error</button>
					<div class="default" data-slug="error">error</div>
				</td>
			</tr>
		<?php else: ?>
			<tr valign="top">
				<th scope="row">Service mocking disabled</th>
				<td>
					<p>Event Aggregator service mocking is disabled while recording responses.</p>
				</td>
			</tr>
		<?php endif; ?>

		<?php
	}
}