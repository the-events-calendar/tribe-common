<?php

namespace Tribe;

require_once codecept_data_dir( 'classes/Repository/Book_Repository.php' );

use Tribe\Common\Tests\Repository\Book_Repository as Repository;
use Tribe__Repository__Read_Interface as Read_Interface;

class RepositoryTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Repository::class, $sut );
	}

	/**
	 * @return Repository
	 */
	protected function make_instance() {
		return new Repository();
	}

	/**
	 * It should return a Read Repository on fetch
	 *
	 * @test
	 */
	public function should_return_a_read_repository_on_fetch() {
		$repository = $this->make_instance();

		$this->assertInstanceOf( Read_Interface::class, $repository->fetch() );
	}

	/**
	 * It should allow getting and setting default arguments
	 *
	 * @test
	 */
	public function should_allow_getting_and_setting_default_arguments() {
		$repository   = $this->make_instance();
		$current_args = $repository->get_default_args();

		$new_args = array_merge( $current_args, [ 'foo' => 'bar' ] );

		$repository->set_default_args( $new_args );

		$this->assertEquals( $new_args, $repository->get_default_args() );
	}
}