<?php


class Tribe__Events__Aggregator_Mocker__Service_Options
	implements Tribe__Events__Aggregator_Mocker__Option_Provider_Interface {

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
			</td>
		</tr>
		<?php
	}
}