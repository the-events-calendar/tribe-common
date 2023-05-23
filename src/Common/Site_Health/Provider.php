<?php

namespace TEC\Common\Site_Health;

use TEC\Common\Contracts\Service_Provider;

/**
 * Class Provider
 *
 * @since   TBD
 *
 * @package TEC\Common\Site_Health
 *
 */
class Provider extends Service_Provider {

	/**
	 * Register the functionality related to this module.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register() {
		$this->add_filters();
	}

	/**
	 * Include the filters related to this module.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function add_filters(): void {
		add_filter( 'debug_information', [ $this, 'filter_include_info_section' ] );
	}

	/**
	 * Includes the info sections controlled by Common.
	 *
	 * @since TBD
	 *
	 * @param array $info Current set of info sections.
	 *
	 * @return array
	 */
	public function filter_include_info_section( $info ): array {
		return $this->container->make( Factory::class )->filter_include_info_sections( (array) $info );
	}
}
