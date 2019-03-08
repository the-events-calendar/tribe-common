<?php

namespace Tribe\Utils;

use \Tribe__Main as Main;

/**
 * TEST CASES
 *
 * - [x] Positive ints
 * - [] Negative ints
 * - [X] Zero
 * - [] Null
 * - [] Strings
 * - [] Nothing (i.e., the function default param is passed)
 */

class Post_ID_HelperTest extends \Codeception\TestCase\WPTestCase {

    /**
     * Create test event.
     *
     * @since TBD
     */
    public function setUp() {
        parent::setUp();

        $this->premade_event_obj = $this->factory()->post->create_and_get( [ 'post_title' => 'Premade Event' ] );
    }

    public function tearDown() {
        parent::tearDown();
    }

    /**
     * @test When passing zero, get the current post ID.
     *
     * @since TBD
     *
     * @param WP_Post $event_obj
     *
     * @testWith [ $this->premade_event_obj ]
     */
    public function it_should_return_post_id_when_passed_post_object( WP_Post $event_obj ) {

        $returned_post_ID = Main::post_id_helper( $event_obj );

        $this->assertEquals( $this->premade_event_obj->ID, $returned_post_ID );
    }

    /**
     * @test When passing zero, get the current post ID.
     *
     * @since TBD
     */
    public function it_should_return_global_post_id_when_passed_zero() {

        global $post;

        $post = $this->factory()->post->create_and_get( [ 'post_title' => 'Example Event' ] );

        $returned_post_ID = Main::post_id_helper( 0 );

        $this->assertEquals( $post->ID, $returned_post_ID );
    }

    /**
     * @test When passing a positive integers explicitly, that int should be returned as-is.
     *
     * @since TBD
     *
     * @param int $post_id
     *
     * @testWith [ 1 ]
     *           [ 33 ]
     *           [ 24356 ]
     */
    public function it_should_return_positive_ints_as_is( int $post_id ) {

        $returned_post_ID = Main::post_id_helper( $post_id );

        $this->assertEquals( $post_id, $returned_post_ID );
    }

}