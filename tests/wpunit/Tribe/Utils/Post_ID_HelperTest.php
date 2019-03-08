<?php

namespace Tribe\Utils;

use \Tribe__Main as Main;

/**
 * TEST CASES
 *
 * - [x] Positive ints
 * - [] Negative ints
 * - [X] Zero
 * - [X] Post object
 * - [] Null
 * - [] Strings
 * - [] Nothing (i.e., the function default param is passed)
 */

class Post_ID_HelperTest extends \Codeception\TestCase\WPTestCase {

    /**
     * dataProvider callback for getting a test event.
     *
     * @since TBD
     */
    public function get_sample_events() {
        return [
            'WP_Post' => [ $this->factory()->post->create_and_get( [ 'post_title' => 'Sample Event' ] ) ]
        ];
    }

    /**
     * @test When passing a WP_Post object, get the ID from it.
     *
     * @since TBD
     *
     * @param WP_Post $event_obj
     *
     * @dataProvider get_sample_events
     */
    public function it_should_return_post_ids_when_passed_post_objects( \WP_Post $event_obj ) {

        $expected = $event_obj->ID;

        $this->assertEquals( $expected,  Main::post_id_helper( $event_obj ) );
    }

    /**
     * @test When passing zero, get the current post ID.
     *
     * @since TBD
     */
    public function it_should_return_global_post_id_when_passed_zero() {

        global $post;

        $post     = $this->factory()->post->create_and_get( [ 'post_title' => 'Sample Event' ] );
        $expected = $post->ID;

        $this->assertEquals( $expected, Main::post_id_helper( 0 ) );
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

        $expected = $post_id;

        $this->assertEquals( $expected, Main::post_id_helper( $post_id ) );
    }

}