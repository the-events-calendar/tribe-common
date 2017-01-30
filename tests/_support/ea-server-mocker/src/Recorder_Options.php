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
			'ea_mocker-recorder-recorded-calls',
		);
	}

	public function hook() {
		add_filter( 'ea_mocker-settings', array( $this, 'settings' ) );
		add_action( 'ea_mocker-options_form', array( $this, 'fields' ), 4 );
	}

	public function settings( array $settings ) {
		return array_merge( $settings, array(
			'ea_mocker-recorder-enabled',
		) );
	}

	public function fields() {
		$enabled = get_option( 'ea_mocker-recorder-enabled' );
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
							<?php
							checked( 'yes', $enabled ) ?>
                        >
                        Enable recording of all Event Aggregator requests and responses on the database; <code>ea_mocker-recorder-recorded-calls</code>
                        and <code>ea_mocker-recorder-recorded-responses</code> respectively.
                    </label>
					<?php if ( $enabled === 'yes' ) : ?>
                        <label for="ea_mocker-recorder-"><b>Recorded responses</b></label>
                        <textarea name="ea_mocker-recorder-recorded-responses"
                                  id="ea_mocker-recorder-recorded-responses"
                                  class="json"
                                  cols="30"
                                  rows="20"
                                  disabled
                        ><?php echo get_option( 'ea_mocker-import-mock_response' ); ?></textarea>
                        <button class="clean button-secondary" data-target="#ea_mocker-recorder-recorded-responses">Clear</button>
					<?php endif; ?>
                </fieldset>
            </td>
        </tr>
		<?php
	}
}