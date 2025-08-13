<?php
/**
 * Fake endpoint extending Post_Entity_Endpoint for tests.
 *
 * @since TBD
 */

namespace TEC\REST\V1;

use TEC\Common\REST\TEC\V1\Abstracts\Post_Entity_Endpoint;
use TEC\Common\REST\TEC\V1\Collections\PathArgumentCollection;
use TEC\Common\REST\TEC\V1\Collections\QueryArgumentCollection;
use TEC\Common\REST\TEC\V1\Contracts\OpenAPI_Schema as OpenAPI_Schema_Interface;
use TEC\Common\REST\TEC\V1\Documentation\OpenAPI_Schema as OpenAPI_Schema_Documentation;
use Tribe__Repository__Interface;
use WP_Post;
use WP_REST_Response;

class Fake_Post_Endpoint extends Post_Entity_Endpoint implements \TEC\Common\REST\TEC\V1\Contracts\Readable_Endpoint {
	public function get_post_type(): string {
		return 'tec_fake_post';
	}

	public function get_model_class(): string {
		return Fake_Post_Model::class;
	}

	public function get_orm(): Tribe__Repository__Interface {
		// Use a lightweight anonymous class implementing minimal contract for tests.
		return new class() implements Tribe__Repository__Interface {
			public function get_default_args() { return []; }
			public function set_default_args( array $default_args ) { return $this; }
			public function filter_name( $filter_name ) { return $this; }
			public function get_filter_name() { return ''; }
			public function set_formatter( \Tribe__Repository__Formatter_Interface $formatter ) { return $this; }
			public function build_query( $use_query_builder = true ) { return new \WP_Query(); }
			public function join_clause( $join ) { return $this; }
			public function where_clause( $where ) { return $this; }
			public function set_query_builder( $query_builder ) { return $this; }
			public function where_or( $callbacks ) { return $this; }
			public function by_related_to_min( $by_meta_keys, $min, $keys = null, $values = null ) { return $this; }
			public function by_related_to_max( $by_meta_keys, $max, $keys = null, $values = null ) { return $this; }
			public function by_related_to_between( $by_meta_keys, $min, $max, $keys = null, $values = null ) { return $this; }
			public function by_not_related_to( $by_meta_keys, $keys = null, $values = null ) { return $this; }
			public function add_schema_entry( $key, $callback ) { return $this; }
			public function hash( array $settings = [], \WP_Query $query = null ) { return ''; }
			public function get_hash_data( array $settings, \WP_Query $query = null ) { return []; }
			public function get_last_built_query() { return null; }
			public function where_multi( array $fields, $compare, $value, $where_relation = 'OR', $value_relation = 'OR' ) { return $this; }
			public function set_query( \WP_Query $query ) { return $this; }
			public function next() { return $this; }
			public function prev() { return $this; }
			public function set_found_rows( $found_rows ) { return $this; }
			public function void_query( $void_query = true ) { return $this; }
			public function get_last_sql(): ?string { return null; }
			public function get_request_context(): ?string { return null; }
			public function set_request_context( string $context = null ): \Tribe__Repository__Interface { return $this; }
            public function all( $return_generator = false, int $batch_size = 50 ) { return []; }
			public function save( $data = null ) { return true; }
			public function update( $data = null ) { return true; }
			public function first() { return null; }
			public function by_args( $args ) { return $this; }
			public function set_args( $args ) { return $this; }
		};
	}

	public function read( array $params = [] ): WP_REST_Response {
		return new WP_REST_Response( [], 200 );
	}

	public function read_args(): QueryArgumentCollection { return new QueryArgumentCollection(); }

    public function read_schema(): OpenAPI_Schema_Interface {
        return new OpenAPI_Schema_Documentation(
            static fn() => 'Fake read.',
            static fn() => 'Fake read schema for tests.',
            'fake.read',
            [],
            null,
            null,
            null,
            false
        );
    }

	public function get_schema(): array { return []; }

	public function get_tags(): array { return []; }

	public function get_operation_id( string $operation ): string { return $operation; }

	public function get_base_path(): string { return '/tec/v1/fake-posts(?:/(?P<id>\\d+))?'; }

	public function get_path_parameters(): PathArgumentCollection { return new PathArgumentCollection(); }

	public function get_formatted_entity( WP_Post $post ): array { return [ 'id' => $post->ID ]; }

	public function guest_can_read(): bool { return true; }
}


