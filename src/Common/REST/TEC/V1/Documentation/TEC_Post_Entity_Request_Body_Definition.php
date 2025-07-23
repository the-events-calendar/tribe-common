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

use TEC\Common\REST\TEC\V1\Abstracts\Definition;

/**
 * TEC Post Entity definitions.
 */
class TEC_Post_Entity_Request_Body_Definition extends Definition {
	/**
	 * Get the type.
	 *
	 * @since TBD
	 *
	 * @return string The type.
	 */
	public function get_type(): string {
		return 'TEC_Post_Entity_Request_Body';
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
		$definition = [
			'title'       => __( 'TEC Post Entity Request Body', 'tribe-common' ),
			'description' => __( 'A TEC post object as expected by the REST API', 'tribe-common' ),
			'type'        => 'object',
			'properties'  => [
				'date'               => [
					'type'        => 'string',
					'format'      => 'date-time',
					'description' => __( 'The date the entity was published, in the site\'s timezone. In RFC3339 format.', 'tribe-common' ),
					'example'     => '2025-06-24T22:36:56+02:00',
					'pattern'     => '^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$',
					'nullable'    => true,
				],
				'date_gmt'           => [
					'type'        => 'string',
					'format'      => 'date-time',
					'description' => __( 'The date the entity was published, as GMT. In RFC3339 format.', 'tribe-common' ),
					'example'     => '2025-06-24T22:36:56Z',
					'pattern'     => '^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$',
					'nullable'    => true,
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
				'title'              => [
					'type'        => 'string',
					'description' => __( 'The title for the entity.', 'tribe-common' ),
					'example'     => 'My Awesome Event',
				],
				'content'            => [
					'type'        => 'string',
					'description' => __( 'The content for the entity.', 'tribe-common' ),
					'example'     => '<p>This is the content of my event...</p>',
				],
				'author'             => [
					'type'        => 'integer',
					'description' => __( 'The ID for the author of the entity.', 'tribe-common' ),
					'example'     => 1,
				],
				'excerpt'            => [
					'type'        => 'string',
					'description' => __( 'The excerpt for the entity.', 'tribe-common' ),
					'example'     => '<p>This is the excerpt...</p>',
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
					'example'     => 'open',
				],
				'ping_status'        => [
					'type'        => 'string',
					'description' => __( 'Whether or not the entity can be pinged', 'tribe-common' ),
					'enum'        => [
						'open',
						'closed',
					],
					'example'     => 'open',
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
