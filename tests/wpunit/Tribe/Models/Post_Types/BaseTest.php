<?php

namespace Tribe\Models\Post_Types;

use Tribe\Events\Collections\Lazy_Post_Collection;
use Tribe\Utils\Lazy_Collection;
use Tribe\Utils\Lazy_String;
use Tribe__Cache_Listener as Cache_Listener;

class Plain_Test_Object_d41d8cd98f00b204e9800998ecf8427e {
	public $one = 1;
	public $two = 2;
	public $three = 3;
}

class BaseTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should not cache properties when post is not decorated by any
	 *
	 * @test
	 */
	public function should_not_cache_properties_when_post_is_not_decorated_by_any() {
		$post = $this->factory()->post->create_and_get();
		$model_class = new class extends Base {
			protected function get_cache_slug() {
				return 'boxes';
			}

			protected function build_properties( $filter ) {
				// No property decoration happening here.
				return [];
			}
		};

		$model = $model_class::from_post( $post );
		$model->commit_to_cache();

		$cached = tribe_cache()->get( $model->get_properties_cache_key( 'raw' ), Cache_Listener::TRIGGER_SAVE_POST );
		$this->assertEquals( [], $cached );
	}

	/**
	 * It should cache scalar properties when post is decorated by only scalar properties
	 *
	 * @test
	 */
	public function should_cache_scalar_properties_when_post_is_decorated_by_only_scalar_properties() {
		$post = $this->factory()->post->create_and_get();
		$model_class = new class extends Base {
			protected function get_cache_slug() {
				return 'boxes';
			}

			protected function build_properties( $filter ) {
				return [
					'prop_1' => 'string value',
					'prop_2' => 23,
					'prop_3' => '2389',
				];
			}
		};

		$model = $model_class::from_post( $post );
		$model->commit_to_cache();

		$cached = tribe_cache()->get( $model->get_properties_cache_key( 'raw' ), Cache_Listener::TRIGGER_SAVE_POST );
		$this->assertEquals( [
			'prop_1' => 'string value',
			'prop_2' => 23,
			'prop_3' => '2389',
		], $cached );
	}

	/**
	 * It should cache serializable properties correctly when post is decorated with serializable properties
	 *
	 * @test
	 */
	public function should_cache_serializable_properties_correctly_when_post_is_decorated_with_serializable_properties() {
		$other_posts = static::factory()->post->create_many( 3 );
		$post = $this->factory()->post->create_and_get();
		$lazy_string = new Lazy_String( static function (): string {
			return 'string value';
		} );

		$lazy_collection = new Lazy_Collection( static function (): array {
			return [ 'hello', 'from', 'the', 'other', 'side' ];
		} );
		$lazy_post_collection = new Lazy_Post_Collection( static function () use ( $other_posts ): array {
			return array_map( 'get_post', $other_posts );
		} );
		$custom_object = new Plain_Test_Object_d41d8cd98f00b204e9800998ecf8427e();
		$model_class = new class() extends Base {
			public $lazy_string;
			public $lazy_collection;
			public $lazy_post_collection;
			public $custom_object;

			protected function get_cache_slug() {
				return 'boxes';
			}

			protected function build_properties( $filter ) {
				return [
					'prop_1' => 'string value',
					'prop_2' => 23,
					'prop_3' => '2389',
					'prop_4' => $this->lazy_string,
					'prop_5' => $this->lazy_collection,
					'prop_6' => $this->lazy_post_collection,
					'prop_7' => $this->custom_object,
				];
			}
		};

		$model = $model_class::from_post( $post );
		$model->lazy_string = $lazy_string;
		$model->lazy_collection = $lazy_collection;
		$model->lazy_post_collection = $lazy_post_collection;
		$model->custom_object = $custom_object;
		$model->commit_to_cache();

		$cached = tribe_cache()->get( $model->get_properties_cache_key( 'raw' ), Cache_Listener::TRIGGER_SAVE_POST );
		$this->assertEquals( [
			'prop_1'                      => 'string value',
			'prop_2'                      => 23,
			'prop_3'                      => '2389',
			Base::PRE_SERIALIZED_PROPERTY => [
				'prop_4' => serialize( $lazy_string ),
				'prop_5' => serialize( $lazy_collection ),
				'prop_6' => serialize( $lazy_post_collection ),
				'prop_7' => serialize( $custom_object ),
			]
		], $cached );
	}

	/**
	 * It should cache built-in object properties correctly
	 *
	 * @test
	 */
	public function should_cache_built_in_object_properties_correctly() {
		$post = $this->factory()->post->create_and_get();
		$model_class = new class() extends Base {
			public $object;

			protected function get_cache_slug() {
				return 'boxes';
			}

			protected function build_properties( $filter ) {
				return [
					'prop_1' => $this->object,
					'prop_2' => 23,
					'prop_3' => '2389',
				];
			}
		};

		$model = $model_class::from_post( $post );
		$object = (object) [ 'one' => 1, 'two' => 2, 'three' => 3 ];
		$model->object = $object;
		$model->commit_to_cache();

		$cached = tribe_cache()->get( $model->get_properties_cache_key( 'raw' ), Cache_Listener::TRIGGER_SAVE_POST );
		$this->assertEquals( [
			'prop_1' => $object,
			'prop_2' => 23,
			'prop_3' => '2389',
		], $cached );
	}

	/**
	 * It should drop object properties that are not built-in or serializable
	 *
	 * @test
	 */
	public function should_drop_object_properties_that_are_not_built_in_or_serializable() {
		$post = $this->factory()->post->create_and_get();
		$model_class = new class() extends Base {
			public $custom_object;

			protected function get_cache_slug() {
				return 'boxes';
			}

			protected function build_properties( $filter ) {
				return [
					'prop_1' => $this->custom_object,
					'prop_2' => 23,
					'prop_3' => '2389',
				];
			}
		};

		$model = $model_class::from_post( $post );
		$model->commit_to_cache();
		$bad_serializaion_object = new class implements \Serializable {
			public function serialize() {
				throw new \RuntimeException( 'Bad serialization' );
			}

			public function unserialize( $data ) {
				throw new \RuntimeException( 'Bad unserialization' );
			}
		};
		$model->custom_object = $bad_serializaion_object;

		$cached = tribe_cache()->get( $model->get_properties_cache_key( 'raw' ), Cache_Listener::TRIGGER_SAVE_POST );
		$this->assertEquals( [
			'prop_1' => null,
			'prop_2' => 23,
			'prop_3' => '2389',
		], $cached );
	}
}
