<?php
/**
 * Class to manage Zapier actions.
 *
 * @since 1.0.0
 *
 * @package TEC\Common\Event_Automator\Zapier
 */

namespace TEC\Common\Event_Automator\Zapier;

/**
 * Class Actions
 *
 * @since 1.0.0
 *
 * @package TEC\Common\Event_Automator\Zapier
 */
class Actions {

	/**
	 * The name of the action used to add a Zapier API Key.
	 * @deprecated 1.4.0 - Use $add_connection instead.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $add_aki_key_action = 'tec-automator-zapier-add-api-key';

	/**
	 * The name of the action used to add a Zapier Connection.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $add_connection = 'tec-automator-zapier-add-connection';

	/**
	 * The name of the action used to generate a Zapier API Key.
	 * @deprecated 1.4.0 - Use $create_access instead.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $generate_action = 'tec-automator-zapier-generate-api-key';

	/**
	 * The name of the action used to create access information for a Zapier.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $create_access = 'tec-automator-zapier-create-access';

	/**
	 * The name of the action used to revoke a Zapier API Key.
	 * @deprecated 1.4.0 - Use $delete_connection instead.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $revoke_action = 'tec-automator-zapier-revoke-api-key';

	/**
	 * The name of the action used to delete a Zapier API Key.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $delete_connection = 'tec-automator-zapier-delete-connection';

	/**
	 * The name of the action used to clear a Zapier endpoint queue.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $clear_action = 'tec-automator-zapier-clear-endpoint-queue';

	/**
	 * The name of the action used to disable a Zapier endpoint.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $disable_action = 'tec-automator-zapier-disable-endpoint';

	/**
	 * The name of the action used to enable a Zapier endpoint.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $enable_action = 'tec-automator-zapier-enable-endpoint';
}
