<?php

namespace Tribe;

use Tribe__Context as Context;

function __context__test__function__() {
	return '__value__';
}

class ContextTest extends \Codeception\TestCase\WPTestCase {

	public static $__key__;
	protected static $__static_method_return_value__;
	public $__public_key__;
	protected $__public_method_return_value__;

	public static function __test_static_method__() {
		return static::$__static_method_return_value__;
	}

	public function __public_method__() {
		return $this->__public_method_return_value__;
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Context::class, $sut );
	}

	/**
	 * @return Context
	 */
	private function make_instance() {
		return new Context();
	}

	/**
	 * It should correctly detect when editing a post
	 *
	 * @test
	 */
	public function should_correctly_detect_when_editing_a_post() {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_editing_post() );

		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$this->go_to( "/wp-admin/post.php?post={$post_id}&action=edit" );
		global $pagenow;
		$pagenow = 'post.php';

		$this->assertFalse( $sut->is_editing_post( 'page' ) );
		$this->assertTrue( $sut->is_editing_post( 'post' ) );
		$this->assertTrue( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertTrue( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );
	}

	/**
	 * It should be editing a post when creating a new post
	 *
	 * @test
	 */
	public function should_be_editing_a_post_when_creating_a_new_post() {
		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_editing_post() );

		$this->go_to( "/wp-admin/post-new.php" );
		global $pagenow;
		$pagenow = 'post-new.php';

		$this->assertTrue( $sut->is_editing_post() );
		$this->assertTrue( $sut->is_editing_post( 'post' ) );
		$this->assertFalse( $sut->is_editing_post( 'page' ) );
		$this->assertFalse( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertFalse( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );

		$this->go_to( "/wp-admin/post-new.php?post_type=page" );
		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'page';

		$this->assertTrue( $sut->is_editing_post() );
		$this->assertFalse( $sut->is_editing_post( 'post' ) );
		$this->assertTrue( $sut->is_editing_post( 'page' ) );
		$this->assertFalse( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertFalse( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );
	}

	/**
	 * It should correctly identify new posts
	 *
	 * @test
	 */
	public function should_correctly_identify_new_posts() {
		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_new_post() );

		$this->go_to( "/wp-admin/post-new.php" );
		global $pagenow;
		$pagenow = 'post-new.php';

		$this->assertTrue( $sut->is_new_post() );
		$this->assertTrue( $sut->is_new_post( 'post' ) );
		$this->assertFalse( $sut->is_new_post( 'page' ) );
		$this->assertFalse( $sut->is_new_post( $post_id ) );
		$this->assertTrue( $sut->is_new_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_new_post( $second_post_id ) );
		$this->assertFalse( $sut->is_new_post( 2389 ) );
		$this->assertFalse( $sut->is_new_post( array( $post_id, $second_post_id ) ) );

		$this->go_to( "/wp-admin/post-new.php?post_type=page" );
		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'page';

		$this->assertTrue( $sut->is_new_post() );
		$this->assertFalse( $sut->is_new_post( 'post' ) );
		$this->assertTrue( $sut->is_new_post( 'page' ) );
		$this->assertFalse( $sut->is_new_post( $post_id ) );
		$this->assertTrue( $sut->is_new_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_new_post( $second_post_id ) );
		$this->assertFalse( $sut->is_new_post( 2389 ) );
		$this->assertFalse( $sut->is_new_post( array( $post_id, $second_post_id ) ) );
	}

	function setUp() {
		parent::setUp();
		global $pagenow;
		$pagenow = null;
		unset( $_GET['post_type'] );
	}

	/**
	 * It should allow reading a value from a request var
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_request_var() {
		$_REQUEST['__request_key__'] = '__request_value__';
		$_POST['__post_key__']       = '__post_value__';
		$_GET['__get_key__']         = '__get_value__';

		$context = tribe_context()->add_read_locations( [
			'__request__' => [ Context::REQUEST_VAR => '__request_key__' ],
			'__post__'    => [ Context::REQUEST_VAR => '__post_key__' ],
			'__get__'     => [ Context::REQUEST_VAR => '__get_key__' ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__request_value__', $context->get( '__request__', '__default__' ) );
		$this->assertEquals( '__post_value__', $context->get( '__post__', '__default__' ) );
		$this->assertEquals( '__get_value__', $context->get( '__get__', '__default__' ) );
		$this->assertEquals( '__default__', $context->get( '__unset__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from the global WP_Query object query vars
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_the_global_wp_query_object_query_vars() {
		global $wp_query;
		$wp_query->set( '__key__', '__value__' );

		$context = tribe_context()->add_read_locations( [
			'__query_var__' => [ Context::QUERY_VAR => '__key__' ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__query_var__', '__default__' ) );
	}
	//* tribe_option - get the value from a Tribe option.

	/**
	 * It should allow reading a value from a tribe_option
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_tribe_option() {
		tribe_update_option( '__key__', '__value__' );

		$context = tribe_context()->add_read_locations( [
			'__tribe_option__' => [ Context::TRIBE_OPTION => '__key__' ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__tribe_option__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from an option
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_an_option() {
		update_option( '__key__', '__value__' );

		$context = tribe_context()->add_read_locations( [
			'__option__' => [ Context::OPTION => '__key__' ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__option__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a transient
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_transient() {
		set_transient( '__key__', '__value__' );

		$context = tribe_context()->add_read_locations( [
			'__transient__' => [ Context::TRANSIENT => '__key__' ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__transient__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a contstant
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_contstant() {
		define( '__KEY__', '__value__' );

		$context = tribe_context()->add_read_locations( [
			'__constant__' => [ Context::CONSTANT => '__KEY__' ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__constant__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a global var
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_global_var() {
		global $__key__;
		$__key__ = '__value__';

		$context = tribe_context()->add_read_locations( [
			'__global__' => [ Context::GLOBAL_VAR => '__key__' ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__global__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a static property
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_static_property() {
		static::$__key__ = '__value__';

		$context = tribe_context()->add_read_locations( [
			'__static_prop__' => [ Context::STATIC_PROP => [ static::class => '__key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__static_prop__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a binding public prop
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_binding_public_prop() {
		$this->__public_key__ = '__value__';
		tribe_register( '__test__', $this );

		$context = tribe_context()->add_read_locations( [
			'__prop__' => [ Context::PROP => [ '__test__' => '__public_key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__prop__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a public static method
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_public_static_method() {
		static::$__static_method_return_value__ = '__value__';

		$context = tribe_context()->add_read_locations( [
			'__static_method__' => [ Context::STATIC_METHOD => [ static::class => '__test_static_method__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__static_method__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a binding public method
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_binding_public_method() {
		$this->__public_method_return_value__ = '__value__';
		tribe_register( '__test__', $this );

		$context = tribe_context()->add_read_locations( [
			'__method__' => [ Context::METHOD => [ '__test__' => '__public_method__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__method__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a function
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_function() {
		$context = tribe_context()->add_read_locations( [
			'__func__' => [ Context::FUNC => [ 'Tribe\\__context__test__function__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__func__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a closure
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_closure() {
		$context = tribe_context()->add_read_locations( [
			'__closure__' => [
				Context::FUNC => [
					function () {
						return '__value__';
					},
				],
			],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__closure__', '__default__' ) );
	}

	/**
	 * It should allow reading values from a number of locations
	 *
	 * @test
	 */
	public function should_allow_reading_values_from_a_number_of_locations() {
		$context = tribe_context()->add_read_locations( [
				'__seeking__' => [
					Context::GLOBAL_VAR => '__nope__',
					Context::QUERY_VAR  => [
						'__niet__',
						'try_here',
					],
					Context::FUNC       => [ 'some_non_existing_function', 'Tribe\\__context__test__function__' ],
				],
			]
		);

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__seeking__', '__default__' ) );
	}
}
