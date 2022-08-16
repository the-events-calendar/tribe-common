<?php
/**
 * Class to manage Zapier actions.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

/**
 * Class Actions
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */
class Actions {

	/**
	 * The name of the action used to add a Zapier API Key.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $add_aki_key_action = 'tec-common-zapier-add-api-key';

	/**
	 * The name of the action used to generate a Zapier API Key.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $generate_action = 'tec-common-zapier-generate-api-key';

	/**
	 * The name of the action used to revoke a Zapier API Key.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $revoke_action = 'tec-common-zapier-revoke-api-key';
}
