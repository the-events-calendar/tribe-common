<?php

namespace TEC\Common\Storage;

/**
 * Class Timed_Option
 *
 * @since   5.0.6
 *
 * @package TEC\Common\Storage
 */
class Timed_OptionTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Timed_Option::class, $sut );
	}

	/**
	 * @return Timed_Option
	 */
	protected function make_instance() {
		return new Timed_Option();
	}

	/**
	 * @test
	 */
	public function it_should_allow_activation() {
		tec_timed_option()->activate();

		$this->assertTrue( tec_timed_option()->is_active() );
	}

	/**
	 * @test
	 */
	public function it_should_allow_deactivation() {
		tec_timed_option()->deactivate();

		$this->assertFalse( tec_timed_option()->is_active() );
	}

	/**
	 * @test
	 */
	public function activated_should_save_option() {
		tec_timed_option()->activate();

		tec_timed_option()->set( 'foo', 'bar' );

		$value = tec_timed_option()->get( 'foo' );

		$non_existent_value = '__NON_EXISTENT__';
		$option_raw = get_option( tec_timed_option()->get_option_name( 'foo' ), $non_existent_value );

		$this->assertEquals( $value, 'bar' );
		$this->assertNotEquals( $option_raw, $non_existent_value );
	}

	/**
	 * @test
	 */
	public function activated_should_allow_deleting() {
		tec_timed_option()->activate();

		tec_timed_option()->set( 'foo', 'bar' );
		$value = tec_timed_option()->get( 'foo' );

		$this->assertEquals( $value, 'bar' );

		tec_timed_option()->delete( 'foo' );
		$value = tec_timed_option()->get( 'foo' );

		$this->assertNotEquals( $value, 'bar' );
	}

	/**
	 * @test
	 */
	public function deactivated_should_not_save_option_but_still_memoize_it() {
		tec_timed_option()->deactivate();
		// Avoid database/cache collisions.
		$id = 'foo' . uniqid();
		tec_timed_option()->set( $id, 'bar' );

		$value = tec_timed_option()->get( $id );

		$non_existent_value = '__NON_EXISTENT__';
		$option_raw         = get_option( tec_timed_option()->get_option_name( $id ), $non_existent_value );

		$this->assertEquals( $value, 'bar' );
		$this->assertEquals( $option_raw, $non_existent_value );
	}

	/**
	 * @test
	 */
	public function deactivated_should_still_allow_deleting_it() {
		tec_timed_option()->deactivate();

		tec_timed_option()->set( 'foo', 'bar' );
		$value = tec_timed_option()->get( 'foo' );

		$this->assertEquals( $value, 'bar' );

		tec_timed_option()->delete( 'foo' );
		$value = tec_timed_option()->get( 'foo' );

		$this->assertNotEquals( $value, 'bar' );
	}

	/**
	 * @test
	 */
	public function deactivated_should_still_overwrite_on_setting_twice() {
		tec_timed_option()->deactivate();

		tec_timed_option()->set( 'foo', 'bar' );
		$value = tec_timed_option()->get( 'foo' );

		$this->assertEquals( $value, 'bar' );

		tec_timed_option()->set( 'foo', 'foobar' );
		$value = tec_timed_option()->get( 'foo' );

		$this->assertEquals( $value, 'foobar' );
	}
}