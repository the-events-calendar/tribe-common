<?php
/**
 * Base test case for Post Entity REST API endpoints.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\TestCases\REST\TEC\V1
 */

namespace TEC\Common\Tests\TestCases\REST\TEC\V1;

use TEC\Common\REST\TEC\V1\Contracts\Post_Entity_Endpoint_Interface as Post_Entity_Endpoint;

/**
 * Class Post_Entity_REST_Test_Case
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\TestCases\REST\TEC\V1
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

		$entity_ids[] = $orm->set_args( $example )->create()->ID;

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

			$new_entity_id = $orm->set_args( $example )->create()->ID;

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

	private function modify_value( array $data ) {
		if ( ! isset( $data['type'] ) ) {
			return null;
		}

		if ( ! empty( $data['readOnly'] ) ) {
			return null;
		}

		if ( 'string' === $data['type'] ) {
			if ( ! empty( $data['enum'] ) ) {
				return $data['enum'][ count( $data['enum'] ) - 1 ];
			}

			return str_replace( [ '6', 'e' ], [ '7', 'p' ], $data['example'] );
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
}
