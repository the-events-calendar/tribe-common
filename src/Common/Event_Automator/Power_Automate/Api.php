<?php
/**
 * Class to manage Power Automate Connections.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */

namespace TEC\Event_Automator\Power_Automate;

use TEC\Common\Firebase\JWT\JWT;
use TEC\Event_Automator\Integrations\Connections\Integration_AJAX;

/**
 * Class Api
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */
class Api extends Integration_AJAX {

	/**
	 * @inheritdoc
	 */
	public static $api_name = 'Power Automate';

	/**
	 * @inheritdoc
	 */
	public static $api_id = 'power-automate';

	/**
	 * @inerhitDoc
	 */
	protected $all_api_keys_key = 'tec_power_automate_connections';

	/**
	 * @inheritDoc
	 */
	protected $single_api_key_prefix = 'tec_power_automate_connection_';

	/**
	 * @inheritDoc
	 */
	protected static $api_secret_key = 'tec_automator_power_automate_secret_key';

	/**
	 * API constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Actions                $actions An instance of the Actions name handler.
	 * @param Template_Modifications $actions An instance of the Template_Modifications.
	 */
	public function __construct( Actions $actions, Template_Modifications $template_modifications ) {
		$this->actions                = $actions;
		$this->template_modifications = $template_modifications;

		// Setup API secret key for integration.
		$this->setup_api_secret();
	}

	/**
	 * @inheritDoc
	 */
	public function save_connection( $consumer_id, $name, $user_id, $permissions ) {
		$message = _x(
			'Power Automate Connection saved.',
			'Power Automate Connection save message.',
			'tribe-common'
		);
		$this->template_modifications->print_settings_message_template( $message );

		$consumer_secret = $this->get_random_hash( 'ck_' );
		$connection_data = [
			'consumer_id'     => $consumer_id,
			'consumer_secret' => $consumer_secret,
			'has_pair'        => true,
			'name'            => esc_html( stripslashes( $name ) ),
			'permissions'     => $permissions,
			'last_access'     => '-',
			'user_id'         => $user_id,
		];

		$this->set_api_key_by_id( $connection_data );

		$connection_data['access_token'] = $this->create_access_token( $consumer_id, $consumer_secret, $name );

		// Add empty fields template
		$this->template_modifications->get_connection_fields(
			$this,
			$consumer_id,
			$connection_data,
			[],
			'saved'
		);

		return;
	}

	/**
	 * Create the access token.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The consumer id.
	 * @param string $consumer_secret        The consumer secret.
	 * @param string $name        The name of the connection.
	 *
	 * @return string
	 */
	public function create_access_token( $consumer_id, $consumer_secret, $name ) {
		$issuedAt = time();
		$access_token    = [
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $issuedAt,
			'data' => [
				'consumer_id'     => $consumer_id,
				'consumer_secret' => $consumer_secret,
				'app_name'        => esc_html( stripslashes( $name ) ),
			],
		];

		return JWT::encode( $access_token, $this->get_api_secret(), 'HS256' );
	}

	/**
	 * @inheritDoc
	 */
	public static function get_confirmation_to_delete_connection() {
		return sprintf(
			_x(
				'Are you sure you want to delete this Power Automate connection? This operation cannot be undone. Existing Power Automate connections using this connection will no longer work.',
				'The message to display to confirm a user would like to delete a Power Automate connection.',
				'tribe-common',
				'tribe-common'
			),
		);
	}
}
