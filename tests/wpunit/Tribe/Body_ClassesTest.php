<?php

namespace Tribe;

use Body_Classes;

class ClassesTest extends \Codeception\TestCase\WPTestCase {

	public function create_classes() {
		$classes = new Body_Classes;

		$classes->add_classes( [ 'vampire', 'mummy', 'wolfman', 'chupacabra' ] );

		return $classes;
	}

	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( 'Body_Classes', Body_Classes::class );
	}

	/**
	 * It should properly detect a class is in the queue.
	 *
	 * @test
	 */
	public function it_should_detect_a_class_is_in_the_queue() {
		$classes = $this->create_classes();

		$this->assertTrue( $classes->class_exists( 'mummy' ) );
	}

	/**
	 * It should properly detect an enqueued class.
	 *
	 * @test
	 */
	public function it_should_detect_an_enqueued_class() {
		$classes = $this->create_classes();

		$this->assertTrue( $classes->class_is_enqueued( 'wolfman' ) );
	}

	/**
	 * It should properly detect a dequeued class.
	 *
	 * @test
	 */
	public function it_should_detect_a_dequeued_class() {
		$classes = $this->create_classes();

		$classes->dequeue_class( 'mummy' );

		$this->assertFalse( $classes->class_is_enqueued( 'mummy' ) );
	}

	/**
	 * It should remove a single class
	 *
	 * @test
	 */
	public function it_should_return_an_associative_array() {
		$classes = $this->create_classes();

		$class_array = $classes->get_classes();

		$this->assertTrue( array_key_exists( 'chupacabra', $class_array ) );
		$this->assertTrue( $class_array['chupacabra'] === true );
	}

	/**
	 * It should remove a single class
	 *
	 * @test
	 */
	public function it_should_return_an_array_of_strings() {
		$classes = $this->create_classes();

		$class_array = $classes->get_class_names();

		$this->assertTrue( in_array( 'chupacabra', $class_array ) );
	}

	/**
	 * It should add a single class
	 *
	 * @test
	 */
	public function it_should_add_a_single_class() {
		$classes = new Body_Classes;

		$classes->add_class( 'vampire' );

		$this->assertTrue( $classes->class_exists( 'vampire' ) );
	}

	/**
	 * It should add an array of classes.
	 *
	 * @test
	 */
	public function it_should_add_an_array_of_classes() {
		$classes = new Body_Classes;

		$classes->add_classes( [ 'vampire', 'mummy' ] );

		$this->assertTrue( $classes->class_exists( 'vampire' ) );
		$this->assertTrue( $classes->class_exists( 'mummy' ) );
	}

	/**
	 * It should remove a single class
	 *
	 * @test
	 */
	public function it_should_remove_a_single_class() {
		$classes = $this->create_classes();

		$classes->remove_class( 'chupacabra' );

		$this->assertFalse( $classes->class_exists( 'chupacabra' ) );
	}

	/**
	 * It should remove an array of classes.
	 *
	 * @test
	 */
	public function it_should_remove_an_array_of_classes() {
		$classes = $this->create_classes();

		$classes->remove_classes( [ 'vampire', 'mummy' ] );

		$this->assertFalse( $classes->class_exists( 'vampire' ) );
		$this->assertFalse( $classes->class_exists( 'mummy' ) );
	}

	/**
	 * It should properly dequeue a class without removing it from the queue.
	 *
	 * @test
	 */
	public function it_should_dequeue_a_class() {
		$classes = $this->create_classes();

		$classes->dequeue_class( 'chupacabra' );

		$this->assertTrue( $classes->class_exists[ 'chupacabra' ] );
		$this->assertFalse( $classes->class_is_enqueued[ 'chupacabra' ] );
	}

	/**
	 * It should properly enqueue a class.
	 *
	 * @test
	 */
	public function it_should_enqueue_a_class() {
		$classes = $this->create_classes();

		$classes->dequeue_class( 'wolfman' );

		$classes->enqueue_class( 'wolfman' );
		$this->assertTrue( $classes->class_is_enqueued[ 'wolfman' ] );
	}
}
