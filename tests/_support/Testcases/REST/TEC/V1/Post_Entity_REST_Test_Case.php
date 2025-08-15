<?php
/**
 * Base test case for Post Entity REST API endpoints.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Testcases\REST\TEC\V1
 */

namespace TEC\Common\Tests\Testcases\REST\TEC\V1;

use TEC\Common\REST\TEC\V1\Contracts\Post_Entity_Endpoint_Interface as Post_Entity_Endpoint;
use TEC\Common\REST\TEC\V1\Collections\RequestBodyCollection;
use TEC\Common\REST\TEC\V1\Contracts\Parameter;
use stdClass;
use ReflectionClass;
use Closure;
use TEC\Common\REST\TEC\V1\Parameter_Types\Integer;
use TEC\Common\REST\TEC\V1\Parameter_Types\Text;
use TEC\Common\REST\TEC\V1\Parameter_Types\Array_Of_Type;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;
use Tribe__Repository as Base_Repo;

/**
 * Class Post_Entity_REST_Test_Case
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Testcases\REST\TEC\V1
 */
abstract class Post_Entity_REST_Test_Case extends REST_Test_Case {
	/**
	 * The endpoint instance.
	 *
	 * @var Post_Entity_Endpoint
	 */
	protected $endpoint;

	abstract public function test_get_formatted_entity();

	abstract public function test_instance_of_orm();

	abstract public function test_get_model_class();

	/**
	 * Test that undefined parameters are filtered out by get_sanitized_params_from_schema.
	 */
	public function test_get_sanitized_params_from_schema_filters_undefined_parameters() {
		$operations = [];
		if ( $this->is_creatable() ) {
			$operations[] = 'create';
		}
		if ( $this->is_updatable() ) {
			$operations[] = 'update';
		}

		if ( empty( $operations ) ) {
			return;
		}

		// Make the protected method accessible for testing
		$reflection = new ReflectionClass( $this->endpoint );
		$method     = $reflection->getMethod( 'get_sanitized_params_from_schema' );
		$method->setAccessible( true );

		$php_injection = new stdClass();
		$php_injection->php_injection = true;
		$php_injection->string = 'string';

		$php_injection = serialize( $php_injection );

		foreach ( $operations as $operation ) {
			$schema_method = "{$operation}_schema";

			/** @var RequestBodyCollection $collection */
			$collection = $this->endpoint->$schema_method()->get_request_body();

			$valid_body = [];

			if ( 'update' === $operation ) {
				$valid_body['id'] = 1;
			}

			$injected                  = false;
			$needs_sanitization_string = false;
			$needs_sanitization_int    = false;
			$needs_sanitization_array  = false;
			$before_injection          = null;

			/** @var Parameter $parameter */
			foreach ( $collection->to_props_array() as $parameter ) {
				if ( ! $needs_sanitization_string && $parameter instanceof Text ) {
					$valid_body[ $parameter->get_name() ] = (int) $parameter->get_example();

					$needs_sanitization_string = $parameter->get_name();
					continue;
				}

				if ( ! $needs_sanitization_int && $parameter instanceof Integer ) {
					$valid_body[ $parameter->get_name() ] = (float) $parameter->get_example();

					$needs_sanitization_int = $parameter->get_name();
					continue;
				}

				if ( ! $needs_sanitization_array && $parameter instanceof Array_Of_Type && ( ( $parameter->get_an_item() instanceof Text && empty( $parameter::get_subitem_format()['format'] ) ) || $parameter->get_an_item() instanceof Integer ) ) {
					$example    = $parameter->get_example();
					$first_elem = array_shift( $example );
					$first_elem = is_string( $first_elem ) ? (int) $first_elem : (string) $first_elem;

					$valid_body[ $parameter->get_name() ] = array_merge( [ $first_elem ], $example );

					$needs_sanitization_array = $parameter->get_name();
					continue;
				}

				if ( ! $injected && $parameter instanceof Text && empty( $parameter::get_subitem_format()['format'] ) ) {
					$before_injection = $parameter->get_example();
					$valid_body[ $parameter->get_name() ] = $php_injection;

					$injected = $parameter->get_name();
					continue;
				}

				$valid_body[ $parameter->get_name() ] = $parameter->get_example();
			}

			$this->assertNotFalse( $needs_sanitization_string );
			$this->assertNotFalse( $needs_sanitization_int );
			$this->assertNotFalse( $needs_sanitization_array );
			$this->assertNotFalse( $injected );

			$whole_body = array_merge(
				$valid_body,
				[
					'random_field'    => 'not in schema',
					'extra_data'      => [ 'nested' => 'value' ],
					'_internal_field' => 'should not pass through',
					'int'             => 1,
					'float'           => 1.1,
					'boolean'         => true,
					'array'           => [ 'item1', 'item2' ],
					'object'          => [ 'property1' => 'value1', 'property2' => 'value2' ],
					'injected'        => $php_injection,
				]
			);

			try {
				// Will fail because of attempt to inject a serialized object.
				$sanitized = $method->invoke( $this->endpoint, $operation, $whole_body );
			} catch ( InvalidRestArgumentException $e ) {
				$this->assertEquals( $e->getMessage(), sprintf( 'Property %s is invalid', $injected ) );
				$this->assertEquals( $e->get_argument(), $injected );
			}

			$whole_body[ $injected ] = $before_injection;

			$sanitized = $method->invoke( $this->endpoint, $operation, $whole_body );

			$this->assertArrayNotHasKey( 'random_field', $sanitized );
			$this->assertArrayNotHasKey( 'extra_data', $sanitized );
			$this->assertArrayNotHasKey( '_internal_field', $sanitized );
			$this->assertArrayNotHasKey( 'int', $sanitized );
			$this->assertArrayNotHasKey( 'float', $sanitized );
			$this->assertArrayNotHasKey( 'boolean', $sanitized );
			$this->assertArrayNotHasKey( 'array', $sanitized );
			$this->assertArrayNotHasKey( 'object', $sanitized );
			$this->assertArrayNotHasKey( 'injected', $sanitized );

			foreach ( array_keys( $valid_body ) as $key ) {
				$this->assertArrayHasKey( $key, $sanitized );
			}

			$this->assertIsString( $sanitized[ $needs_sanitization_string ] );
			$this->assertIsInt( $sanitized[ $needs_sanitization_int ] );
			$this->assertIsString( $sanitized[ $injected ] );

			$this->assertNotSame( $valid_body[ $needs_sanitization_string ], $sanitized[ $needs_sanitization_string ] );
			$this->assertNotSame( $valid_body[ $needs_sanitization_int ], $sanitized[ $needs_sanitization_int ] );
			$this->assertNotSame( $valid_body[ $needs_sanitization_array ], $sanitized[ $needs_sanitization_array ] );

			$serialized_string = @unserialize( $sanitized[ $injected ] );

			$this->assertNotInstanceOf( stdClass::class, $serialized_string );
		}
	}

	public function test_validate_status() {
		$this->assertTrue( $this->endpoint->validate_status( 'publish' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'draft' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'pending' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'private' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'future' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'inherit' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'any' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'random' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'random,publish' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,random' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,trash' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,draft' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,pending' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,private' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,future' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,draft,pending,private,future' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any,random' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any,random,trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any,random,trash,inherit' ) );
	}

	public function test_each_parameter_affects_the_entity() {
		$update_schema = $this->is_updatable() ? $this->endpoint->update_schema() : null;
		$create_schema = $this->is_creatable() ? $this->endpoint->create_schema() : null;

		if ( ! $create_schema && ! $update_schema ) {
			return;
		}

		if ( $create_schema && $update_schema ) {
			$this->assertSame(
				$create_schema->get_request_body()->to_array(),
				$update_schema->get_request_body()->to_array()
			);
		}

		if ( $create_schema ) {
			$body = $create_schema->get_request_body()->to_array()['content']['application/json'];
		}

		if ( $update_schema ) {
			$body = $update_schema->get_request_body()->to_array()['content']['application/json'];
		}

		$this->assertArrayHasKey( 'schema', $body );
		$this->assertArrayHasKey( 'example', $body );

		$example = $body['example'];

		if ( isset( $body['schema']['$ref'] ) ) {
			$definition = $this->get_instance_from_ref( $body['schema']['$ref'] )->get_documentation();
			$properties = $this->get_props_from_doc( $definition );

			$body['schema'] = [ 'properties' => $properties ];
		}

		$properties = $body['schema']['properties'];

		$orm = $this->endpoint->get_orm();

		$entity_ids = [];

		wp_set_current_user( 1 );
		$entity_ids[] = $orm->set_args( $example )->create()->ID;
		wp_set_current_user( 0 );

		foreach ( $properties as $property => $data ) {
			if ( ! isset( $example[ $property ] ) ) {
				continue;
			}

			if ( 'date' === $property ) {
				continue;
			}

			$new_value = $this->modify_value( $data );

			if ( null === $new_value ) {
				continue;
			}

			$example[ $property ] = $new_value;

			wp_set_current_user( 1 );
			$new_entity_id = $orm->set_args( $example )->create()->ID;
			wp_set_current_user( 0 );

			$new_formatted_entity = $this->data_cleaner( $this->endpoint->get_formatted_entity( $this->endpoint->get_orm()->by_args( [ 'id' => $new_entity_id, 'status' => 'any' ] )->first() ) );

			// Assert that the new entity is different than all the previous ones.
			foreach ( $entity_ids as $entity_id ) {
				$previous_formatted_entity = $this->data_cleaner( $this->endpoint->get_formatted_entity( $this->endpoint->get_orm()->by_args( [ 'id' => $entity_id, 'status' => 'any' ] )->first() ) );

				$this->assertNotEquals(
					$previous_formatted_entity,
					$new_formatted_entity,
					'Changed property ' . $property . ' should have affected the entity.'
				);
			}

			$entity_ids[] = $new_entity_id;
		}
	}

	public function test_update_handles_save_failure() {
		if ( ! $this->is_updatable() ) {
			return;
		}

		$this->set_class_fn_return( Base_Repo::class, 'save', false );

		$example = $this->get_example_create_data();
		unset( $example['id'] );

		$orm = $this->endpoint->get_orm();

		wp_set_current_user( 1 );
		$entity_id = $orm->set_args( $example )->create()->ID;
		$this->assert_endpoint( sprintf( $this->endpoint->get_base_path(), $entity_id ), 'PUT', 500, [ 'title' => 'Updated Title' ] );
	}

	public function test_update_handles_entity_not_found_after_save() {
		if ( ! $this->is_updatable() ) {
			return;
		}

		$this->set_class_fn_return( Base_Repo::class, 'first', null );

		$example = $this->get_example_create_data();
		unset( $example['id'] );

		$orm = $this->endpoint->get_orm();

		wp_set_current_user( 1 );
		$entity_id = $orm->set_args( $example )->create()->ID;
		$this->assert_endpoint( sprintf( $this->endpoint->get_base_path(), $entity_id ), 'PUT', 500, [ 'title' => 'Updated Title' ] );
	}

	private function modify_value( array $data ) {
		if ( ! isset( $data['type'] ) ) {
			return null;
		}

		if ( ! empty( $data['readOnly'] ) ) {
			return null;
		}

		if ( 'Europe/Athens' === $data['example'] ) {
			return 'Europe/Berlin';
		}

		if ( 'string' === $data['type'] ) {
			if ( ! empty( $data['enum'] ) ) {
				$result = $data['enum'][ count( $data['enum'] ) - 1 ];

				return 'unlimited' === $result ? 'capped' : $result;
			}

			if ( isset( $data['format'] ) && 'date-time' === $data['format'] ) {
				return date( 'Y-m-d H:i:s', strtotime( $data['example'] ) + 3 * DAY_IN_SECONDS + 4 * HOUR_IN_SECONDS + 9 * MINUTE_IN_SECONDS );
			}

			return str_replace( [ '6', 'e', '$10', '123' ], [ '7', 'p', '$13', '098' ], $data['example'] );
		}

		if ( 'boolean' === $data['type'] ) {
			return ! $data['example'];
		}

		if ( 'integer' === $data['type'] ) {
			return $data['example'] + 3;
		}

		if ( 'number' === $data['type'] ) {
			return $data['example'] + 5;
		}

		if ( 'array' === $data['type'] ) {
			return array_map( fn( $item ) => $this->modify_value( [ 'example' => $item, 'type' => $data['items']['type'] ] ), $data['example'] );
		}

		if ( 'object' === $data['type'] ) {
			$new_example = [];
			foreach ( $data['properties'] as $property => $property_data ) {
				$new_example[ $property ] = $this->modify_value( $property_data );

				if ( null === $new_example[ $property ] ) {
					continue;
				}

				return $new_example;
			}
		}
	}

	private function data_cleaner( array $data ): array {
		$good_keys = [
			'date',
			'status',
			'slug',
			'title',
			'content',
			'excerpt',
			'author',
			'start_date',
			'start_date_utc',
			'end_date',
			'end_date_utc',
			'dates',
			'timezone',
			'duration',
			'cost',
			'multiday',
			'is_past',
			'is_now',
			'all_day',
			'tribe_events_cat',
			'tribe_events_tag',
		];

		return array_intersect_key( $data, array_flip( $good_keys ) );
	}

	/**
	 * @dataProvider different_user_roles_provider
	 */
	public function test_create_responses( Closure $fixture ) {
		if ( ! $this->is_creatable() ) {
			return;
		}
		$request_body = $this->endpoint->create_schema()->get_request_body()->to_array()['content']['application/json'];

		$example = $this->get_example_create_data();
		unset( $example['id'] );

		if ( isset( $request_body['schema']['$ref'] ) ) {
			$definition = $this->get_instance_from_ref( $request_body['schema']['$ref'] )->get_documentation();
			$properties = $this->get_props_from_doc( $definition );

			$request_body['schema'] = [ 'properties' => $properties ];
		}

		$properties = $request_body['schema']['properties']->to_array();

		$orm = $this->endpoint->get_orm();

		$event_cat_term_1 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 1' ] );
		$event_cat_term_2 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 2' ] );
		$event_tag_term_1 = self::factory()->term->create( [ 'taxonomy' => 'post_tag', 'name' => 'Tag 1' ] );

		$organizer_1 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 1' ] );
		$organizer_2 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 2' ] );

		$venue_1 = self::factory()->post->create( [ 'post_type' => 'tribe_venue', 'post_title' => 'Venue 1' ] );

		// Update example with valid IDs
		if ( isset( $example['tribe_events_cat'] ) ) {
			$example['tribe_events_cat'] = [ $event_cat_term_1, $event_cat_term_2 ];
		}
		if ( isset( $example['tags'] ) ) {
			$example['tags'] = [ $event_tag_term_1 ];
		}
		if ( isset( $example['organizers'] ) ) {
			$example['organizers'] = [ $organizer_1, $organizer_2 ];
		}
		if ( isset( $example['venues'] ) || isset( $example['venue'] ) ) {
			if ( isset( $example['venues'] ) ) {
				// venues should be an array
				$example['venues'] = [ $venue_1 ];
			} else {
				// venue should be a scalar (backwards compatibility)
				$example['venue'] = $venue_1;
			}
		}

		$fixture();

		$user_is_logged_in = is_user_logged_in();
		$user_can_create = $user_is_logged_in && current_user_can( get_post_type_object( $this->endpoint->get_post_type() )->cap->create_posts );

		// Test creating with full example data
		$response = $this->assert_endpoint(
			$this->endpoint->get_base_path(),
			'POST',
			$user_can_create ? 201 : ( is_user_logged_in() ? 403 : 401 ),
			$example
		);

		if ( $user_can_create ) {
			$this->assertIsArray( $response );
			$this->assertArrayHasKey( 'id', $response );

			// Verify the created entity exists
			$created_entity = $orm->by_args( [ 'id' => $response['id'], 'status' => 'any' ] )->first();
			$this->assertNotNull( $created_entity );

			// Clean up
			wp_delete_post( $response['id'], true );
		}

		// Test creating with minimal required fields only
		$minimal_example = [];
		foreach ( $properties as $property => $data ) {
			if ( ! empty( $data['required'] ) ) {
				$minimal_example[ $property ] = $example[ $property ] ?? $data['example'];
			}
		}

		if ( ! empty( $minimal_example ) ) {
			$response = $this->assert_endpoint(
				$this->endpoint->get_base_path(),
				'POST',
				$user_can_create ? 201 : ( is_user_logged_in() ? 403 : 401 ),
				$minimal_example
			);

			if ( $user_can_create ) {
				$this->assertIsArray( $response );
				$this->assertArrayHasKey( 'id', $response );

				// Verify the created entity exists
				$created_entity = $orm->by_args( [ 'id' => $response['id'], 'status' => 'any' ] )->first();
				$this->assertNotNull( $created_entity );

				// Clean up
				wp_delete_post( $response['id'], true );
			}
		}

		// Test creating with invalid data (missing required fields)
		$invalid_example = $example;
		foreach ( $properties as $property => $data ) {
			if ( ! empty( $data['required'] ) ) {
				unset( $invalid_example[ $property ] );
				break; // Remove just one required field
			}
		}

		if ( count( $invalid_example ) < count( $example ) ) {
			$this->assert_endpoint(
				$this->endpoint->get_base_path(),
				'POST',
				400,
				$invalid_example
			);
		}
	}

	/**
	 * @dataProvider different_user_roles_provider
	 */
	public function test_update_responses( Closure $fixture ) {
		if ( ! $this->is_updatable() ) {
			return;
		}
		$request_body = $this->endpoint->update_schema()->get_request_body()->to_array()['content']['application/json'];

		$example = $this->get_example_create_data();
		unset( $example['id'] );

		if ( isset( $request_body['schema']['$ref'] ) ) {
			$definition = $this->get_instance_from_ref( $request_body['schema']['$ref'] )->get_documentation();
			$properties = $this->get_props_from_doc( $definition );

			$request_body['schema'] = [ 'properties' => $properties ];
		}

		$properties = $request_body['schema']['properties']->to_array();

		$orm = $this->endpoint->get_orm();

		$event_cat_term_1 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 1' ] );
		$event_cat_term_2 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 2' ] );
		$event_cat_term_3 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 3' ] );
		$event_tag_term_1 = self::factory()->term->create( [ 'taxonomy' => 'post_tag' ] );
		$event_tag_term_2 = self::factory()->term->create( [ 'taxonomy' => 'post_tag' ] );
		$event_tag_term_3 = self::factory()->term->create( [ 'taxonomy' => 'post_tag' ] );

		$event_1 = self::factory()->post->create( [ 'post_title' => 'Event 1' ] );
		$event_2 = self::factory()->post->create( [ 'post_title' => 'Event 2' ] );

		$organizer_1 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 1' ] );
		$organizer_2 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 2' ] );
		$organizer_3 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 3' ] );

		$venue_1 = self::factory()->post->create( [ 'post_type' => 'tribe_venue', 'post_title' => 'Venue 1' ] );
		$venue_2 = self::factory()->post->create( [ 'post_type' => 'tribe_venue', 'post_title' => 'Venue 2' ] );

		if ( isset( $example['tribe_events_cat'] ) ) {
			$example['tribe_events_cat'] = [ $event_cat_term_1, $event_cat_term_2 ];
		}

		if ( isset( $example['tags'] ) ) {
			$example['tags'] = [ $event_tag_term_1 ];
		}

		if ( isset( $example['organizers'] ) ) {
			$example['organizers'] = [ $organizer_1, $organizer_2 ];
		}

		if ( isset( $example['venues'] ) ) {
			$example['venues'] = [ $venue_1 ];
		}

		if ( isset( $example['event'] ) ) {
			$example['event'] = $event_1;
		}

		wp_set_current_user( 1 );
		$entity_id = $orm->set_args( $example )->create()->ID;
		wp_set_current_user( 0 );

		$fixture();

		$user_is_logged_in = is_user_logged_in();

		$property_keys = array_keys( $properties );
		// Sort the properties so that keys that contain the `end` word come before keys that have the `start` word.
		// Sort function: end values first, then start values, then everything else
		usort(
			$property_keys,
			function ( $a, $b ) {
				$get_priority = function ( $value ) {
					if ( strpos( $value, 'end' ) !== false ) {
						return 1;
					}
					if ( strpos( $value, 'start' ) !== false ) {
						return 2;
					}
					return 3;
				};

				return $get_priority( $a ) - $get_priority( $b );
			}
		);
		// Place the all day flag at the end.
		$all_day_key = array_search( 'all_day', $property_keys );
		if ( false !== $all_day_key ) {
			unset( $property_keys[ $all_day_key ] );
			$property_keys[] = 'all_day';
		}

		$timezone_key = array_search( 'timezone', $property_keys );
		if ( false !== $timezone_key ) {
			unset( $property_keys[ $timezone_key ] );
			$property_keys[] = 'timezone';
		}

		$sale_price_start_date_key = array_search( 'sale_price_start_date', $property_keys );
		if ( false !== $sale_price_start_date_key ) {
			unset( $property_keys[ $sale_price_start_date_key ] );
			$property_keys = array_merge( [ 'sale_price_start_date' ], $property_keys );
		}

		$sale_price_end_date_key = array_search( 'sale_price_end_date', $property_keys );
		if ( false !== $sale_price_end_date_key ) {
			unset( $property_keys[ $sale_price_end_date_key ] );
			$property_keys = array_merge( [ 'sale_price_end_date' ], $property_keys );
		}

		$stock_mode_key = array_search( 'stock_mode', $property_keys );
		if ( false !== $stock_mode_key ) {
			unset( $property_keys[ $stock_mode_key ] );
			$property_keys[] = 'stock_mode';
		}

		$sale_price_key = array_search( 'sale_price', $property_keys );
		if ( false !== $sale_price_key ) {
			unset( $property_keys[ $sale_price_key ] );
			$property_keys = array_merge( [ 'sale_price' ], $property_keys );
		}

		$price_key = array_search( 'price', $property_keys );
		if ( false !== $price_key ) {
			unset( $property_keys[ $price_key ] );
			$property_keys = array_merge( [ 'price' ], $property_keys );
		}

		$new_properties = [];

		foreach ( $property_keys as $property ) {
			$new_properties[ $property ] = $properties[ $property ];
		}

		$properties = $new_properties;

		foreach ( $properties as $property => $data ) {
			if ( ! isset( $example[ $property ] ) ) {
				continue;
			}

			if ( in_array( $property, [ 'date_gmt', 'end_date_utc', 'start_date_utc', 'event_capacity' ], true ) ) {
				continue;
			}

			if ( isset( $data['format'] ) && 'date-time' === $data['format'] ) {
				$data['example'] = date( 'Y-m-d H:i:s', strtotime( $data['example'] ) );
			}

			$old_value = $data['example'];

			if ( in_array( $property, [ 'tribe_events_cat', 'tags', 'organizers', 'venues', 'event', 'capacity', 'sale_price' ], true ) ) {
				switch ( $property ) {
					case 'tribe_events_cat':
						$new_value = [ $event_cat_term_3 ];
						$old_value = $example['tribe_events_cat'];
						break;
					case 'tags':
						$new_value = [ $event_tag_term_1, $event_tag_term_2, $event_tag_term_3 ];
						$old_value = $example['tags'];
						break;
					case 'organizers':
						$new_value = [ $organizer_3 ];
						$old_value = $example['organizers'];
						break;
					case 'venues':
						$new_value = [ $venue_2 ];
						$old_value = $example['venues'];
						break;
					case 'event':
						$new_value = $event_2;
						$old_value = $example['event'];
						break;
					case 'capacity':
						$new_value = 106;
						$old_value = $stock_new_value ?? $old_value;
						break;
					case 'sale_price':
						$new_value = 16.45;
						break;
				}
			} else {
				$new_value = $this->modify_value( $data );
			}

			if ( null === $new_value ) {
				continue;
			}

			$this->assertNotSame( $old_value, $new_value, 'Old and new values should not be the same for ' . $property );

			$params = [ $property => $new_value ];

			$user_can_update = $user_is_logged_in && current_user_can( get_post_type_object( $this->endpoint->get_post_type() )->cap->edit_post, $entity_id );

			$fresh_entity = $this->normalize_entity( $orm->by_args( [ 'id' => $entity_id, 'status' => 'any' ] )->first() );

			$actual_property = $orm->get_update_fields_aliases()[ $property ] ?? $property;

			$using_property = isset( $fresh_entity->{$property} ) ? $property : $actual_property;

			// Skip sticky property for non-event post types as it's not supported
			if ( 'sticky' === $property && ! in_array( $this->endpoint->get_post_type(), [ 'tribe_events' ], true ) ) {
				continue;
			}

			$this->assertSame( $old_value, $fresh_entity->{$using_property}, 'The property ' . $actual_property . ' / ' . $property . ' should be as expected.' );

			$this->assert_endpoint( sprintf( $this->endpoint->get_base_path(), $entity_id ), 'PUT', $user_can_update ? 200 : ( is_user_logged_in() ? 403 : 401 ), $params );

			wp_cache_flush();

			$fresh_entity = $this->normalize_entity( $orm->by_args( [ 'id' => $entity_id, 'status' => 'any' ] )->first() );

			if ( $user_can_update ) {
				// Special case ! We don't allow updating the event of a ticket. This is set in stone.
				if ( in_array( $this->endpoint->get_post_type(), [ 'tec_tc_ticket', 'product' ], true ) && 'event' === $property ) {
					$this->assertSame( $old_value, $fresh_entity->{$using_property}, 'The property ' . $actual_property . ' / ' . $property . ' should not have been updated.' );
					continue;
				}

				if ( 'stock' === $property ) {
					$stock_new_value = $new_value;
				}

				$this->assertSame( $new_value, $fresh_entity->{$using_property}, 'The property ' . $actual_property . ' / ' . $property . ' should have been updated.' );
			} else {
				$this->assertSame( $old_value, $fresh_entity->{$using_property}, 'The property ' . $actual_property . ' / ' . $property . ' should not have been updated.' );
			}
		}

		wp_delete_post( $entity_id, true );
	}

	/**
	 * @dataProvider different_user_roles_provider
	 */
	public function test_delete_responses( Closure $fixture ) {
		if ( ! $this->is_deletable() ) {
			return;
		}

		$example = $this->get_example_create_data();
		unset( $example['id'] );

		$orm = $this->endpoint->get_orm();

		wp_set_current_user( 1 );
		$entity_id = $orm->set_args( $example )->create()->ID;
		wp_set_current_user( 0 );

		$fixture();

		$user_can_delete = is_user_logged_in() && current_user_can( get_post_type_object( $this->endpoint->get_post_type() )->cap->delete_post, $entity_id );
		$this->assert_endpoint( sprintf( $this->endpoint->get_base_path(), $entity_id ), 'DELETE', $user_can_delete ? 200 : ( is_user_logged_in() ? 403 : 401 ), [ 'force' => true ] );

		if ( $user_can_delete ) {
			$this->assertNull( get_post( $entity_id ) );
		} else {
			$this->assertNotNull( get_post( $entity_id ) );
		}
	}

	private function normalize_entity( $entity ) {
		foreach ( (array) $entity as $prop => $data ) {
			if ( ! is_object( $entity->{$prop} ) ) {
				continue;
			}

			if ( is_callable( [ $entity->{$prop}, '__toString' ] ) ) {
				$entity->{$prop} = (string) $entity->{$prop};
				continue;
			}

			if ( is_callable( [ $entity->{$prop}, 'all' ] ) ) {
				$entity->{$prop} = $entity->{$prop}->all();
				continue;
			}
		}

		$entity->post_author      = (int) $entity->post_author;
		$entity->featured_media   = (int) $entity->featured_media;
		$entity->post_tag         = wp_list_pluck( wp_get_post_tags( $entity->ID ), 'term_id' );
		$entity->tribe_events_cat = wp_list_pluck( wp_get_post_terms( $entity->ID, 'tribe_events_cat' ), 'term_id' );
		if ( isset( $entity->duration ) ) {
			$entity->duration = (int) $entity->duration;
		}

		if ( isset( $entity->organizers ) ) {
			$entity->organizers = is_object( $entity->organizers['0'] ) ? wp_list_pluck( $entity->organizers, 'ID' ) : $entity->organizers;
		}

		if ( isset( $entity->venues ) ) {
			$entity->venues = wp_list_pluck( $entity->venues, 'ID' );
		}

		$entity->excerpt = trim( $entity->post_excerpt );
		$entity->content = trim( $entity->post_content );

		if ( isset( $entity->_tribe_ticket_show_description ) ) {
			$entity->_tribe_ticket_show_description = (bool) $entity->_tribe_ticket_show_description;
		}

		// Handle title property - some post types use post_title instead of title
		if ( isset( $entity->title ) ) {
			$entity->title = str_replace( 'Private: ', '', $entity->title );
		} elseif ( isset( $entity->post_title ) ) {
			// For post types that don't have a title property, set it from post_title
			$entity->title = str_replace( 'Private: ', '', $entity->post_title );
		}

		if ( isset( $entity->_sale_price_dates_from ) ) {
			$entity->_sale_price_dates_from = date( 'Y-m-d', is_numeric( $entity->_sale_price_dates_from ) ? $entity->_sale_price_dates_from : strtotime( $entity->_sale_price_dates_from ) );
			$entity->sale_price_start_date  = $entity->_sale_price_dates_from;
		}

		if ( isset( $entity->_sale_price_dates_to ) ) {
			$entity->_sale_price_dates_to = date( 'Y-m-d', is_numeric( $entity->_sale_price_dates_to ) ? $entity->_sale_price_dates_to : strtotime( $entity->_sale_price_dates_to ) );
			$entity->sale_price_end_date  = $entity->_sale_price_dates_to;
		}

		if ( isset( $entity->_VenueLat ) ) {
			$entity->_VenueLat = (float) $entity->_VenueLat;
		}

		if ( isset( $entity->_VenueLng ) ) {
			$entity->_VenueLng = (float) $entity->_VenueLng;
		}

		if ( isset( $entity->_tec_tickets_commerce_event ) ) {
			$entity->_tec_tickets_commerce_event = (int) $entity->_tec_tickets_commerce_event;
		}

		if ( isset( $entity->_tribe_wooticket_for_event ) ) {
			$entity->_tribe_wooticket_for_event = (int) $entity->_tribe_wooticket_for_event;
		}

		if ( isset( $entity->event_capacity ) ) {
			$entity->event_capacity = (int) $entity->event_capacity;
		}

		if ( isset( $entity->capacity ) ) {
			$entity->capacity = (int) $entity->capacity;
		}

		if ( isset( $entity->_tribe_ticket_capacity ) ) {
			$entity->_tribe_ticket_capacity = (int) $entity->_tribe_ticket_capacity;
		}

		if ( ! empty( $entity->price ) && ! empty( $entity->regular_price ) ) {
			$entity->price = $entity->regular_price;
		}

		return $entity;
	}

	protected function get_example_create_data(): array {
		$method = $this->is_updatable() ? 'update_schema' : 'create_schema';

		$request_body = $this->endpoint->$method()->get_request_body()->to_array()['content']['application/json'];

		return $request_body['example'];
	}
}
