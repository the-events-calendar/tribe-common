<?php
/**
 * Base test case for REST API endpoints.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\TestCases\REST\TEC\V1
 */

namespace TEC\Common\Tests\TestCases\REST\TEC\V1;

use lucatume\WPBrowser\TestCase\WPTestCase as WPBrowserTestCase;
use Codeception\TestCase\WPTestCase;
use TEC\Common\Contracts\Container;
use TEC\Common\REST\TEC\V1\Contracts\Endpoint_Interface as Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Readable_Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Creatable_Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Updatable_Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Deletable_Endpoint;
use Tribe\Tests\Traits\With_Uopz;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use RuntimeException;
use WP_REST_Server;
use Generator;

if ( ! class_exists( WPBrowserTestCase::class ) ) {
	class_alias( WPTestCase::class, WPBrowserTestCase::class );
}

/**
 * Class REST_Test_Case
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\TestCases\REST\TEC\V1
 *
 * @property string $endpoint_class The class name of the endpoint to test.
 */
abstract class REST_Test_Case extends WPBrowserTestCase {
	use With_Uopz;
	use SnapshotAssertions;

	/**
	 * The OpenAPI schema loaded from the JSON file.
	 *
	 * @var array|null
	 */
	protected array $openapi_schema;

	/**
	 * The REST server instance.
	 *
	 * @var WP_REST_Server
	 */
	protected WP_REST_Server $rest_server;

	/**
	 * The container instance.
	 *
	 * @var Container
	 */
	protected Container $container;

	/**
	 * The endpoint instance.
	 *
	 * @var Endpoint
	 */
	protected $endpoint;

	/**
	 * Set up the test case.
	 *
	 * @before
	 */
	public function set_up() {
		// Ensure the endpoint class is defined.
		if ( ! property_exists( $this, 'endpoint_class' ) ) {
			throw new RuntimeException( 'Each REST test case must define an endpoint_class property.' );
		}

		$this->container   = tribe();
		$this->endpoint    = $this->container->get( $this->endpoint_class );
		$this->rest_server = rest_get_server();

		// Encode and decode to have the JsonSerializable objects converted to arrays.
		$this->openapi_schema = json_decode( wp_json_encode( $this->endpoint->get_documentation() ), true );

		$this->assert_supported_operations();
	}

	/**
	 * @after
	 */
	public function reset() {
		wp_set_current_user( 0 );
	}

	public function different_user_roles_provider(): Generator {
		yield [
			'guest' => function (): void {
				wp_set_current_user( 0 );
			},
		];

		yield [
			'contributor' => function (): void {
				$user = $this->factory()->user->create( [ 'role' => 'contributor' ] );
				wp_set_current_user( $user );
			},
		];

		yield [
			'author' => function (): void {
				$user = $this->factory()->user->create( [ 'role' => 'author' ] );
				wp_set_current_user( $user );
			},
		];

		yield [
			'editor' => function (): void {
				$user = $this->factory()->user->create( [ 'role' => 'editor' ] );
				wp_set_current_user( $user );
			},
		];

		yield [
			'administrator' => function (): void {
				$user = $this->factory()->user->create( [ 'role' => 'administrator' ] );
				wp_set_current_user( $user );
			},
		];
	}

	protected function assert_supported_operations() {
		$this->supported_methods = array_keys( $this->openapi_schema );

		$verification = [];

		if ( $this->is_readable() ) {
			$verification[] = 'get';
		}

		if ( $this->is_creatable() ) {
			$verification[] = 'post';
		}

		if ( $this->is_updatable() ) {
			$verification[] = 'put';
		}

		if ( $this->is_deletable() ) {
			$verification[] = 'delete';
		}

		sort( $verification );
		sort( $this->supported_methods );

		$this->assertSame( $verification, $this->supported_methods );
	}

	protected function is_readable(): bool {
		return $this->endpoint instanceof Readable_Endpoint;
	}

	protected function is_creatable(): bool {
		return $this->endpoint instanceof Creatable_Endpoint;
	}

	protected function is_updatable(): bool {
		return $this->endpoint instanceof Updatable_Endpoint;
	}

	protected function is_deletable(): bool {
		return $this->endpoint instanceof Deletable_Endpoint;
	}

	public function test_get_url() {
		$path_params = $this->endpoint->get_path_parameters();

		$args = count( $path_params ) ? range( 1, count( $path_params ) ) : [];

		$url = $this->endpoint->get_url( ...$args );

		$this->assertSame( rest_url( 'tec/v1' . sprintf( $this->endpoint->get_base_path(), ...$args ) ), $url );
	}

	public function test_get_path_parameters() {
		$path_params = $this->endpoint->get_path_parameters();

		$this->assertIsArray( $path_params );
		if ( empty( $path_params ) ) {
			return;
		}

		foreach ( $path_params as $param => $data ) {
			$this->assertArrayHasKey( 'type', $data );
			$this->assertContains( $data['type'], [ 'integer', 'string' ] );
		}
	}

	public function test_get_base_path() {
		$this->assertStringStartsWith( '/', $this->endpoint->get_base_path() );
	}

	public function test_get_path() {
		$path_params = $this->endpoint->get_path_parameters();

		$replacements = [];

		foreach ( $path_params as $param => $data ) {
			switch ( $data['type'] ) {
				case 'integer':
					$regex = '\\d+';
					break;
				case 'string':
					$regex = '[a-zA-Z0-9_-]+';
					break;
				default:
					$regex = $data['type'];
					break;
			}
			$replacements[] = "(?P<{$param}>{$regex})";
		}

		$path = sprintf( $this->endpoint->get_base_path(), ...$replacements );

		$this->assertSame( $path, $this->endpoint->get_path() );
	}

	public function test_get_schema() {
		$schema = $this->endpoint->get_schema();

		$this->assertIsArray( $schema );
		$this->assertArrayHasKey( '$schema', $schema );
		$this->assertSame( 'http://json-schema.org/draft-04/schema#', $schema['$schema'] );
		$this->assertArrayHasKey( 'title', $schema );
		$this->assertArrayHasKey( 'type', $schema );
	}

	public function test_get_documentation() {
		$this->assertIsArray( $this->openapi_schema );
		$this->assertNotEmpty( $this->openapi_schema );

		foreach ( $this->openapi_schema as $method => $operation ) {
			$this->assertTrue( in_array( $method, $this->supported_methods, true ) );
			$this->assertArrayHasKey( 'summary', $operation );
			$this->assertIsString( $operation['summary'] );
			$this->assertNotEmpty( $operation['summary'] );
			$this->assertArrayHasKey( 'security', $operation );
			$this->assertIsArray( $operation['security'] );
			$this->assertArrayHasKey( 'description', $operation );
			$this->assertIsString( $operation['description'] );
			$this->assertNotEmpty( $operation['description'] );
			$this->assertArrayHasKey( 'operationId', $operation );
			$this->assertIsString( $operation['operationId'] );
			$this->assertNotEmpty( $operation['operationId'] );
			$this->assertArrayHasKey( 'tags', $operation );
			$this->assertIsArray( $operation['tags'] );
			$this->assertNotEmpty( $operation['tags'] );
			$this->assertArrayHasKey( 'responses', $operation );
			$this->assertIsArray( $operation['responses'] );
			$this->assertNotEmpty( $operation['responses'] );
			foreach ( $operation['responses'] as $code => $response ) {
				$this->assertIsInt( $code );
				$this->assertArrayHasKey( 'description', $response );
				$this->assertIsString( $response['description'] );
				$this->assertNotEmpty( $response['description'] );
				if ( $code > 299 || 'delete' === $method ) {
					continue;
				}

				$this->assertArrayHasKey( 'content', $response, $operation['operationId'] . ' for code ' . $code );
				$this->assertIsArray( $response['content'] );
				$this->assertNotEmpty( $response['content'] );
				foreach ( $response['content'] as $content_type => $content ) {
					$this->assertIsString( $content_type );
					$this->assertNotEmpty( $content_type );
					$this->assertArrayHasKey( 'schema', $content );
					$this->assertIsArray( $content['schema'] );
					$this->assertNotEmpty( $content['schema'] );
				}
			}
		}
	}
}
