<?php
/**
 * TEC Post Entity definitions.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Abstracts\Definition;
use TEC\Common\REST\TEC\V1\Abstracts\Post_Entity_Endpoint;
use TEC\Common\REST\TEC\V1\Collections\PropertiesCollection;
use TEC\Common\REST\TEC\V1\Parameter_Types\Date_Time;
use TEC\Common\REST\TEC\V1\Parameter_Types\Positive_Integer;
use TEC\Common\REST\TEC\V1\Parameter_Types\Text;
use TEC\Common\REST\TEC\V1\Parameter_Types\Boolean;
use TEC\Common\REST\TEC\V1\Parameter_Types\Array_Of_Type;

/**
 * TEC Post Entity definitions.
 */
class TEC_Post_Entity_Request_Body_Definition extends Definition {
	/**
	 * Get the type.
	 *
	 * @since 6.9.0
	 *
	 * @return string The type.
	 */
	public function get_type(): string {
		return 'TEC_Post_Entity_Request_Body';
	}

	/**
	 * Get the documentation.
	 *
	 * @since 6.9.0
	 *
	 * @see https://developer.wordpress.org/rest-api/reference/posts/
	 *
	 * @return array The documentation.
	 */
	public function get_documentation(): array {
		$properties = new PropertiesCollection();

		$properties[] = (
			new Date_Time(
				'date',
				fn() => __( 'The date the entity was published, in the site\'s timezone. In RFC3339 format.', 'tribe-common' ),
			)
		)->set_example( '2025-06-04T23:36:56+01:00' )->set_pattern( '^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$' )->set_nullable( true );

		$properties[] = (
			new Date_Time(
				'date_gmt',
				fn() => __( 'The date the entity was published, as GMT. In RFC3339 format.', 'tribe-common' ),
			)
		)->set_example( '2025-06-04T22:36:56Z' )->set_pattern( '^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$' )->set_nullable( true );

		$properties[] = (
			new Text(
				'slug',
				fn() => __( 'An alphanumeric identifier for the entity unique to its type', 'tribe-common' ),
			)
		)->set_example( 'my-awesome-title' );

		$properties[] = (
			new Text(
				'status',
				fn() => __( 'A named status for the entity.', 'tribe-common' ),
				'publish',
				Post_Entity_Endpoint::ALLOWED_STATUS
			)
		)->set_example( 'publish' );

		$properties[] = (
			new Text(
				'title',
				fn() => __( 'HTML title for the entity, transformed for display', 'tribe-common' ),
			)
		)->set_example( 'My Awesome Title' );

		$properties[] = (
			new Text(
				'content',
				fn() => __( 'HTML content for the entity, transformed for display', 'tribe-common' ),
			)
		)->set_example( 'This is the content...' );

		$properties[] = (
			new Text(
				'excerpt',
				fn() => __( 'HTML excerpt for the entity, transformed for display', 'tribe-common' ),
			)
		)->set_example( 'This is the excerpt...' );

		$properties[] = (
			new Positive_Integer(
				'author',
				fn() => __( 'The ID for the author of the entity.', 'tribe-common' ),
			)
		)->set_example( 1 );

		$properties[] = (
			new Positive_Integer(
				'featured_media',
				fn() => __( 'The ID of the featured media for the entity.', 'tribe-common' ),
			)
		)->set_example( 123 );

		$properties[] = (
			new Text(
				'comment_status',
				fn() => __( 'Whether or not comments are open on the entity.', 'tribe-common' ),
				'open',
				[ 'open', 'closed' ]
			)
		)->set_example( 'open' );

		$properties[] = (
			new Text(
				'ping_status',
				fn() => __( 'Whether or not the entity can be pinged', 'tribe-common' ),
				'open',
				[ 'open', 'closed' ]
			)
		)->set_example( 'open' );

		$properties[] = (
			new Text(
				'format',
				fn() => __( 'The format for the entity.', 'tribe-common' ),
				'standard',
				[ 'standard', 'aside', 'chat', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ]
			)
		)->set_example( 'standard' );

		$properties[] = (
			new Boolean(
				'sticky',
				fn() => __( 'Whether or not the entity should be treated as sticky', 'tribe-common' ),
				false,
				false,
			)
		)->set_example( false );

		$properties[] = (
			new Text(
				'template',
				fn() => __( 'The theme file to use to display the entity.', 'tribe-common' ),
			)
		)->set_example( 'templates-full.php' );

		$properties[] = (
			new Array_Of_Type(
				'tags',
				fn() => __( 'The terms assigned to the entity in the post_tag taxonomy', 'tribe-common' ),
				Positive_Integer::class,
			)
		)->set_example( [ 2, 8, 15 ] );

		$definition = [
			'title'       => __( 'TEC Post Entity Request Body', 'tribe-common' ),
			'description' => __( 'A TEC post object as expected by the REST API', 'tribe-common' ),
			'type'        => 'object',
			'properties'  => $properties,
		];

		$type = strtolower( $this->get_type() );

		/**
		 * Filters the Swagger documentation generated for an TEC_Post_Entity_Request_Body in the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array                                   $documentation An associative PHP array in the format supported by Swagger.
		 * @param TEC_Post_Entity_Request_Body_Definition $this          The TEC_Post_Entity_Request_Body_Definition instance.
		 *
		 * @return array
		 */
		$documentation = (array) apply_filters( "tec_rest_swagger_{$type}_definition", $definition, $this );

		/**
		 * Filters the Swagger documentation generated for a definition in the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array                                   $documentation An associative PHP array in the format supported by Swagger.
		 * @param TEC_Post_Entity_Request_Body_Definition $this          The TEC_Post_Entity_Request_Body_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_definition', $documentation, $this );
	}

	/**
	 * Get the priority.
	 *
	 * @since 6.9.0
	 *
	 * @return int The priority.
	 */
	public function get_priority(): int {
		return 0;
	}
}
