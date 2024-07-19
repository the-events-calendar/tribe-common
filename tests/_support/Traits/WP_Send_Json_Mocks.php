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

	private $value;
	private $status_code;
	private $flags;

	public function __construct( string $name, $value = null, ?int $status_code = null, ?int $flags = null ) {
		$this->name        = $name;
		$this->value       = $value;
		$this->status_code = $status_code;
		$this->flags       = $flags;
	}

	public function get_value() {
		return $this->value;
	}

	public function get_status_code(): ?int {
		return $this->status_code;
	}

	public function get_flags(): ?int {
		return $this->flags;
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

	/**
	 * @before
	 */
	public function set_up_wp_send_json_mocks(): void {
		$this->wp_send_json_spies = [];
		$test_case                = $this;
		$log_unexpected           = static function ( $response, $status_code = null, $flags = 0 ) use ( $test_case ) {
			$spy = new WP_Send_Json_Mock_Spy( 'wp_send_json', $response, $status_code, $flags );
			$spy->should_not_be_called();
			$spy->register_call( [ $response, $status_code, $flags ] );
			$test_case->wp_send_json_spies[] = $spy;
		};
		$this->set_fn_return( "wp_send_json", $log_unexpected, true );
	}

	protected function mock_wp_send_json_error( $value = null, $status_code = null, $flags = 0 ): WP_Send_Json_Mock_Spy {
		$spy   = new WP_Send_Json_Mock_Spy( 'wp_send_json_error', $value, $status_code );
		$spies = $this->wp_send_json_spies;

		$mock = static function (
			$call_value = null, $call_status_code = null, $call_flags = 0
		) use ( $flags, $status_code, $value, $spy, &$spies ): void {
			if ( ! ( $call_value === $value && $call_status_code === $status_code && $call_flags === $flags ) ) {
				wp_send_json_error( $value, $status_code, $flags );

				return;
			}

			$spy->register_call( [ $value, $status_code, $flags ] );
		};

		$this->set_fn_return( "wp_send_json_error", $mock, true );
		$this->wp_send_json_spies[] = $spy;

		return $spy;
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
						"The %s mock function was never called for %s, status %d and flags %d.",
						$spy->get_name(),
						print_r( $spy->get_value(), true ),
						print_r( $spy->get_status_code(), true ),
						print_r( $spy->get_flags(), true )
					)
				);
			} else {
				$this->assertFalse( $spy->was_called(),
					sprintf(
						"The %s mock function was unexpectedly called for %s, status %d and flags %d.",
						$spy->get_name(),
						print_r( $spy->get_value(), true ),
						print_r( $spy->get_status_code(), true ),
						print_r( $spy->get_flags(), true )
					)
				);
			}
		}

		$this->wp_send_json_spies = [];
	}
}