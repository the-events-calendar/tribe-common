<?php
/**
 * OpenAPI schema for the Events endpoint.
 *
 * @since TBD
 *
 * @package TEC\Events\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\OpenAPI_Schema as OpenAPI_Schema_Contract;
use TEC\Common\REST\TEC\V1\Collections\Collection;
use TEC\Common\REST\TEC\V1\Collections\HeadersCollection;
use TEC\Common\REST\TEC\V1\Collections\QueryArgumentCollection;
use TEC\Common\REST\TEC\V1\Collections\RequestBodyCollection;
use TEC\Common\REST\TEC\V1\Collections\PathArgumentCollection;
use Closure;
use TEC\Common\REST\TEC\V1\Contracts\Tag_Interface as Tag;
use TEC\Common\REST\TEC\V1\Contracts\Parameter;
use Tribe\Utils\Lazy_String;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * OpenAPI schema for the Events endpoint.
 *
 * @since TBD
 */
class OpenAPI_Schema implements OpenAPI_Schema_Contract {
	/**
	 * The summary of the schema.
	 *
	 * @since TBD
	 *
	 * @var Closure
	 */
	private Closure $summary_provider;

	/**
	 * The description of the schema.
	 *
	 * @since TBD
	 *
	 * @var Closure
	 */
	private Closure $description_provider;

	/**
	 * The operation ID of the schema.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private string $operation_id;

	/**
	 * The tags of the schema.
	 *
	 * @since TBD
	 *
	 * @var Tag[]
	 */
	private array $tags;

	/**
	 * The path arguments of the schema.
	 *
	 * @since TBD
	 *
	 * @var ?PathArgumentCollection
	 */
	private ?PathArgumentCollection $path_arguments = null;

	/**
	 * The parameters of the schema.
	 *
	 * @since TBD
	 *
	 * @var ?QueryArgumentCollection
	 */
	private ?QueryArgumentCollection $parameters = null;

	/**
	 * The request body of the schema.
	 *
	 * @since TBD
	 *
	 * @var ?RequestBodyCollection
	 */
	private ?RequestBodyCollection $request_body = null;

	/**
	 * The responses of the schema.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private array $responses;

	/**
	 * Whether the schema requires privileges.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	private bool $requiring_privileges;

	/**
	 * The constructor.
	 *
	 * @since TBD
	 *
	 * @param Closure                  $summary_provider The summary provider.
	 * @param Closure                  $description_provider The description provider.
	 * @param string                   $operation_id The operation ID.
	 * @param Tag[]                    $tags The tags.
	 * @param ?PathArgumentCollection  $path_arguments The path arguments.
	 * @param ?QueryArgumentCollection $parameters The parameters.
	 * @param ?RequestBodyCollection   $request_body The request body.
	 * @param ?bool                    $requiring_privileges Whether the schema requires privileges.
	 */
	public function __construct(
		Closure $summary_provider,
		Closure $description_provider,
		string $operation_id,
		array $tags,
		?PathArgumentCollection $path_arguments = null,
		?QueryArgumentCollection $parameters = null,
		?RequestBodyCollection $request_body = null,
		?bool $requiring_privileges = false
	) {
		$this->summary_provider     = $summary_provider;
		$this->description_provider = $description_provider;
		$this->operation_id         = $operation_id;
		$this->tags                 = $tags;
		$this->path_arguments       = $path_arguments;
		$this->parameters           = $parameters;
		$this->request_body         = $request_body;
		$this->requiring_privileges = $requiring_privileges;
	}

	/**
	 * @inheritDoc
	 */
	public function get_summary(): string {
		return call_user_func( $this->summary_provider );
	}

	/**
	 * @inheritDoc
	 */
	public function get_description(): string {
		return call_user_func( $this->description_provider );
	}

	/**
	 * @inheritDoc
	 */
	public function get_operation_id(): string {
		return $this->operation_id;
	}

	/**
	 * @inheritDoc
	 */
	public function get_tags(): array {
		return array_map( fn( Tag $tag ) => $tag->get_name(), $this->tags );
	}

	/**
	 * @inheritDoc
	 */
	public function get_parameters(): array {
		if ( null === $this->parameters && null === $this->path_arguments ) {
			return [ null, null ];
		}

		return [ $this->path_arguments, $this->parameters ];
	}

	/**
	 * @inheritDoc
	 */
	public function get_request_body(): ?RequestBodyCollection {
		return $this->request_body;
	}

	/**
	 * @inheritDoc
	 */
	public function get_responses(): array {
		return $this->responses;
	}

	/**
	 * Adds a response to the schema.
	 *
	 * @since TBD
	 *
	 * @param int                $code The HTTP status code of the response.
	 * @param Closure            $description_provider The closure that provides the description of the response.
	 * @param ?HeadersCollection $headers The headers of the response.
	 * @param ?string            $content_type The content type of the response.
	 * @param ?Parameter         $content The content of the response.
	 */
	public function add_response( int $code, Closure $description_provider, ?HeadersCollection $headers = null, ?string $content_type = null, ?Parameter $content = null ): void {
		$this->responses[ $code ] = [
			'description' => new Lazy_String( $description_provider ),
		];

		if ( null !== $headers ) {
			$this->responses[ $code ]['headers'] = $headers;
		}

		if ( null !== $content_type && null !== $content ) {
			$content_schema = $content->to_openapi_schema()['schema'];

			$this->responses[ $code ]['content'][ $content_type ] = [
				'schema' => $content_schema,
			];
		}
	}

	/**
	 * @inheritDoc
	 */
	public function to_array(): array {
		return array_filter(
			[
				'summary'     => $this->get_summary(),
				'security'    => $this->requiring_privileges ? [ [ 'BasicAuth' => [] ] ] : [],
				'description' => $this->get_description(),
				'operationId' => $this->get_operation_id(),
				'tags'        => $this->get_tags(),
				'requestBody' => $this->get_request_body(),
				'parameters'  => array_merge( ...array_map( static fn( Collection $collection ) => $collection->map( fn( Parameter $parameter ) => $parameter->to_openapi_schema() ), array_filter( $this->get_parameters() ) ) ),
				'responses'   => $this->get_responses(),
			],
			fn( $value ) => null !== $value,
		);
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		return $this->to_array();
	}
}
