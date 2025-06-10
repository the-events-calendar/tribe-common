<?php
/**
 * Handlese the `options/country` endpoint of the Classy REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\Classy\REST\Endpoints\Options;
 */

namespace TEC\Common\Classy\REST\Endpoints\Options;

use Tribe__Languages__Locations as Locations;
use WP_REST_Response;

/**
 * Class Country.
 *
 * @since TBD
 *
 * @package TEC\Common\Classy\REST\Endpoints\Options;
 */
class Country {
	/**
	 * A reference to the `Tribe__Languages__Locations` instance.
	 *
	 * @since TBD
	 *
	 * @var Locations
	 */
	private Locations $locations;

	/**
	 * Country constructor.
	 *
	 * since TBD
	 *
	 * @param Locations $locations A reference to the `Tribe__Languages__Locations` instance.
	 */
	public function __construct( Locations $locations ) {
		$this->locations = $locations;
	}

	/**
	 * Returns the list of countries in a REST API re.
	 *
	 * @since TBD
	 *
	 * @return WP_REST_Response
	 */
	public function get(): WP_REST_Response {
		$country_array = $this->locations->build_country_array();

		foreach ( $country_array as $key => &$value ) {
			$value = [
				'value' => $key,
				'name'  => urldecode( $value ),
			];
		}

		return new WP_REST_Response( $country_array, 200 );
	}
}
