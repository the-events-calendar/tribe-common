<?php


class Tribe__Events__Aggregator_Mocker__Options_Page {

	public function hook() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	public function register_menu() {
		add_menu_page(
				'Event Aggregator Server Mocker',
				'EA Mocker',
				'administrator',
				'ea-mocker',
				array( $this, 'render' )
		);
	}

	public function register_settings() {
		/**
		 * Filter this to add settings.
		 *
		 * @param array $settings
		 */
		$settings = apply_filters( 'ea_mocker-settings', array() );

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $setting ) {
				register_setting( 'ea_mocker', $setting );
			}
		}

		register_setting( 'ea_mocker', 'ea_mocker-enable' );
	}

	public function render() {
		?>
		<div class="wrap">
			<h1>Event Aggregator Server Mocker Settings</h1>

			<p>This tool <strong>will not</strong> delete existing transients and options: that's on purpose to allow you to set up complex fixtures.</p>
			<p>If you do not know what you are doing (exactly) but want to experiment: if things go downhill deactivate and re-activate the plugin to clear all its data.</p>
			<p>Just in case: deactivating the plugin <strong>will wipe all its data.</strong></p>
			<p>Want to make this even more awesome? Learn to use <a href="http://www.openvim.com/" target="_blank">vim</a> and then install <a href="http://appsweets.net/wasavi/" target="_blank">wasavi</a>.</p>

			<form method="post" action="options.php" id="ea-mocker">
				<?php settings_fields( 'ea_mocker' ); ?>
				<?php do_settings_sections( 'ea_mocker' ); ?>
				<table class="form-table">

					<tr valign="top">
						<th scope="row">Enable mocking</th>
						<td>
							<label>
								<input type="checkbox" value="yes" name="ea_mocker-enable" <?php checked( 'yes',
									get_option( 'ea_mocker-enable' ) ); ?>>
								Enable server mocking
							</label>
						</td>
					</tr>

					<?php
					/**
					 * Use this action to print your settings.
					 */
					do_action( 'ea_mocker-options_form' );
					?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php }
}
