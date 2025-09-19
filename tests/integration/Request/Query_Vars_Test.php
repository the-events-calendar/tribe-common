<?php
/**
 * Integration tests for request query vars sanitization.
 *
 * @since TBD
 *
 * @package TEC\Events\Tests\Integration\Events\Request
 */

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
	 * @var \TEC\Events\Request\Query_Vars
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
		$this->query_vars = new \TEC\Common\Request\Query_Vars();
		$this->query_vars->register();

		// Use the helper to register a generic query var for testing.
		$this->getModule('QueryVarHelper')->registerGenericQueryVar($this->test_var_name, true); // Allow superglobal modification for this test var
	}

	/**
	 * Tear down the test case.
	 *
	 * @since TBD
	 */
	public function after() {
		remove_filter( 'request', [ $this->query_vars, 'sanitize_query_vars' ], 0 );
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
	 * It should set the test var to 1 when truthy values are provided.
	 *
	 * @since TBD
	 * @dataProvider truthy_values_provider
	 */
	public function test_it_leaves_unchanged_when_test_var_present( $value ) {
		$_GET[ $this->test_var_name ]     = $value;
		$_POST[ $this->test_var_name ]    = $value;
		$_REQUEST[ $this->test_var_name ] = $value;

		$vars   = [ $this->test_var_name => $value ];
		$result = apply_filters( 'request', $vars );

		$this->assertArrayHasKey( $this->test_var_name, $result, 'Expected ' . $this->test_var_name . ' to be present for value: ' . var_export( $value, true ) );
		$this->assertSame( $value, $result[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) );
		$this->assertSame( $value, $_GET[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) . ' in GET' );
		$this->assertSame( $value, $_POST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) . ' in POST' );
		$this->assertSame( $value, $_REQUEST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) . ' in REQUEST' );
	}

	/**
	 * It should remove the test var when null values are provided.
	 *
	 * @since TBD
	 * @dataProvider null_values_provider
	 */
	public function test_it_unsets_test_var_when_null( $value ) {
		$_GET[ $this->test_var_name ]     = $value;
		$_POST[ $this->test_var_name ]    = $value;
		$_REQUEST[ $this->test_var_name ] = $value;

		$vars   = [ $this->test_var_name => $value ];
		$result = apply_filters( 'request', $vars );

		$this->assertArrayNotHasKey( $this->test_var_name, $result, 'Expected ' . $this->test_var_name . ' to be removed for value: ' . var_export( $value, true ) );
		$this->assertArrayNotHasKey( $this->test_var_name, $_GET, 'Expected ' . $this->test_var_name . ' to be removed from GET for value: ' . var_export( $value, true ) );
		$this->assertArrayNotHasKey( $this->test_var_name, $_POST, 'Expected ' . $this->test_var_name . ' to be removed from POST for value: ' . var_export( $value, true ) );
		$this->assertArrayNotHasKey( $this->test_var_name, $_REQUEST, 'Expected ' . $this->test_var_name . ' to be removed from REQUEST for value: ' . var_export( $value, true ) );
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
	 * It should retain falsey but non-null values.
	 *
	 * @since TBD
	 * @dataProvider falsey_non_null_values_provider
	 */
	public function test_it_retains_falsey_non_null_values( $value ) {
		$_GET[ $this->test_var_name ]     = $value;
		$_POST[ $this->test_var_name ]    = $value;
		$_REQUEST[ $this->test_var_name ] = $value;

		$vars   = [ $this->test_var_name => $value ];
		$result = apply_filters( 'request', $vars );

		$this->assertArrayHasKey( $this->test_var_name, $result, 'Expected ' . $this->test_var_name . ' to be present for value: ' . var_export( $value, true ) );
		$this->assertSame( $value, $result[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) . ' for value: ' . var_export( $value, true ) );
		$this->assertArrayHasKey( $this->test_var_name, $_GET, 'Expected ' . $this->test_var_name . ' to be present in GET for value: ' . var_export( $value, true ) );
		$this->assertArrayHasKey( $this->test_var_name, $_POST, 'Expected ' . $this->test_var_name . ' to be present in POST for value: ' . var_export( $value, true ) );
		$this->assertArrayHasKey( $this->test_var_name, $_REQUEST, 'Expected ' . $this->test_var_name . ' to be present in REQUEST for value: ' . var_export( $value, true ) );
		$this->assertSame( $value, $_GET[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) . ' in GET for value: ' . var_export( $value, true ) );
		$this->assertSame( $value, $_POST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) . ' in POST for value: ' . var_export( $value, true ) );
		$this->assertSame( $value, $_REQUEST[ $this->test_var_name ], 'Expected ' . $this->test_var_name . ' to be ' . var_export( $value, true ) . ' in REQUEST for value: ' . var_export( $value, true ) );
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
