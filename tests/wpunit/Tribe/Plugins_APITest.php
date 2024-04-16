<?php
namespace Tribe;

use Tribe__Plugins_API as API;

class PluginsAPITest extends \Codeception\TestCase\WPTestCase { // phpcs:ignore

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( API::class, $sut );
	}

	/**
	 * @test
	 * test the array of our products and their properties
	 */
	public function our_plugins_must_define_all_properties() {
		$sut = $this->make_instance();

		$products = $sut->get_products();

		$this->assertNotEmpty( $products );
		$this->assertIsArray( $products );

		foreach ( $products as $product ) {
			$this->assertArrayHasKey( 'title', $product );
			$this->assertArrayHasKey( 'slug', $product );
			$this->assertArrayHasKey( 'link', $product );
			$this->assertArrayHasKey( 'plugin-dir', $product );
			$this->assertArrayHasKey( 'main-file', $product );
			$this->assertArrayHasKey( 'description', $product );
			$this->assertArrayHasKey( 'description-help', $product );
			$this->assertArrayHasKey( 'features', $product );
			$this->assertArrayHasKey( 'image', $product );
			$this->assertArrayHasKey( 'logo', $product );
			$this->assertArrayHasKey( 'is_installed', $product );
			$this->assertArrayHasKey( 'free', $product );
			$this->assertArrayHasKey( 'active_installs', $product );
		}

		$services = [ 'promoter', 'event-aggregator' ];

		foreach ( $products as $product ) {
			$this->assertNotEmpty( $product['title'] );
			$this->assertNotEmpty( $product['slug'] );
			$this->assertNotEmpty( $product['link'] );
			if ( ! in_array( $product['slug'], $services ) ) {
				$this->assertNotEmpty( $product['plugin-dir'] );
				$this->assertNotEmpty( $product['main-file'] );
			}
			$this->assertNotEmpty( $product['description'] );
			$this->assertNotEmpty( $product['description-help'] );
			$this->assertNotEmpty( $product['features'] );
			$this->assertNotEmpty( $product['image'] );
			$this->assertNotEmpty( $product['logo'] );
			$this->assertIsBool( $product['is_installed'] );
			$this->assertIsBool( $product['free'] );
			$this->assertIsInt( $product['active_installs'] );
		}
	}

	/**
	 * @return API
	 */
	private function make_instance() {
		return new API();
	}
}
