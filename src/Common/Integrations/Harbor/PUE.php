<?php
/**
 * The PUE Harbor integration.
 *
 * @since 6.11.0
 *
 * @package TEC\Common\Integrations\Harbor
 */

namespace TEC\Common\Integrations\Harbor;

use TEC\Common\Integrations\Harbor\Integration_Controller;
use TEC\Common\LiquidWeb\Harbor\Licensing\Repositories\License_Repository;
use TEC\Common\LiquidWeb\Harbor\Portal\Catalog_Repository;
use TEC\Common\LiquidWeb\Harbor\Portal\Catalog_Collection;
use TEC\Common\LiquidWeb\Harbor\Portal\Results\Catalog_Feature;
use TEC\Common\LiquidWeb\Harbor\Licensing\Results\Product_Entry;
use TEC\Common\LiquidWeb\Harbor\Portal\Results\Product_Catalog;
use TEC\Common\LiquidWeb\Harbor\Licensing\Product_Collection;
use TEC\Common\StellarWP\Uplink\Resources\Resource as Uplink_Resource;
use TEC\Common\LiquidWeb\Harbor\Config;

/**
 * The PUE Harbor integration.
 *
 * @since 6.11.0
 *
 * @package TEC\Common\Integrations\Harbor
 */
class PUE extends Integration_Controller {
	/**
	 * Register the controller.
	 *
	 * @since 6.11.0
	 *
	 * @return void
	 */
	protected function do_register(): void {
		add_filter( 'pre_http_request', [ $this, 'filter_pre_http_request' ], 10, 3 );
		add_filter( 'pre_option', [ $this, 'filter_pre_get_option' ], 10, 3 );
		add_filter( 'stellarwp/uplink/tec/license_get_key', [ $this, 'filter_stellarwp_uplink_tec_license_get_key' ], 10, 2 );
		add_filter( 'tec_common_uplink_auth_url', [ $this, 'filter_stellarwp_uplink_tec_authorize_button_url' ], 10, 2 );
		add_filter( 'pue_get_update_url', [ $this, 'filter_pue_get_update_url' ], 10, 2 );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.11.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_filter( 'pre_http_request', [ $this, 'filter_pre_http_request' ] );
		remove_filter( 'pre_option', [ $this, 'filter_pre_get_option' ] );
		remove_filter( 'stellarwp/uplink/tec/license_get_key', [ $this, 'filter_stellarwp_uplink_tec_license_get_key' ] );
		remove_filter( 'tec_common_uplink_auth_url', [ $this, 'filter_stellarwp_uplink_tec_authorize_button_url' ] );
		remove_filter( 'pue_get_update_url', [ $this, 'filter_pue_get_update_url' ] );
	}

	/**
	 * Filter the PUE update URL.
	 *
	 * @since 6.11.0
	 *
	 * @param string $update_url The update URL.
	 * @param string $slug The plugin slug.
	 *
	 * @return string
	 */
	public function filter_pue_get_update_url( string $update_url, string $slug ): string {
		if ( ! $this->harbor->is_product_licensed( $slug ) ) {
			return $update_url;
		}

		return 'https://herald.stellarwp.com';
	}

	/**
	 * Filter the StellarWP Uplink TEC license get key.
	 *
	 * @since 6.11.0
	 *
	 * @param ?string         $license         The license.
	 * @param Uplink_Resource $uplink_resource The resource.
	 *
	 * @return ?string
	 */
	public function filter_stellarwp_uplink_tec_license_get_key( ?string $license, Uplink_Resource $uplink_resource ) {
		$harbor_slug = $this->harbor->get_harbor_product_slug( $uplink_resource->get_slug() );
		if ( ! $this->harbor->is_product_licensed( $harbor_slug ) ) {
			return $license;
		}

		return $this->harbor->get_unified_license_key();
	}

	/**
	 * Filter the pre get option.
	 *
	 * @since 6.11.0
	 *
	 * @param mixed  $value         The value.
	 * @param string $option        The option.
	 * @param mixed  $default_value The default value.
	 *
	 * @return mixed
	 */
	public function filter_pre_get_option( $value, $option, $default_value ) {
		if ( ! str_starts_with( $option, 'pue_install_key_' ) ) {
			return $value;
		}

		$product = str_replace( [ 'pue_install_key_', '_' ], [ '', '-' ], $option );

		$harbor_product_slug = $this->harbor->get_harbor_product_slug( $product );
		if ( ! $this->harbor->is_product_licensed( $harbor_product_slug ) ) {
			return $value;
		}

		return $this->harbor->get_unified_license_key();
	}

	/**
	 * Filter the pre HTTP request.
	 *
	 * @since 6.11.0
	 *
	 * @param false|array|WP_Error $response    The response.
	 * @param array                $parsed_args The parsed arguments.
	 * @param string               $url         The URL.
	 *
	 * @return false|array
	 */
	public function filter_pre_http_request( $response, array $parsed_args, string $url ) {
		if ( false !== $response ) {
			return $response;
		}

		$parsed_url = wp_parse_url( $url );

		$allowed_paths = '/api/plugins/v2/license/validate';

		if ( empty( $parsed_url['path'] ) || empty( $parsed_url['host'] ) ) {
			return $response;
		}

		if ( rtrim( $parsed_url['path'], '/' ) !== $allowed_paths ) {
			return $response;
		}

		$is_production = Config::get_licensing_base_url() === Config::DEFAULT_LICENSING_BASE_URL;

		$allowed_hosts = [
			'licensing.nexcess.com',
			'licensing.stellarwp.com',
			'pue.theeventscalendar.com',
		];

		// Be extra safe for production requests.
		if ( $is_production && ! in_array( $parsed_url['host'], $allowed_hosts, true ) ) {
			return $response;
		}

		if ( is_string( $parsed_args['body'] ) ) {
			$body = json_decode( $parsed_args['body'], true );
		} else {
			$body = $parsed_args['body'];
		}

		if ( empty( $body['plugin'] ) || ! is_string( $body['plugin'] ) ) {
			return $response;
		}

		$harbor_product_slug = $this->harbor->get_harbor_product_slug( $body['plugin'] );

		if ( ! $this->harbor->is_product_licensed( $harbor_product_slug ) ) {
			return $response;
		}

		/** @var ?Catalog_Collection $catalog */
		$catalog = tribe( Catalog_Repository::class )->get_cached();

		/** @var ?Product_Collection $products */
		$products = tribe( License_Repository::class )->get_products();

		if ( $catalog && ! is_wp_error( $catalog ) && $products && ! is_wp_error( $products ) ) {
			$tec_product         = $products->get_activated_entry( 'the-events-calendar' );
			$tec_product_catalog = $catalog->get( 'the-events-calendar' );
			if ( $tec_product && $tec_product_catalog ) {
				$response = $this->response_from_catalog( $tec_product_catalog, $tec_product, $body['plugin'] );
				if ( $response ) {
					return $response;
				}
			}
		}

		return [
			'headers'  => [],
			'body'     => wp_json_encode(
				[
					'results' => [
						[
							'name'            => '',
							'plugin'          => $body['plugin'],
							'slug'            => $body['plugin'],
							'version'         => '',
							'homepage'        => '',
							'sections'        => [],
							'download_url'    => '',
							'home_url'        => '',
							'origin_url'      => '',
							'zip_url'         => '',
							'icon_svg_url'    => '',
							'auth_url'        => '',
							'file_prefix'     => '',
							'author'          => '',
							'author_homepage' => '',
							'requires'        => '',
							'auth_required'   => '',
							'is_authorized'   => '',
							'tested'          => '',
							'upgrade_notice'  => '',
							'rating'          => '',
							'num_ratings'     => '',
							'downloaded'      => '',
							'release_date'    => '',
							'last_updated'    => '',
							'expiration'      => '',
							'daily_limit'     => '',
							'custom_update'   => '',
							'api_message'     => '',
							'license_key'     => '',
						],
					],
				]
			),
			'response' => [
				'code'    => 200,
				'message' => 'OK',
			],
			'cookies'  => [],
		];
	}

	/**
	 * Get the response from the catalog.
	 *
	 * @since 6.11.0
	 *
	 * @param Product_Catalog $tec_product_catalog The TEC product entry.
	 * @param Product_Entry   $tec                 The TEC product.
	 * @param string          $plugin              The plugin.
	 *
	 * @return array|null
	 */
	private function response_from_catalog( Product_Catalog $tec_product_catalog, Product_Entry $tec, string $plugin ): ?array {
		$harbor_product_slug = $this->harbor->get_harbor_product_slug( $plugin );

		$features = $tec_product_catalog->get_features();

		$feature = current(
			array_filter(
				$features,
				static fn( Catalog_Feature $feature ) => $feature->get_slug() === $harbor_product_slug
			)
		);

		if ( ! $feature ) {
			return null;
		}

		$version = $feature->get_version();

		$version = $version ? $version : '1.0.0';

		return [
			'headers'  => [],
			'body'     => wp_json_encode(
				[
					'results' => [
						[
							'name'            => $feature->get_name(),
							'plugin'          => $plugin,
							'slug'            => $feature->get_slug(),
							'version'         => $version,
							'homepage'        => $feature->get_homepage(),
							'sections'        => [],
							'download_url'    => '',
							'home_url'        => $feature->get_homepage(),
							'origin_url'      => $feature->get_homepage(),
							'zip_url'         => '',
							'icon_svg_url'    => $feature->get_homepage(),
							'auth_url'        => $feature->get_homepage(),
							'file_prefix'     => $feature->get_homepage(),
							'author'          => $feature->get_authors()['0'],
							'author_homepage' => $feature->get_homepage(),
							'requires'        => '',
							'auth_required'   => false,
							'is_authorized'   => '',
							'tested'          => '',
							'upgrade_notice'  => '',
							'rating'          => '',
							'num_ratings'     => '',
							'downloaded'      => '',
							'release_date'    => $feature->get_release_date(),
							'last_updated'    => '',
							'expiration'      => $tec->get_expires()->format( 'Y-m-d H:i:s' ),
							'daily_limit'     => '',
							'custom_update'   => '',
							'license_key'     => $this->harbor->get_unified_license_key(),
						],
					],
				]
			),
			'response' => [
				'code'    => 200,
				'message' => 'OK',
			],
			'cookies'  => [],
		];
	}

	/**
	 * Filter the StellarWP Uplink TEC authorize button URL.
	 *
	 * @since 6.11.0
	 *
	 * @param string $url   The URL.
	 * @param string $slug  The slug.
	 *
	 * @return string
	 */
	public function filter_stellarwp_uplink_tec_authorize_button_url( string $url, string $slug ): string {
		if ( 'tec-seating' !== $slug ) {
			return $url;
		}

		$url_parsed = wp_parse_url( $url );
		if ( empty( $url_parsed['host'] ) || empty( $url_parsed['path'] ) ) {
			return $url;
		}

		if ( $url_parsed['path'] !== '/seating-connect/' ) {
			return $url;
		}

		return $this->harbor->get_portal_url( $url_parsed['path'] );
	}
}
