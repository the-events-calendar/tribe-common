<?php

namespace Tribe\Tests\Traits;

/**
 * Class Http_API_Requests
 *
 * Adds methods to mock and stub WP HTTP API requests.
 * Meant to be used in `XTestCase` classes.
 *
 * @package Tribe\Tests\Traits
 */
trait Http_API_Requests {
	/**
	 * Mocks requests sent to a specific URL.
	 *
	 * If no mocks are set in the `mocks` parameter then nothing is mocked and the call is forwarded to the WP HTTP API
	 * to handle.
	 *
	 * @param       string $url   The URL to intercept and mock.
	 * @param array        $mocks An associative array of HTTP methods (GET, POST, PUT, DELETE, HEAD...) and mocks to
	 *                            use to answer requests made to the specified URL with a specific HTTP method. If a
	 *                            method is not mocked the call will be forwarded to the WP HTTP API. Mocks can be
	 *                            either the response to return or a callable that will receive three arguments: the
	 *                            requested URL, the request arguments and the test case instance.
	 */
	protected function mock_http_requests_for( $url, array $mocks = array() ) {
		$test_case = $this;
		add_filter( 'pre_http_request', function ( $handle, array $args, $requested_url ) use ( $url, $test_case, $mocks ) {
			$is_regex = false !== @preg_match( $url, $requested_url );
			$matches = $is_regex && preg_match( $url, $requested_url );
			if ( ( ! $matches && $url !== $requested_url ) || empty( $mocks ) ) {
				// do not mock it
				return false;
			}

			$method = $args['method'];
			if ( isset( $mocks[ $method ] ) ) {
				if ( is_callable( $mocks[ $method ] ) ) {
					return call_user_func_array( $mocks[ $method ], [ $requested_url, $args, $test_case ] );
				}

				return $mocks[ $method ];
			}

			return false;
		}, 10, 3 );
	}

	/**
	 * Mocks requests sent to a specific URL wit the HEAD HTTP method.
	 *
	 * If no mock is set in the `mock` parameter then nothing is mocked and the call is forwarded to the WP HTTP API
	 * to handle.
	 *
	 * @param       string $url   The URL to intercept and mock.
	 * @param mixed        $mock  A mock to use to answer requests made to the specified URL with a specific HTTP
	 *                            method. If the mock is empty the call will be forwarded to the WP HTTP API. The mock
	 *                            can be either the response to return or a callable that will receive three arguments:
	 *                            the requested URL, the request arguments and the test case instance.
	 */
	protected function mock_http_head_requests_for( $url, $mock ) {
		$this->mock_http_requests_for( $url, [ 'HEAD' => $mock ] );
	}

	/**
	 * Mocks requests sent to a specific URL wit the GET HTTP method.
	 *
	 * If no mock is set in the `mock` parameter then nothing is mocked and the call is forwarded to the WP HTTP API
	 * to handle.
	 *
	 * @param       string $url   The URL to intercept and mock.
	 * @param mixed        $mock  A mock to use to answer requests made to the specified URL with a specific HTTP
	 *                            method. If the mock is empty the call will be forwarded to the WP HTTP API. The mock
	 *                            can be either the response to return or a callable that will receive three arguments:
	 *                            the requested URL, the request arguments and the test case instance.
	 */
	protected function mock_http_get_requests_for( $url, $mock ) {
		$this->mock_http_requests_for( $url, [ 'GET' => $mock ] );
	}

	/**
	 * Mocks requests sent to a specific URL wit the POST HTTP method.
	 *
	 * If no mock is set in the `mock` parameter then nothing is mocked and the call is forwarded to the WP HTTP API
	 * to handle.
	 *
	 * @param       string $url   The URL to intercept and mock.
	 * @param mixed        $mock  A mock to use to answer requests made to the specified URL with a specific HTTP
	 *                            method. If the mock is empty the call will be forwarded to the WP HTTP API. The mock
	 *                            can be either the response to return or a callable that will receive three arguments:
	 *                            the requested URL, the request arguments and the test case instance.
	 */
	protected function mock_http_post_requests_for( $url, $mock ) {
		$this->mock_http_requests_for( $url, [ 'POST' => $mock ] );
	}

	/**
	 * Mocks requests sent to a specific URL wit the PUT HTTP method.
	 *
	 * If no mock is set in the `mock` parameter then nothing is mocked and the call is forwarded to the WP HTTP API
	 * to handle.
	 *
	 * @param       string $url   The URL to intercept and mock.
	 * @param mixed        $mock  A mock to use to answer requests made to the specified URL with a specific HTTP
	 *                            method. If the mock is empty the call will be forwarded to the WP HTTP API. The mock
	 *                            can be either the response to return or a callable that will receive three arguments:
	 *                            the requested URL, the request arguments and the test case instance.
	 */
	protected function mock_http_put_requests_for( $url, $mock ) {
		$this->mock_http_requests_for( $url, [ 'PUT' => $mock ] );
	}

	/**
	 * Mocks requests sent to a specific URL wit the DELETE HTTP method.
	 *
	 * If no mock is set in the `mock` parameter then nothing is mocked and the call is forwarded to the WP HTTP API
	 * to handle.
	 *
	 * @param       string $url   The URL to intercept and mock.
	 * @param mixed        $mock  A mock to use to answer requests made to the specified URL with a specific HTTP
	 *                            method. If the mock is empty the call will be forwarded to the WP HTTP API. The mock
	 *                            can be either the response to return or a callable that will receive three arguments:
	 *                            the requested URL, the request arguments and the test case instance.
	 */
	protected function mock_http_delete_requests_for( $url, $mock ) {
		$this->mock_http_requests_for( $url, [ 'DELETE' => $mock ] );
	}
}
