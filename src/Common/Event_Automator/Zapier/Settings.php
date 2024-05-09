<?php
/**
 * Class to manage Zapier settings.
 *
 * @since 1.0.0
 *
 * @package TEC\Common\Event_Automator\Zapier
 */

namespace TEC\Common\Event_Automator\Zapier;

use TEC\Common\Event_Automator\Integrations\Connections\Integration_Settings;

/**
 * Class Settings
 *
 * @since 1.0.0
 * @since 1.4.0 -  Utilize Integration_Settings to share coding among integrations.
 *
 * @package TEC\Common\Event_Automator\Zapier
 */
class Settings extends Integration_Settings {

	/**
	 * @inheritdoc
	 */
	public static string $option_prefix = 'tec_zapier_';

	/**
	 * @inheritdoc
	 */
	public static string $api_id = 'zapier';

	/**
	 * Settings constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Api                    $api                    An instance of the Zapier API handler.
	 * @param Template_Modifications $template_modifications An instance of the Template_Modifications handler.
	 * @param Url                    $url                    An instance of the URL handler.
	 */
	public function __construct( Api $api, Template_Modifications $template_modifications, Url $url ) {
		$this->api                    = $api;
		$this->template_modifications = $template_modifications;
		$this->url                    = $url;
	}

	/**
	 * Get the Zapier API authorization fields.
	 * @deprecated 1.4.0 - use get_all_connection_fields();
	 *
	 * @since 1.0.0
	 *
	 * @return string The HTML fields.
	 */
	protected function get_authorize_fields() {
		_deprecated_function( __METHOD__, '1.4.0', 'get_all_connection_fields');

		return $this->template_modifications->get_api_authorize_fields( $this->api, $this->url );
	}
}
