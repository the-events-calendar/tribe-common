<?php

class Tribe__Events__Aggregator_Mocker__Recorder_Options
	implements Tribe__Events__Aggregator_Mocker__Option_Provider_Interface {

	/**
	 * Returns an array of options the class uses.
	 */
	public static function provides_options() {
		return array(
			'ea_mocker-recorder-enabled',
			'ea_mocker-recorder-recorded-responses',
		);
	}

	public function hook() {
		add_filter( 'ea_mocker-settings', array( $this, 'settings' ) );
		add_action( 'ea_mocker-options_form', array( $this, 'fields' ), 4 );
	}

	public function settings( array $settings ) {
		return array_merge( $settings, array(
			'ea_mocker-recorder-enabled',
			'ea_mocker-recorder-recorded-responses',
		) );
	}

	public function fields() {
		$enabled  = get_option( 'ea_mocker-recorder-enabled' );
		$recorded = get_option( 'ea_mocker-recorder-recorded-responses' );
		$recorded = empty( $recorded ) ? '' : json_encode( unserialize( $recorded ) );
		?>
		<tr valign="top">
			<th scope="row">Record Event Aggregator API calls and responses</th>
			<td>
				<fieldset>
					<label>
						<input
								type="checkbox"
								name="ea_mocker-recorder-enabled"
								value="yes"
								<?php checked( 'yes', $enabled ) ?>
						>
						Enable recording of all Event Aggregator requests and responses on the database. <strong>Will disable
							service mocking!</strong>
					</label>
					<?php if ( $enabled === 'yes' ) : ?>
						<label for="ea_mocker-recorder-"><b>Recorded responses</b></label>
						<textarea name="ea_mocker-recorder-recorded-responses"
						          id="ea_mocker-recorder-recorded-responses"
						          class="json"
						          cols="30"
						          rows="20"
						          readonly="readonly"
						><?php echo $recorded; ?></textarea>
						<button class="clean button-secondary" data-target="#ea_mocker-recorder-recorded-responses">
							Clear
						</button>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<?php
	}
}