<?php

namespace Tribe\JSON_LD;

require_once codecept_data_dir( 'classes/Tribe__JSON_LD__Test_Class.php' );

use Tribe__Events__Main as Main;
use Tribe__JSON_LD__Test_Class as Jsonld;

class AbstractTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here
		\Tribe__JSON_LD__Abstract::unregister_all();

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
			[ [] ],
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
	 * it should return an empty array when trying to get data for same post a second time
	 */
	public function it_should_return_an_empty_array_when_trying_to_get_data_for_same_post_a_second_time() {
		$post = $this->factory()->post->create_and_get( [ 'post_type' => Main::POSTTYPE ] );

		$sut = $this->make_instance();
		$sut->register( $post );
		$sut->set_type( $post, strtolower( $sut->type ) );
		$second_fetch_data = $sut->get_data( $post );

		$this->assertInternalType( 'array', $second_fetch_data );
		$this->assertEmpty( $second_fetch_data );
	}

	/**
	 * @test
	 * it should allow getting already fetched post IDs
	 */
	public function it_should_allow_getting_already_fetched_post_i_ds() {
		$ids = $this->factory()->post->create_many( 3, [ 'post_type' => Main::POSTTYPE ] );

		$sut = $this->make_instance();

		foreach ( $ids as $id ) {
			$sut->register( $id );
		}

		$this->assertEqualSets( $ids, Jsonld::get_registered_post_ids() );
	}

	/**
	 * @test
	 * it should allow resetting the fetched post IDs
	 */
	public function it_should_allow_resetting_the_fetched_post_i_ds() {
		$ids = $this->factory()->post->create_many( 3, [ 'post_type' => Main::POSTTYPE ] );

		$sut = $this->make_instance();

		foreach ( $ids as $id ) {
			$sut->register( $id );
		}

		$this->assertCount( count( $ids ), Jsonld::get_registered_post_ids() );

		Jsonld::unregister_all();

		$this->assertEmpty( Jsonld::get_registered_post_ids() );
	}

	/**
	 * @test
	 * it should allow removing a fetched post ID
	 */
	public function it_should_allow_removing_a_fetched_post_id() {
		$ids = $this->factory()->post->create_many( 3, [ 'post_type' => Main::POSTTYPE ] );

		$sut = $this->make_instance();

		foreach ( $ids as $id ) {
			$sut->register( $id );
		}

		$this->assertCount( count( $ids ), $sut->get_registered_post_ids() );

		$sut->remove( reset( $ids ) );

		$this->assertEqualSets( array_splice( $ids, 1 ), $sut->get_registered_post_ids() );
	}

	/**
	 * It should show JSON-LD information to visitors
	 *
	 * @test
	 */
	public function should_show_json_ld_information_to_visitors(): void {
		wp_set_current_user( 0 ); // 0 is the visitor.

		$event   = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'publish',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();
		$post_id = static::factory()->post->create();

		$this->assertFalse(
			current_user_can( 'read' ),
			'A visitor user will not have the read capability.'
		);

		$this->assertFalse(
			current_user_can( 'read', $post_id ),
			'A visitor user will not have the read capability for posts.'
		);

		$this->assertFalse(
			current_user_can( 'read', $event->ID ),
			'A visitor user will not have the read capability for events.'
		);

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $event->ID );

		$this->assertCount( 1, $json_data );
		$json_ld_data = array_shift( $json_data );
		$this->assertObjectHasAttribute( 'eventAttendanceMode', $json_ld_data );
		$this->assertObjectHasAttribute( 'startDate', $json_ld_data );
		$this->assertObjectHasAttribute( 'endDate', $json_ld_data );
	}

	/**
	 * It should not show JSON-LD information about private events to visitors
	 *
	 * @test
	 */
	public function should_not_show_json_ld_information_about_private_events_to_visitors(): void {
		wp_set_current_user( 0 ); // 0 is the visitor.

		$private_event = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'private',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertEmpty( $json_data );
	}

	/**
	 * It should not show JSON-LD information about password-protected events to visitors
	 *
	 * @test
	 */
	public function should_not_show_json_ld_information_about_password_protected_events_to_visitors(): void {
		wp_set_current_user( 0 ); // 0 is the visitor.

		$private_event = tribe_events()->set_args( [
			'title'         => 'Test Event',
			'status'        => 'publish',
			'start_date'    => '2020-01-01 10:00:00',
			'end_date'      => '2020-01-01 12:00:00',
			'post_password' => 'secret',
		] )->create();

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertEmpty( $json_data );
	}

	/**
	 * It should show JSON-LD information to subscribers
	 *
	 * @test
	 */
	public function should_show_json_ld_information_to_subscribers(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'subscriber' ] ) );

		$event   = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'publish',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();
		$post_id = static::factory()->post->create();

		$this->assertTrue(
			current_user_can( 'read' ),
			'A subscriber user will have the read capability.'
		);

		$this->assertTrue(
			current_user_can( 'read', $post_id ),
			'A subscriber user will have the read capability for posts.'
		);

		$this->assertTrue(
			current_user_can( 'read', $event->ID ),
			'A subscriber user will have the read capability for events.'
		);

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $event->ID );

		$this->assertCount( 1, $json_data );
		$json_ld_data = array_shift( $json_data );
		$this->assertObjectHasAttribute( 'eventAttendanceMode', $json_ld_data );
		$this->assertObjectHasAttribute( 'startDate', $json_ld_data );
		$this->assertObjectHasAttribute( 'endDate', $json_ld_data );
	}

	/**
	 * It should not show JSON-LD information about private events to subscribers
	 *
	 * @test
	 */
	public function should_not_show_json_ld_information_about_private_events_to_subscribers(): void {
		// Have an Author create a private post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$private_event = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'private',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();

		// Have a Subscriber try to read the private post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'subscriber' ] ) );

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertEmpty( $json_data );
	}

	/**
	 * It should not show JSON-LD information about password-protected events to subscribers
	 *
	 * @test
	 */
	public function should_show_json_ld_information_about_password_protected_events_to_subscribers(): void {
		// Have an Author create a password-protected post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$private_event = tribe_events()->set_args( [
			'title'         => 'Test Event',
			'status'        => 'publish',
			'start_date'    => '2020-01-01 10:00:00',
			'end_date'      => '2020-01-01 12:00:00',
			'post_password' => 'secret',
		] )->create();

		// Have a Subscriber try to read the password-protected post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'subscriber' ] ) );

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertEmpty( $json_data );
	}

	/**
	 * It should show JSON-LD information to authors
	 *
	 * @test
	 */
	public function should_show_json_ld_information_to_authors(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'author' ] ) );

		$event   = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'publish',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $event->ID );

		$this->assertCount( 1, $json_data );
		$json_ld_data = array_shift( $json_data );
		$this->assertObjectHasAttribute( 'eventAttendanceMode', $json_ld_data );
		$this->assertObjectHasAttribute( 'startDate', $json_ld_data );
		$this->assertObjectHasAttribute( 'endDate', $json_ld_data );
	}

	/**
	 * It should not show JSON-LD information about private events to authors
	 *
	 * @test
	 */
	public function should_not_show_json_ld_information_about_private_events_to_authors(): void {
		// Have an Editor create a private post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$private_event = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'private',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();

		// Have an Author try to read the private post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'author' ] ) );

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertEmpty( $json_data );
	}

	/**
	 * It should not show JSON-LD information about password-protected events to authors
	 *
	 * @test
	 */
	public function should_not_show_json_ld_information_about_password_protected_events_to_authors(): void {
		// Have an Editor create a password-protected post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$private_event = tribe_events()->set_args( [
			'title'         => 'Test Event',
			'status'        => 'publish',
			'start_date'    => '2020-01-01 10:00:00',
			'end_date'      => '2020-01-01 12:00:00',
			'post_password' => 'secret',
		] )->create();

		// Have an Author try to read the password-protected post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'author' ] ) );

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertEmpty( $json_data );
	}

	/**
	 * It should show JSON-LD information to editors
	 *
	 * @test
	 */
	public function should_show_json_ld_information_to_editors(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );

		$event   = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'publish',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $event->ID );

		$this->assertCount( 1, $json_data );
		$json_ld_data = array_shift( $json_data );
		$this->assertObjectHasAttribute( 'eventAttendanceMode', $json_ld_data );
		$this->assertObjectHasAttribute( 'startDate', $json_ld_data );
		$this->assertObjectHasAttribute( 'endDate', $json_ld_data );
	}

	/**
	 * It should show JSON-LD information about private events to authors
	 *
	 * @test
	 */
	public function should_show_json_ld_information_about_private_events_to_editors(): void {
		// Have another Editor create a private post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$private_event = tribe_events()->set_args( [
			'title'      => 'Test Event',
			'status'     => 'private',
			'start_date' => '2020-01-01 10:00:00',
			'end_date'   => '2020-01-01 12:00:00',
		] )->create();

		// Have an Editor try to read the private post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertCount( 1, $json_data );
		$json_ld_data = array_shift( $json_data );
		$this->assertObjectHasAttribute( 'eventAttendanceMode', $json_ld_data );
		$this->assertObjectHasAttribute( 'startDate', $json_ld_data );
		$this->assertObjectHasAttribute( 'endDate', $json_ld_data );
	}

	/**
	 * It should not show JSON-LD information about password-protected events to editors
	 *
	 * @test
	 */
	public function should_not_show_json_ld_information_about_password_protected_events_to_editors(): void {
		// Have another Editor create a password-protected post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$private_event = tribe_events()->set_args( [
			'title'         => 'Test Event',
			'status'        => 'publish',
			'start_date'    => '2020-01-01 10:00:00',
			'end_date'      => '2020-01-01 12:00:00',
			'post_password' => 'secret',
		] )->create();

		// Have an Editor try to read the password-protected post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertEmpty( $json_data );
	}

	/**
	 * It should show JSON-LD information of password-protected events to their authors
	 *
	 * @test
	 */
	public function should_show_json_ld_information_of_password_protected_events_to_their_authors(): void {
		// Have an Editor create a password-protected post.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$private_event = tribe_events()->set_args( [
			'title'         => 'Test Event',
			'status'        => 'publish',
			'start_date'    => '2020-01-01 10:00:00',
			'end_date'      => '2020-01-01 12:00:00',
			'post_password' => 'secret',
		] )->create();

		$json_data = \Tribe__Events__JSON_LD__Event::instance()->get_data( $private_event->ID );

		$this->assertCount( 1, $json_data );
		$json_ld_data = array_shift( $json_data );
		$this->assertObjectHasAttribute( 'eventAttendanceMode', $json_ld_data );
		$this->assertObjectHasAttribute( 'startDate', $json_ld_data );
		$this->assertObjectHasAttribute( 'endDate', $json_ld_data );
	}
}
