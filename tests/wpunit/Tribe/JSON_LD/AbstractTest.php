<?php
namespace Tribe\JSON_LD;

require_once codecept_data_dir( 'classes/Tribe__JSON_LD__Test_Class.php' );

use Tribe__JSON_LD__Test_Class as Jsonld;

class AbstractTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here
		\Tribe__JSON_LD__Abstract::class_reset_fetched_post_ids();

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Jsonld::class, $sut );
	}

	private function make_instance() {
		return new Jsonld();
	}

	public function empties() {
		return [
			[ '' ],
			[ [ ] ],
			[ null ],
			[ 0 ]
		];
	}

	/**
	 * @test
	 * it should return an empty array if trying to get data for empty
	 * @dataProvider empties
	 */
	public function it_should_return_an_empty_array_if_trying_to_get_data_for_empty( $empty ) {
		$this->assertEquals( array(), $this->make_instance()->get_data( $empty ) );
	}

	/**
	 * @test
	 * it should return array with one post in it if trying to get data for one post
	 */
	public function it_should_return_array_with_one_post_in_it_if_trying_to_get_data_for_one_post() {
		$post = $this->factory()->post->create();

		$sut  = $this->make_instance();
		$data = $sut->get_data( $post );

		$this->assertInternalType( 'array', $data );
		$this->assertCount( 1, $data );
		$this->assertContainsOnly( 'stdClass', $data );
	}

	/**
	 * @test
	 * it should return an empty array when trying to get data for same post a second time and skip duplicates is set to true
	 */
	public function it_should_return_an_empty_array_when_trying_to_get_data_for_same_post_a_second_time_and_skip_duplicates_is_set_to_true() {
		$post = $this->factory()->post->create();

		$sut = $this->make_instance();
		$sut->get_data( $post );
		$second_fetch_data = $sut->get_data( $post, [ 'skip_duplicates' => true ] );

		$this->assertInternalType( 'array', $second_fetch_data );
		$this->assertEmpty( $second_fetch_data );
	}

	/**
	 * @test
	 * it should return the same date when trying to get data for same post a second time and skip duplicates is set to false
	 */
	public function it_should_return_the_same_date_when_trying_to_get_data_for_same_post_a_second_time_and_skip_duplicates_is_set_to_false() {
		$post = $this->factory()->post->create();

		$sut               = $this->make_instance();
		$first_fetch_data  = $sut->get_data( $post );
		$second_fetch_data = $sut->get_data( $post, [ 'skip_duplicates' => false ] );

		$this->assertInternalType( 'array', $second_fetch_data );
		$this->assertEqualSets( $first_fetch_data, $second_fetch_data );
	}

	/**
	 * @test
	 * it should allow getting already fetched post IDs
	 */
	public function it_should_allow_getting_already_fetched_post_i_ds() {
		$ids = $this->factory()->post->create_many( 3 );

		$sut = $this->make_instance();

		foreach ( $ids as $id ) {
			$sut->get_data( $id );
		}

		$this->assertEqualSets( $ids, $sut->get_fetched_post_ids() );
	}

	/**
	 * @test
	 * it should not store duplicate post IDs among the already fetched ones
	 */
	public function it_should_not_store_duplicate_post_i_ds_among_the_already_fetched_ones() {
		$ids = $this->factory()->post->create_many( 3 );

		$sut = $this->make_instance();

		foreach ( $ids as $id ) {
			$sut->get_data( $id );
		}

		$sut->set_fetched_post_id( reset( $ids ) );

		$this->assertCount( count( $ids ), $sut->get_fetched_post_ids() );
	}

	/**
	 * @test
	 * it should allow resetting the fetched post IDs
	 */
	public function it_should_allow_resetting_the_fetched_post_i_ds() {
		$ids = $this->factory()->post->create_many( 3 );

		$sut = $this->make_instance();

		foreach ( $ids as $id ) {
			$sut->get_data( $id );
		}

		$this->assertCount( count( $ids ), $sut->get_fetched_post_ids() );

		$sut->reset_fetched_post_ids();

		$this->assertEmpty( $sut->get_fetched_post_ids() );
	}

	/**
	 * @test
	 * it should allow unsetting a fetched post ID
	 */
	public function it_should_allow_unsetting_a_fetched_post_id() {
		$ids = $this->factory()->post->create_many( 3 );

		$sut = $this->make_instance();

		foreach ( $ids as $id ) {
			$sut->get_data( $id );
		}

		$this->assertCount( count( $ids ), $sut->get_fetched_post_ids() );

		$sut->unset_fetched_post_id( reset( $ids ) );

		$this->assertEqualSets( array_splice( $ids, 1 ), $sut->get_fetched_post_ids() );
	}

}
