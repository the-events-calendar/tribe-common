<?php

class Tribe__Events__Aggregator_Mocker__License_Options
	implements Tribe__Events__Aggregator_Mocker__Option_Provider_Interface {

	/**
	 * Returns an array of options the class uses.
	 */
	public static function provides_options() {
		return array(
			'ea_mocker-license-mock_enabled',
		);
	}

	public function hook() {
		add_filter( 'ea_mocker-settings', array( $this, 'settings' ) );
		add_action( 'ea_mocker-options_form', array( $this, 'fields' ), 4 );
	}

	public function settings( array $settings ) {
		return array_merge( $settings, array(
			'ea_mocker-license-mock_enabled',
		) );
	}

	public function fields() {
		?>
		<tr valign="top">
			<th scope="row">Mock license key</th>
			<td>
				<label>
					<input
							type="checkbox"
							name="ea_mocker-license-mock_enabled"
							value="yes"
						<?php checked( 'yes', get_option( 'ea_mocker-license-mock_enabled' ) ) ?>
					>
					Will mock the license key and short-circuit the check.
				</label>
			</td>
		</tr>
		<?php
	}
}