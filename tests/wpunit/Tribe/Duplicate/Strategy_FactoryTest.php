<?php

namespace Tribe\Duplicate;

use Tribe__Duplicate__Strategy_Factory as Factory;

class Strategy_FactoryTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Factory::class, $this->make_instance() );
	}

	/**
	 * @return Factory
	 */
	protected function make_instance() {
		return new Factory();
	}

	/**
	 * It should build the strategy associated to a slug
	 *
	 * @test
	 */
	public function it_should_build_the_strategy_associated_to_a_slug() {
		$sut = $this->make_instance();
		$sut->set_strategy_map( [
			'default' => \Tribe__Duplicate__Strategy__Like::class,
			'foo'     => \Tribe__Duplicate__Strategy__Like::class,
			'bar'     => \Tribe__Duplicate__Strategy__Same::class,
		] );

		$this->assertInstanceOf( \Tribe__Duplicate__Strategy__Like::class, $sut->make( 'foo' ) );
		$this->assertInstanceOf( \Tribe__Duplicate__Strategy__Same::class, $sut->make( 'bar' ) );
	}

	/**
	 * It should build the filtered strategy associated to a slug
	 *
	 * @test
	 */
	public function it_should_build_the_filtered_strategy_associated_to_a_slug() {
		add_filter( 'tribe_duplicate_post_strategies', function () {
			return [
				'default' => \Tribe__Duplicate__Strategy__Like::class,
				'wooz'    => \Tribe__Duplicate__Strategy__Same::class,
			];
		} );

		$sut = $this->make_instance();

		$this->assertInstanceOf( \Tribe__Duplicate__Strategy__Same::class, $sut->make( 'wooz' ) );
		// default
		$this->assertInstanceOf( \Tribe__Duplicate__Strategy__Like::class, $sut->make( 'bar' ) );
	}

	/**
	 * It should fall back on the default strategy when the slug is not associated with any strategy
	 *
	 * @test
	 */
	public function it_should_fall_back_on_the_default_strategy_when_the_slug_is_not_associated_with_any_strategy() {
		$sut = $this->make_instance();
		$sut->set_strategy_map( [
			'default' => \Tribe__Duplicate__Strategy__Like::class,
			'foo'     => \Tribe__Duplicate__Strategy__Like::class,
			'bar'     => \Tribe__Duplicate__Strategy__Same::class,
		] );

		$this->assertInstanceOf( \Tribe__Duplicate__Strategy__Like::class, $sut->make( 'wooz' ) );
	}

	/**
	 * It should use the first strategy as default strategy if map does not provide a default strategy
	 *
	 * @test
	 */
	public function it_should_use_the_first_strategy_as_default_strategy_if_map_does_not_provide_a_default_strategy() {
		$sut = $this->make_instance();
		$sut->set_strategy_map( [
			'first' => \Tribe__Duplicate__Strategy__Same::class,
			'foo'   => \Tribe__Duplicate__Strategy__Like::class,
			'bar'   => \Tribe__Duplicate__Strategy__Same::class,
		] );

		$this->assertInstanceOf( \Tribe__Duplicate__Strategy__Same::class, $sut->make( 'wooz' ) );
	}

	/**
	 * It should return false when trying to build with empy map
	 *
	 * @test
	 */
	public function it_should_return_false_when_trying_to_build_with_empy_map() {
		$sut = $this->make_instance();
		$sut->set_strategy_map( [] );

		$this->assertFalse( $sut->make( 'wooz' ) );
	}

	/**
	 * It should allow overriding the factory operations completely
	 *
	 * @test
	 */
	public function it_should_allow_overriding_the_factory_operations_completely() {
		$sut = $this->make_instance();
		$sut->set_strategy_map( [
			'default' => \Tribe__Duplicate__Strategy__Like::class,
			'foo'     => \Tribe__Duplicate__Strategy__Like::class,
			'bar'     => \Tribe__Duplicate__Strategy__Same::class,
		] );
		add_filter( 'tribe_duplicate_post_strategy', function () {
			return 'foo bar';
		} );

		$this->assertEquals( 'foo bar', $sut->make( 'wooz' ) );
	}
}