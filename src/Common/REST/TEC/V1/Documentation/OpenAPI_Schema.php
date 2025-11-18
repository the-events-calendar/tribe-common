<?php
/**
 * OpenAPI schema for the Events endpoint.
 *
 * @since 6.9.0
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
use TEC\Common\REST\TEC\V1\Parameter_Types\Definition_Parameter;
use TEC\Common\REST\TEC\V1\Parameter_Types\Number;
use TEC\Common\REST\TEC\V1\Parameter_Types\Integer;
use Tribe\Utils\Lazy_String;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * OpenAPI schema for the Events endpoint.
 *
 * @since 6.9.0
 */
class OpenAPI_Schema implements OpenAPI_Schema_Contract {
	/**
	 * The summary of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var Closure
	 */
	private Closure $summary_provider;

	/**
	 * The description of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var Closure
	 */
	private Closure $description_provider;

	/**
	 * The operation ID of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	private string $operation_id;

	/**
	 * The tags of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var Tag[]
	 */
	private array $tags;

	/**
	 * The path arguments of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var ?PathArgumentCollection
	 */
	private ?PathArgumentCollection $path_arguments = null;

	/**
	 * The parameters of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var ?QueryArgumentCollection
	 */
	private ?QueryArgumentCollection $parameters = null;

	/**
	 * The request body of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var ?RequestBodyCollection
	 */
	private ?RequestBodyCollection $request_body = null;

	/**
	 * The responses of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @var array
	 */
	private array $responses;

	/**
	 * Whether the schema requires privileges.
	 *
	 * @since 6.9.0
	 *
	 * @var bool
	 */
	private bool $requiring_privileges;

	/**
	 * The constructor.
	 *
	 * @since 6.9.0
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
	 * @since 6.9.0
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

	/**
	 * @inheritDoc
	 *
	 * @param array $data The data to filter.
	 *
	 * @return array The filtered schema.
	 *
	 * @throws InvalidRestArgumentException If parameter is invalid.
	 */
	public function filter_before_request( array $data = [] ): array {
		[
			/** @var ?PathArgumentCollection $path_params */
			$path_params,
			/** @var ?QueryArgumentCollection $query_params */
			$query_params,
		] = $this->get_parameters();

		/** @var ?RequestBodyCollection $request_body */
		$request_body = $this->get_request_body();

		$params = array_filter(
			[
				'Path'  => $path_params,
				'Query' => $query_params,
				'Body'  => $request_body,
			],
			fn( $value ) => null !== $value,
		);

		// Will hold sanitized and only defined from the schema data.
		$new_data = [];

		foreach ( $params as $type => $collection ) {
			/** @var Parameter $param */
			foreach ( $collection as $param ) {
				if ( $param instanceof Definition_Parameter ) {
					$new_data = array_merge( $new_data, $param->filter_before_request( $data ) );
					continue;
				}

				$param_name = $param->get_name();
				if ( $param->is_required() && ! isset( $data[ $param_name ] ) ) {
					throw InvalidRestArgumentException::create(
						// translators: 1) is the type of the parameter, 2) is the name of the parameter.
						sprintf( __( '%1$s argument `{%2$s}` is required.', 'tribe-common' ), $type, $param_name ),
						$param_name,
						'tec_rest_invalid_' . strtolower( $type ) . '_argument',
						// translators: 1) is the type of the parameter, 2) is the name of the parameter.
						sprintf( __( 'The %1$s argument `{%2$s}` is missing.', 'tribe-common' ), $type, $param_name )
					);
				}

				if ( empty( $data[ $param_name ] ) && null !== $param->get_default() ) {
					$data[ $param_name ] = $param->get_default();
				}

				if ( ! isset( $data[ $param_name ] ) ) {
					continue;
				}

				if ( 'Body' !== $type && $param instanceof Number ) {
					/**
					 * Parameter that are part of the Query or the Path may be converted to string by WP. We forgive that issue here.
					 */
					$data[ $param_name ] = $param instanceof Integer ? intval( $data[ $param_name ] ) : floatval( $data[ $param_name ] );
				}

				$new_data[ $param_name ] = $data[ $param_name ];
			}
		}

		/**
		 * Filters the schema defined parameters.
		 *
		 * @since 6.10.0
		 *
		 * @param array          $new_data The filtered and documented data.
		 * @param array          $data     The raw data.
		 * @param OpenAPI_Schema $schema   The schema.
		 *
		 * @return array The filtered data.
		 */
		return (array) apply_filters( 'tec_rest_schema_filter', $new_data, $data, $this );
	}
}
