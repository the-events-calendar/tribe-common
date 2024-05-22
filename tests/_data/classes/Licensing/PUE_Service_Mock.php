<?php

namespace TEC\Common\Tests\Licensing;

use TEC\Common\Tests\Http_API\Http_API_Mock;

class PUE_Service_Mock extends Http_API_Mock {
	/**
	 * Returns the body of a success response to the key validation request, in array format.
	 *
	 * @return array<string,mixed> The body of a success response to the key validation request.
	 */
	public function get_validate_key_success_body(): array {
		return [
			'results' => [
				[
					'name'           => 'Test Plugin',
					'slug'           => 'test-plugin',
					'zip_url'        => '',
					'file_prefix'    => 'test-plugin.6.0.1',
					'homepage'       => 'http://evnt.is/test-plugin',
					'download_url'   => 'https://pue.theeventscalendar.com/api/plugins/v2/download?plugin=test-plugin&version=6.0.1',
					'icon_svg_url'   => 'https://pue.theeventscalendar.com/product-images/test-plugin.svg',
					'version'        => '6.0.1',
					'requires'       => '5.8.4',
					'tested'         => '6.0.2',
					'release_date'   => '2022-09-22 00:00:00',
					'upgrade_notice' => 'Remember to always make a backup of your database and files before updating!',
					'last_updated'   => '2022-09-22 17:52:39',
					'sections'       =>
						[
							'description'  => 'A test plugin',
							'installation' => 'Installation instructions.',
							'changelog'    => '<p>Changelog notes</p>',
						],
					'expiration'     => '2028-05-12',
					'daily_limit'    => null,
					'custom_update'  =>
						[
							'icons' =>
								[
									'svg' => 'https://pue.theeventscalendar.com/product-icons/test-plugin.svg',
								],
						],
					'api_upgrade'    => false,
					'api_expired'    => false,
					'api_message'    => null,
				],
			],
		];
	}


	/**
	 * {@inheritdoc }
	 */
	protected function get_url(): string {
		return 'https://pue.theeventscalendar.com/api/';
	}
}