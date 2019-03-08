<?php

namespace Tribe\Utils;

use \Tribe__Main as Main;

/**
 * TEST CASES
 *
 * - Positive ints
 * - Negative ints
 * - Zero
 * - Null
 * - Strings
 * - Nothing (i.e., the function default param is passed)
 */

class Post_ID_HelperTest extends \Codeception\TestCase\WPTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
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