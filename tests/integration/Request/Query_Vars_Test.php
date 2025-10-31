<?php
/**
 * Integration tests for request query vars sanitization.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Integration\Request
 */

use TEC\Common\Request\Query_Vars;
use TEC\Events\Request\Ical;
use TEC\Common\Request\Dummy_Query_Var;

/**
 * Class Query_Vars_Test
 *
 * @since TBD
 */
class Query_Vars_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Holds the Query_Vars instance to manage hooks lifecycle.
	 *
	 * @since TBD
	 *
	 * @var \TEC\Common\Request\Query_Vars
	 */
	protected $query_vars;

	/**
	 * The name of the test query var.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $test_var_name = 'test_var';

	/**
	 * Set up the test case.
	 *
	 * @since TBD
	 */
	public function before() {
		$this->query_vars = tribe( Query_Vars::class );

		// Register the iCal query var directly
		$ical_query_var = tribe( Ical::class );
		$test_query_var = tribe( Dummy_Query_Var::class );

	}

	/**
	 * Tear down the test case.
	 *
	 * @since TBD
	 */
	public function after() {
		remove_filter( 'request', [ $this->query_vars, 'clean_query_vars' ], 0 );

		// Clean up container binding
		if ( tribe()->isBound( \TEC\Common\Request\Query_Vars::class ) ) {
			tribe()->offsetUnset( \TEC\Common\Request\Query_Vars::class );
		}
	}

	/**
	 * It should leave vars unchanged when the test var is not present.
	 *
	 * @since TBD
	 */
	public function test_it_leaves_unchanged_when_test_var_not_present() {
		$vars   = [ 'foo' => 'bar' ];
		$result = apply_filters( 'request', $vars );

		$this->assertEquals( $vars, $result, 'Expected vars to be unchanged for value: ' . var_export( $vars, true ) );
		$this->assertArrayNotHasKey( $this->test_var_name, $result, 'Expected ' . $this->test_var_name . ' to be removed for value: ' . var_export( $vars, true ) );
	}

	/**
	 * It should normalize truthy values to 1 (iCal behavior).
	 *
	 * @since TBD
	 * @dataProvider truthy_values_provider
	 */
	public function test_it_normalizes_truthy_values_to_1( $value ) {
		$_GET[ $this->test_var_name ]     = $value;
		$_POST[ $this->test_var_name ]    = $value;
		$_REQUEST[ $this->test_var_name ] = $value;

		$vars   = [ $this->test_var_name => $value ];
		$result = apply_filters( 'request', $vars );

		$this->assertArrayHasKey( $this->test_var_name, $result, 'Expected ' . $this->test_var_name . ' to be present for value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $result[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be normalized to 1 for value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $_GET[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be normalized to 1 in GET for value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $_POST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be normalized to 1 in POST for value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $_REQUEST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be normalized to 1 in REQUEST for value: ' . var_export( $value, true ) );
	}

	/**
	 * It should convert null values to 1 for presence-only support (iCal behavior).
	 *
	 * @since TBD
	 * @dataProvider null_values_provider
	 */
	public function test_it_converts_null_to_presence_only( $value ) {
		$_GET[ $this->test_var_name ]     = $value;
		$_POST[ $this->test_var_name ]    = $value;
		$_REQUEST[ $this->test_var_name ] = $value;

		$vars   = [ $this->test_var_name => $value ];
		$result = apply_filters( 'request', $vars );

		// iCal supports presence-only query vars, so null becomes 1 when key exists
		$this->assertArrayHasKey( $this->test_var_name, $result, 'Expected ' . $this->test_var_name . ' to be present for value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $result[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be 1 for presence-only support with value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $_GET[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be 1 in GET for presence-only support with value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $_POST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be 1 in POST for presence-only support with value: ' . var_export( $value, true ) );
		$this->assertSame( 1, $_REQUEST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be 1 in REQUEST for presence-only support with value: ' . var_export( $value, true ) );
	}

	/**
	 * It should retain null values when should_accept_valueless_params is false.
	 *
	 * @since TBD
	 */
	public function test_it_retains_null_when_accept_disabled() {
		$test_var_name = 'simple_test_var';

		// Create a simple query var that returns null for falsey values
		$simple_query_var = new class( tribe() ) extends \TEC\Common\Request\Abstract_Query_Var {
			protected string $name = '';
			protected bool $should_filter = true;
			protected bool $should_accept_valueless_params = true; // Will be overridden by filter

			public function set_name( string $name ): void {
				$this->name = $name;
			}

			public function filter_query_var( $value, array $query_vars ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
				// Simple behavior: return null for falsey values, keep truthy values as-is
				return $value ?: null;
			}

			protected function register_with_query_vars(): void {
				// Skip self-registration - we'll handle manually
			}
		};

		$simple_query_var->set_name( $test_var_name );

		// Use filter to disable accepting valueless params for this test
		add_filter( "tec_request_query_vars_should_accept_valueless_params_{$test_var_name}", '__return_false' );

		// Register the simple query var
		$simple_query_var->register();
		$this->query_vars->register_query_var( $simple_query_var );

		$_GET[ $test_var_name ]     = false;
		$_POST[ $test_var_name ]    = false;
		$_REQUEST[ $test_var_name ] = false;

		$vars   = [ $test_var_name => false ];
		$result = apply_filters( 'request', $vars );

		// When accepting valueless params is disabled, null values should be retained in the array
		$this->assertArrayHasKey( $test_var_name, $result, 'Expected ' . $test_var_name . ' to be retained when accept disabled' );
		$this->assertNull( $result[ $test_var_name ], 'Expected ' . $test_var_name . ' to be null after filtering' );

		// Clean up
		remove_filter( "tec_request_query_vars_should_accept_valueless_params_{$test_var_name}", '__return_false' );
		$simple_query_var->unregister();
	}

	/**
	 * Provides null values for the test query var.
	 *
	 * @return array
	 */
	public static function null_values_provider() {
		return [
			[ null ],
		];
	}

	/**
	 * It should remove falsey values (iCal behavior - falsey values become null and get removed).
	 *
	 * @since TBD
	 * @dataProvider falsey_non_null_values_provider
	 */
	public function test_it_removes_falsey_values( $value ) {
		$_GET[ $this->test_var_name ]     = $value;
		$_POST[ $this->test_var_name ]    = $value;
		$_REQUEST[ $this->test_var_name ] = $value;

		$vars   = [ $this->test_var_name => $value ];
		$result = apply_filters( 'request', $vars );

		// iCal converts falsey values to null, which then get removed due to should_overwrite_valueless_params = true
		$this->assertArrayNotHasKey( $this->test_var_name, $result, 'Expected ' . $this->test_var_name . ' to be removed for falsey value: ' . var_export( $value, true ) );
		$this->assertArrayNotHasKey( $this->test_var_name, $_GET, 'Expected ' . $this->test_var_name . ' to be removed from GET for falsey value: ' . var_export( $value, true ) );
		$this->assertArrayNotHasKey( $this->test_var_name, $_POST, 'Expected ' . $this->test_var_name . ' to be removed from POST for falsey value: ' . var_export( $value, true ) );
		$this->assertArrayNotHasKey( $this->test_var_name, $_REQUEST, 'Expected ' . $this->test_var_name . ' to be removed from REQUEST for falsey value: ' . var_export( $value, true ) );
	}

	/**
	 * Provides falsey but non-null values for the test query var.
	 *
	 * @return array
	 */
	public static function falsey_non_null_values_provider() {
		return [
			[ '0' ],
			[ 0 ],
			[ false ],
			[ 'false' ],
			[ 'no' ],
			[ 'off' ],
			[ 'random-string' ],
		];
	}

	/**
	 * Provides truthy values for the test query var.
	 *
	 * @return array
	 */
	public static function truthy_values_provider() {
		return [
			// Presence only (?ical)
			[ '' ],
			// set truthy values
			[ '1' ],
			[ 1 ],
			[ true ],
			[ 'true' ],
			[ 'TRUE' ],
			[ 'yes' ],
			[ 'y' ],
			[ 'on' ],
		];
	}

	/**
	 * Provides array inputs and expectations.
	 *
	 * @return array
	 */
	public static function array_values_provider() {
		return [
			// Truthy first element sets test var to 1.
			[ [ '1', '0' ], true, 1 ],
			// Null first element unsets test var.
			[ [ null, '1' ], false, null ],
		];
	}
}
