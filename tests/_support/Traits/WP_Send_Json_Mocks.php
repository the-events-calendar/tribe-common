<?php
/**
 * Provides methods to mock `wp_send_json` functions in tests using the `uopz` extension.
 *
 * @since   TBD
 *
 * @package Traits;
 */

namespace Tribe\Tests\Traits;

require_once __DIR__ . '/Function_Spy.php';

class WP_Send_Json_Mock_Spy {
	use Function_Spy;
	use With_Uopz;

	private $data;
	private $status_code;
	private $flags;

	public function __construct( string $name ) {
		$this->name = $name;
	}

	public function get_pretty_arguments(): string {
		return json_encode( [
			$this->data,
			$this->status_code,
			$this->flags,
		], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES );
	}

	public function get_pretty_calls(): array {
		return array_map( static function ( $call ) {
			return var_export( $call, true );
		}, $this->calls );
	}

	public function get_calls_as_string(): string {
		return var_export( $this->calls, true );
	}
}

/**
 * Class WP_Send_Json_Mocks.
 *
 * @since   TBD
 *
 * @package Traits;
 */
trait WP_Send_Json_Mocks {
	/**
	 * A map from the hash of the mock function to the calls that were made to it.
	 *
	 * @var WP_Send_Json_Mock_Spy[]
	 */
	public array $wp_send_json_spies = [];

	public WP_Send_Json_Mock_Spy $wp_send_json_unexpected_spy;

	/**
	 * @before
	 */
	public function set_up_wp_send_json_mocks(): void {
		$this->wp_send_json_spies          = [];
		$this->wp_send_json_unexpected_spy = new WP_Send_Json_Mock_Spy( 'wp_send_json' );
		$this->wp_send_json_unexpected_spy->should_not_be_called();
		$this->wp_send_json_spies[] = $this->wp_send_json_unexpected_spy;
		$test_case                  = $this;
		$log_unexpected             = static function ( $response, $status_code = null, $flags = 0 ) use ( $test_case ) {
			$test_case->wp_send_json_unexpected_spy->register_call( [ $response, $status_code, $flags ] );
		};
		$this->set_fn_return( "wp_send_json", $log_unexpected, true );
	}

	protected function mock_wp_send_json_error( $value = null, $status_code = null, $flags = 0 ): WP_Send_Json_Mock_Spy {
		$spy = new WP_Send_Json_Mock_Spy( 'wp_send_json_error' );

		$mock = static function ( $value = null, $status_code = null, $flags = 0 ) use ( $spy ) {
			$spy->register_call( [ $value, $status_code, $flags ] );
		};

		$this->set_fn_return( "wp_send_json_error", $mock, true );
		$this->wp_send_json_spies[] = $spy;

		return $spy;
	}

	protected function mock_wp_send_json_success(): WP_Send_Json_Mock_Spy {
		$spy  = new WP_Send_Json_Mock_Spy( 'wp_send_json_success' );
		$mock = function ( $data = null, $status_code = null, $options = 0 ) use ( $spy ) {
			$spy->register_call( [ $data, $status_code, $options ] );
		};

		$this->set_fn_return( "wp_send_json_success", $mock, true );
		$this->wp_send_json_spies[] = $spy;

		return $spy;
	}

	protected function reset_wp_send_json_mocks(): void {
		$this->wp_send_json_spies = [];
		uopz_unset_return( 'wp_send_json_error' );
		uopz_unset_return( 'wp_send_json_success' );
	}

	/**
	 * @after
	 */
	public function assert_wp_send_json_mocks_post_conditions(): void {
		foreach ( $this->wp_send_json_spies as $spy ) {
			if ( $spy->was_verified() ) {
				continue;
			}

			if ( $spy->expects_calls() ) {
				$this->assertTrue( $spy->was_called(),
					sprintf(
						"The %s mock function was never called with expected arguments:\n%s\n\nUnexpected calls:\n%s\n\nwp_send_json calls:\n%s",
						$spy->get_name(),
						$spy->get_pretty_arguments(),
						implode( "\n", $spy->get_pretty_calls() ) ?: 'none',
						implode( "\n", $this->wp_send_json_unexpected_spy->get_pretty_calls() ) ?: 'none',
					)
				);
			} else {
				$this->assertFalse( $spy->was_called(),
					sprintf(
						"The %s mock function was unexpectedly called with:\n%s",
						$spy->get_name(),
						implode( "\n", $spy->get_pretty_calls() )
					)
				);
			}
		}

		$this->wp_send_json_spies          = [];
		$this->wp_send_json_unexpected_spy = new WP_Send_Json_Mock_Spy( 'wp_send_json' );
	}
}