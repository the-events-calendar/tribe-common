<?php
/**
 * The REST API handler for the Onboarding Wizard.
 * Cleverly named...API.
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Admin\Onboarding
 */

namespace TEC\Common\Admin\Onboarding;

use WP_REST_Request;
use WP_REST_Server;
use WP_Error;
use WP_REST_Response;
use TEC\Common\Admin\Onboarding\Data;

/**
 * Class API
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Admin\Onboarding
 */
abstract class Abstract_API {

	/**
	 * The action for this nonce.
	 *
	 * @since 6.7.0
	 *
	 * @var string
	 */
	public const NONCE_ACTION = '_tec_wizard';

	/**
	 * Rest Endpoint namespace
	 *
	 * @since 6.7.0
	 *
	 * @var string
	 */
	protected const ROOT_NAMESPACE = 'tec/onboarding';

	/**
	 * The data object.
	 *
	 * @since 6.7.0
	 *
	 * @var Abstract_Data
	 */
	protected Abstract_Data $data;

	/**
	 * Register the endpoint.
	 *
	 * @since 6.7.0
	 *
	 * @return bool If we registered the endpoint.
	 */
	public function register(): bool {
		return register_rest_route(
			self::ROOT_NAMESPACE,
			'/wizard',
			[
				'methods'             => [ WP_REST_Server::CREATABLE ],
				'callback'            => [ $this, 'handle' ],
				'permission_callback' => [ $this, 'check_permissions' ],
				'args'                => [
					'action_nonce' => [
						'type'              => 'string',
						'description'       => __( 'The action nonce for the request.', 'tribe-common' ),
						'required'          => true,
						'validate_callback' => [ $this, 'check_nonce' ],
					],
				],
			]
		);
	}

	/**
	 * Set the data object.
	 *
	 * @since 6.7.0
	 *
	 * @param Abstract_Data $data The data object.
	 *
	 * @throws \InvalidArgumentException If the data is not an instance of Abstract_Data.
	 */
	public function set_data( Abstract_Data $data ): void {
		if ( ! $data instanceof Abstract_Data ) {
			throw new \InvalidArgumentException( 'Data must be an instance of Abstract_Data' );
		}

		$this->data = $data;
	}

	/**
	 * Check the nonce.
	 *
	 * @since 6.7.0
	 *
	 * @param string $nonce The nonce.
	 *
	 * @return bool|WP_Error True if the nonce is valid, WP_Error if not.
	 */
	public function check_nonce( $nonce ) {
		$verified = wp_verify_nonce( $nonce, self::NONCE_ACTION );

		if ( $verified ) {
			return true;
		}

		return new WP_Error(
			'tec_invalid_nonce',
			__( 'Invalid nonce.', 'tribe-common' ),
			[ 'status' => 403 ]
		);
	}

	/**
	 * Check the permissions.
	 *
	 * @since 6.7.0
	 *
	 * @return bool If the user has the correct permissions.
	 */
	public function check_permissions(): bool {
		$required_permission = 'manage_options';

		/**
		 * Filter the required permission for the onboarding wizard.
		 *
		 * @since 6.7.0
		 *
		 * @param string $required_permission The required permission.
		 * @param Abstract_API    $api The api object.
		 *
		 * @return string The required permission.
		 */
		$required_permission = (string) apply_filters( 'tec_onboarding_wizard_permissions', $required_permission, $this );

		return current_user_can( $required_permission );
	}

	/**
	 * Handle the request.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function handle( WP_REST_Request $request ): WP_REST_Response {
		/**
		 * Each step hooks in here and potentially modifies the response.
		 *
		 * @since 6.7.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WP_REST_Request  $request  The request object.
		 */
		return apply_filters( 'tec_onboarding_wizard_handle', $this->set_tab_records( $request ), $request );
	}

	/**
	 * Passes the request and data to the handler.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response.
	 */
	protected function set_tab_records( $request ): WP_REST_Response {
		$params   = $request->get_params();
		$begun    = $params['begun'] ?? false;
		$finished = $params['finished'] ?? false;
		$skipped  = $params['skippedTabs'] ?? [];
		$complete = $params['completedTabs'] ?? [];

		// Remove any elements in $completed from $skipped.
		$skipped = array_values( array_diff( $skipped, $complete ) );

		// If the wizard is finished, ensure we set the begun flag.
		if ( $finished ) {
			$begun = true;
		}

		// If the wizard has been started, ensure we add the first tab to the completed tabs.
		if ( $begun ) {
			$complete = array_push( $complete, 0 );
		}

		// Set up our data for a single save.
		$settings                   = $this->data->get_wizard_settings();
		$settings['begun']          = $begun;
		$settings['finished']       = $finished;
		$settings['current_tab']    = $params['currentTab'] ?? 0;
		$settings['completed_tabs'] = $this->normalize_tabs( $complete );
		$settings['skipped_tabs']   = $this->normalize_tabs( $skipped );

		// Stuff we don't want/need to store in the settings.
		$do_not_save = [
			'timezones',
			'countries',
			'currencies',
			'action_nonce',
			'_wpnonce',
		];

		/**
		 * Allows filtering of the keys that should not be saved.
		 *
		 * @since 6.7.0
		 *
		 * @param array<string> $do_not_save The keys that should not be saved.
		 *
		 * @return array<string> The keys that should not be saved.
		 */
		$do_not_save = apply_filters( 'tec_onboarding_wizard_do_not_save', $do_not_save );

		foreach ( $do_not_save as $key ) {
			unset( $params[ $key ] );
		}


		// Add a snapshot of the data from the last request.
		$settings['last_send'] = $params;

		// Update the option.
		$updated = $this->data->update_wizard_settings( $settings );

		// We want to record the issue but we *don't* want to send back a failure since this part is not required for the user.
		return new WP_REST_Response(
			[
				'success' => true,
				'message' => $updated ? [ __( 'Onboarding wizard step completed successfully.', 'tribe-common' ) ] : [ __( 'Failed to update wizard settings.', 'tribe-common' ) ],
			],
			200
		);
	}

	/**
	 * Normalize the tabs. Remove duplicates
	 *
	 * @since 6.7.0
	 *
	 * @param array<int> $tabs An array of tab indexes (int).
	 *
	 * @return array
	 */
	protected function normalize_tabs( $tabs ): array {
		// Filter out duplicates.
		$tabs = array_unique( (array) $tabs, SORT_NUMERIC );

		// Reindex the array.
		return array_values( $tabs );
	}
}
