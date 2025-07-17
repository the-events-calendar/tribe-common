<?php
/**
 * TEC Post Entity definitions.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * TEC Post Entity definitions.
 */
class TEC_Post_Entity_Definition implements Definition_Interface {
	/**
	 * Get the type.
	 *
	 * @since TBD
	 *
	 * @return string The type.
	 */
	public function get_type(): string {
		return 'TEC_Post_Entity';
	}

	/**
	 * Get the documentation.
	 *
	 * @since TBD
	 *
	 * @see https://developer.wordpress.org/rest-api/reference/posts/
	 *
	 * @return array The documentation.
	 */
	public function get_documentation(): array {
		$tec_entity_types = [
			'tribe_events',
			'tec_tc_attendee',
			'tec_tc_order',
			'tec_tc_ticket',
			'ticket-meta-fieldset',
			'tribe_event_series',
			'tec_calendar_embed',
			'tribe_organizer',
			'tribe_venue',
			'tribe-ea-record',
			'tribe_payout',
			'tribe_wooticket',
			'tribe_eddticket',
			'tribe_tpp_orders',
			'tribe_tpp_attendees',
			'tribe_tpp_tickets',
			'tribe_rsvp_attendees',
			'tribe_rsvp_tickets',
		];

		$definition = [
			'type'        => 'object',
			'title'       => __( 'TEC Post Entity', 'tribe-common' ),
			'description' => __( 'A TEC post object as returned by the REST API', 'tribe-common' ),
			'properties'  => [
				'date'               => [
					'type'        => 'string',
					'format'      => 'date-time',
					'description' => __( 'The date the entity was published, in the site\'s timezone. In RFC3339 format.', 'tribe-common' ),
					'example'     => '2025-06-24T22:36:56Z+01:00',
					'pattern'     => '#^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$#',
					'nullable'    => true,
				],
				'date_gmt'           => [
					'type'        => 'string',
					'format'      => 'date-time',
					'description' => __( 'The date the entity was published, as GMT. In RFC3339 format.', 'tribe-common' ),
					'example'     => '2025-06-24T22:36:56Z+00:00',
					'pattern'     => '#^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$#',
					'nullable'    => true,
				],
				'guid'               => [
					'type'        => 'object',
					'description' => __( 'The globally unique identifier for the entity.', 'tribe-common' ),
					'readOnly'    => true,
					'properties'  => [
						'rendered' => [
							'type'        => 'string',
							'format'      => 'uri',
							'description' => __( 'The globally unique identifier for the post', 'tribe-common' ),
							'example'     => 'https://example.com/?p=12345',
						],
					],
				],
				'id'                 => [
					'type'        => 'integer',
					'description' => __( 'Unique identifier for the entity.', 'tribe-common' ),
					'readOnly'    => true,
					'example'     => 12345,
				],
				'link'               => [
					'type'        => 'string',
					'format'      => 'uri',
					'description' => __( 'URL to the entity.', 'tribe-common' ),
					'readOnly'    => true,
					'example'     => 'https://example.com/my-awesome-event',
				],
				'modified'           => [
					'type'        => 'string',
					'format'      => 'date-time',
					'description' => __( 'The date the entity was last modified, in the site\'s timezone. In RFC3339 format.', 'tribe-common' ),
					'readOnly'    => true,
					'pattern'     => '#^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$#',
					'example'     => '2025-06-24T22:36:56Z+01:00',
				],
				'modified_gmt'       => [
					'type'        => 'string',
					'format'      => 'date-time',
					'description' => __( 'The date the entity was last modified, as GMT. In RFC3339 format.', 'tribe-common' ),
					'readOnly'    => true,
					'pattern'     => '#^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$#',
					'example'     => '2025-06-24T22:36:56Z+00:00',
				],
				'slug'               => [
					'type'        => 'string',
					'description' => __( 'An alphanumeric identifier for the entity unique to its type', 'tribe-common' ),
					'example'     => 'my-awesome-event',
				],
				'status'             => [
					'type'        => 'string',
					'description' => __( 'A named status for the entity.', 'tribe-common' ),
					'enum'        => [
						'publish',
						'future',
						'draft',
						'pending',
						'private',
					],
					'example'     => 'publish',
				],
				'type'               => [
					'type'        => 'string',
					'description' => __( 'Type of entity.', 'tribe-common' ),
					'readOnly'    => true,
					'enum'        => $tec_entity_types,
					'example'     => $tec_entity_types[0],
				],
				'permalink_template' => [
					'type'        => 'string',
					'description' => __( 'Permalink template for the entity.', 'tribe-common' ),
					'readOnly'    => true,
					'example'     => 'https://example.com/sample-event/',
				],
				'generated_slug'     => [
					'type'        => 'string',
					'description' => __( 'Slug automatically generated from the entity title', 'tribe-common' ),
					'readOnly'    => true,
					'example'     => 'my-awesome-event',
				],
				'title'              => [
					'type'        => 'object',
					'description' => __( 'The title for the entity.', 'tribe-common' ),
					'properties'  => [
						'rendered' => [
							'type'        => 'string',
							'description' => __( 'HTML title for the entity, transformed for display', 'tribe-common' ),
							'example'     => 'My Awesome Event',
						],
					],
				],
				'content'            => [
					'type'        => 'object',
					'description' => __( 'The content for the entity.', 'tribe-common' ),
					'properties'  => [
						'rendered'  => [
							'type'        => 'string',
							'description' => __( 'HTML content for the entity, transformed for display', 'tribe-common' ),
							'example'     => '<p>This is the content of my event...</p>',
						],
						'protected' => [
							'type'        => 'boolean',
							'description' => __( 'Whether the content is protected with a password', 'tribe-common' ),
							'example'     => false,
						],
					],
				],
				'author'             => [
					'type'        => 'integer',
					'description' => __( 'The ID for the author of the entity.', 'tribe-common' ),
					'example'     => 1,
				],
				'excerpt'            => [
					'type'        => 'object',
					'description' => __( 'The excerpt for the entity.', 'tribe-common' ),
					'properties'  => [
						'rendered'  => [
							'type'        => 'string',
							'description' => __( 'HTML excerpt for the entity, transformed for display', 'tribe-common' ),
							'example'     => '<p>This is the excerpt...</p>',
						],
						'protected' => [
							'type'        => 'boolean',
							'description' => __( 'Whether the excerpt is protected with a password', 'tribe-common' ),
							'example'     => false,
						],
					],
				],
				'featured_media'     => [
					'type'        => 'integer',
					'description' => __( 'The ID of the featured media for the entity.', 'tribe-common' ),
					'example'     => 123,
				],
				'comment_status'     => [
					'type'        => 'string',
					'description' => __( 'Whether or not comments are open on the entity.', 'tribe-common' ),
					'enum'        => [
						'open',
						'closed',
					],
					'example'     => 'closed',
				],
				'ping_status'        => [
					'type'        => 'string',
					'description' => __( 'Whether or not the entity can be pinged', 'tribe-common' ),
					'enum'        => [
						'open',
						'closed',
					],
					'example'     => 'closed',
				],
				'format'             => [
					'type'        => 'string',
					'description' => __( 'The format for the entity.', 'tribe-common' ),
					'enum'        => [
						'standard',
						'aside',
						'chat',
						'gallery',
						'link',
						'image',
						'quote',
						'status',
						'video',
						'audio',
					],
					'example'     => 'standard',
				],
				'meta'               => [
					'type'                 => 'object',
					'description'          => __( 'Meta fields', 'tribe-common' ),
					'additionalProperties' => true,
					'example'              => [
						'custom_field' => 'value',
					],
				],
				'sticky'             => [
					'type'        => 'boolean',
					'description' => __( 'Whether or not the entity should be treated as sticky', 'tribe-common' ),
					'example'     => false,
				],
				'template'           => [
					'type'        => 'string',
					'description' => __( 'The theme file to use to display the entity.', 'tribe-common' ),
					'example'     => '',
				],
				'categories'         => [
					'type'        => 'array',
					'description' => __( 'The terms assigned to the entity in the category taxonomy', 'tribe-common' ),
					'items'       => [
						'type' => 'integer',
					],
					'example'     => [ 1, 5, 12 ],
				],
				'tags'               => [
					'type'        => 'array',
					'description' => __( 'The terms assigned to the entity in the post_tag taxonomy', 'tribe-common' ),
					'items'       => [
						'type' => 'integer',
					],
					'example'     => [ 2, 8, 15 ],
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for an TEC_Post_Entity in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array                      $documentation An associative PHP array in the format supported by Swagger.
		 * @param TEC_Post_Entity_Definition $this          The TEC_Post_Entity_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $definition, $this );
	}

	/**
	 * Get the priority.
	 *
	 * @since TBD
	 *
	 * @return int The priority.
	 */
	public function get_priority(): int {
		return 0;
	}
}
