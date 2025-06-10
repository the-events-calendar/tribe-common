<?php
/**
 * Handles the `options/currencies` endpoint of the Classy REST API.
 *
 * @since   TBD
 *
 * @package TEC\Common\Classy\REST\Endpoints\Options
 */

declare( strict_types=1 );

namespace TEC\Classy\REST\Endpoints\Options;

use TEC\Common\Lists\Currency;
use WP_REST_Response as Response;

/**
 * Class Currencies
 *
 * @since TBD
 */
class Currencies {
	/**
	 * Returns the list of currencies in a REST API response.
	 *
	 * @since TBD
	 *
	 * @return Response
	 */
	public function get(): Response {
		$currencies = tribe( Currency::class )->get_currency_list();

		$return = [];
		foreach ( $currencies as $currency ) {
			$return[] = [
				'code'     => $currency['code'] ?? '',
				'symbol'   => $currency['symbol'] ?? '',
				'position' => $currency['position'] ?? 'prefix',
			];
		}

		return rest_ensure_response( $return );
	}
}
