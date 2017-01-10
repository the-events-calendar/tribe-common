<?php


class Tribe__Events__Aggregator_Mocker__Service_Options implements Tribe__Events__Aggregator_Mocker__Option_Provider_Interface {

	protected $examples
		= array(
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
				array(
					'origin' => array(
						0 => array(
							'id'       => 'some-source',
							'name'     => 'Some Source',
							'disabled' => false,
						),
					),
				)
			)
		);

	/**
	 * Returns an array of options the class uses.
	 */
	public static function provides_options() {
		return array(
			'ea_mocker-origins-mock_response',
		);
	}

	public function hook() {
		add_filter( 'ea_mocker-settings', array( $this, 'settings' ) );
		add_action( 'ea_mocker-options_form', array( $this, 'fields' ) );
	}

	public function settings() {
		return array(
			'ea_mocker-origins-mock_response',
		);
	}

	public function fields() {
		?>
		<tr valign="top">
			<th scope="row">Origins Mock response</th>
			<td>
				<label for="ea_mocker-origins-mock_response">
					Paste a JSON representation of the origins response array here to mock it; if blank the origins response will not be mocked.
				</label>
				<textarea name="ea_mocker-origins-mock_response"
						  id="ea_mocker-origins-mock_response"
						  class="json"
						  cols="30"
						  rows="10"
				><?php echo get_option( 'ea_mocker-origins-mock_response' ); ?></textarea>
				<button class="button-secondary insert-default" data-slug="all_active">All active</button>
				<div class="default" data-slug="all_active"><?php echo json_encode( $this->examples['all_active'] ); ?></div>
				<button class="button-secondary insert-default" data-slug="all_inactive">One active</button>
				<div class="default" data-slug="all_inactive"><?php echo json_encode( $this->examples['all_inactive'] ); ?></div>
				<button class="button-secondary insert-default" data-slug="one_active">One active</button>
				<div class="default" data-slug="one_active"><?php echo json_encode( $this->examples['one_active'] ); ?></div>
			</td>
		</tr>
		<?php
	}
}