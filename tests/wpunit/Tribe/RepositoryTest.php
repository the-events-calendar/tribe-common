<?php

namespace Tribe;

require_once codecept_data_dir( 'classes/Repository/Book_Repository.php' );

use Tribe\Common\Tests\Repository\Book_Repository as Repository;

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

	/**
	 * It should return a Read repository when calling Read repo methods on it
	 *
	 * @test
	 */
	public function should_return_a_read_repository_when_calling_read_repo_methods_on_it() {
		$repository = $this->make_instance();

		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->by( 'title', 'foo' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->where( 'title', 'foo' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->where_args( [ 'title' => 'foo' ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->page( 2 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->per_page( 2 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->offset( 2 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->order( 'DESC' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->order_by( 'id' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->fields( 'ids' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->permission( 'editable' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->not_in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->parent( 1 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->parent_in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->parent_not_in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->search( 'foo' ) );
	}
}