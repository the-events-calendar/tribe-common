<?php
namespace Tribe\Repository;


use Codeception\TestCase\WPTestCase;
use Tribe__Repository as Read_Repository;

class ReadTestBase extends WPTestCase
{

	/**
	 * @var \Tribe__Repository
	 */
	protected $class;

	public function setUp(): void {
		parent::setUp();
		register_post_type( 'book' );
		register_post_type( 'review' );
		register_post_status( 'good' );
		register_post_status( 'bad' );
		register_taxonomy( 'genre', 'book' );
		register_taxonomy( 'user_genre', 'book' );
		$this->class = new class extends \Tribe__Repository {
			protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];
		};
	}

	/**
	 * @return Read_Repository
	 */
	protected function repository() {
		return new $this->class();
	}
}
