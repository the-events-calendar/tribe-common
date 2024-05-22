<?php

namespace TEC\Tribe\Repository;

use Tribe\Repository\ReadTestBase;
use Tribe__Repository__Decorator as Decorator;

require_once __DIR__ . '/ReadTestBase.php';

class ContextTest extends ReadTestBase {
	/**
	 * It should have a null context by default
	 *
	 * @test
	 */
	public function should_have_a_null_context_by_default(): void {
		$repository = $this->repository();

		$context = $repository->get_request_context();

		$this->assertNull( $context );
	}

	/**
	 * It should allow getting and setting the request context
	 *
	 * @test
	 */
	public function should_allow_getting_and_setting_the_request_context(): void {
		$repository = $this->repository();

		$repository->set_request_context( 'some-context' );

		$this->assertEquals( 'some-context', $repository->get_request_context() );
	}

	/**
	 * It should allow getting and setting request context in Decorator
	 *
	 * @test
	 */
	public function should_allow_getting_and_setting_request_context_in_decorator(): void {
		$decorator = new class extends Decorator {
			public function __construct() {
				$this->decorated = new class extends \Tribe__Repository {
					protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];
				};
			}
		};

		$this->assertNull( $decorator->get_request_context() );

		$decorator->set_request_context( 'some-context' );

		$this->assertEquals( 'some-context', $decorator->get_request_context() );
	}
}
