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
use Closure;

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
				return $data['enum'][ count( $data['enum'] ) - 1 ];
			}

			if ( isset( $data['format'] ) && 'date-time' === $data['format'] ) {
				return date( 'Y-m-d H:i:s', strtotime( $data['example'] ) + 3 * DAY_IN_SECONDS + 4 * HOUR_IN_SECONDS + 9 * MINUTE_IN_SECONDS );
			}

			return str_replace( [ '6', 'e', '$10' ], [ '7', 'p', '$13' ], $data['example'] );
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
	public function test_update_responses( Closure $fixture ) {
		if ( ! $this->is_updatable() ) {
			return;
		}
		$request_body = $this->endpoint->update_schema()->get_request_body()->to_array()['content']['application/json'];

		$example = $request_body['example'];
		unset( $example['id'] );

		if ( isset( $request_body['schema']['$ref'] ) ) {
			$definition = $this->get_instance_from_ref( $request_body['schema']['$ref'] )->get_documentation();
			$properties = $this->get_props_from_doc( $definition );

			$request_body['schema'] = [ 'properties' => $properties ];
		}

		$properties = $request_body['schema']['properties'];

		$orm = $this->endpoint->get_orm();

		$event_cat_term_1 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 1' ] );
		$event_cat_term_2 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 2' ] );
		$event_cat_term_3 = self::factory()->term->create( [ 'taxonomy' => 'tribe_events_cat', 'name' => 'Category 3' ] );
		$event_tag_term_1 = self::factory()->term->create( [ 'taxonomy' => 'post_tag', 'name' => 'Tag 1' ] );
		$event_tag_term_2 = self::factory()->term->create( [ 'taxonomy' => 'post_tag', 'name' => 'Tag 2' ] );
		$event_tag_term_3 = self::factory()->term->create( [ 'taxonomy' => 'post_tag', 'name' => 'Tag 3' ] );

		$organizer_1 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 1' ] );
		$organizer_2 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 2' ] );
		$organizer_3 = self::factory()->post->create( [ 'post_type' => 'tribe_organizer', 'post_title' => 'Organizer 3' ] );

		$venue_1 = self::factory()->post->create( [ 'post_type' => 'tribe_venue', 'post_title' => 'Venue 1' ] );
		$venue_2 = self::factory()->post->create( [ 'post_type' => 'tribe_venue', 'post_title' => 'Venue 2' ] );

		$example['tribe_events_cat'] = [ $event_cat_term_1, $event_cat_term_2 ];
		$example['tags']             = [ $event_tag_term_1 ];
		$example['organizers']       = [ $organizer_1, $organizer_2 ];
		$example['venues']           = $venue_1;

		wp_set_current_user( 1 );
		$entity_id = $orm->set_args( $example )->create()->ID;
		wp_set_current_user( 0 );

		$fixture();

		$user_is_logged_in = is_user_logged_in();

		$property_keys = array_keys( $properties );
		// Sort the properties so that keys that contain the `end` word come before keys that have the `start` word.
		usort( $property_keys, fn( $a, $b ) => strpos( $a, 'end' ) <=> strpos( $b, 'end' ) );

		$new_properties = [];

		foreach ( $property_keys as $property ) {
			$new_properties[ $property ] = $properties[ $property ];
		}

		$properties = $new_properties;

		foreach ( $properties as $property => $data ) {
			if ( ! isset( $example[ $property ] ) ) {
				continue;
			}

			if ( in_array( $property, [ 'date', 'template', 'excerpt', 'content', 'author', 'end_date_utc', 'start_date_utc', 'timezone' ], true ) ) {
				continue;
			}

			if ( isset( $data['format'] ) && 'date-time' === $data['format'] ) {
				$data['example'] = date( 'Y-m-d H:i:s', strtotime( $data['example'] ) );
			}

			$old_value = $data['example'];

			if ( in_array( $property, [ 'tribe_events_cat', 'tags', 'organizers', 'venues' ], true ) ) {
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

			$fresh_entity = $orm->by_args( [ 'id' => $entity_id, 'status' => 'any' ] )->first();
			foreach ( (array) $fresh_entity as $prop => $data ) {
				if ( ! is_object( $fresh_entity->{$prop} ) ) {
					continue;
				}

				if ( is_callable( [ $fresh_entity->{$prop}, '__toString' ] ) ) {
					$fresh_entity->{$prop} = (string) $fresh_entity->{$prop};
					continue;
				}

				if ( is_callable( [ $fresh_entity->{$prop}, 'all' ] ) ) {
					$fresh_entity->{$prop} = $fresh_entity->{$prop}->all();
					continue;
				}
			}

			$fresh_entity->post_author      = (int) $fresh_entity->post_author;
			$fresh_entity->featured_media   = (int) $fresh_entity->featured_media;
			$fresh_entity->post_tag         = wp_list_pluck( wp_get_post_tags( $entity_id ), 'term_id' );
			$fresh_entity->tribe_events_cat = wp_list_pluck( wp_get_post_terms( $entity_id, 'tribe_events_cat' ), 'term_id' );
			$fresh_entity->duration         = (int) $fresh_entity->duration;
			$fresh_entity->organizers       = wp_list_pluck( $fresh_entity->organizers, 'ID' );
			$fresh_entity->venues           = wp_list_pluck( $fresh_entity->venues, 'ID' );
			$fresh_entity->title            = str_replace( 'Private: ', '', $fresh_entity->title );

			$actual_property = $orm->get_update_fields_aliases()[ $property ] ?? $property;

			$using_property = isset( $fresh_entity->{$property} ) ? $property : $actual_property;

			$this->assertSame( 'venues' === $property ? [ $old_value ] : $old_value, $fresh_entity->{$using_property}, 'The property ' . $actual_property . ' / ' . $property . ' should be as expected.' );

			$this->assert_endpoint( sprintf( $this->endpoint->get_base_path(), $entity_id ), 'PUT', $user_can_update ? 200 : ( is_user_logged_in() ? 403 : 401 ), $params );

			wp_cache_flush();

			$fresh_entity = $orm->by_args( [ 'id' => $entity_id, 'status' => 'any' ] )->first();
			foreach ( (array) $fresh_entity as $prop => $data ) {
				if ( ! is_object( $fresh_entity->{$prop} ) ) {
					continue;
				}

				if ( is_callable( [ $fresh_entity->{$prop}, '__toString' ] ) ) {
					$fresh_entity->{$prop} = (string) $fresh_entity->{$prop};
					continue;
				}

				if ( is_callable( [ $fresh_entity->{$prop}, 'all' ] ) ) {
					$fresh_entity->{$prop} = $fresh_entity->{$prop}->all();
					continue;
				}
			}

			$fresh_entity->post_author      = (int) $fresh_entity->post_author;
			$fresh_entity->featured_media   = (int) $fresh_entity->featured_media;
			$fresh_entity->post_tag         = wp_list_pluck( wp_get_post_tags( $entity_id ), 'term_id' );
			$fresh_entity->tribe_events_cat = wp_list_pluck( wp_get_post_terms( $entity_id, 'tribe_events_cat' ), 'term_id' );
			$fresh_entity->duration         = (int) $fresh_entity->duration;
			$fresh_entity->organizers       = wp_list_pluck( $fresh_entity->organizers, 'ID' );
			$fresh_entity->venues           = wp_list_pluck( $fresh_entity->venues, 'ID' );
			$fresh_entity->title            = str_replace( 'Private: ', '', $fresh_entity->title );

			if ( $user_can_update ) {
				$this->assertSame( $new_value, $fresh_entity->{$using_property}, 'The property ' . $actual_property . ' / ' . $property . ' should have been updated.' );
			} else {
				$this->assertSame( 'venues' === $property ? [ $old_value ] : $old_value, $fresh_entity->{$using_property}, 'The property ' . $actual_property . ' / ' . $property . ' should not have been updated.' );
			}
		}
	}

	/**
	 * @dataProvider different_user_roles_provider
	 */
	public function test_delete_responses( Closure $fixture ) {
		if ( ! $this->is_deletable() ) {
			return;
		}

		$request_body = $this->endpoint->update_schema()->get_request_body()->to_array()['content']['application/json'];

		$example = $request_body['example'];
		unset( $example['id'] );

		if ( isset( $request_body['schema']['$ref'] ) ) {
			$definition = $this->get_instance_from_ref( $request_body['schema']['$ref'] )->get_documentation();
			$properties = $this->get_props_from_doc( $definition );

			$request_body['schema'] = [ 'properties' => $properties ];
		}

		$properties = $request_body['schema']['properties'];

		$orm = $this->endpoint->get_orm();

		wp_set_current_user( 1 );
		$entity_id = $orm->set_args( $example )->create()->ID;
		wp_set_current_user( 0 );

		$fixture();

		$user_can_delete = is_user_logged_in() && current_user_can( get_post_type_object( $this->endpoint->get_post_type() )->cap->delete_post, $entity_id );
		$this->assert_endpoint( sprintf( $this->endpoint->get_base_path(), $entity_id ), 'DELETE', $user_can_delete ? 200 : ( is_user_logged_in() ? 403 : 401 ) );

		if ( $user_can_delete ) {
			$this->assertNull( get_post( $entity_id ) );
		} else {
			$this->assertNotNull( get_post( $entity_id ) );
		}
	}
}
