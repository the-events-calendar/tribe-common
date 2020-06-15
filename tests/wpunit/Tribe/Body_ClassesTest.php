<?php

namespace Tribe;

use Tribe\Utils\Body_Classes;

class Body_ClassesTest extends \Codeception\TestCase\WPTestCase {

	protected $class_object;

	function setUp() {
		parent::setUp();


		$this->class_object = new Body_Classes();
		add_filter( 'tribe_body_class_should_add_to_queue', '__return_true' );
		add_filter( 'tribe_body_classes_should_add', '__return_true' );
	}

	public function create_classes() {
		$this->class_object->add_classes( [ 'vampire', 'mummy', 'wolfman', 'chupacabra' ] );
	}

	public function create_admin_classes() {
		$this->class_object->add_classes( [ 'vampire', 'mummy', 'wolfman', 'chupacabra' ], 'admin' );
	}

	public function create_mixed_classes() {
		$this->class_object->add_classes( [ 'vampire', 'mummy', 'wolfman', 'chupacabra' ] );
		$this->class_object->add_classes( [ 'van-helsing', 'invisible-man', 'frankenstein', 'ygor' ], 'admin' );
	}

	/**
	 * It should properly detect a class is in the queue.
	 *
	 * @test
	 */
	public function it_should_detect_a_class_is_in_the_queue() {
		$this->create_classes();

		$this->assertTrue( $this->class_object->class_exists( 'mummy' ) );
	}

	/**
	 * It should properly detect an enqueued class.
	 *
	 * @test
	 */
	public function it_should_detect_an_enqueued_class() {
		$this->create_classes();

		$this->assertTrue( $this->class_object->class_is_enqueued( 'wolfman' ) );
	}

	/**
	 * It should properly detect a dequeued class.
	 *
	 * @test
	 */
	public function it_should_detect_a_dequeued_class() {
		$this->create_classes();

		$this->class_object->dequeue_class( 'mummy' );

		codecept_debug( $this->class_object->get_classes_for_queue() );

		$this->assertFalse( $this->class_object->class_is_enqueued( 'mummy' ) );
	}

	/**
	 * It should remove a single class
	 *
	 * @test
	 */
	public function it_should_return_an_associative_array() {
		$this->create_classes();

		$class_array = $this->class_object->get_classes();

		$this->assertTrue( array_key_exists( 'chupacabra', $class_array ) );
		$this->assertTrue( $class_array['chupacabra'] === true );
	}

	/**
	 * It should remove a single class
	 *
	 * @test
	 */
	public function it_should_return_an_array_of_strings() {
		$this->create_classes();

		$class_array = $this->class_object->get_class_names();

		$this->assertTrue( in_array( 'chupacabra', $class_array ) );
	}

	/**
	 * It should add a single class
	 *
	 * @test
	 */
	public function it_should_add_a_single_class() {
		$this->class_object->add_class( 'vampire' );

		$this->assertTrue( $this->class_object->class_exists( 'vampire' ) );
	}

	/**
	 * It should add an array of classes.
	 *
	 * @test
	 */
	public function it_should_add_an_array_of_classes() {
		$this->class_object->add_classes( [ 'vampire', 'mummy' ] );

		$this->assertTrue( $this->class_object->class_exists( 'vampire' ) );
		$this->assertTrue( $this->class_object->class_exists( 'mummy' ) );
	}

	/**
	 * It should remove a single class
	 *
	 * @test
	 */
	public function it_should_remove_a_single_class() {
		$this->create_classes();

		$this->class_object->remove_class( 'chupacabra' );

		$this->assertFalse( $this->class_object->class_exists( 'chupacabra' ) );
	}

	/**
	 * It should remove an array of classes.
	 *
	 * @test
	 */
	public function it_should_remove_an_array_of_classes() {
		$this->create_classes();

		$this->class_object->remove_classes( [ 'vampire', 'mummy' ] );

		$this->assertFalse( $this->class_object->class_exists( 'vampire' ) );
		$this->assertFalse( $this->class_object->class_exists( 'mummy' ) );
	}

	/**
	 * It should properly dequeue a class without removing it from the queue.
	 *
	 * @test
	 */
	public function it_should_dequeue_a_class() {
		$this->create_classes();

		$this->class_object->dequeue_class( 'chupacabra' );

		$this->assertTrue( $this->class_object->class_exists( 'chupacabra' ) );
		$this->assertFalse( $this->class_object->class_is_enqueued( 'chupacabra' ) );
	}

	/**
	 * It should properly enqueue a class.
	 *
	 * @test
	 */
	public function it_should_enqueue_a_class() {
		$this->create_classes();

		$this->class_object->dequeue_class( 'wolfman' );

		$this->class_object->enqueue_class( 'wolfman' );
		$this->assertTrue( $this->class_object->class_is_enqueued( 'wolfman' ) );
	}
}
